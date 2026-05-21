@extends('admin.layouts.master')


@section('content')
    
    @include('admin.common.message')

    <h2 class="pb-3 border-bottom">
        Questions
        <a href="{{ route('admin.questions.createQuestion', ['movieId' => $movieId]) }}" class="btn btn-secondary float-right addnewbtn">+ Add New Question</a>
    </h2>
    <form method="POST" action="{{ route('admin.questions.bulkDelete', $movieId) }}">
    <input type="hidden" name="page" value="{{ request()->page }}">
    @csrf

    <div class="mb-2">
        <button type="submit" class="btn btn-danger btn-sm"
            onclick="return confirm('Delete selected questions?')">
            Delete Selected
        </button>
    </div>
    <div class="table">
        <table class="table table-bordered yajra-datatable">
            <thead>
                <tr>
                    <th><input type="checkbox" id="checkAll"></th>
                    <th>S.No1</th>
                    <th class="text-center">Question</th>
                    <th class="text-center">Language</th>
                    <th class="text-center">Question Showing Time</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($questions as $index => $question)
                    <tr>
                        <td><input type="checkbox" name="question_ids[]" value="{{ $question->id }}" class="rowCheckbox"></td>
                        <td>{{ $questions->firstItem() + $index }}</td>
                        <td>{{ $question->question_name }}</td>
                        <td align="center">{{ ucfirst($question->language) }}</td>
                        <td align="center">{{ $question->show_question_time }}</td>
                        <td align="center"><a href="{{ route('admin.questions.editQuestion', ['movieId' => $movieId,'questionId' => $question->id,'page' => request()->page]) }}" class="btn btn-primary btn-sm">Edit</a>
                            <!-- <a href="{{ route('admin.questions.deleteQuestion', ['movieId' => $movieId,'questionId' => $question->id]) }}" class="btn btn-danger btn-sm">Delete</a> -->
                            <a href="{{ route('admin.questions.deleteQuestion', ['movieId' => $movieId,'questionId' => $question->id,'page' => request()->page]) }}" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $questions->links() }}
        </div>
    </div>
<script>
document.getElementById('checkAll').addEventListener('click', function(){
    let checkboxes = document.querySelectorAll('.rowCheckbox');
    checkboxes.forEach(function(box){
        box.checked = event.target.checked;
    });
});
</script>
@endsection



