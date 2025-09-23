@extends('admin.layouts.master')


@section('content')
{{ Form::model($model, ['route' => ['admin.menu.editsave', $model->id], 'method' => 'post']) }}
  <div class="row">
    <div class="col-md-8">
        <div class="container-fluid">
        @include('admin.common.message')
          <h2 class="pb-2 border-bottom">{{ $model->name  }} </h2>

          <div class="form-row">
              <label class="form-label" for="name">Name</label>
              <input type="text" class="form-control" id="name" name="name" value="{{ old('name',$model->name) }}" >
          </div>
          <div class="form-row">
              <label class="form-label" for="name">Url</label>
              <input type="text" class="form-control" id="url" name="url" value="{{ old('name',$model->url) }}" >
          </div>

          
          <div class="form-row rel">
              <label class="form-label" for="post_id">Page (optional)</label>
              <!--FilterCode00-->
              @php ($nm='post_id')
              @php ($val=empty($model->$nm)?'':$model->$nm)
              @php ($uuid=\Illuminate\Support\Str::uuid()->toString())
              @php ($val=old($nm,$val))
              @php ($ajxurl=route('admin.post.ajax'))
              <div class="filterdd-menu shadow overflow-hidden {{ $uuid }}">
                <input type="hidden" class="form-control ajaxdata-selected" id="{{ $nm }}" name="{{ $nm }}" value="{{ $val }}">
                <input type="hidden" class="ajaxdata-url" value="{{ $ajxurl }}" />
                <input type="text" data-div="{{ $uuid }}" class="form-control ajax-generate" autocomplete="false" placeholder="Type to filter..." value="{{ Field::getPostTitleById($model->$nm) }}">
                <div class="generate"></div>
              </div>
              <!--FilterCode00-->
          </div>
          <div class="form-row">
              <label class="form-label">New Window?</label>
              <div class="mb-3 form-check form-switch">
                {{Form::hidden('target',0)}}<input class="form-check-input" type="checkbox" name="target" role="switch" @if(old('target',(empty($model->target)?0:1))) checked="" @endif>
              </div>
          </div>
          <div class="form-row">
              <label class="form-label">Visbile for Logged in User only?</label>
              <div class="mb-3 form-check form-switch">
                {{Form::hidden('show_loggedin_user',0)}}<input class="form-check-input" type="checkbox" name="show_loggedin_user" role="switch" @if(old('show_loggedin_user',(empty($model->show_loggedin_user)?0:1))) checked="" @endif>
              </div>
          </div>
          <!-- <div class="form-row">
            <label class="form-label" for="content">Content</label>
            <textarea class="form-control editor-excerpt" name="content" rows="5">{{ old('content',(!empty($model->postcontent->content)?$model->postcontent->content:'')) }}</textarea>
            {!! Field::editor('editor-content') !!}
          </div> -->

          

          <div class="form-row dnn">
              <label class="form-label" for="parent_id">Parent Category</label>
              @php ($val=empty($model->parent_id)?0:$model->parent_id)
              @php ($cid=empty($model->id)?0:$model->id)
              @php ($selval=old('template',$val)) 
              <select name="parent_id" class="form-select">
                  <option value="0">None</option>
                  {!! App\Models\Menu::selectList($selval,$cid) !!}
              </select>
          </div>

          <div class="row mt-1 form-img-upload">
            <div class="col-3 themed-grid-col">
                {!! Field::mediaUpload('icon_id','Icon',$model) !!}
            </div>
            <div class="col-3 themed-grid-col">
                {!! Field::mediaUpload('image_id','Image',$model) !!}
            </div>
            <div class="col-3 themed-grid-col">
                {!! Field::mediaUpload('thumbnail_id','Thumbnail',$model) !!}
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
                      <label class="form-label">Status</label>
                      <div class="mb-3 form-check form-switch">
                        {{Form::hidden('status',0)}}<input class="form-check-input" type="checkbox" name="status" role="switch" @if(old('status',(empty($model->status)?0:1))) checked="" @endif>
                      </div>
                  </div>
                  <div class="form-row">
                      <label class="form-label" for="classname">Class Name</label>
                      <input type="text" class="form-control" id="classname" name="classname" value="{{ old('classname',$model->classname) }}" >
                  </div>
                  <div class="form-row">
                      <label class="form-label" for="sort">Sort Order</label>
                      @php ($val=empty($model->sort)?0:$model->sort)
                      <input type="number" class="form-control" id="sort" name="sort" value="{{ old('sort',$val) }}">
                  </div>
                  <div class="form-row col-md-12">
                      <button type="submit" class="btn btn-primary">Update</button>
                      @if(!empty($model->post))
                        <a href="{{ Lib::publicUrl($model->post->urlkey) }}" target="_blank" class="btn btn-info">View</a>
                      @else
                        <a href="{{ $model->url }}" target="_blank" class="btn btn-info">View</a>
                      @endif
                      <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary backbtn">Back</a>
                  </div>
              </div>
              <div class="card-footer text-muted">
                @include('admin.common.userinfo') 
                <div class="deleteaction">
                      <a class="btn btn-danger btn-delete-copy-{{ $model->id }}"  data-bs-toggle="modal" data-bs-target="#delete{{ $model->id }}">Delete</a>
                      @php ($delid=$model->id)
                      @php ($delurl=route('admin.menu.delete',$model->id))
                      @include('admin.common.modaldelete')
                </div>
              </div>
            </div>
          
        </div>
    </div>
  </div>
{{ Form::close() }}



@endsection