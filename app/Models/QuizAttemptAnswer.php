<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttemptAnswer extends Model
{

    protected $table = 'quiz_attempt_answers';

    protected $fillable = [
        'quiz_attempts_id',
        'user_id',
        'movie_id',
        'quiz_question_id',
        'question_option_id',
        'answered_seconds',
    ];

    public function quizattempts(): BelongsTo
    {
        return $this->belongsTo(QuizAttempts::class, 'quiz_attempts_id');
    }
    public function attempt()
    {
        return $this->belongsTo(QuizAttempts::class, 'quiz_attempts_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'quiz_question_id');
    }
    public function option()
    {
        return $this->belongsTo(QuestionOptions::class, 'question_option_id');
    }
}
