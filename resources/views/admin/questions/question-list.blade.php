@extends('admin.layouts.master')


@section('content')
    
    @include('admin.common.message')

    <h2 class="pb-3 border-bottom">
        Questions
        <a href="{{ route('admin.questions.createQuestion', ['movieId' => $movieId]) }}" class="btn btn-secondary float-right addnewbtn">+ Add New Question</a>
    </h2>

    <div class="table">
        <table class="table table-bordered yajra-datatable">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th class="text-center">Question</th>
                    <th class="text-center">Question Showing Time</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($questions as $index => $question)
                    <tr>
                        <td>{{ $questions->firstItem() + $index }}</td>
                        <td>{{ $question->question_name }}</td>
                        <td align="center">{{ $question->show_question_time }}</td>
                        <td align="center"><a href="{{ route('admin.questions.editQuestion', ['movieId' => $movieId,'questionId' => $question->id]) }}" class="btn btn-primary btn-sm">Edit</a>
                            <a href="{{ route('admin.questions.deleteQuestion', ['movieId' => $movieId,'questionId' => $question->id]) }}" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $questions->links() }}
        </div>
    </div>
@endsection



