@extends('admin.layouts.master')

@section('content')
{{ Form::model($model, ['route' => ['admin.blocks.editsave', $model->id], 'method' => 'post']) }}

  <div class="row">
    <div class="col-md-8">
      <div class="container-fluid">
        @include('admin.common.message')
          <h2 class="pb-2 border-bottom">Edit Blocks : {{ $model->title  }} </h2> <br>
          <div class="form-row">
              <label class="form-label" for="name">Title</label>
              <input type="text" class="form-control" id="title" name="title" value="{{ old('title',$model->title) }}" >
          </div>

          <div class="form-row dnn"><!--Tv Shows Extend-->
            <label class="form-label"for="excerpt">Type</label>
            {{ Form::select('type', [0=>"Movies",1=>"Tv Shows"],old('type',$model->type), array('id'=>'type','class' => 'form-select')); }}
            <script>
                jQuery(document).ready(function($) {
                    $('#type').change(function() {
                        var selectedValue = $(this).val();
                        console.log(selectedValue);
                        if (selectedValue==1) {
                            $('#movies_dropdown').addClass('dnn');
                            $('#shows_dropdown').removeClass('dnn');
                        } else {
                            $('#movies_dropdown').removeClass('dnn');
                            $('#shows_dropdown').addClass('dnn');
                        }
                    });
                });
            </script>
          </div>



          <div class="form-row form-select2 {{ empty($model->type)?'':'dnn' }}"  id="movies_dropdown">
            <label class="form-label">Movies</label>
            <select name="movies_id[]" class="form-control" id="movies_id" multiple="multiple">
              @if(!empty($model->movies) && count($model->movies))
                @foreach($model->movies as $relShowItem)
                  @if(!empty($relShowItem->movies))
                    <option value="{{$relShowItem->movies_id}}" selected="selected">{{$relShowItem->movies->title}}</option>
                  @endif
                @endforeach
              @endif
            </select>

            <script>
            jQuery(document).ready(function($) {
              $('#movies_id').select2({
                  placeholder: "Choose Movies...",
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

          <div class="form-row form-select2 {{ !empty($model->type)?'':'dnn' }}"  id="shows_dropdown">
            <label class="form-label">Tv Shows</label>
            <select name="shows_id[]" class="form-control" id="shows_id" multiple="multiple">
              @if(!empty($model->shows) && count($model->shows))
                @foreach($model->shows as $relShowItem)
                  @if(!empty($relShowItem->shows))
                    <option value="{{$relShowItem->shows_id}}" selected="selected">{{$relShowItem->shows->title}}</option>
                  @endif
                @endforeach
              @endif
            </select>

            <script>
            jQuery(document).ready(function($) {
              $('#shows_id').select2({
                  placeholder: "Choose TV Show...",
                  minimumInputLength: 2,
                  ajax: {
                      url: function (params) {
                        return  "{{ route('admin.shows.searchbytitle') }}/"+params.term;
                      },  dataType: 'json',
                      processResults: function (data) { return {  results: data };  },cache: true
                  }
              });
            });
            </script>
          </div>


          <div class="form-row form-select2"  id="tvshows_dropdown">
            <label class="form-label">Page to Display (optional)</label>
            @php ($selectedValue=empty($model->page_id)?[]:[$model->page_id=>$model->page->title])
            {{ Form::select('page_id', $selectedValue,old('page_id',$model->page_id), array('id'=>'page_id','class' => 'form-select')); }}
            <!-- <select id="show_ids" name="show_ids[]" class="form-control" multiple></select> -->
            <script>
            jQuery(document).ready(function($) {
              $('#page_id').select2({
                  placeholder: "Choose Page to Display...",
                  minimumInputLength: 0,
                  ajax: {
                      url: function (params) {
                        return  "{{ route('admin.page.searchbytitle') }}/"+params.term;
                      },  dataType: 'json',
                      processResults: function (data) { return {  results: data };  },cache: true
                  }
              });
            });
            </script>
          </div>


          <div class="form-row form-select2"  id="movies_dropdown">
            <label class="form-label">Genres (optional)</label>
            <select name="genre_id[]" class="form-control" id="genre_id" multiple="multiple">
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
              $('#genre_id').select2({
                  placeholder: "Choose Genres...",
                  minimumInputLength: 0,
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


          <div class="form-row form-select2"  id="movies_dropdown">
            <label class="form-label">Languages (optional)</label>
            <select name="language_id[]" class="form-control" id="language_id" multiple="multiple">
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
              $('#language_id').select2({
                  placeholder: "Choose Genres...",
                  minimumInputLength: 0,
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


          <div class="form-row">
              <label class="form-label" for="name">Sort Order</label>
              <input type="number" class="form-control" id="sortorder" name="sortorder" value="{{ old('sortorder',$model->sortorder) }}" >
          </div>

          <div class="row pt-2 form-img-upload">
            <div class="col-3 themed-grid-col">
                {!! Field::mediaUpload('image_id','Image',$model) !!}
            </div>
            <div class="col-3 themed-grid-col">
                {!! Field::mediaUpload('thumbnail_id','Thumbnail',$model) !!}
            </div>
            <div class="col-3 themed-grid-col">
                <p><b>Image</b> (1920X1080)<br><b>Thumbnail</b> (720X405)</p>
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
                      <label class="form-label" for="urlkey">URL Key</label>
                      <input type="text" class="form-control" id="urlkey" name="urlkey" value="{{ old('urlkey',$model->urlkey) }}">
                      <div class="comment">eg: loriem-ipsum</div>
                  </div>
                  

                  <div class="form-row col-md-12">
                      <div class="form-row btnaction">
                          <button type="submit" class="btn btn-primary">Update</button>
                          <a href="{{ route('admin.blocks.index') }}" class="btn btn-secondary backbtn">Back</a>
                      </div>
                  </div>
              </div>
              <div class="card-footer text-muted">
                @include('admin.common.userinfo') 
                <div class="deleteaction">
                  <a class="btn btn-danger btn-delete-copy-{{ $model->id }}"  data-bs-toggle="modal" data-bs-target="#delete{{ $model->id }}">Delete</a>
                  @php ($delid=$model->id)
                  @php ($delurl=route('admin.blocks.delete',$model->id))
                  @include('admin.common.modaldelete')
                </div>
              </div>
            </div>


          
        </div>
    </div>
</div>

{{ Form::close() }}

@endsection


