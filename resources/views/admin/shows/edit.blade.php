@extends('admin.layouts.master')


@section('content')
{{ Form::model($model, ['route' => ['admin.shows.editsave', $model->id], 'method' => 'post']) }}
          <input type="hidden" class="form-control" id="urlkey" name="urlkey" value="{{ $model->urlkey }}">

<div class="small-grid">
        @include('admin.common.message')
          <h2 class="pb-2 border-bottom">Edit Movie : {{ $model->title  }} </h2>


          <div class="form-row">
            <label for="excerpt" class="form-label">Status</label>
            <div class="mb-3 form-check form-switch">
              {{Form::hidden('status',0)}}<input class="form-check-input" type="checkbox" name="status" role="switch" @if(old('status',(empty($model->status)?0:1))) checked="" @endif />
            </div>
          </div>
          
          <div class="form-row">
              <label class="form-label" for="name">Title</label>
              <input type="text" class="form-control" id="title" name="title" value="{{ old('title',$model->title) }}" >
          </div>



          <div class="form-row a-center btnaction">
              <button type="submit" class="btn btn-primary">Update</button>
              <a href="{{ route('admin.shows.index') }}" class="btn btn-secondary backbtn">Back</a>
          </div>
</div>

{{ Form::close() }}

@endsection

