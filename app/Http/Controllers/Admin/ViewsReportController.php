<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\Models\Movies;
use App\Models\UserMovieWatchLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\TrailerWatchLog;

class ViewsReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $movies = Movies::query()
        ->where('status', 1)      // only active
        ->orderBy('title', 'asc')
        ->pluck('title', 'id');

        $selectedMovieId = $request->get('movies_id');

        $totalViews = null;

        if ($selectedMovieId) {
            $totalViews = UserMovieWatchLog::where('movie_id', $selectedMovieId)->count();
        }

        return view('admin.viewsreport.index', compact('movies', 'selectedMovieId', 'totalViews'));
    }
    
    public function getViewsData(Request $request)
    {
        $movieId = $request->get('movie_id');
        if (!$movieId) {
            return response()->json([
                'labels'      => [],
                'trailerData' => [],
                'movieData'   => [],
            ]);
        }

        // Last 12 months (current month + 11 previous)
        $months = collect(range(0, 11))
            ->map(fn($i) => now()->subMonths($i)->format('M Y'))
            ->reverse()
            ->values();

        // Query: count views by month and watch_type
        $raw = UserMovieWatchLog::selectRaw("
                DATE_FORMAT(watched_at, '%b %Y') as month,
                watch_type,
                COUNT(*) as total
            ")
            ->where('movie_id', $movieId)
            ->where('watched_at', '>=', now()->subYear())
            ->groupBy('month', 'watch_type')
            ->get();

        // Organize results: [month][watch_type] = total
        $grouped = [];
        foreach ($raw as $row) {
            $grouped[$row->month][$row->watch_type] = $row->total;
        }

        // Build two datasets aligned with $months
        $trailerData = $months->map(fn($m) => $grouped[$m]['trailer'] ?? 0);
        $movieData   = $months->map(fn($m) => $grouped[$m]['movie'] ?? 0);

        return response()->json([
            'labels'      => $months,
            'trailerData' => $trailerData,
            'movieData'   => $movieData,
        ]);
    }
}
