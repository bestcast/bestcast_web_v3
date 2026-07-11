<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CaptureReferralCode
{
    /**
     * Alphanumeric only, 6-20 characters
     */
    protected $pattern = '/^[A-Za-z0-9]{6,20}$/';

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('ref') && !empty($request->ref)) {
            $refCode = trim($request->ref);

            if (preg_match($this->pattern, $refCode)) {
                session(['referral_code' => $refCode]);
            } else {
                \Log::warning('Rejected malformed referral code', [
                    'ref' => $refCode,
                    'ip' => $request->ip(),
                    'url' => $request->fullUrl(),
                ]);
                // Do not store invalid codes in session
            }
        }

        return $next($request);
    }
}