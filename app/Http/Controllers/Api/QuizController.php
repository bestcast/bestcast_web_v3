<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Carbon\Carbon;
use App\Http\Requests\QuizRequest;
use App\Models\QuizModel;
use App\Models\Question;

class QuizController extends Controller
{
    private $QuizModel;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(QuizModel $QuizModel)
    {
        $this->middleware('auth');
        $this->QuizModel = $QuizModel;
    }
    public function getMovieQuiz(Request $request)
    {
        $userId = $request->user_id;
        $movieId = $request->movie_id;
        $interval = 15; 
        $maxRequired = 3;  // total questions to show

        $user = Auth::user();
        $requestData = $request->all();
        if (!$user || $user->plan_expiry === null || Carbon::now()->gte(Carbon::parse($user->plan_expiry))) {
            return response()->json([
                'redirect' => url('/pricing')
            ], 403);
        }else{
            // Step 1 — Load ALL questions
            $all = Question::where('movie_id', $movieId)
                ->orderBy('show_question_time')
                ->with('options')
                ->get();

            if ($all->isEmpty()) {
                return response()->json([]);
            }

            // Step 2 — Randomly select 9 questions
            $selected = $all->shuffle()->take($maxRequired);

            $final = [];

            foreach ($selected as $q) {

                // Step 3 — Determine interval dynamically
                $intervalIndex = floor($q->show_question_time / $interval);
                $intervalStart = $intervalIndex * $interval;
                $intervalEnd   = $intervalStart + $interval;

                // Step 4 — Ensure question belongs to this interval
                if ($q->show_question_time > $intervalEnd) {
                    continue;
                }

                // Step 5 — Dynamic buffer rule
                $buffer = ($q->show_question_time >= 20) ? 3 : 2;

                $popupTime = $q->show_question_time + $buffer;

                $final[] = [
                    'id' => $q->id,
                    'question' => $q->question_name,
                    'show_question_time' => $q->show_question_time,
                    'popup_time' => $popupTime,
                    //'interval_start' => $intervalStart,
                    //'interval_end' => $intervalEnd,
                    'options' => $q->options
                ];
            }

            // Step 6 — Sort by popup_time so popups appear in order
            $final = collect($final)->sortBy('popup_time')->values();

            //return response()->json($final);
            return response()->json([
                'questions' => $final
            ]);

        }
        
    }

    public function quizsubmit(Request $request){
        $requestData = $request->all();
        return $this->QuizModel->QuizAttempt($requestData);
    }
    public function quizresult(Request $request)
    {
        $requestData = $request->all();
        return $this->QuizModel->QuizAttemptAnswer($requestData);
    }
}
