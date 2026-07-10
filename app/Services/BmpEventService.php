<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BmpEventService
{
    public static function sendEvent(string $eventType, array $data)
    {
        $referralCode = $data['referralCode'] ?? null;

        // No referral code means this subscriber didn't come via a partner — skip silently
        if (empty($referralCode)) {
            return null;
        }

        $payload = array_merge([
            'eventType' => $eventType,
            'source' => 'bestcast_ott',
        ], $data);

        try {
            $response = Http::withHeaders([
                'x-bmp-api-key' => config('services.bmp.api_key'),
                'Content-Type' => 'application/json',
            ])
            ->timeout(5)
            ->post(config('services.bmp.api_url'), $payload);

            if ($response->successful()) {
                Log::info('BMP event sent successfully', [
                    'eventType' => $eventType,
                    'referralCode' => $referralCode,
                    'response' => $response->json(),
                ]);
                return $response->json();
            }

            // Invalid referral code / duplicate / API-side error — log, don't block OTT flow
            Log::warning('BMP event failed', [
                'eventType' => $eventType,
                'referralCode' => $referralCode,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return null;

        } catch (\Exception $e) {
            // Network error, timeout, etc. — NEVER block payment/subscription flow because of this
            Log::error('BMP API call exception', [
                'eventType' => $eventType,
                'referralCode' => $referralCode,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}