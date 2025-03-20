@extends('admin.layouts.master')



@section('content')
{{ Form::model($model, ['route' => ['admin.appnotify.editsave', $model->id], 'method' => 'post']) }}

  <div class="row">
    <div class="col-md-8">
      <div class="container-fluid">
        @include('admin.common.message')
          <h2 class="pb-2 border-bottom">Edit App Notification : {{ $model->title  }} </h2> <br>
          <div class="form-row">
              <label class="form-label" for="name">Title</label>
              <input type="text" class="form-control" id="title" name="title" value="{{ old('title',$model->title) }}" >
          </div>



          <div class="form-row form-select2" id="movies_dropdown">
            <label class="form-label">Movies</label>
            @php ($selectedValue=empty($model->movie_id)?[]:[$model->movie_id=>$model->movie->title])
            {{ Form::select('movie_id', $selectedValue,old('movie_id',$model->movie_id), array('id'=>'movie_id','class' => 'form-select')); }}
            <!-- <select id="show_ids" name="show_ids[]" class="form-control" multiple></select> -->
            <script>
            jQuery(document).ready(function($) {
              $('#movie_id').select2({
                  placeholder: "Choose Movie...",
                  minimumInputLength: 0,
                  ajax: {
                      url: function (params) {
                        return  "{{ route('admin.movies.searchbytitle') }}/"+params.term;
                      },  dataType: 'json',
                      processResults: function (data) { return {  results: data };  },cache: true
                  }
              });
            });
            </script>
          </div>


          <div class="row pt-2 form-img-upload">
            <div class="col-3 themed-grid-col">
                {!! Field::mediaUpload('thumbnail_id','Thumbnail',$model) !!}
            </div>
            <div class="col-3 themed-grid-col">
                <p><b>Thumbnail</b> (360X203)</p>
            </div>
          </div>


    </div>
  </div>
  <div class="col-md-4">
        <div class="container">
          <div class="card">
              <div class="card-header">Option</div>
              <div class="card-body">

                  <div class="form-row col-md-12">
                      <div class="form-row btnaction">
                          <button type="submit" class="btn btn-primary">Update</button>
                          <a href="{{ route('admin.appnotify.index') }}" class="btn btn-secondary backbtn">Back</a>
                      </div>
                  </div>
              </div>
              <div class="card-footer text-muted">
                @include('admin.common.userinfo') 
                <div class="deleteaction">
                  <a class="btn btn-danger btn-delete-copy-{{ $model->id }}"  data-bs-toggle="modal" data-bs-target="#delete{{ $model->id }}">Delete</a>
                  @php ($delid=$model->id)
                  @php ($delurl=route('admin.appnotify.delete',$model->id))
                  @include('admin.common.modaldelete')
                </div>
              </div>
            </div>


          
        </div>
    </div>
</div>

{{ Form::close() }}

@endsection


