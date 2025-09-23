<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Movies;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\TrailerWatchLog;


class TrailerViewsReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function index(Request $request)
    {
        $ids = TrailerWatchLog::distinct()->pluck('movie_id');

        $trailers = Movies::whereIn('id', $ids)
            ->orderBy('title')
            ->pluck('title', 'id');

        $selectedTrailerId = $request->get('trailer_id');   // <-- was movie_id

        $totalViews = null;
        if ($selectedTrailerId) {
            $totalViews = TrailerWatchLog::where('movie_id', $selectedTrailerId)->count();
        }

        return view('admin.trailerviews.index',
            compact('trailers', 'selectedTrailerId', 'totalViews')
        );
    }

    public function getViewsData(Request $request)
    {
        $trailerId = $request->get('trailer_id');   // <-- match JS

        $months = collect(range(0, 11))
            ->map(fn ($i) => now()->subMonths($i)->format('M Y'))
            ->reverse()
            ->values();

        if (!$trailerId) {
            return response()->json([
                'labels' => $months,
                'data'   => array_fill(0, 12, 0),
            ]);
        }

        $raw = TrailerWatchLog::selectRaw("
                DATE_FORMAT(first_watched_at, '%b %Y') as month,
                COUNT(*) as total
            ")
            ->where('movie_id', $trailerId)
            ->where('first_watched_at', '>=', now()->subYear())
            ->groupBy('month')
            ->pluck('total', 'month');

        $data = $months->map(fn($m) => $raw[$m] ?? 0);

        return response()->json([
            'labels' => $months,
            'data'   => $data,
        ]);
    }
}
