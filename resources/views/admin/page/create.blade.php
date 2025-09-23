@extends('admin.layouts.master')


@section('content')


{{ Form::model('', ['route' => ['admin.page.createsave'], 'method' => 'post']) }}
  <div class="small-grid">
    @include('admin.common.message')
    <h2 class="pb-2 border-bottom a-center">Create New Page</h2>
    <input type="hidden" class="form-control" id="urlkey" name="urlkey" value="{{ Str::uuid(time()) }}">
    <input type="hidden" class="form-control" id="type" name="type" value="page" />

    <div class="form-row">
        <label for="name" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" >
    </div>
    
    <div class="form-row">
      <label for="excerpt" class="form-label">Template</label>
      {{ Form::select('template', Field::template(), old('template'), array('class' => 'form-select')); }}
    </div>
    <div class="form-row a-center btnaction">
        <button type="submit" class="btn btn-primary btn-lg">Continue</button> &nbsp; 
        <a href="{{ route('admin.page.index') }}" class="btn btn-secondary btn-lg backbtn">Back</a>
    </div>
  </div>
{{ Form::close() }}


@endsection



