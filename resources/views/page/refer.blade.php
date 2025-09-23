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
                                @if(!empty($post->title)) <h1 class="title">{!! $post->title !!}</h1> @endif
                                @if(!empty($meta['subtitle'])) <p class="description">{!! $meta['subtitle'] !!}</p> @endif
                                @if(!empty($post->postcontent)) {!! $post->postcontent->content !!} @endif
                            </div>
                            <div class="newsletter-form newsletter-form-style-1 mt--40">
                                <a class="edu-btn registerbtn" href="{{ url('/register') }}">Register Now</a>
                                <script type="text/javascript">
                                    var referral_code = "{{ $code }}";
                                    localStorage.setItem('referral_code', referral_code);
                                    window.location.href="{{ url('/register') }}";
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




@endsection
