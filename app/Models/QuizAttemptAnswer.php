<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttemptAnswer extends Model
{
    protected $fillable = [
        'quiz_attempts_id',
        'quiz_question_id',
        'question_option_id',
    ];

    public function quizattempts(): BelongsTo
    {
        return $this->belongsTo(QuizAttempts::class);
    }
    public function option()
    {
        return $this->belongsTo(QuestionOptions::class, 'question_option_id');
    }
}
