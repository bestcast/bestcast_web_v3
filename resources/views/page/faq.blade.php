@if(!empty($meta['faq_enable']))
    @php ($faq_count=(empty($meta['faq_count'])?0:$meta['faq_count']))
    @for($no=1;$no<=$faq_count;$no++)
        @if(!empty($meta['faq_enable_'.$no]))
        <div class="container page-faq">
	        <div class="row g-5 mb--10">
	            <div class="col-xl-12">
	                <div class="landing-demo-faq edu-accordion-02 variation-2 landing-page-accordion" id="homeFAQ">

	                    <div class="edu-accordion-item">
	                        <div class="edu-accordion-header" id="heading{{ $no }}">
	                            <button class="edu-accordion-button {{ ($no==1)?'':'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $no }}" aria-expanded="{{ ($no==1)?'true':'false' }}" aria-controls="collapse{{ $no }}">
	                                @if(!empty($meta['faq_title_'.$no]))
	                                    <span class="pre-title">{{ $meta['faq_title_'.$no] }}</span>
	                                @endif
	                            </button>
	                        </div>
	                        <div id="collapse{{ $no }}" class="accordion-collapse collapse {{ ($no==1)?'show':'' }}" aria-labelledby="heading{{ $no }}" data-bs-parent="#homeFAQ">
	                            <div class="edu-accordion-body">
	                                @if(!empty($meta['faq_content_'.$no]))
	                                    {!! $meta['faq_content_'.$no] !!}
	                                @endif
	                            </div>
	                        </div>
	                    </div>

	                </div>
	            </div>
	        </div>
        </div>
        @endif
    @endfor
@endif