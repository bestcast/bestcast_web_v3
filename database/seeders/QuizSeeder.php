<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
         $path = database_path('data/NEW_QUIZ_BANK.json');

    if (!file_exists($path)) {
        dd('NEW_QUIZ_BANK.json file not found');
    }

    $data = json_decode(file_get_contents($path), true);

    foreach ($data as $item) {

        $exists = DB::table('questions')
            ->where('question_name', $item['question']['text'])
            ->exists();

        if ($exists) {
            continue;
        }

        $seconds = $item['quiz_rules']['ask_after_seconds'];

        $totalMinutes = floor($seconds / 60);
        $hour = floor($totalMinutes / 60);
        $min  = $totalMinutes % 60;
        $sec  = $seconds % 60;

        $questionId = DB::table('questions')->insertGetId([
            'movie_id'          => 17,
            'question_name'     => $item['question']['text'],
            'show_question_time'=> $totalMinutes,
            'show_time_hour'    => $hour,
            'show_time_min'     => $min,
            'show_time_sec'     => $sec,
            'has_been_shown'    => 0,
            'is_active'         => 1,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        foreach ($item['options'] as $option) {
            DB::table('question_options')->insert([
                'question_id' => $questionId,
                'name'        => $option['text'],
                'is_correct'  => !empty($option['is_correct']) ? 1 : 0,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ]);
        }
    }

    dd('Import Completed Successfully');
    }
}
