<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'movie_id',
        'question_name',
        'show_time_hour',
        'show_time_min',
        'show_time_sec',
        'show_question_time',
        'is_active',
    ];

    public function options(): HasMany
    {
        return $this->hasMany(QuestionOptions::class)->orderBy('id','asc');
    }

    public static function getQuestionsWithOptions($movieId)
    {
        return self::with('options')
                    ->where('movie_id', $movieId)
                    ->inRandomOrder() // <-- this line randomizes the order
                    ->get();
    }
}
