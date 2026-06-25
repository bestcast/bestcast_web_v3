@extends('admin.layouts.master')
@section('content')
    
    @include('admin.common.message')
    <style type="text/css">
        .language-filter-select {
            width: auto;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
    </style>
    <h2 class="pb-3 border-bottom">
        Questions
        <a href="{{ route('admin.movies.edit', ['id' => $movieId]) }}" class="btn btn-secondary float-right addnewbtn">Back</a>
        <a href="{{ route('admin.questions.createQuestion', ['movieId' => $movieId]) }}" class="btn btn-secondary float-right addnewbtn">+ Add New Question</a>
    </h2>

    <div class="mb-3 d-flex align-items-center" style="gap:10px;">
        <span class="badge badge-primary">
            English Questions: {{ $englishCount }}
        </span>
        <span class="badge badge-success">
            Tamil Questions: {{ $tamilCount }}
        </span>

        <form method="GET" action="{{ route('admin.questions.list', $movieId) }}" style="margin-left:auto;">
            <select name="language" class="form-control" onchange="this.form.submit()" style="width:auto; padding:6px 12px; border:1px solid #ccc; border-radius:6px; display:inline-block;">
                <option value="">All Languages</option>
                <option value="english" {{ request('language') == 'english' ? 'selected' : '' }}>English</option>
                <option value="tamil" {{ request('language') == 'tamil' ? 'selected' : '' }}>Tamil</option>
            </select>
        </form>
    </div>

    <form method="POST" action="{{ route('admin.questions.bulkDelete', $movieId) }}">
        <input type="hidden" name="page" value="{{ request()->page }}">
        <input type="hidden" name="language" value="{{ request()->language }}">
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
                            <td align="center">
                                <a href="{{ route('admin.questions.editQuestion', ['movieId' => $movieId,'questionId' => $question->id,'page' => request()->page, 'language' => request()->language]) }}" class="btn btn-primary btn-sm">Edit</a>
                                <a href="{{ route('admin.questions.deleteQuestion', ['movieId' => $movieId,'questionId' => $question->id,'page' => request()->page, 'language' => request()->language]) }}" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-3">
                {{ $questions->links() }}
            </div>
        </div>
    </form>

<script>
document.getElementById('checkAll').addEventListener('click', function(){
    let checkboxes = document.querySelectorAll('.rowCheckbox');
    checkboxes.forEach(function(box){
        box.checked = event.target.checked;
    });
});
</script>
@endsection