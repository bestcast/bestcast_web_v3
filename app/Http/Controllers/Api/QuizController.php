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
    public function getQuiz(Request $request, $movieId)
    {   
        $user = Auth::user();
        $requestData = $request->all();
        if (!$user || $user->plan_expiry === null || Carbon::now()->gte(Carbon::parse($user->plan_expiry))) {
            return response()->json([
                'redirect' => url('/pricing')
            ], 403);
        }
        else{

            // $fromTime = (int) $request->cookie('from_time'); dd($fromTime);exit;
            $fromTime = $requestData['from_time']+1;
            $toTime = $fromTime + 14;
            /*\Log::info("from_time (cookie): " . $fromTime);
            \Log::info("to_time (calculated): " . $toTime);*/

            $questions = Question::with('options')
                        ->where('movie_id', $movieId)
                        ->whereBetween('show_question_time', [$fromTime, $toTime])
                        ->orderBy('show_question_time')
                        ->inRandomOrder()
                        ->take(10)
                        ->get();
            if ($questions->isEmpty()) {
                return response()->json([
                    'questions' => [],
                    'skip' => true // tells frontend to skip ahead
                ]);
            }

            $formatted = $questions->map(function ($q) {
                return [
                    'id' => $q->id,
                    'question' => $q->question_name,
                    // assuming each option has a 'name' field
                    'options' => $q->options->toArray(),
                    'show_question_time' => $q->show_question_time
                ];
            });
            /*dd($formatted);*/
            return response()->json(['questions' => $formatted, 'skip' => false]);
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
