<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttemptQuestionMap extends Model
{

    protected $table = 'quiz_attempt_question_maps';

    protected $fillable = [
        'attempt_id',
        'user_id',
        'question_id'
    ];

    public function attempt()
    {
        return $this->belongsTo(QuizAttempts::class, 'attempt_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
