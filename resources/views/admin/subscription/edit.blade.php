@extends('admin.layouts.master')


@section('content')
{{ Form::model($model, ['route' => ['admin.subscription.editsave', $model->id], 'method' => 'post']) }}

  <div class="row">
    <div class="col-md-8">
      <div class="container-fluid">
        @include('admin.common.message')
          <h2 class="pb-2 border-bottom">Edit subscription : {{ $model->title  }} </h2>
          @if(!empty($model->price))
            @if(!empty(Paymentgateway::razorpay_value('razorpay_auto_subscription')))
            <p class="highlights">Razorpay Plan ID: {{ empty($model->razorpay_id)?' Not Created ':$model->razorpay_id }}</p>
            @endif
          @endif

          <div class="form-row">
              <label class="form-label" for="name">Title</label>
              <input type="text" class="form-control" id="title" name="title" value="{{ old('title',$model->title) }}" >
          </div>
          <div class="form-group row">
            <div class="col-sm-4">
              <div class="form-row">
                  <label class="form-label" for="name">Duration</label>
                  <input type="text" class="form-control" id="duration" name="duration" value="{{ old('duration',$model->duration) }}" >
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-row">
                  <label class="form-label">&nbsp;</label>
                  @php ($val=old('duration_type',$model->duration_type))
                  <select class="form-select selectbox-default" name="duration_type">
                    @php ($ls=App\Models\Subscription::durationValues())
                    @foreach($ls as $key=>$item)
                      <option value="{{ $key }}" @if($val==$key) selected="selected" @endif>{{ $item }}</option>   
                    @endforeach 
                  </select>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-6">
              <div class="form-row">
                  <label class="form-label" for="price">Price</label>
                  <input type="number" step=".01" class="form-control" id="price" name="price" value="{{ old('price',$model->price) }}" >
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-row">
                  <label class="form-label" for="before_price">Before Price (optional)</label>
                  <input type="number" step=".01" class="form-control" id="before_price" name="before_price" value="{{ old('before_price',$model->before_price) }}" >
              </div>
            </div>
          </div>
          <div class="form-row">
              <label class="form-label" for="name">Tag Text</label>
              <input type="text" class="form-control" id="tagtext" name="tagtext" value="{{ old('tagtext',$model->tagtext) }}" >
          </div>
          <div class="form-group row dnn">
            <div class="col-sm-6">
              <div class="form-row">
                  <label class="form-label" for="name">Device Limit</label>
                  <input type="number" class="form-control" id="device_limit" name="device_limit" value="{{ old('device_limit',$model->device_limit) }}" >
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-row">
                  <label class="form-label" for="name">Same time to watch</label>
                  <input type="number" class="form-control" id="video_sametime" name="video_sametime" value="{{ old('video_sametime',$model->video_sametime) }}" >
              </div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-4">
              <div class="form-row">
                  <label class="form-label" for="name">Video Quality</label>
                  @php ($val=old('video_quality',$model->video_quality))
                  <select class="form-select selectbox-default" name="video_quality">
                    @php ($ls=App\Models\Subscription::qualityValues())
                    @foreach($ls as $key=>$item)
                      <option value="{{ $key }}" @if($val==$key) selected="selected" @endif>{{ $item }}</option>   
                    @endforeach 
                  </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-row">
                  <label class="form-label" for="name">Video Resolution</label>
                  @php ($val=old('video_resolution',$model->video_resolution))
                  <select class="form-select selectbox-default" name="video_resolution">
                    @php ($ls=App\Models\Subscription::resolutionValues())
                    @foreach($ls as $key=>$item)
                      <option value="{{ $key }}" @if($val==$key) selected="selected" @endif>{{ $item }}</option>   
                    @endforeach 
                  </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-row">
                  <label class="form-label" for="name">Video Device</label>
                  @php ($val=old('video_device',$model->video_device))
                  <select class="form-select selectbox-default" name="video_device">
                    @php ($ls=App\Models\Subscription::deviceValues())
                    @foreach($ls as $key=>$item)
                      <option value="{{ $key }}" @if($val==$key) selected="selected" @endif>{{ $item }}</option>   
                    @endforeach 
                  </select>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-6">
              <div class="form-row">
                  <label class="form-label" for="name">Sort Order</label>
                  <input type="number" class="form-control" id="sortorder" name="sortorder" value="{{ old('sortorder',$model->sortorder) }}" >
              </div>
            </div>
          </div>
          <div class="form-row">
            <label class="form-label" for="content">Content</label>
            <textarea class="form-control editor-content" name="content" rows="5">{{ old('content',(!empty($model->content)?$model->content:'')) }}</textarea>
            {!! Field::editor('editor-content') !!}
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
                  
                  @if(!empty(Paymentgateway::razorpay_value('razorpay_auto_subscription')))
                  <div class="form-row">
                    <label for="excerpt" class="form-label">Regenrate Razorpay ID</label>
                    <input class="form-control" type="text" name="razorpay_generate" value="NO" />
                    <p class="comment">Type "YES" for to Generate. "CLEAR" for empty</p>
                  </div>
                  @endif

                  <div class="form-row col-md-12">
                      <div class="form-row btnaction">
                          <button type="submit" class="btn btn-primary">Update</button>
                          <a href="{{ route('admin.subscription.index') }}" class="btn btn-secondary backbtn">Back</a>
                      </div>
                  </div>
              </div>
              <div class="card-footer text-muted">
                @include('admin.common.userinfo') 
                <div class="deleteaction">
                  <a class="btn btn-danger btn-delete-copy-{{ $model->id }}"  data-bs-toggle="modal" data-bs-target="#delete{{ $model->id }}">Delete</a>
                  @php ($delid=$model->id)
                  @php ($delurl=route('admin.subscription.delete',$model->id))
                  @include('admin.common.modaldelete')
                </div>
              </div>
            </div>


          
        </div>
    </div>
</div>

{{ Form::close() }}

@endsection


