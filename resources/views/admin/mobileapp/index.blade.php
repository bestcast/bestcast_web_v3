@extends('admin.layouts.master')


@section('content')


    @include('admin.common.message')

    {{ Form::model($meta, ['route' => ['admin.mobileapp.save',1], 'method' => 'post']) }}
    <!-- 1 is mobile app -->
    <h2 class="pb-3 border-bottom">
        Mobile App <button type="submit" class="btn btn-primary float-right">Update</button>
    </h2>

    
    <div class="image txt-right">
        <div class="row">
            <div class="col-6">
                <div class="form-row">
                  <label class="form-label"for="name">App ID</label>
                  <input type="text" class="form-control" name="meta[appid]" value="{{ old('meta.appid',(empty($meta['appid'])?'':$meta['appid']))  }}" >
                </div>
            </div>
        </div>
    </div>
    <div class="btn-sec">

        <div class="card mb-4">
            <div class="card-header">TV Connection</div>
            <div class="card-body">
                <div class="form-row">
                    <label class="form-label">Intro Logo URL</label>
                    <input type="text" class="form-control full" name="meta[tv_intro_logo]" value="{{ old('meta.tv_intro_logo',empty($meta['tv_intro_logo'])?'':$meta['tv_intro_logo']) }}" >
                </div>
                <div class="form-row">
                    <label class="form-label">Intro Video URL</label>
                    <input type="text" class="form-control full" name="meta[tv_intro_video]" value="{{ old('meta.tv_intro_video',empty($meta['tv_intro_video'])?'':$meta['tv_intro_video']) }}" >
                </div>
            </div>
        </div>

    </div>
    <div class="btn-sec">

          @for($no=1;$no<=3;$no++)
            <div class="card mb-4">
                <div class="card-header">Front Banner {{ $no }}</div>
                <div class="card-body">
                    <div class="form-row">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control full" name="meta[front_title_{{ $no }}]" value="{{ old('meta.front_title_'.$no,empty($meta['front_title_'.$no])?'':$meta['front_title_'.$no]) }}" >
                    </div>
                    <div class="form-row">
                        <label class="form-label">Sub Title</label>
                        <input type="text" class="form-control full" name="meta[front_subtitle_{{ $no }}]" value="{{ old('meta.front_subtitle_'.$no,empty($meta['front_subtitle_'.$no])?'':$meta['front_subtitle_'.$no]) }}" >
                    </div>

                    <div class="row">
                    <div class="col-3 themed-grid-col">
                        {!! Field::galleryUpload('front_image_'.$no,'Image',$meta) !!}
                    </div>
                    </div>

                </div>
            </div>
          @endfor

    </div>

  <div class="form-row col-md-12">
      <button type="submit" class="btn btn-primary">Update</button>
  </div>

    </form>

@endsection



