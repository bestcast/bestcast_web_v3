<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class QuizAttempts extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'participant_id',
        'movie_id',
        'score',
        'quiz_attempts_id'
    ];

    public function quizattemptanswer(): HasMany
    {
        return $this->hasMany(QuizAttemptAnswer::class)->orderBy('id','asc');
    }
}
