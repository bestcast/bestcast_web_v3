<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Question;
use App\Models\QuestionOptions;
use App\Models\QuizAttempts;
use App\Models\QuizAttemptAnswer;
use DB;use Log;

class QuizModel extends Model
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
                'question_option_id' => $answer['option_id']
            ]);
        }

        // Recalculate Score
        $correctAnswerCount = QuizAttemptAnswer::where('quiz_attempts_id', $attemptId)
            ->whereHas('option', function ($q) {
                $q->where('is_correct', 1);
            })
            ->count();

        QuizAttempts::where('id', $attemptId)->update([
            'score' => $correctAnswerCount
        ]);

        // End Attempt when Completed
        $totalAnswered = QuizAttemptAnswer::where('quiz_attempts_id', $attemptId)->count();
        $required = 3;

        if ($totalAnswered >= $required && $attempt->ended_at === null) {
            $attempt->update(['ended_at' => now()]);
        }

        return response()->json([
            'quizAttemptId'       => $attemptId,
            'success'             => true,
            'message'             => 'Answer submitted successfully.',
            'totalQuestions'      => $totalAnswered,
            'correctAnswerCount'  => $correctAnswerCount,
        ]);
    }

    public function QuizAttemptAnswerCount($requestData){
        $attemptId = $requestData['attemptId'];
        $attempt = QuizAttempts::find($attemptId);

        if (!$attempt) {
            return response()->json(['error' => 'Attempt not found'], 404);
        }

        // Assuming "score" column already stores correct answer count
        $correctAnswers = $attempt->score;

        $totalQuestions = QuizAttemptAnswer::where('quiz_attempts_id', $attemptId)->count();

        return response()->json([
            'attemptId' => $attempt->id,
            'correctAnswerCount' => $correctAnswers,
            'totalQuestions' => $totalQuestions
        ]);
        //get correctAnswer in quiz_attempts table column score
        //get totalQuestion count in quiz_attempts_answer table.
    }
    
}
