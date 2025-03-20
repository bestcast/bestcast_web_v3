@extends('layouts.frontend')

@section('header-script')
<script src="{{ asset('js/auth/tokenexist.js') }}?v=1" defer></script>
<!-- <script src="{{ asset('js/auth/emailverify.js') }}" defer></script> -->
@endsection

@section('content')    

    @php($bgurl=empty($meta['banner_background_urlkey'])?'':$meta['banner_background_urlkey'])
    @php($bgimg=empty($bgurl)?'':Lib::publicImgSrc($bgurl))
    <div class="main-wrapper {{ empty($bgimg)?'':'bgimage' }}" @if(!empty($bgimg)) style='background-image:url("{{ $bgimg }}");' @endif>
        <div class="edu-newsletter-area newsletter-style-1 edu-section-gap bg-black fixwithHeader">
            <div class="container eduvibe-animated-shape">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="inner text-center">
                            <div class="section-title text-white text-center">
                                @if(!empty($meta['subtitle'])) <h1 class="title">{!! $meta['subtitle'] !!}</h1> @endif
                                @if(!empty($post->postcontent)) {!! $post->postcontent->content !!} @endif
                            </div>
                            <div class="newsletter-form newsletter-form-style-1 mt--40">
                                @if(!empty($meta['register_form_content'])) <p>{!! $meta['register_form_content'] !!}</p> @endif
                                <form method="POST" action="{{ route('api.emailverify') }}" class="ajx-home-register-form">
                                    @csrf
                                    <div class="form-cont">
                                        <div class="input-box">
                                            <input type="text" name="email" placeholder="Email address" class="bg-black formemail" />
                                        </div>
                                        <button class="edu-btn registerbtn" type="submit">Get Started</button>
                                    </div>
                                    <div class="load-error-msg"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @php ($sec_count=(empty($meta['sec_count'])?0:$meta['sec_count']))
        @for($no=1;$no<=$sec_count;$no++)
            @if(!empty($meta['sec_enable_'.$no]))
            <div class="edu-workshop-area eduvibe-home-three-video workshop-style-1 edu-section-gap bg-image bg-dark secBrd secNo{{ $no }}">
                <div class="container eduvibe-animated-shape">
                    <div class="row gy-lg-0 gy-5 row--60 align-items-center">
                        <div class="col-lg-6 order-2 order-lg-{{ ($no%2==0)?2:1 }}">
                            <div class="workshop-inner">
                                <div class="section-title text-white">
                                    @if(!empty($meta['sec_subtitle_'.$no]))
                                        <span class="color-primary pre-title">{{ $meta['sec_subtitle_'.$no] }}</span>
                                    @endif
                                    @if(!empty($meta['sec_title_'.$no]))
                                        <h3 class="title">{{ $meta['sec_title_'.$no] }}</h3>
                                    @endif
                                </div>
                                @if(!empty($meta['sec_content_'.$no]))
                                    <div class="description">{!! $meta['sec_content_'.$no] !!}</div>
                                @endif
                                @if(!empty($meta['sec_button_'.$no]))
                                    <div class="read-more-btn">
                                        <a class="edu-btn" href="{!! empty($meta['sec_button_'.$no])?'#':$meta['sec_buttonlink_'.$no] !!}">{!! $meta['sec_button_'.$no] !!}<i class="icon-arrow-right-line-right"></i></a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 order-1 order-lg-{{ ($no%2==0)?1:2 }}">
                            <div class="thumbnail video-popup-wrapper">
                                @php($imgUrl=empty($meta['sec_image_'.$no.'_urlkey'])?'':$meta['sec_image_'.$no.'_urlkey'])
                                @php($imgUrl=empty($imgUrl)?'':Lib::publicImgSrc($imgUrl))
                                @if(!empty($imgUrl))
                                    <img class="radius-small w-100" src="{{ $imgUrl  }}" />
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endfor
 

        @if(!empty($meta['faq_enable']))
        <div class="landing-demo-faq-wrapper edu-accordion-area accordion-shape-1 edu-section-gap bg-dark" id="faq">
            <div class="container eduvibe-animated-shape">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="pre-section-title text-center">
                            @if(!empty($meta['faq_subtitle'])) 
                                <span class="color-primary pretitle">{{ $meta['faq_subtitle'] }}</span> 
                            @endif
                            @if(!empty($meta['faq_title'])) 
                                <h3 class="title">{{ $meta['faq_title'] }}</h3>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row g-5 mt--10">

                    <div class="col-xl-12">
                        <div class="landing-demo-faq edu-accordion-02 variation-2 landing-page-accordion" id="homeFAQ">
                        @php ($faq_count=(empty($meta['faq_count'])?0:$meta['faq_count']))
                        @for($no=1;$no<=$faq_count;$no++)
                            @if(!empty($meta['faq_enable_'.$no]))

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

                            @endif
                        @endfor
                        </div>
                    </div>


                    <div class="col-lg-12 mt--100">
                        <div class="inner text-center">
                            <div class="newsletter-form newsletter-form-style-1">
                                @if(!empty($meta['register_form_content'])) <p>{!! $meta['register_form_content'] !!}</p> @endif
                                <form method="POST" action="{{ route('api.emailverify') }}" class="ajx-home-register-form">
                                    @csrf
                                    <div class="form-cont">
                                        <div class="input-box">
                                            <input type="text" name="email" placeholder="Email address" class="bg-black formemail" />
                                        </div>
                                        <button class="edu-btn registerbtn" type="submit">Get Started</button>
                                    </div>
                                    <div class="load-error-msg"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        @endif


@endsection
