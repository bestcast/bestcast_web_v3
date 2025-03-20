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

         						<?php 
						         	$rpay=Paymentgateway::razorpay('core');						         	
						     	?>

         						@if(!empty($rpay['paymentstatus_title']))<h1 class="title">{{ $rpay['paymentstatus_title'] }}</h1>@endif
         						@if(!empty($rpay['paymentstatus_content']))<div class="description small text-center">{!! $rpay['paymentstatus_content'] !!}</div>@endif

         						@if(!empty($trans) && $trans->status==1)
         							@if(!empty($rpay['paymentstatus_warning']))<p class="payment-warning">{{ $rpay['paymentstatus_warning'] }}</p>@endif
         							<div><a href="{{ url('browse') }}"  class="whiteBtn">{{ env('APP_NAME') }} Home</a></div>
         						@elseif(!empty($trans) && $trans->status==2)
         							@if(!empty($rpay['paymentstatus_success']))<p class="payment-success">{{ $rpay['paymentstatus_success'] }}</p>@endif
         							<div><a href="{{ url('browse') }}"  class="whiteBtn">{{ env('APP_NAME') }} Home</a></div>
         						@else
         							@if(!empty($rpay['paymentstatus_error']))<p class="payment-error">{{ $rpay['paymentstatus_error'] }}</p>@endif
         						@endif

                                <script>
                                // Function to redirect after 5 seconds
                                function redirectToPage() {
                                    setTimeout(function() {
                                        window.location.href = '/browse';
                                    }, 5000);
                                }
                                redirectToPage();
                                </script>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- 4718 6091 0820 4366 -->

@endsection
