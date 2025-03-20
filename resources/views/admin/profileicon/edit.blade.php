@extends('admin.layouts.master')

@section('content')
{{ Form::model($model, ['route' => ['admin.profileicon.editsave', $model->id], 'method' => 'post']) }}

  <div class="row">
    <div class="col-md-8">
      <div class="container-fluid">
        @include('admin.common.message')
          <h2 class="pb-2 border-bottom">Edit Profile Icon : {{ $model->title  }} </h2> <br>
          <div class="form-row">
              <label class="form-label" for="name">Title</label>
              <input type="text" class="form-control" id="title" name="title" value="{{ old('title',$model->title) }}" >
          </div>

          <div class="row pt-2 form-img-upload">
            <div class="col-3 themed-grid-col">
                {!! Field::mediaUpload('thumbnail_id','Thumbnail',$model) !!}
            </div>
            <div class="col-3 themed-grid-col">
                <p><b>Thumbnail</b> (200X200)</p>
            </div>
          </div>
    </div>
  </div>
  <div class="col-md-4">
        <div class="container">
          <div class="card">
              <div class="card-header">Option</div>
              <div class="card-body">
                  <input type="hidden" class="form-control" id="urlkey" name="urlkey" value="{{ old('urlkey',$model->urlkey) }}">
                  <div class="form-row col-md-12">
                      <div class="form-row btnaction">
                          <button type="submit" class="btn btn-primary">Update</button>
                          <a href="{{ route('admin.profileicon.index') }}" class="btn btn-secondary backbtn">Back</a>
                      </div>
                  </div>
              </div>
              <div class="card-footer text-muted">
                @include('admin.common.userinfo') 
                <div class="deleteaction">
                  <a class="btn btn-danger btn-delete-copy-{{ $model->id }}"  data-bs-toggle="modal" data-bs-target="#delete{{ $model->id }}">Delete</a>
                  @php ($delid=$model->id)
                  @php ($delurl=route('admin.profileicon.delete',$model->id))
                  @include('admin.common.modaldelete')
                </div>
              </div>
            </div>
          
        </div>
    </div>
</div>

{{ Form::close() }}

@endsection


