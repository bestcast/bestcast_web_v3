<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movies;
use DB;

class TrailerController extends Controller
{

    public function showTrailer($movieId, Request $request)
    {


        $userId    = auth()->check() ? auth()->id() : null;
        $sessionId = $request->session()->getId();
        $ip        = $request->ip();

        // Detect platform
        $userAgent = strtolower($request->header('User-Agent', ''));
        \Log::info('User Agent: ' . $request->header('User-Agent'));
        if (preg_match('/android|iphone|ipad/i', $userAgent)) {
            $platform = 'mobile';
        } else {
            $platform = 'web';
        }

        // Check if record already exists (per session/user)
        $record = \DB::table('trailer_watch_logs')
            ->where('movie_id', $movieId)
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->when(!$userId, fn($q) => $q->where('session_id', $sessionId))
            ->first();

        if ($record) {
            \DB::table('trailer_watch_logs')
                ->where('id', $record->id)
                ->update([
                    'watch_count'     => $record->watch_count + 1,
                    'platform'        => $platform,
                    'last_watched_at' => now(),
                    'updated_at'      => now(),
                ]);
        } else {
            \DB::table('trailer_watch_logs')->insert([
                'movie_id'        => $movieId,
                'user_id'         => $userId,
                'session_id'      => $sessionId,
                'ip_address'      => $ip,
                'watch_count'     => 1,
                'platform'        => $platform,
                'first_watched_at'=> now(),
                'last_watched_at' => now(),
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        $movie = Movies::findOrFail($movieId);
        return view('trailer.trailer', compact('movie'));   
    }


}