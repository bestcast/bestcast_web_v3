@extends('admin.layouts.master')


@section('content')
    @include('admin.common.message')

    <h2 class="pb-3 border-bottom">Media: {{ $model->title  }} </h2> <br>

    {{ Form::model($model, ['route' => ['admin.media.editsave', $model->id], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
      <div class="form-row">
          <label for="icon">Upload Image</label>
          {!! Field::file('urlkey',$model->urlkey) !!}
      </div>
      <div class="form-row">
          <label for="title">Title</label>
          <input type="text" class="form-control" id="title" name="title" value="{{ old('title',$model->title) }}" >
      </div>
      <div class="form-row">
          <label for="alt">Alt</label>
          <input type="text" class="form-control" id="alt" name="alt" value="{{ old('alt',$model->alt) }}" >
      </div>
      <div class="form-row">
        <label for="excerpt">Short Description</label>
        <textarea class="form-control editor" name="excerpt" rows="5">{{ old('excerpt',$model->excerpt) }}</textarea>
      </div>
      @include('admin.common.userinfo') 
      <div class="form-row mt-3 col-md-12">
          <a href="{{ route('admin.media.index') }}" class="btn btn-secondary backbtn">Back</a>
          <a href="{{ route('admin.media.view',$model->id) }}" class="btn btn-info">View</a>       
          <a class="btn btn-danger btn-delete-copy-{{ $model->id }}"  data-bs-toggle="modal" data-bs-target="#delete{{ $model->id }}">Delete</a>
          @php ($delid=$model->id)
          @php ($delurl=route('admin.media.delete',$model->id))
          @include('admin.common.modaldelete')
          <button type="submit" class="btn btn-primary">Update</button>
      </div>
    {{ Form::close() }}


@endsection



