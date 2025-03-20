@extends('admin.layouts.master')


@section('content')


{{ Form::model('', ['route' => ['admin.post.createsave'], 'method' => 'post']) }}
    <div class="small-grid">
        @include('admin.common.message')
        <h2 class="pb-2 border-bottom a-center">Create New Category</h2>
        <input type="hidden" class="form-control" id="urlkey" name="urlkey" value="{{ Str::uuid(time()) }}">
        <input type="hidden" class="form-control" id="type" name="type" value="category">

        <div class="form-row">
            <label for="name" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" >
        </div>
        
        <div class="form-row dnn">
          <label for="excerpt" class="form-label">Template</label>
          @php ($val=old('template'))
          @php ($val=empty($val)?1:$val)
          {{ Form::select('template', Field::template(), $val, array('class' => 'form-select')); }}
        </div>

        <div class="form-row a-center">
            <a href="{{ route('admin.post.category') }}" class="btn btn-secondary btn-lg backbtn">Back</a> &nbsp; 
            <button type="submit" class="btn btn-primary btn-lg">Continue</button>
        </div>
    </div>
    
{{ Form::close() }}


@endsection



