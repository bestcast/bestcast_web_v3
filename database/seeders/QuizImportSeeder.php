<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class QuizImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $path = database_path('data/quiz.json');

        if (!file_exists($path)) {
            dd('quiz.json file not found');
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {

            $item = json_decode($line, true);

            if (!$item) {
                continue; // skip invalid line
            }

            // Check duplicate
            $exists = DB::table('questions')
                ->where('question_name', $item['question']['text'])
                ->exists();

            if ($exists) {
                continue;
            }
            $seconds = $item['quiz_rules']['ask_after_seconds'];

            // Convert seconds → total minutes
            $totalMinutes = floor($seconds / 60);

            // Now calculate hour/min/sec FROM MINUTES (same as your old logic)
            $hour = floor($totalMinutes / 60);
            $min  = $totalMinutes % 60;
            $sec  = $seconds % 60; // remaining seconds (optional)

            $questionId = DB::table('questions')->insertGetId([
                'movie_id'          => 17,
                'question_name'     => $item['question']['text'],
                'show_question_time'=> $totalMinutes,  // stored as minutes
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
