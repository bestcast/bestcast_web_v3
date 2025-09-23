@extends('admin.layouts.master')


@section('content')
{{ Form::model($model, ['route' => ['admin.post.editsave', $model->id], 'method' => 'post']) }}
  <div class="row">
    <div class="col-md-8">
        <div class="container-fluid">
        @include('admin.common.message')
          <h2 class="pb-2 border-bottom">{{ $model->title  }} </h2>
          <input type="hidden" class="form-control" id="type" name="type" value="{{ $model->type }}">

          <div class="form-row">
              <label class="form-label" for="name">Title</label>
              <input type="text" class="form-control" id="title" name="title" value="{{ old('title',$model->title) }}" >
          </div>
          <div class="form-row">
              <label class="form-label">Subtitle</label>
              <input type="text" class="form-control" name="meta[subtitle]" value="{{ old('meta.subtitle',(empty($meta['subtitle'])?'':$meta['subtitle'])) }}">
          </div>
          <div class="form-row dnn">
            <label class="form-label" for="excerpt">Short Description</label>
            <textarea class="form-control editor-excerpt" name="excerpt" rows="5">{{ old('excerpt',$model->excerpt) }}</textarea>
            {!! Field::editor('editor-excerpt') !!}
          </div>
          <div class="form-row">
            <label class="form-label" for="content">Content</label>
            <textarea class="form-control editor-content" name="content" rows="5">{{ old('content',(!empty($model->postcontent->content)?$model->postcontent->content:'')) }}</textarea>
            {!! Field::editor('editor-content') !!}
          </div>


          @if($model->type=="category")
            <div class="card mt-2">
                <div class="card-header">Category</div>
                <div class="card-body">
                    <div class="row">
                      <div class="form-row">
                          <label class="form-label" for="urlkey_cat">URL Key</label>
                          @php ($val=empty($model->category->urlkey)?$model->urlkey:$model->category->urlkey)
                          <input type="text" class="form-control" id="urlkey_cat" name="urlkey_cat" value="{{ old('urlkey_cat',$val) }}">
                          <div class="comment">eg: loriem-ipsum</div>
                      </div>
                      <div class="form-row dnn">
                          <label class="form-label" for="cat_parent_id">Parent Category</label>
                          @php ($val=empty($model->category->parent_id)?0:$model->category->parent_id)
                          @php ($cid=empty($model->category->id)?0:$model->category->id)
                          <?php /*{{ Form::select('cat_parent_id', App\Models\Category::selectList($cid), old('template',$val), array('class' => 'form-select')); }} */?>
                          @php ($selval=old('template',$val)) 
                          <select name="cat_parent_id" class="form-select">
                              <?php /*@foreach(App\Models\Category::selectList($cid) as $id=>$val)
                                <option value="{{ $id }}" @if($id==$selval) selected="selected" @endif @if($id==$cid) disabled="disabled" @endif>{{ $val }}</option>
                              @endforeach */ ?>
                              <option value="0">None</option>
                              {!! App\Models\Category::selectList($selval,$cid) !!}
                          </select>

                          <div class="comment">Path: {{ $model->category->path }}</div>
                      </div>

                      <div class="form-row">
                          <label class="form-label" for="cat_sort">Sort</label>
                          @php ($val=empty($model->category->sort)?0:$model->category->sort)
                          <input type="number" class="form-control" id="cat_sort" name="cat_sort" value="{{ old('cat_sort',$val) }}">
                      </div>
                    </div>
                </div>
            </div><br>
          @else
            @if($model->slug!="home" && ($model->type!="page"))
            <div class="form-row">
                <label class="form-label" for="cat_parent_id">Select Category</label>
                @php ($postcategory=$model->postcategory)
                @php ($val=$postcategory->pluck('category_id')->toArray())
                @php ($postcategoryprimary=$model->postcategoryprimary($postcategory))
                @php ($selval=old('template',$val))
                <?php /*<select name="category[]" class="form-select multiple" multiple size="15">
                    {!! App\Models\Category::selectList($selval,$cid=0,'checkbox') !!}
                </select> */ ?>
                <div class="form-tree-checkbox">
                    {!! App\Models\Category::selectList($selval,$cid=0,'checkbox',array('primary'=>$postcategoryprimary)) !!}
                </div>
                <style type="text/css">
                </style>
            </div>
            @endif
          @endif

          <div class="row mt-1 form-img-upload">
            <div class="col-3 themed-grid-col">
                {!! Field::mediaUpload('image_id','Image',$model) !!}
            </div>
            <div class="col-3 themed-grid-col">
                {!! Field::mediaUpload('thumbnail_id','Thumbnail',$model) !!}
            </div>
            <div class="col-3 themed-grid-col">
                {!! Field::galleryUpload('banner_background','Background',$meta) !!}
            </div>
            <div class="col-3 themed-grid-col">
                <p><b>Image</b> (1360X500)<br><b>Thumbnail</b> (800X500)<br><b>Background</b> (1920X350)</p>
            </div>
          </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="container">
          <div class="card">
              <div class="card-header">Option</div>
              <div class="card-body">
                  <div class="form-row dnn">
                    <label class="form-label"for="excerpt">Template:</label>
                    {{ Field::getTemplate($model->template) }}
                  </div>
                  <div class="form-row">
                    <label for="excerpt" class="form-label">Template</label>
                    {{ Form::select('template', Field::template(), old('template',$model->template), array('class' => 'form-select')); }}
                  </div>
                  <div class="form-row">
                    <label class="form-label"for="excerpt">Status</label>
                    {{ Form::select('status', Field::status(),old('status',$model->status), array('class' => 'form-select')); }}
                  </div>
                  @if($model->status==3)
                    <div class="form-row">
                        <label class="form-label" for="name">Password</label>
                        <input type="text" class="form-control" id="password" name="password" value="{{ old('password',$model->password) }}" >
                    </div>
                  @endif
                  <div class="form-row">
                      <label class="form-label" for="name">Published Date</label>
                      <?php
                      $pubdate=empty($model->published_date)?'':date("d/m/Y H:i",strtotime($model->published_date));
                      ?>
                      <input type="text" class="form-control datetimepicker" id="published_date" name="published_date" value="{{ old('published_date',$pubdate) }}" >
                  </div>
                  <div class="form-row @if($model->type=="category") dnn @endif">
                      <label class="form-label" for="urlkey">URL Key</label>
                      <input type="text" class="form-control" id="urlkey" name="urlkey" value="{{ old('urlkey',$model->urlkey) }}">
                      <div class="comment">eg: loriem-ipsum</div>
                  </div>
                  
                  <?php /*
                  <div class="form-row rel">
                      <label class="form-label">Copy Section</label>
                      @php ($val=empty($model->copy_id)?0:$model->copy_id)

                      <!--FilterCode00-->
                      @php ($uuid=\Illuminate\Support\Str::uuid()->toString())
                      @php ($nm='copy_id')
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
                  */?>

                  <div class="form-row col-md-12">
                      <button type="submit" class="btn btn-primary">Update</button>
                      @if($model->type=="category")
                        <a href="{{ Lib::publicUrl($model->category->path) }}" target="_blank" class="btn btn-info">View</a>
                        <a href="{{ route('admin.post.category') }}" class="btn btn-secondary backbtn">Back</a>
                      @elseif($model->type=="blog")
                        @php ($primarypath=$model->primarypath($model))
                        <a href="{{ Lib::publicUrl($primarypath) }}" target="_blank" class="btn btn-info">View</a>
                        <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary backbtn">Back</a>
                      @elseif($model->type=="page")
                        <a href="{{ Lib::publicUrl($model->urlkey) }}" target="_blank" class="btn btn-info">View</a>
                        <a href="{{ route('admin.page.index') }}" class="btn btn-secondary backbtn">Back</a>
                      @else
                        <a href="{{ Lib::publicUrl($model->urlkey) }}" target="_blank" class="btn btn-info">View</a>
                        <a href="{{ route('admin.post.index') }}" class="btn btn-secondary backbtn">Back</a>
                      @endif
                  </div>
              </div>
              <div class="card-footer text-muted">
                @include('admin.common.userinfo') 
                <div class="deleteaction">
                  <a class="btn btn-danger btn-delete-copy-{{ $model->id }}"  data-bs-toggle="modal" data-bs-target="#delete{{ $model->id }}">Delete</a>
                  @php ($delid=$model->id)
                  @php ($delurl=route('admin.post.delete',$model->id))
                  @include('admin.common.modaldelete')
                </div>
              </div>
            </div>


            
          <div class="card mt-2">
              <div class="card-header">SEO</div>
              <div class="card-body">
                  <div class="form-row">
                      <label class="form-label">Seo Title</label>
                      <input type="text" class="form-control" name="meta[seo_title]" value="{{ old('meta.seo_title',(empty($meta['seo_title'])?'':$meta['seo_title'])) }}">
                  </div>
                  <div class="form-row">
                      <label class="form-label">Seo Description</label>
                      <input type="text" class="form-control" name="meta[seo_description]" value="{{ old('meta.seo_description',(empty($meta['seo_description'])?'':$meta['seo_description'])) }}">
                  </div>
                  <div class="form-row">
                      <label class="form-label">Classname</label>
                      <input type="text" class="form-control" name="meta[classname]" value="{{ old('meta.classname',(empty($meta['classname'])?'':$meta['classname'])) }}">
                  </div>
              </div>
          </div>


          <?php /*
          <div class="card mt-2 ">
              <div class="card-header">Enable?</div>
              <div class="card-body">
                  <div class="row">
                    <div class="col-md-6"><div class="form-row">
                        <label class="form-label">Title</label>
                        <div class="mb-3 form-check form-switch">
                          {{Form::hidden('meta[show_title]',0)}}
                          <input class="form-check-input" type="checkbox" name="meta[show_title]" role="switch" @if(old('meta.show_title',(empty($meta['show_title'])?0:1))) checked="" @endif>
                        </div>
                    </div></div>
                    <div class="col-md-6"><div class="form-row">
                        <label class="form-label">Banner</label>
                        <div class="mb-3 form-check form-switch">
                          {{Form::hidden('meta[show_banner]',0)}}
                          <input class="form-check-input" type="checkbox" name="meta[show_banner]" role="switch" @if(old('meta.show_banner',(empty($meta['show_banner'])?0:1))) checked="" @endif>
                        </div>
                    </div></div>
                  </div>
                  <div class="row">
                    <div class="col-md-6"><div class="form-row">
                        <label class="form-label">Short Description</label>
                        <div class="mb-3 form-check form-switch">
                          {{Form::hidden('meta[show_excerpt]',0)}}
                          <input class="form-check-input" type="checkbox" name="meta[show_excerpt]" role="switch" @if(old('meta.show_excerpt',(empty($meta['show_excerpt'])?0:1))) checked="" @endif>
                        </div>
                    </div></div>
                    <div class="col-md-6"><div class="form-row">
                        <label class="form-label">Content</label>
                        <div class="mb-3 form-check form-switch">
                          @php ($meta['show_content']=(isset($meta['show_content'])?$meta['show_content']:'on'))
                          {{Form::hidden('meta[show_content]',0)}}
                          <input class="form-check-input" type="checkbox" name="meta[show_content]" role="switch" @if(old('meta.show_content',(empty($meta['show_content'])?0:1))) checked="" @endif>
                        </div>
                    </div></div>
                  </div>
                  <div class="row">
                    <div class="col-md-6"><div class="form-row">
                        <label class="form-label">Show Content Top</label>
                        <div class="mb-3 form-check form-switch">
                          {{Form::hidden('meta[contenttop]',0)}}
                          <input class="form-check-input" type="checkbox" name="meta[contenttop]" role="switch" @if(old('meta.contenttop',(empty($meta['contenttop'])?0:1))) checked="" @endif>
                        </div>
                    </div></div>
                    <div class="col-md-6"><div class="form-row">
                        <label class="form-label">Show Date</label>
                        <div class="mb-3 form-check form-switch">
                          {{Form::hidden('meta[showdate]',0)}}
                          <input class="form-check-input" type="checkbox" name="meta[showdate]" role="switch" @if(old('meta.showdate',(empty($meta['showdate'])?0:1))) checked="" @endif>
                        </div>
                    </div></div>
                  </div>
                  <div class="row dnn">
                    <div class="col-md-6"><div class="form-row">
                        <label class="form-label">Section Numbers?</label>
                        <div class="mb-3 form-check form-switch">
                          {{Form::hidden('meta[section_number]',0)}}
                          <input class="form-check-input" type="checkbox" name="meta[section_number]" role="switch" @if(old('meta.section_number',(empty($meta['section_number'])?0:1))) checked="" @endif>
                        </div>
                    </div></div>
                  </div>
              </div>
            </div>
            */ ?>


            <?php /*
          <div class="card mt-2">
              <div class="card-header">Layout</div>
              <div class="card-body">
                  <div class="form-row">
                    @php ($val=(!empty($meta['design'])?$meta['design']:''))
                    @php ($design=$selval=old('meta.design',$val)) 
                    <div class="radiobtnOut">
                      <div class="radiobtn">
                        <input type="radio" id="default" @if(empty($selval)) checked="checked" @endif name="meta[design]" value="" />
                        <label for="default"><img src="{{ asset('img/admin/default.jpg') }}" /></label>
                      </div>                      
                      <div class="radiobtn">@php ($radval=1)
                        <input type="radio" id="design{{ $radval }}" @if($selval==$radval) checked="checked" @endif name="meta[design]" value="{{ $radval }}" />
                        <label for="design{{ $radval }}"><img src="{{ asset('img/admin/design'.$radval.'.jpg') }}" /></label>
                      </div>                    
                      <div class="radiobtn">@php ($radval=2)
                        <input type="radio" id="design{{ $radval }}" @if($selval==$radval) checked="checked" @endif name="meta[design]" value="{{ $radval }}" />
                        <label for="design{{ $radval }}"><img src="{{ asset('img/admin/design'.$radval.'.jpg') }}" /></label>
                      </div>
                    </div>
                    
                  </div>
              </div>
          </div>
          */ ?>


          
        </div>
    </div>
  </div>

  @if($model->urlkey=="home")
    @include('admin.post.meta.'.$model->urlkey)
  @endif

  @if($model->urlkey=="faq")
    @include('admin.post.meta.'.$model->urlkey)
  @endif


  @if($model->urlkey=="pricing")
    @include('admin.post.meta.'.$model->urlkey)
  @endif


@if(!empty($design) && $design==1)
  @include('admin.post.design.'.$design)
@endif

{{ Form::close() }}


<?php $tempSlug=Field::getTemplateSlug($model->template);?>


@endsection

