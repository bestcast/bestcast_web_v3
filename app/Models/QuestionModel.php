<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Question;
use App\Models\QuestionOptions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class QuestionModel extends Model
{
    private $question;
    private $questionOptions;

    /**
     * Create a new model instance.
     *
     * @return void
     */
    public function __construct(Question $question, QuestionOptions $questionOptions)
    {
        $this->question = $question;
        $this->questionOptions = $questionOptions;
    }

    /**
     * Get list of questions based on movie id
     *
     * Integer $movieId
     * @return Array
     */
    public function getQuestions(int $movieId):LengthAwarePaginator{
        return $this->question
            ->where('movie_id', $movieId)
            ->orderBy('show_question_time', 'asc')
            ->orderBy('id')
            ->paginate(25);
    }
    /**
     * Create a new question and options.
     *
     * Array $requestData
     * @return redirectResponse
     */
    public function createQuestion(Array $requestData):RedirectResponse{
        $this->question->fill($requestData);
        $this->question->save();

        //if(isset($requestData))

        $questionId = $this->question->id;
        foreach($requestData['options'] as $index => $optionText){
            $isCorrect = $index == $requestData['correct_option'] ? 1 : 0;
            $this->question->options()->create([
                'name' => $optionText,
                'is_correct' => $isCorrect,
            ]);
        }
        return to_route('admin.questions.createQuestion',$requestData['movie_id'])->with('success', 'Question Created Successfully');
    }
    /**
     * Create a new question and options.
     *
     * Int $questionId
     * @return Array
     */
    public function getQuestionOptionsById(int $questionId):object{
        return $this->question->with('options')->where('id',$questionId)->first();
    }

    /**
     * Update question and options.
     *
     * Array $requestData
     * @return redirectResponse
     */
    public function updateQuestion(Array $requestData):RedirectResponse{
        $question = $this->question->findOrFail($requestData['question_id']);
        $question->question_name = $requestData['question_name'];
        $question->show_time_hour = $requestData['show_time_hour'];
        $question->show_time_min = $requestData['show_time_min'];
        $question->show_time_sec = $requestData['show_time_sec'];
        $question->show_question_time = ($requestData['show_time_hour'] * 60) + $requestData['show_time_min'];
        $question->language = $requestData['language'];
        $question->save();

        $existingOptionIds = $requestData['option_ids'] ?? []; // old IDs from form
        $newOptions = [];
        $submittedOptionIds = [];

        //if(isset($requestData))

        $questionId = $question->id;
        foreach($requestData['options'] as $index => $optionText){
            $isCorrect = $index == $requestData['correct_option'] ? 1 : 0;
            if (!empty($existingOptionIds[$index])) {
                // UPDATE
                $option = $this->questionOptions->find($existingOptionIds[$index]);
                if ($option) {
                    $option->name = $optionText;
                    $option->is_correct = $isCorrect;
                    $option->save();
                    $submittedOptionIds[] = $option->id;
                }
            } else {
                // CREATE
                $new = $this->questionOptions->create([
                    'question_id' => $question->id,
                    'name' => $optionText,
                    'is_correct'=>$isCorrect
                ]);
                $submittedOptionIds[] = $new->id;
            }
        }
        // DELETE removed options
        $this->questionOptions->where('question_id', $question->id)
        ->whereNotIn('id', $submittedOptionIds)
        ->delete();
        return to_route('admin.questions.editQuestion',['movieId'=>$requestData['movie_id'],'questionId' => $requestData['question_id'],'page' => $requestData['page']])->with('success', 'Updated Successfully');
    }
    /**
     * Delete question by id
     *
     * Integer $questionId
     * @return Array
     */
    public function deleteQuestion(int $questionId):int{
        return $this->question->where('id',$questionId)->delete();
    }
    public function bulkDeleteQuestion(array $questionIds): int
    {
        return $this->question->whereIn('id', $questionIds)->delete();
    }
}
