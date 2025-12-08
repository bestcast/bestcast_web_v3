<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Carbon\Carbon;
use App\Http\Requests\QuizRequest;
use App\Models\QuizAttempts;
use App\Models\QuizModel;
use App\Models\Question;
use App\Models\QuizAttemptAnswer;
use App\Models\QuizAttemptQuestionMap;
use App\Helpers\QuizCryptoHelper;

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
        $userId      = $request->user_id;
        $movieId     = $request->movie_id;
        $interval    = 15;
        $maxRequired = 3;

        $user = Auth::user();
        if (!$user || $user->plan_expiry === null || Carbon::now()->gte(Carbon::parse($user->plan_expiry))) {
            return response()->json([
                'redirect' => url('/pricing')
            ], 403);
        }
        
        // Detect Existing Attempt (not finished) or create new
        
        $attempt = QuizAttempts::where('participant_id', $userId)
            ->where('movie_id', $movieId)
            ->whereNull('ended_at')
            ->first();

        if (!$attempt) {
            $attempt = QuizAttempts::create([
                'participant_id' => $userId,
                'movie_id'       => $movieId,
                'started_at'     => now(),
            ]);
        }

        $attemptId = $attempt->id;

        
        // Fetch Previously Used Questions in this Attempt
        
        $usedQuestions = QuizAttemptQuestionMap::where('user_id', $userId)
                        ->pluck('question_id')
                        ->toArray();


        
        // Unused Questions
        
        /*$unused = Question::where('movie_id', $movieId)
            ->whereNotIn('id', $usedQuestions)
            ->with('options')
            ->orderBy('show_question_time')
            ->get();

        // If unused < required, mix unused + used
        if ($unused->count() < $maxRequired) {

            $needed = $maxRequired - $unused->count();

            $usedAgain = Question::where('movie_id', $movieId)
                ->whereIn('id', $usedQuestions)
                ->with('options')
                ->inRandomOrder()
                ->take($needed)
                ->get();

            $allQuestions = $unused->merge($usedAgain);
        } else {
            $allQuestions = $unused;
        }*/

        // UNIQUE unused questions by show_question_time
        $unused = Question::where('movie_id', $movieId)
            ->whereNotIn('id', $usedQuestions)
            ->with('options')
            ->orderBy('show_question_time')
            ->get()
            ->groupBy('show_question_time')
            ->map(fn($group) => $group->first())
            ->values();

        // If unused < required, mix unused + used
        if ($unused->count() < $maxRequired) {

            $needed = $maxRequired - $unused->count();

            // UNIQUE used questions by show_question_time
            $usedAgain = Question::where('movie_id', $movieId)
                ->whereIn('id', $usedQuestions)
                ->with('options')
                ->get()
                ->groupBy('show_question_time')
                ->map(fn($group) => $group->first())
                ->values()
                ->shuffle()
                ->take($needed);

            $allQuestions = $unused->merge($usedAgain);
        } else {
            $allQuestions = $unused;
        }


        // Select final 3 questions (shuffled)
        $selected = $allQuestions->shuffle()->take($maxRequired);

        $final = [];

        // Apply popup buffer logic
        foreach ($selected as $q) {

            // Dynamic buffer
            $buffer = ($q->show_question_time >= 20) ? 3 : 6;

            $popupTime = $q->show_question_time + $buffer;

            $final[] = [
                'id' => $q->id,
                'question' => $q->question_name,
                'show_question_time' => $q->show_question_time,
                'popup_time' => $popupTime,
                'options' => $q->options
            ];
        }

        // Sort by popup time
        $final = collect($final)->sortBy('popup_time')->values();

        $encryptedResponse = QuizCryptoHelper::encryptPayload([
            'attempt_id' => $attemptId,
            'questions'  => $final
        ]);

        return response()->json($encryptedResponse);

    }

    public function quizsubmit(Request $request){
        $requestData = $request->all();
        return $this->QuizModel->submitAnswerQuiz($requestData);
    }
    public function quizresult(Request $request)
    {
        $requestData = $request->all();
        return $this->QuizModel->QuizAttemptAnswerCount($requestData);
    }
}
