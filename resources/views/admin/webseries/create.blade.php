@extends('admin.layouts.master')

@section('content')
{{ Form::model($model, ['route' => ['admin.webseries.createsave'], 'method' => 'post']) }}

  <div class="row">
    <div class="col-md-8">
      <div class="container-fluid">
          <h2 class="pb-2 border-bottom d-flex justify-content-between align-items-center">
              <span>Create New WebSeries</span>
              @if(!empty($model->id))
              <a href="{{ route('admin.webseries.createfolder', $model->id) }}" class="btn btn-dark">
                  @if(!empty($model->mediaFolder)) View Folder @else + Add Folder @endif
              </a>
              @endif
          </h2>
          <div class="form-row">
              <label class="form-label" for="name">Title <em>*</em></label>
              <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $model->title ?? '') }}">

          </div>
          <div class="form-row col-md-12">
              <div class="form-row btnaction">
                  <button type="submit" class="btn btn-primary">Create</button>
                  <a href="{{ route('admin.webseries.index') }}" class="btn btn-secondary backbtn">Back</a>
              </div>
          </div>
      </div>
    </div>
  </div>

{{ Form::close() }}
@endsection






