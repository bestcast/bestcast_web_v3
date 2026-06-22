<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use app\User;
use App\Models\Movie;

class QuizAttempts extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'quiz_attempts';

    protected $fillable = [
        'participant_id',
        'movie_id',
        'score',
        'started_at',
        'ended_at',
        'quiz_attempts_id'
    ];

    public function quizattemptanswer(): HasMany
    {
        return $this->hasMany(QuizAttemptAnswer::class)->orderBy('id','asc');
    }

    public function assignedQuestions()
    {
        return $this->hasMany(QuizAttemptQuestionMap::class, 'attempt_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'participant_id');
    }

    public function movie()
    {
        return $this->belongsTo(Movies::class, 'movie_id');
    }
}