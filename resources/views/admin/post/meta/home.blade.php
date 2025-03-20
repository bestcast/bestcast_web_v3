
<div class="container-fluid">

  <h3 class="pb-2 border-bottom mb-4 mt-5">Additional Settings</h3>
  <div class="form-row">
      <label class="form-label">Register Form Content</label>
      <input type="text" class="form-control" name="meta[register_form_content]" value="{{ old('meta.register_form_content',(empty($meta['register_form_content'])?'':$meta['register_form_content'])) }}">
  </div>


  <h3 class="pb-2 border-bottom mb-4 mt-5">Sections</h3>
    @php ($sec_count=(empty($meta['sec_count'])?0:$meta['sec_count']))
    <div class="form-row">
        <label class="form-label"for="name">Number of Tab</label>
        <input type="number" min="0" max="10" class="form-control form-number" name="meta[sec_count]" value="{{ old('meta.sec_count',$sec_count) }}" >
    </div>
    <div class="btn-sec">
      @for($no=1;$no<=$sec_count;$no++)
        <div class="card mb-4">
            <div class="card-header">Section {{ $no }}</div>
            <div class="card-body">

                <div class="row">
                  <div class="col-1">
                    <div class="form-row">
                        <label class="form-label">Enable?</label>
                        <div class="mb-3 form-check form-switch">
                          {{Form::hidden('meta[sec_enable_'.$no.']',0)}}
                          <input class="form-check-input" type="checkbox" name="meta[sec_enable_{{ $no }}]" role="switch" @if(old('meta.sec_enable_'.$no,(empty($meta['sec_enable_'.$no])?0:1))) checked="" @endif>
                        </div>
                    </div>
                  </div>
                  <div class="col-11">
                    <div class="form-row">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="meta[sec_title_{{ $no }}]" value="{{ old('meta.sec_title_'.$no,empty($meta['sec_title_'.$no])?'':$meta['sec_title_'.$no]) }}" >
                    </div>
                  </div>
                </div>
                <div class="form-row">
                    <label class="form-label">Sub Title</label>
                    <input type="text" class="form-control" name="meta[sec_subtitle_{{ $no }}]" value="{{ old('meta.sec_subtitle_'.$no,empty($meta['sec_subtitle_'.$no])?'':$meta['sec_subtitle_'.$no]) }}" >
                </div>
                <div class="form-row">
                    <label class="form-label">Content</label>
                    <textarea class="form-control editor-list-{{ $no }}" name="meta[sec_content_{{ $no }}]">{{ old('meta.sec_content_'.$no,empty($meta['sec_content_'.$no])?'':$meta['sec_content_'.$no]) }}</textarea>
                    {!! Field::editor('editor-list-'.$no) !!}
                </div>

                <div class="row mt-1 form-img-upload">
                  <div class="col-3 themed-grid-col">
                      {!! Field::galleryUpload('sec_image_'.$no,'Background',$meta) !!}
                      <p class="comments">Recommended Size: 720X405</p>
                  </div>
                </div>
            </div>
        </div>
      @endfor
    </div>





  <h3 class="pb-2 border-bottom mb-4 mt-5">FAQ</h3>
    @php ($faq_count=(empty($meta['faq_count'])?0:$meta['faq_count']))
    <div class="row">
      <div class="col-4">
          <div class="form-row">
              <label class="form-label"for="name">Number of Tab</label>
              <input type="number" min="0" max="10" class="form-control form-number" name="meta[faq_count]" value="{{ old('meta.faq_count',$faq_count) }}" >
          </div>
      </div>
      <div class="col-2">
          <div class="form-row">
              <label class="form-label">Enable?</label>
              <div class="mb-3 form-check form-switch">
                {{Form::hidden('meta[faq_enable]',0)}}
                <input class="form-check-input" type="checkbox" name="meta[faq_enable]" role="switch" @if(old('meta.faq_enable',(empty($meta['faq_enable'])?0:1))) checked="" @endif>
              </div>
          </div>
      </div>
    </div>
    <div class="row">
      <div class="col-6">
        <div class="form-row">
          <label class="form-label"for="name">Title</label>
          <input type="text" class="form-control" name="meta[faq_title]" value="{{ old('meta.faq_title',(empty($meta['faq_title'])?'':$meta['faq_title']))  }}" >
        </div>
      </div>
      <div class="col-6">
        <div class="form-row">
          <label class="form-label"for="name">Sub Title</label>
          <input type="text" class="form-control" name="meta[faq_subtitle]" value="{{ old('meta.faq_subtitle',(empty($meta['faq_subtitle'])?'':$meta['faq_subtitle'])) }}" >
        </div>
      </div>
    </div>
    <div class="btn-sec">
      @for($no=1;$no<=$faq_count;$no++)
        <div class="card mb-4">
            <div class="card-header">FAQ {{ $no }}</div>
            <div class="card-body">

                <div class="row">
                  <div class="col-1">
                    <div class="form-row">
                        <label class="form-label">Enable?</label>
                        <div class="mb-3 form-check form-switch">
                          {{Form::hidden('meta[faq_enable_'.$no.']',0)}}
                          <input class="form-check-input" type="checkbox" name="meta[faq_enable_{{ $no }}]" role="switch" @if(old('meta.faq_enable_'.$no,(empty($meta['faq_enable_'.$no])?0:1))) checked="" @endif>
                        </div>
                    </div>
                  </div>
                  <div class="col-11">
                    <div class="form-row">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="meta[faq_title_{{ $no }}]" value="{{ old('meta.faq_title_'.$no,empty($meta['faq_title_'.$no])?'':$meta['faq_title_'.$no]) }}" >
                    </div>
                  </div>
                </div>
                <div class="form-row">
                    <label class="form-label">Content</label>
                    <textarea class="form-control editor-list-{{ $no }}" name="meta[faq_content_{{ $no }}]">{{ old('meta.faq_content_'.$no,empty($meta['faq_content_'.$no])?'':$meta['faq_content_'.$no]) }}</textarea>
                    {!! Field::editor('editor-list-'.$no) !!}
                </div>

            </div>
        </div>
      @endfor
    </div>

  <?php /*

  <div class="form-row">
      <label class="form-label">Number of Section</label>
      <input type="text" class="form-control" name="meta[sec_count]" value="{{ old('meta.sec_count',(empty($meta['sec_count'])?0:$meta['sec_count'])) }}">
  </div>
  <div class="row">
    <div class="col-md-2"><div class="form-row">
        <label class="form-label">Enable?</label>
        <div class="mb-3 form-check form-switch">
          @php($sec='sec_enable_1')
          {{Form::hidden('meta['.$sec.']',0)}}
          <input class="form-check-input" type="checkbox" name="meta[{{$sec}}]" role="switch" @if(old('meta.'.$sec,(empty($meta[$sec])?0:1))) checked="" @endif>
        </div>
    </div></div>
    <div class="col-md-5"><div class="form-row">
      <div class="form-row">
          @php($sec='sec_title_1')
          <label class="form-label">Title</label>
          <input type="text" class="form-control" name="meta[{{$sec}}]" value="{{ old('meta.'.$sec,(empty($meta[$sec])?'':$meta[$sec])) }}">
      </div>
    </div></div>
    <div class="col-md-5"><div class="form-row">
      <div class="form-row">
          @php($sec='sec_subtitle_1')
          <label class="form-label">Sub Title</label>
          <input type="text" class="form-control" name="meta[{{$sec}}]" value="{{ old('meta.'.$sec,(empty($meta[$sec])?'':$meta[$sec])) }}">
      </div>
    </div></div>
  </div>
  <div class="form-row">
    @php($sec='sec_content_1')
    <label class="form-label" for="{{$sec}}">Content</label>
    <textarea class="form-control editor-{{$sec}}" name="{{$sec}}" rows="5">{{ old('meta.'.$sec,(!empty($meta[$sec])?$meta[$sec]:'')) }}</textarea>
    {!! Field::editor('editor-'.$sec) !!}
  </div>

  <div class="row mt-1 form-img-upload">
    <div class="col-3 themed-grid-col">
        {!! Field::galleryUpload('banner_background','Background',$meta) !!}
    </div>
    <p class="comments">Recommended Size: 1920X350</p>
  </div>
  */?>


</div>