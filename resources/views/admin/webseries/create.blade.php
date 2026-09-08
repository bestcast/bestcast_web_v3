@extends('admin.layouts.master')

@section('content')
{{ Form::model($model, ['route' => ['admin.webseries.createsave'], 'method' => 'post']) }}

  <div class="row">
    <div class="col-md-8">
      <div class="container-fluid">
        @include('admin.common.message')
          <h2 class="pb-2 border-bottom d-flex justify-content-between align-items-center">
              <span>Create New WebSeries</span>
              @if(!empty($model->id))
              <a href="{{ route('admin.webseries.createfolder', $model->id) }}" class="btn btn-dark">
                  @if(!empty($model->mediaFolder)) View Folder @else + Add Folder @endif
              </a>
              @endif
          </h2>

          @if(!empty($model->id) && empty($model->mediaFolder))
              <div class="alert alert-warning py-2 px-3 mb-3">
                  No folder created yet for this webseries. Click <strong>"+ Add Folder"</strong> above before uploading images.
              </div>
          @endif
          <div class="form-row">
              <label class="form-label" for="name">Title <em>*</em></label>
              <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $model->title ?? '') }}">

          </div>

          <div class="row mt-1 form-img-upload">
            <div class="col-3 themed-grid-col">
                {!! Field::mediaUpload('image_id','Image',$model, optional($model->mediaFolder)->id) !!}
            </div>
            <div class="col-3 themed-grid-col">
                {!! Field::mediaUpload('medium_id','Medium',$model, optional($model->mediaFolder)->id) !!}
            </div>
            <div class="col-3 themed-grid-col">
                {!! Field::mediaUpload('thumbnail_id','Thumbnail',$model, optional($model->mediaFolder)->id) !!}
            </div>
          </div>
          <div class="row pt-2 form-img-upload">
            <div class="col-3 themed-grid-col">
                {!! Field::mediaUpload('portraitsmall_id','Portrait Small',$model, optional($model->mediaFolder)->id) !!}
            </div>
            <div class="col-3 themed-grid-col">
                {!! Field::mediaUpload('portrait_id','Portrait',$model, optional($model->mediaFolder)->id) !!}
            </div>
            <div class="col-3 themed-grid-col">
                <p><b>Image</b> (1920X1080)<br><b>Medium</b> (720X405)<br><b>Thumbnail</b> (360X203)<br><b>Portrait Small</b> (400X600)<br><b>Portrait</b> (1000X1500)</p>
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
                          <button type="submit" class="btn btn-primary">Create</button>
                          <a href="{{ route('admin.webseries.index') }}" class="btn btn-secondary backbtn">Back</a>
                      </div>
                  </div>
              </div>
            </div>


          
        </div>
    </div>
</div>

{{ Form::close() }}
<script>
var mediaPickerChannel = new BroadcastChannel('media_picker_channel');
mediaPickerChannel.onmessage = function(e){
    var data = e.data;
    if (!data || data.type !== 'mediaSelected') return;
    var field = data.field, id = data.id, fullurl = data.fullurl;
    var hidden = document.getElementById(field);
    if (hidden) hidden.value = id;
    var btn = document.querySelector('.um-' + field);
    if (btn) {
        var container = btn.parentNode.querySelector('.imgContainer');
        if (container) {
            var hLTIn = container.querySelector('.hLTIn');
            if (hLTIn) hLTIn.innerHTML = '<div class="hLTImg"><img src="' + fullurl + '" /></div>';
            var removeBtn = container.querySelector('.btnremove');
            if (removeBtn) removeBtn.classList.add('active');
        }
        var span = btn.querySelector('span');
        if (span) span.innerHTML = 'Change';
    }
};
</script>
@endsection






