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
    public function QuizAttempt($requestData){
        $data = ['participant_id' => $requestData['user_id'],
                    'movie_id' => $requestData['movie_id']
                ];
        $attempt_id = $requestData['attempt_id'];
        $requestData['attempt_id'] = (int)$requestData['attempt_id'];
        if($requestData['attempt_id'] == 0){
            $this->QuizAttempts->fill($data);
            $this->QuizAttempts->save();
            $quizAttemptId = $this->QuizAttempts->id;
        }else{
            $quizAttemptId = $requestData['attempt_id'];
        }
        //$answers = $requestData['answers'];
        $ans = $requestData['answer'];

        QuizAttemptAnswer::create([
            'quiz_attempts_id' => $quizAttemptId,
            'quiz_question_id' => $ans['question_id'],
            'question_option_id' => $ans['option_id'],
        ]);
        /*foreach ($answers as $ans) {
            //Log::info('quiz attempt id'.$quizAttemptId);
            QuizAttemptAnswer::create([
                'quiz_attempts_id' => $quizAttemptId,
                'quiz_question_id' => $ans['question_id'],
                'question_option_id' => $ans['option_id'],
            ]);
        }*/

        $correctAnswerCount = QuizAttemptAnswer::with('option')
                                ->where('quiz_attempts_id', $quizAttemptId)
                                ->whereHas('option', function ($optionQuery) {
                                    $optionQuery->where('is_correct', 1);
                                })
                                ->count();

        $score = QuizAttempts::where('id', $quizAttemptId)->update(['score' => $correctAnswerCount]);

        /*$score = DB::table('quiz_attempts')
            ->where('id', $quizAttemptId)
            ->update(['score' => $correctAnswerCount]);*/

        /*$totalQuestions = DB::table('quiz_attempt_answers')
                    ->where('quiz_attempts_id', $quizAttemptId)
                    ->count();*/
        $totalQuestions = QuizAttemptAnswer::where('quiz_attempts_id', $quizAttemptId)->count();

        return response()->json([
            'quizAttemptId' => $quizAttemptId,
            'success' => true,
            'message' => 'All answers submitted successfully.',
            'totalQuestions' => $totalQuestions,
            'correctAnswerCount' => $correctAnswerCount,
        ]);
    }
    public function QuizAttemptAnswer($requestData){
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
