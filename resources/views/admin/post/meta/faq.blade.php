
<div class="container-fluid">




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

</div>