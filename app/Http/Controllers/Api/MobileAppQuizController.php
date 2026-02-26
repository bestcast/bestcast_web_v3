<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuizAttempts;
use App\Models\MobileAppQuizModel;
use App\Models\Question;
use App\Models\QuizAttemptAnswer;
use App\Models\QuizAttemptQuestionMap;
use App\Helpers\QuizCryptoHelper;
use App\Models\UsersDevice;
use App\Models\UsersMovies;
use App\User;
use Auth;
use App\Models\RewardClaim;
use App\Models\Movies;

class MobileAppQuizController extends Controller
{
    private $MobileAppQuizModel;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MobileAppQuizModel $MobileAppQuizModel)
    {
        $this->MobileAppQuizModel = $MobileAppQuizModel;
    }
    public function getMovieQuizMobile(Request $request)
    {
        $movieId     = $request['movie_id'] ?? null;
        $userId      = auth()->id();
        $deviceToken = $request['device_token'] ?? null; // plain device token
        $interval    = 15;
        $maxRequired = 9;

        //Check Movie Exists
        $movie = Movies::select('id', 'movie_quiz_status')->where('id', $movieId)->first();
        if (!$movie) {
            return response()->json([
                'status' => false,
                'message' => 'Movie not found'
            ], 404);
        }

        //Check Admin Quiz Status
        if ((int)$movie->movie_quiz_status !== 1) {

            return response()->json([
                'status' => false,
                'message' => 'Quiz is disabled by admin',
                'movie_quiz_status' => 0,
                'question_available' => 0
            ], 403);
        }

        //Check Question Availability
        $question_available = Question::where('movie_id', $movieId)->exists() ? 1 : 0;

        if ($question_available !== 1) {

            return response()->json([
                'status' => false,
                'message' => 'Quiz questions not available',
                'movie_quiz_status' => 1,
                'question_available' => 0
            ], 403);
        }

        //IMPORTANT: ONLY IF BOTH TRUE,QUIZ WILL OPEN
        //Subscription Check
        $user = auth()->user();

        if (!$user || !$user->plan_expiry || now()->gte($user->plan_expiry)) {

            return response()->json([
                'status' => false,
                'message' => 'Subscription expired'
            ], 403);
        }

        //Device Lock Check
        if ($deviceToken) {
            $currentDevice = UsersDevice::where('user_id', $userId)
                ->where('token', md5($deviceToken))
                ->first();
            $activeDevice = UsersDevice::where('user_id', $userId)
                ->where('is_quiz_active', 1)
                ->first();
            if ($activeDevice && $currentDevice && $activeDevice->id !== $currentDevice->id) {

                return response()->json([
                    'status' => false,
                    'message' => 'Quiz already active on another device'
                ], 403);
            }

            if ($currentDevice) {
                $currentDevice->update([
                    'is_quiz_active' => 1,
                    'quiz_started_at' => now()
                ]);
            }
        }

        //Load or Create Attempt
        $attempt = QuizAttempts::firstOrCreate(
            [
                'participant_id' => $userId,
                'movie_id' => $movieId,
                'ended_at' => null
            ],
            [
                'started_at' => now()
            ]
        );

        $attemptId = $attempt->id;

        //Exclude Correct Answered Questions
        $answeredIds = QuizAttemptAnswer::where('quiz_attempts_id', $attemptId)
            ->pluck('quiz_question_id')
            ->toArray();

        //Fetch Questions
        $questions = Question::where('movie_id', $movieId)
            ->whereNotIn('id', $answeredIds)
            ->with('options:id,question_id,name')
            ->orderBy('show_question_time')
            ->get()
            ->unique('show_question_time')
            ->shuffle()
            ->take($maxRequired);

        //Format Response
        $final = $questions->map(function ($q) {

            $buffer = $q->show_question_time >= 20 ? 3 : 6;

            return [
                'id' => $q->id,
                'question' => $q->question_name,
                'show_question_time' => $q->show_question_time,
                'popup_time' => $q->show_question_time + $buffer,
                'options' => $q->options->map(function ($opt) {
                    return [
                        'id' => $opt->id,
                        'option' => $opt->option_name
                    ];
                })
            ];
        })->sortBy('popup_time')->values();

        //Final Success Response
        return response()->json([
            'status' => true,
            'movie_quiz_status' => 1,
            'question_available' => 1,
            'attempt_id' => $attemptId,
            'total_questions' => $final->count(),
            'questions' => $final
        ]);
    }
    
    public function quizsubmitmobile(Request $request)
    {
        $data = $request->all();
        return $this->MobileAppQuizModel->submitAnswerQuiz($data);
    }
    public function quizresultmobile(Request $request)
    {
        $data = $request->all();
        return $this->MobileAppQuizModel->QuizAttemptAnswerCount($data);
    }
    public function mobilequizStatus(Request $request)
    {
        $data = $request->all();
        return $this->MobileAppQuizModel->QuizPooling($data);
    }
    public function mobilequizPromptShown(Request $request)
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

        $Response = [
            'already_shown' => false
        ];

        return response()->json($Response);
    }
    public function mobilequizPromptSkipped(Request $request)
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
    public function submitRewardClaim(Request $request)
    {
        // Check if already claimed
        if (RewardClaim::where('user_id', auth()->id())->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'You have already submitted a reward claim.'
            ], 409);
        }

        // Validate
        $validated = $request->validate([
            'full_name'   => 'required|string|max:255',
            'door_no'     => 'required|string|max:255',
            'street_name' => 'required|string|max:255',
            'country'     => 'required|string|max:100',
            'state'       => 'required|string|max:100',
            'city'        => 'required|string|max:100',
            'pin_code'    => 'required|string|max:10',
            'mobile_no'   => 'required|string|max:20',
        ]);

        // Save
        $claim = RewardClaim::create([
            'user_id'   => auth()->id(),
            'full_name'   => $validated['full_name'],
            'door_no'     => $validated['door_no'],
            'street_name' => $validated['street_name'],
            'country'     => $validated['country'],
            'state'       => $validated['state'],
            'city'        => $validated['city'],
            'pin_code'    => $validated['pin_code'],
            'mobile_no'   => $validated['mobile_no'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reward claim submitted successfully!',
            'data'    => $claim
        ]);
    }
    public function update(Request $request, $id)
    {
        $claim = RewardClaim::where('id', $id)
            ->where('user_id', auth()->id()) // secure: user can update only their claim
            ->first();

        if (!$claim) {
            return response()->json([
                'success' => false,
                'message' => 'Reward claim not found.'
            ], 404);
        }

        $validated = $request->validate([
            'full_name'   => 'required|string|max:255',
            'door_no'     => 'required|string|max:255',
            'street_name' => 'required|string|max:255',
            'country'     => 'required|string|max:100',
            'state'       => 'required|string|max:100',
            'city'        => 'required|string|max:100',
            'pin_code'    => 'required|string|max:10',
            'mobile_no'   => 'required|string|max:20',
        ]);

        $claim->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Reward claim updated successfully!',
            'data'    => $claim
        ]);
    }

}
