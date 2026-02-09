<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizAttempts;
use App\Models\Question;
use App\Models\Movies;

class LeaderBoardController extends Controller
{
    public function index(Request $request){
        $movieId = $request->get('movie_id');

        // Base leaderboard query
        $leaderboardQuery = QuizAttempts::with('user')
            ->where('total_attended_questions', 9)
            ->whereNotNull('ended_at');

        // Filter by movie if clicked
        if ($movieId) {
            $leaderboardQuery->where('movie_id', $movieId);
        }

        // Best attempt per user
        $leaderboard = $leaderboardQuery
            ->whereIn('id', function ($q) use ($movieId) {
                $q->selectRaw('MAX(id)')
                  ->from('quiz_attempts')
                  ->where('total_attended_questions', 9)
                  ->whereNotNull('ended_at')
                  ->when($movieId, function ($qq) use ($movieId) {
                      $qq->where('movie_id', $movieId);
                  })
                  ->groupBy('participant_id');
            })
            ->orderByDesc('score')
            ->orderBy('total_answered_seconds')
            ->orderBy('ended_at')
            ->limit(50)
            ->get();

        $movies = Movies::where('status', 1)
            ->whereIn('id', function ($q) {
                $q->select('movie_id')
                  ->from('quiz_attempts')
                  ->whereNotNull('movie_id')
                  ->whereNotNull('ended_at'); // only completed quizzes
            })
            ->orderBy('title')
            ->get();

        return view('leaderboard.index', compact('leaderboard', 'movies', 'movieId'));
    }
}
