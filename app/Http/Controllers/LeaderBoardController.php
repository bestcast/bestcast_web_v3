<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizAttempts;

class LeaderBoardController extends Controller
{
    public function index(){
        /*$leaderboard = QuizAttempts::with('user')
            ->where('total_attended_questions', 9)
            ->whereNotNull('ended_at')
            ->orderByDesc('score')
            ->orderBy('total_answered_seconds')
            ->orderBy('ended_at')
            ->limit(50)
            ->get();*/
        $leaderboard = QuizAttempts::with('user')
            ->where('total_attended_questions', 9)
            ->whereNotNull('ended_at')
            ->whereIn('id', function ($q) {
                $q->selectRaw('MAX(id)')
                  ->from('quiz_attempts')
                  ->where('total_attended_questions', 9)
                  ->whereNotNull('ended_at')
                  ->groupBy('participant_id');
            })
            ->orderByDesc('score')
            ->orderBy('total_answered_seconds')
            ->limit(50)
            ->get();


        return view('leaderboard.index', compact('leaderboard'));
    }
}
