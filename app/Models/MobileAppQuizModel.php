<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Question;
use App\Models\QuestionOptions;
use App\Models\QuizAttempts;
use App\Models\QuizAttemptAnswer;
use DB;use Log;
use App\Helpers\QuizCryptoHelper;
use App\Models\UsersDevice;
use App\Models\UsersMovies;

class MobileAppQuizModel extends Model
{
    private $QuizAttempts;

    /**
     * Create a new model instance.
     *
     * @return void
     */
    public function __construct(QuizAttempts $QuizAttempts)
    {
        $this->QuizAttempts=$QuizAttempts;
    }

    public function submitAnswerQuiz($requestData)
    {

        $userId  = $requestData['user_id'];
        $movieId = $requestData['movie_id'];
        $attemptId = (int)$requestData['attempt_id'];
        $answer = $requestData['answer'];

        // Load or Create Attempt
        if (!$attemptId || $attemptId == 0 || $attemptId === "0") {
            $attempt = QuizAttempts::create([
                'participant_id' => $userId,
                'movie_id'       => $movieId,
                'started_at'     => now(),
            ]);
            $attemptId = $attempt->id;
        } else {
            $attempt = QuizAttempts::find($attemptId);

            if (!$attempt) {
                $attempt = QuizAttempts::create([
                    'participant_id' => $userId,
                    'movie_id'       => $movieId,
                    'started_at'     => now(),
                ]);
                $attemptId = $attempt->id;
            }
        }

        QuizAttemptQuestionMap::firstOrCreate([
            'attempt_id'  => $attemptId,
            'user_id'     => $userId,
            'question_id' => $answer['question_id']
        ]);

        // Avoid Duplicate Answer Save
        $alreadyAnswered = QuizAttemptAnswer::where('quiz_attempts_id', $attemptId)
            ->where('quiz_question_id', $answer['question_id'])
            ->exists();

        if (!$alreadyAnswered) {
            QuizAttemptAnswer::create([
                'quiz_attempts_id'   => $attemptId,
                'user_id'            => $userId,
                'movie_id'           => $movieId,
                'quiz_question_id'   => $answer['question_id'],
                'question_option_id' => $answer['option_id'],
                'answered_seconds'   => $answer['answered_seconds']
            ]);
        }

        // Recalculate Score
        $correctAnswerCount = QuizAttemptAnswer::where('quiz_attempts_id', $attemptId)
            ->whereHas('option', function ($q) {
                $q->where('is_correct', 1);
            })
            ->count();

        $totalAnsweredSeconds = QuizAttemptAnswer::where('quiz_attempts_id', $attemptId)
            ->sum('answered_seconds');

        $totalAttendedQuestions = QuizAttemptAnswer::where('quiz_attempts_id', $attemptId)
            ->count();

        QuizAttempts::where('id', $attemptId)->update([
            'score' => $correctAnswerCount,
            'total_answered_seconds' => $totalAnsweredSeconds,
            'total_attended_questions' => $totalAttendedQuestions,
        ]);

        // End Attempt when Completed
        $totalAnswered = QuizAttemptAnswer::where('quiz_attempts_id', $attemptId)->count();
        $required = 9;

        if ($totalAnswered >= $required && $attempt->ended_at === null) {
            $attempt->update(['ended_at' => now()]);
        }

        $Response = [
            'quizAttemptId'       => $attemptId,
            'success'             => true,
            'message'             => 'Answer submitted successfully.',
            'totalQuestions'      => $totalAnswered,
            'correctAnswerCount'  => $correctAnswerCount,
        ];

        return response()->json($Response);
    }

    public function QuizAttemptAnswerCount($requestData){
        $attemptId = $requestData['attemptId'];
        $plainToken = $requestData['tokenEncrypted'];
        $userId  = $requestData['user_id'];
        $movieId = $requestData['movieId'];
        $currentDevice = null;

        if ($plainToken) {
            $currentDevice = UsersDevice::where('token', md5($plainToken))->first();
        }

        if ($currentDevice && $currentDevice->is_quiz_active == 1) {
            $currentDevice->update([
                'is_quiz_active'   => 0,
                'quiz_started_at'  => null
            ]);
        }
        UsersMovies::where('user_id', $userId)
            ->where('movie_id', $movieId)
            ->update([
                'quiz_prompt_shown' => 0
            ]);
        $attempt = QuizAttempts::find($attemptId);

        if (!$attempt) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Attempt not found'
                ]
            );
        }

        // Assuming "score" column already stores correct answer count
        $correctAnswers = $attempt->score;

        $totalQuestions = QuizAttemptAnswer::where('quiz_attempts_id', $attemptId)->count();

        $Response = [
            'attemptId' => $attempt->id,
            'correctAnswerCount' => $correctAnswers,
            'totalQuestions' => $totalQuestions
        ];

        return response()->json($Response);
        //get correctAnswer in quiz_attempts table column score
        //get totalQuestion count in quiz_attempts_answer table.
    }

    public function QuizPooling($requestData){
        /*$user = $request->user();
        $plainToken = $request->bearerToken();*/
        $plainToken = $requestData['tokenEncrypted'];
        $userId  = $requestData['user_id'];

        $currentDevice = UsersDevice::where('token', md5($plainToken))->first();

        $activeQuiz = UsersDevice::where('user_id', $userId)
            ->where('is_quiz_active', 1)
            ->first();

        // Quiz active on another device
        if ($activeQuiz && (!$currentDevice || $activeQuiz->id !== $currentDevice->id)) {
            return response()->json(
                [
                    'quiz_allowed' => false
                ]
            );
        }

        return response()->json(
            [
                'quiz_allowed' => true
            ]
        );
    }
}
