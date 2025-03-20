@extends('admin.layouts.master')

@section('content')
{{ Form::model($model, ['route' => ['admin.movies.editsave', $model->id], 'method' => 'post']) }}

  <div class="row">
    <div class="col-md-8">
      <div class="container-fluid">
        @include('admin.common.message')
          <h2 class="pb-2 border-bottom">Edit subscription : {{ $model->title  }} </h2>
          <div class="form-row">
              <label class="form-label" for="name">Title <em>*</em></label>
              <input type="text" class="form-control" id="title" name="title" value="{{ old('title',$model->title) }}" >
          </div>

          <div class="row mt-1 form-img-upload">
            <div class="col-3 themed-grid-col">
                {!! Field::mediaUpload('image_id','Image',$model) !!}
            </div>
            <div class="col-3 themed-grid-col">
                {!! Field::mediaUpload('medium_id','Medium',$model) !!}
            </div>
            <div class="col-3 themed-grid-col">
                {!! Field::mediaUpload('thumbnail_id','Thumbnail',$model) !!}
            </div>
          </div>
          <div class="row pt-2 form-img-upload">
            <div class="col-3 themed-grid-col">
                {!! Field::mediaUpload('portraitsmall_id','Portrait Small',$model) !!}
            </div>
            <div class="col-3 themed-grid-col">
                {!! Field::mediaUpload('portrait_id','Portrait',$model) !!}
            </div>
            <div class="col-3 themed-grid-col">
                <p><b>Image</b> (1920X1080)<br><b>Medium</b> (720X405)<br><b>Thumbnail</b> (360X203)<br><b>Portrait Small</b> (400X600)<br><b>Portrait</b> (1000X1500)</p>
            </div>
          </div>

          <div class="form-row">
              <label class="form-label" for="name">Tag Text</label>
              <input type="text" class="form-control" id="tag_text" name="tag_text" value="{{ old('tag_text',$model->tag_text) }}" >
              <p class="comment">eg: Voilent, Action, Thriller (visiblity only on movies listing grid)</p>
          </div>
          <div class="form-row">
            <label class="form-label" for="content">Content</label>
            <textarea class="form-control editor-content" name="content" rows="5">{{ old('content',(!empty($model->content)?$model->content:'')) }}</textarea>
            {!! Field::editor('editor-content') !!}
          </div>


          <h3 class="sectitle">Category Details</h3>


          <div class="form-row form-select2 fluid"  id="genres_tagging_box">
            <label class="form-label">Genres</label>
            <select name="genre_id[]" class="form-control" id="genres_tagging" multiple="multiple">
              @if(!empty($model->genres) && count($model->genres))
                @foreach($model->genres as $relShowItem)
                  @if(!empty($relShowItem->genres))
                    <option value="{{$relShowItem->genre_id}}" selected="selected">{{$relShowItem->genres->title}}</option>
                  @endif
                @endforeach
              @endif
            </select>
            <script>
            jQuery(document).ready(function($) {
              $('#genres_tagging').select2({
                  placeholder: "Choose genres...",
                  minimumInputLength: 2,
                  ajax: {
                      url: function (params) {
                        return  "{{ route('admin.genres.searchbytitle') }}/"+params.term;
                      },  dataType: 'json',
                      processResults: function (data) { return {  results: data };  },cache: true
                  }
              });
            });
            </script>
          </div>


          <div class="form-row form-select2 fluid"  id="languages_tagging_box">
            <label class="form-label">Languages</label>
            <select name="language_id[]" class="form-control" id="languages_tagging" multiple="multiple">
              @if(!empty($model->languages) && count($model->languages))
                @foreach($model->languages as $relShowItem)
                  @if(!empty($relShowItem->languages))
                    <option value="{{$relShowItem->language_id}}" selected="selected">{{$relShowItem->languages->title}}</option>
                  @endif
                @endforeach
              @endif
            </select>
            <script>
            jQuery(document).ready(function($) {
              $('#languages_tagging').select2({
                  placeholder: "Choose language...",
                  minimumInputLength: 2,
                  ajax: {
                      url: function (params) {
                        return  "{{ route('admin.languages.searchbytitle') }}/"+params.term;
                      },  dataType: 'json',
                      processResults: function (data) { return {  results: data };  },cache: true
                  }
              });
            });
            </script>
          </div>


          @php($userGroupLabel=App\User::groupLabel())
          @php($userGroupSlug=App\User::groupSlug())
          @foreach($userGroupSlug as $uKey=>$uSlug)
          <div class="form-row form-select2 fluid"  id="{{ $uSlug }}_tagging_box">
            <label class="form-label">{{ $userGroupLabel[$uKey] }}</label>
            <select name="{{ $uSlug }}[]" class="form-control" id="{{ $uSlug }}_tagging" multiple="multiple">
              @if(!empty($model->users) && count($model->users))
                @foreach($model->users as $relShowItem)
                  @if(!empty($relShowItem->users) && ($relShowItem->group==$uKey))
                    <option value="{{$relShowItem->user_id}}" selected="selected">{{$relShowItem->users->name}}</option>
                  @endif
                @endforeach
              @endif
            </select>
            <script>
            jQuery(document).ready(function($) {
              $('#{{ $uSlug }}_tagging').select2({
                  placeholder: "Choose {{ $userGroupLabel[$uKey] }}...",
                  minimumInputLength: 2,
                  ajax: {
                      url: function (params) {
                        return  "{{ route('admin.user.searchcastbyname') }}/"+params.term;
                      },  dataType: 'json',
                      processResults: function (data) { return {  results: data };  },cache: true
                  }
              });
            });
            </script>
          </div>
          @endforeach


          <div class="form-row form-select2 fluid"  id="related_tagging_box">
            <label class="form-label">Related</label>
            <select name="related[]" class="form-control" id="related_tagging" multiple="multiple">
              @if(!empty($model->related) && count($model->related))
                @foreach($model->related as $relShowItem)
                  @if(!empty($relShowItem->movies) && !empty($relShowItem->related))
                    <option value="{{$relShowItem->movie_id}}" selected="selected">{{$relShowItem->related->title}}</option>
                  @endif
                @endforeach
              @endif
            </select>
            <script>
            jQuery(document).ready(function($) {
              $('#related_tagging').select2({
                  placeholder: "Choose movies...",
                  minimumInputLength: 2,
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


          <h3 class="sectitle">Video Information</h3>
          <div class="form-row">
              <label class="form-label" for="name">Trailer URL <em>*</em></label>
              <input type="text" class="form-control full" id="trailer_url" name="trailer_url" value="{{ old('trailer_url',$model->trailer_url) }}" onclick="this.select();">
          </div>
          <div class="form-row">
              <label class="form-label" for="name">Trailer URL (480p)</label>
              <input type="text" class="form-control full" id="trailer_url_480p" name="trailer_url_480p" value="{{ old('trailer_url_480p',$model->trailer_url_480p) }}" onclick="this.select();">
          </div>

          <div class="form-row">
              <label class="form-label" for="name">Video URL <em>*</em></label>
              <input type="text" class="form-control full" id="video_url" name="video_url" value="{{ old('video_url',$model->video_url) }}" onclick="this.select();">
          </div>
          <div class="form-row">
              <label class="form-label" for="name">Video URL (480p)</label>
              <input type="text" class="form-control full" id="video_url_480p" name="video_url_480p" value="{{ old('video_url_480p',$model->video_url_480p) }}" onclick="this.select();">
          </div>
          <div class="form-row">
              <label class="form-label" for="name">Video URL (720p)</label>
              <input type="text" class="form-control full" id="video_url_720p" name="video_url_720p" value="{{ old('video_url_720p',$model->video_url_720p) }}" onclick="this.select();">
          </div>
          <div class="form-row">
              <label class="form-label" for="name">Video URL (1080p)</label>
              <input type="text" class="form-control full" id="video_url_1080p" name="video_url_1080p" value="{{ old('video_url_1080p',$model->video_url_1080p) }}" onclick="this.select();">
          </div>


          <div class="form-row">
              <label class="form-label" for="name">Download Movie URL (mobile app only)</label>
              <input type="text" class="form-control full" id="moviesource" name="moviesource" value="{{ old('moviesource',$model->moviesource) }}" onclick="this.select();">
          </div>





          <h3 class="sectitle">Additional Information</h3>

          <div class="form-group row">
            <div class="col-sm-3">
              <div class="form-row">
                  <label class="form-label" for="name">Certificate</label>
                  <input type="text" class="form-control" id="certificate" name="certificate" value="{{ old('certificate',$model->certificate) }}" >
                  <p class="comment">eg: U/A 13+ </p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-row">
                  <label class="form-label" for="name">Certificate Text</label>
                  <input type="text" class="form-control" id="certificate_text" name="certificate_text" value="{{ old('certificate_text',$model->certificate_text) }}" >
                  <p class="comment">eg: tobacoo, substances, violence </p>
              </div>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-3">
              <div class="form-row">
                  <label class="form-label" for="name">Duration</label>
                  <input type="text" class="form-control" id="duration" name="duration" value="{{ old('duration',$model->duration) }}" >
                  <p class="comment">Duration should be in seconds. eg: 8520 for 2hr 22min</p>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-row">
                  <label class="form-label" for="name">Age Restriction</label>
                  <input type="number" class="form-control" id="age_restriction" name="age_restriction" value="{{ old('age_restriction',$model->age_restriction) }}" >
                  <p class="comment">eg: 18 (above 18yr user only)</p>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-row">
                  <label class="form-label" for="name">Is Upcoming</label>
                  <div class="mb-3 form-check form-switch">
                    {{Form::hidden('is_upcoming',0)}}
                    <input class="form-check-input" type="checkbox" name="is_upcoming" role="switch" @if(old('is_upcoming' ,(empty($model->is_upcoming)?0:1))) checked="" @endif />
                  </div>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-row">
                  <label class="form-label" for="name">Top Ten</label>
                  <div class="mb-3 form-check form-switch">
                    {{Form::hidden('topten',0)}}
                    <input class="form-check-input" type="checkbox" name="topten" role="switch" @if(old('topten' ,(empty($model->topten)?0:1))) checked="" @endif />
                  </div>
              </div>
            </div>
          </div>




          <h3 class="sectitle">Subtitle Details</h3>

          <div class="row">
              <div class="col-4">
                  <div class="form-row">
                    <label for="excerpt" class="form-label">Subtitle Status</label>
                    <div class="mb-3 form-check form-switch">
                      {{Form::hidden('subtitle_status',0)}}
                      <input class="form-check-input" type="checkbox" name="subtitle_status" role="switch" @if(old('subtitle_status' ,(empty($model->subtitle_status)?0:1))) checked="" @endif />
                    </div>
                  </div>
              </div>
          </div>

          <div class="btn-sec">
            <script type="text/javascript">
              function deleteParentOfParent(element) {
                  var grandparent = element.parentNode.parentNode;
                  grandparent.parentNode.removeChild(grandparent);
              }
            </script>
              @php($i=0)
              @if(!empty($model->subtitle) && count($model->subtitle))
                @foreach($model->subtitle as $subtitleItem)
                  @if(!empty($subtitleItem->url) && !empty($subtitleItem->label))
                    <div class="card mb-4">
                        <div class="card-header">Subtitle {{ ($i+1) }} <div onclick="deleteParentOfParent(this)" class="close btn btn-danger btn-sm" style="float: right;">Delete</div></div>
                        <div class="card-body"><input type="hidden" name="subtitle[]" value="1">
                          <div class="row">
                              <div class="col-2">
                                  <div class="form-row">
                                    <label for="excerpt" class="form-label">Is Active</label>
                                    <div class="mb-3 form-check form-switch">
                                      @php($val=empty($subtitleItem->is_active)?0:1)
                                      {{Form::hidden('subtitle_is_active['.$i.']',0)}}
                                      <input class="form-check-input" type="checkbox" name="subtitle_is_active[{{$i}}]" role="switch" @if(old('subtitle_is_active.'.$i,$val)) checked="" @endif />
                                    </div>
                                  </div>
                              </div>
                              <div class="col-10">
                                <div class="form-row">
                                    <label class="form-label">Label</label>
                                     @php($val=empty($subtitleItem->label)?'':$subtitleItem->label)
                                    <input type="text" class="form-control" name="subtitle_label[]" value="{{ old('subtitle_label.'.$i,$val) }}" >
                                </div>
                              </div>
                          </div>
                          <div class="form-row">
                              <label class="form-label">URL</label>
                               @php($val=empty($subtitleItem->url)?'':$subtitleItem->url)
                              <input type="text" class="form-control full" name="subtitle_url[]" value="{{ old('subtitle_url.'.$i,$val) }}" >
                          </div>
                        </div>
                    </div>
                    @php($i++)
                  @endif
                @endforeach
              @endif
              <div class="card mb-4">
                  <div class="card-header">Subtitle New</div>
                  <div class="card-body"><input type="hidden" name="subtitle[]" value="1">
                    <div class="row">
                        <div class="col-2">
                            <div class="form-row">
                              <label for="excerpt" class="form-label">Is Active</label>
                              <div class="mb-3 form-check form-switch">
                                {{Form::hidden('subtitle_is_active['.$i.']',0)}}
                                <input class="form-check-input" type="checkbox" name="subtitle_is_active[{{$i}}]" role="switch" />
                              </div>
                            </div>
                        </div>
                        <div class="col-10">
                          <div class="form-row">
                              <label class="form-label">Label</label>
                              <input type="text" class="form-control" name="subtitle_label[{{$i}}]" value="" >
                          </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <label class="form-label">URL</label>
                        <input type="text" class="form-control full" name="subtitle_url[{{$i}}]" value="" >
                    </div>
                  </div>
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

                  <div class="form-row">
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
                  </div>
                  

                  <div class="form-row col-md-12">
                      <div class="form-row btnaction">
                          <button type="submit" class="btn btn-primary">Update</button>
                          <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary backbtn">Back</a>
                      </div>
                  </div>
              </div>
              <div class="card-footer text-muted">
                @include('admin.common.userinfo') 
                <div class="deleteaction">
                  <a class="btn btn-danger btn-delete-copy-{{ $model->id }}"  data-bs-toggle="modal" data-bs-target="#delete{{ $model->id }}">Delete</a>
                  @php ($delid=$model->id)
                  @php ($delurl=route('admin.movies.delete',$model->id))
                  @include('admin.common.modaldelete')
                </div>
              </div>
            </div>


          
        </div>
    </div>
</div>

{{ Form::close() }}

@endsection


