@extends('admin.layouts.master')

@section('content')
{{ Form::model($model, ['route' => ['admin.webseries.editsave', $model->id], 'method' => 'post']) }}

  <div class="row">
    <div class="col-md-8">
      <div class="container-fluid">
        @include('admin.common.message')
        <h2 class="pb-2 border-bottom a-center">Create New WebSeries</h2>
          <div class="form-row">
              <label class="form-label" for="name">Title <em>*</em></label>
              <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $model->title ?? '') }}">

          </div>

          <div class="row mt-1 form-img-upload">
            
            <div class="col-3 themed-grid-col">
                {!! Field::mediaUpload('thumbnail_id','Thumbnail',$model) !!}
            </div>
          </div>
          <div class="row pt-2 form-img-upload">
            
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

                  <div class="form-row">
                    <label for="excerpt" class="form-label">Status</label>
                    <div class="mb-3 form-check form-switch">
                      {{Form::hidden('status',0)}}
                      <input class="form-check-input" type="checkbox" name="status" role="switch" @if(old('status' ,(empty($model->status)?0:1))) checked="" @endif />
                    </div>
                  </div>


                  <div class="form-row">
                    <label for="excerpt" class="form-label">Free Access?</label>
                    <div class="mb-3 form-check form-switch">
                      {{Form::hidden('movie_access',0)}}
                      <input class="form-check-input" type="checkbox" name="movie_access" role="switch" @if(old('movie_access' ,(empty($model->movie_access)?0:1))) checked="" @endif />
                    </div>
                  </div>

                  <!-- <div class="form-row">
                      <label class="form-label" for="name">Published Date</label>
                      <?php
                      $pubdate=empty($model->published_date)?'':date("Y-m-d",strtotime($model->published_date));
                      ?>
                      <input type="text" class="form-control datepicker_system" id="published_date" name="published_date" value="{{ old('published_date',$pubdate) }}" >
                  </div>
                  <div class="form-row">
                      <label class="form-label" for="name">Release Date <em>*</em></label>
                      <?php
                      $pubdate=empty($model->release_date)?'':date("Y-m-d",strtotime($model->release_date));
                      ?>
                      <input type="text" class="form-control datepicker_system" id="release_date" name="release_date" value="{{ old('release_date',$pubdate) }}" >
                  </div>
                  <div class="form-row">
                      <label class="form-label" for="urlkey">URL Key</label>
                      <input type="text" class="form-control" id="urlkey" name="urlkey" value="{{ old('urlkey',$model->urlkey) }}">
                      <div class="comment">eg: loriem-ipsum</div>
                  </div> -->
                  

                  <div class="form-row col-md-12">
                      <div class="form-row btnaction">
                          <button type="submit" class="btn btn-primary">Update</button>
                          <a href="{{ route('admin.webseries.index') }}" class="btn btn-secondary backbtn">Back</a>
                      </div>
                  </div>
              </div>
            </div>


          
        </div>
    </div>
</div>

{{ Form::close() }}

@endsection






