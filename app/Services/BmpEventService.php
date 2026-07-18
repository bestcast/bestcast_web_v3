<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\BmpEventQueue;

class BmpEventService
{
    protected static $refCodePattern = '/^[A-Za-z0-9]{6,20}$/';

    /**
     * @param string $eventType e.g. 'lead_created', 'subscription_paid'
     * @param array $data must include referralCode, customerId, customerName, phone, email, etc.
     * @param string|null $eventId deterministic id for idempotency (e.g. 'txn_123_paid'); auto-generated if omitted
     */
    public static function sendEvent(string $eventType, array $data, ?string $eventId = null)
    {
        $referralCode = $data['referralCode'] ?? null;

        // No referral code means this subscriber didn't come via a partner — skip silently
        if (empty($referralCode)) {
            return null;
        }

        if (!preg_match(self::$refCodePattern, $referralCode)) {
            Log::warning('BMP event blocked — invalid referral code format', [
                'eventType' => $eventType,
                'referralCode' => $referralCode,
            ]);
            return null;
        }

        $eventId = $eventId ?? (string) Str::uuid();

        $payload = array_merge([
            'eventType' => $eventType, // renamed from eventType per new contract
            'source' => 'bestcast_ott',
            'environment' => config('app.env') === 'production' ? 'production' : 'staging',
            'eventId' => $eventId,
            'eventTime' => now()->toIso8601String(),
        ], $data);

        // Idempotency check: if this exact event was already successfully sent, don't resend
        $queueItem = BmpEventQueue::firstOrNew(['event_id' => $eventId]);
        if ($queueItem->exists && $queueItem->status === 'sent') {
            Log::info('BMP event already sent previously, skipping', ['eventId' => $eventId]);
            return $queueItem->last_response ? json_decode($queueItem->last_response, true) : null;
        }

        $queueItem->event_type = $eventType;
        $queueItem->payload = $payload;
        $queueItem->status = 'pending';
        $queueItem->save();

        return self::attemptSend($queueItem, $payload, $eventType, $referralCode);
    }

    /**
     * Attempt to actually deliver a queued event to BMP. Used both for first-send and retries.
     */
    public static function attemptSend(BmpEventQueue $queueItem, array $payload, string $eventType, string $referralCode)
    {
        try {
            $response = Http::withHeaders([
                'x-bmp-api-key' => config('services.bmp.api_key'),
                'Content-Type' => 'application/json',
            ])
            ->timeout(5)
            ->post(config('services.bmp.api_url'), $payload);

            $queueItem->attempts += 1;
            $queueItem->last_response = $response->body();

            if ($response->successful()) {
                $queueItem->status = 'sent';
                $queueItem->save();

                Log::info('BMP event sent successfully', [
                    'eventType' => $eventType,
                    'eventId' => $queueItem->event_id,
                    'referralCode' => $referralCode,
                    'response' => $response->json(),
                ]);
                return $response->json();
            }

            $queueItem->status = 'failed';
            $queueItem->save();

            Log::warning('BMP event failed', [
                'eventType' => $eventType,
                'eventId' => $queueItem->event_id,
                'referralCode' => $referralCode,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return null;

        } catch (\Exception $e) {
            $queueItem->attempts += 1;
            $queueItem->status = 'failed';
            $queueItem->last_response = $e->getMessage();
            $queueItem->save();

            Log::error('BMP API call exception', [
                'eventType' => $eventType,
                'eventId' => $queueItem->event_id,
                'referralCode' => $referralCode,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Retry all pending/failed events (called by scheduled command).
     */
    public static function retryPending(int $maxAttempts = 5)
    {
        $items = BmpEventQueue::whereIn('status', ['pending', 'failed'])
            ->where('attempts', '<', $maxAttempts)
            ->get();

        foreach ($items as $item) {
            $referralCode = $item->payload['referralCode'] ?? '';
            self::attemptSend($item, $item->payload, $item->event_type, $referralCode);
        }

        return $items->count();
    }
}