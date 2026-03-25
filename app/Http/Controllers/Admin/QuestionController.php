<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\editQuestionRequest;
use App\Models\QuestionModel;

class QuestionController extends Controller
{
    private $QuestionModel;

    public array $options = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(QuestionModel $QuestionModel)
    {
        $this->middleware('auth');
        $this->QuestionModel = $QuestionModel;
    }

    public function list(int $movieId){
        $questions = $this->QuestionModel->getQuestions($movieId); 
        return view('admin.questions.question-list', compact('questions', 'movieId'));
    }

    public function create($movieId){
        //$options = [];
        return view('admin.questions.question-form', ['movieId' => $movieId]);
    }
    public function saveQuestion(StoreQuestionRequest $request){
        $validated = $request->validated();
        $requestData = $request->all();

        $requestData['show_time_hour'] = (int) $request->input('show_time_hour');
        $requestData['show_time_min']  = (int) $request->input('show_time_min');
        $requestData['show_time_sec']  = (int) $request->input('show_time_sec');
        $show_question_time = ($requestData['show_time_hour'] * 60) + $requestData['show_time_min'];
        $requestData['show_question_time'] = $show_question_time;
        
        return $this->QuestionModel->createQuestion($requestData);
    }
    public function edit(Request $request,int $movieId, int $questionId){
        $questionDetail = $this->QuestionModel->getQuestionOptionsById($questionId);
        $page = $request->page;
        return view('admin.questions.question-form', ['movieId' => $movieId,'questionDetail' => $questionDetail,'page' => $page]);
    }
    public function updateQuestion(editQuestionRequest $request){
        $validated = $request->validated();
        $requestData = $request->all();
        return $this->QuestionModel->updateQuestion($requestData);
    }
    public function deleteQuestion(int $movieId, int $questionId)
    {
        $this->QuestionModel->deleteQuestion($questionId);
        return redirect()
            ->route('admin.questions.list', ['movieId'=>$movieId, 'page'=>request()->page])
            ->with('success','Deleted Successfully');
    }
    public function bulkDeleteQuestion(Request $request, int $movieId)
    {
        $questionIds = $request->question_ids;
        if (!empty($questionIds)) {
            $this->QuestionModel->bulkDeleteQuestion($questionIds);
        }
        return redirect()
            ->route('admin.questions.list', [
                'movieId'=>$movieId,
                'page'=>$request->page
            ])
            ->with('success','Selected Questions Deleted Successfully');
    }
}
