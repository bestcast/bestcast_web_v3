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
use App\Models\UsersDevice;
use App\Http\Controllers\Traits\DecryptsQuizPayload;
use App\Models\UsersMovies;

class QuizController extends Controller
{
    use DecryptsQuizPayload;

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
        $payload = $this->decryptPayloadFromRequest($request);
        // Access decrypted values
        $movieId = $payload['movie_id'] ?? null;
        $userId  = $payload['user_id'] ?? null;
        $plainToken   = $payload['tokenEncrypted'] ?? null;
        $interval    = 15;
        $maxRequired = 9;

        // Check if quiz already active on another device
        $activeQuiz = UsersDevice::where('user_id', $userId)
                        ->where('is_quiz_active', 1)
                        ->first();

        // Identify current device
        if ($plainToken) {
            $currentDevice = UsersDevice::where('token', md5($plainToken))->first();
        }
        // If quiz active on another device - BLOCK
        if ($activeQuiz && $activeQuiz->token !== $currentDevice->token) {
            return response()->json([
                'status' => 'error',
                'message' => 'You already participated in the quiz in another device.'
            ], 403);
        }

        // Mark quiz active for this device (only if not active already)
        if ($currentDevice) {
            $currentDevice->update([
                'is_quiz_active' => 1,
                'quiz_started_at' => now()
            ]);
        }
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
        //$requestData = $request->all();
        $payload = $this->decryptPayloadFromRequest($request);
        return $this->QuizModel->submitAnswerQuiz($payload);
    }
    public function quizresult(Request $request)
    {
        $payload = $this->decryptPayloadFromRequest($request);
        return $this->QuizModel->QuizAttemptAnswerCount($payload);
    }

    public function quizStatus(Request $request)
    {
        $payload = $this->decryptPayloadFromRequest($request);
        return $this->QuizModel->QuizPooling($payload);
    }
    public function quizPromptShown(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id'
        ]);

        $userId = auth()->id();
        $movieId = $request->movie_id;

        // Always exists because already create it earlier
        $record = UsersMovies::where('user_id', $userId)
            ->where('movie_id', $movieId)
            ->first();

        // Already shown - block popup
        if ($record && $record->quiz_prompt_shown == 1) {
            return response()->json([
                'already_shown' => true
            ]);
        }

        // Mark as shown now
        UsersMovies::where('user_id', $userId)
            ->where('movie_id', $movieId)
            ->update([
                'quiz_prompt_shown' => 1
            ]);

        return response()->json([
            'already_shown' => false
        ]);
    }
    public function quizPromptSkipped(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['movie_id'])) {
            return response()->noContent();
        }
        $plainToken = $data['tokenEncrypted'];
        $currentDevice = UsersDevice::where('token', md5($plainToken))->first();
        if ($currentDevice) {
            $currentDevice->update([
                'is_quiz_active' => 0,
                'quiz_started_at' => null
            ]);
        }
        UsersMovies::where('user_id', auth()->id())
            ->where('movie_id', $data['movie_id'])
            ->update([
                'quiz_prompt_shown' => 0
            ]);

        return response()->noContent();
    }

}
