<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TamilQuizSeeder extends Seeder
{
    public function run()
    {
        /*
        |--------------------------------------------------------------------------
        | File Paths
        |--------------------------------------------------------------------------
        */

        $questionsFile = database_path('data/questions_tamil.json');

        $optionsFile = database_path('data/question_options_tamil.json');

        if (!file_exists($questionsFile)) {
            dd('questions_tamil.json not found');
        }

        if (!file_exists($optionsFile)) {
            dd('question_options_tamil.json not found');
        }

        /*
        |--------------------------------------------------------------------------
        | Store English Question ID => Tamil Question ID
        |--------------------------------------------------------------------------
        */

        $questionIdMap = [];

        /*
        |--------------------------------------------------------------------------
        | Insert Tamil Questions
        |--------------------------------------------------------------------------
        */

        $questionLines = file(
            $questionsFile,
            FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
        );

        foreach ($questionLines as $line) {

            $line = trim($line);

            $line = rtrim($line, ",");

            $question = json_decode($line, true);

            if (!$question) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Skip if already exists but store mapping
            |--------------------------------------------------------------------------
            */

            $existingQuestion = DB::table('questions')
                ->where('question_name', $question['question_name_tamil'])
                ->where('language', 'tamil')
                ->first();

            if ($existingQuestion) {
                $questionIdMap[$question['id']] = $existingQuestion->id;
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Insert Tamil Question
            |--------------------------------------------------------------------------
            */

            $newQuestionId = DB::table('questions')->insertGetId([

                'movie_id'           => $question['movie_id'],

                'question_name'      => $question['question_name_tamil'],

                'show_question_time' => $question['show_question_time'],

                'show_time_hour'     => $question['show_time_hour'],

                'show_time_min'      => $question['show_time_min'],

                'show_time_sec'      => $question['show_time_sec'],

                'has_been_shown'     => 0,

                'is_active'          => 1,

                'language'           => 'tamil',

                'created_at'         => Carbon::now(),

                'updated_at'         => Carbon::now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Store ID Mapping
            |--------------------------------------------------------------------------
            */

            $questionIdMap[$question['id']] = $newQuestionId;

            echo "Inserted Tamil Question: {$newQuestionId}" . PHP_EOL;
        }

        /*
        |--------------------------------------------------------------------------
        | Insert Tamil Options
        |--------------------------------------------------------------------------
        */

        $optionLines = file(
            $optionsFile,
            FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
        );

        foreach ($optionLines as $line) {

            $line = trim($line);

            $line = rtrim($line, ",");

            $option = json_decode($line, true);

            if (!$option) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Find Tamil Question ID
            |--------------------------------------------------------------------------
            */

            $newQuestionId =
                $questionIdMap[$option['question_id']] ?? null;

            if (!$newQuestionId) {

                echo "Question ID mapping not found for option ID: "
                    . $option['id']
                    . PHP_EOL;

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Insert Tamil Option if it doesn't already exist
            |--------------------------------------------------------------------------
            */

            $optionExists = DB::table('question_options')
                ->where('question_id', $newQuestionId)
                ->where('name', $option['name_tamil'])
                ->exists();

            if (!$optionExists) {
                DB::table('question_options')->insert([
                    
                    'question_id' => $newQuestionId,

                    'name'        => $option['name_tamil'],

                    'is_correct'  => $option['is_correct'],

                    'created_at'  => Carbon::now(),

                    'updated_at'  => Carbon::now(),
                ]);

                echo "Inserted Tamil Option: {$option['id']}" . PHP_EOL;
            } else {
                echo "Tamil Option already exists: {$option['id']}" . PHP_EOL;
            }
        }

        echo 'Tamil Questions & Options Imported Successfully' . PHP_EOL;
    }
}