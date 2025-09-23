@extends('layouts.myaccount')

@section('header-script')
@endsection

@section('content') 


    @php($bgurl=empty($meta['banner_background_urlkey'])?'':$meta['banner_background_urlkey'])
    @php($bgimg=empty($bgurl)?'':Lib::publicImgSrc($bgurl))
    <div class="main-wrapper {{ empty($bgimg)?'':'bgimage' }}" @if(!empty($bgimg)) style='background-image:url("{{ $bgimg }}");' @endif>
        <div class="edu-newsletter-area newsletter-style-1 edu-section-gap bg-black">
            <div class="container eduvibe-animated-shape">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="inner text-center">
                            <div class="section-title text-white text-center">
         						@if(!empty($post->title)) <h1 class="title">{!! $post->title !!}</h1> @endif
                                @if(!empty($meta['subtitle'])) <p class="description">{!! $meta['subtitle'] !!}</p> @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   @if(!empty($post->postcontent))
   <div class="container page-content">
      <div class="row">
         <div class="col-lg-12">
         	 {!! $post->postcontent->content !!} 
         </div>
      </div>
   </div>
   @endif

   @include('page.pricing')


   @include('page.faq')




@endsection
