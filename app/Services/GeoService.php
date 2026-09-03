<?php
    namespace App\Services;

    use Illuminate\Http\Request;
    use Stevebauman\Location\Facades\Location;

    class GeoService
    {
        public static function getCountry(Request $request): string
        {
            // TEMP: local testing override — REMOVE before deploying
            /*if (app()->environment('local') && $request->query('test_country')) {
                return $request->query('test_country');
            }*/

            $ip = $request->ip();
            $position = Location::get($ip);
            return $position->countryCode ?? 'UNKNOWN';
        }
    }
?>