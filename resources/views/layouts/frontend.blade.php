<?php
if(Auth::user()){
   $menu=1;
   $mobile_menu=1;
   $footer=1;
}else{
   $menu=1;
   $mobile_menu=1;
   $footer=1;
}

$postid=empty($post->id)?0:$post->id;

$currentUrl=\URL::current();
$bodyclass=(request()->is('/') || request()->is('home'))?' home ':'';
$bodyclass.=(!empty($loadbox))?' overflow-hidden ':'';

// Session::forget('profileToken');
// dd(Session::get('profileToken'));

$bgimg='';
$getRouteName=Route::currentRouteName();
if(in_array($getRouteName,array('login','send.otp','login.otp','password.request','password.reset')))
   $bgimg=empty($core['pages_login_bg'])?'':$core['pages_login_bg'];

if(Route::currentRouteName() == 'register')
   $bgimg=empty($core['pages_register_bg'])?'':$core['pages_register_bg'];

$pagetitle=(request()->is('/') || request()->is('home')) ?env('APP_NAME')." ".$core['global_seo_title']:env('APP_NAME');
$pagetitle=empty($meta['seo_title'])?$pagetitle:$meta['seo_title'];
$seodesc=empty($meta['seo_description'])?$core['global_seo_description']:$meta['seo_description'];
$bodyclass.=' '.$getRouteName.' ';$bodyclass.=empty($meta['classname'])?'':' '.$meta['classname'];

$genreid=app('request')->input('genre');$genreid=empty($genre->id)?$genreid:$genre->id;
$languageid=app('request')->input('language');$languageid=empty($language->id)?$languageid:$language->id;
$movieid=empty($movie->id)?'':$movie->id;
?>

<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
   <meta charset="utf-8">
   <meta http-equiv="x-ua-compatible" content="ie=edge">
   <meta name="robots" content="index, follow" />
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="theme-color" content="#000">
   <!-- <meta name="format-detection" content="telephone=no"> -->
   <meta name='robots' content="{{ $core['global_seo_robots'] }}" />
   <title>{{ $pagetitle }}</title>
   <meta name="description" content="{{ strip_tags($seodesc) }}" />

   @include('layouts.assets')

   @yield('header-script')

   
   <!-- Google tag (gtag.js) --> <script async src="https://www.googletagmanager.com/gtag/js?id=AW-11427077669"></script> <script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'AW-11427077669'); </script>

   <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-7VVB9LEN3M">
</script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-7VVB9LEN3M');
</script>
   
</head>

<body  class="{{ $bodyclass }} @if(!empty($bgimg)) bgimage @endif" @if(!empty($bgimg)) style='background-image:url("{{ Lib::publicImgSrc($bgimg) }}");' @endif>
   <div class="current-url" data-page="{{ $postid }}" data-genre="{{ $genreid }}" data-language="{{ $languageid }}" data-movie="{{ $movieid }}" data-url="{{ $currentUrl }}" data-domain="{{ url('/') }}" data-appname="{{ env('APP_NAME') }}" data-search="{{ app('request')->input('search') }}"></div>
   <meta name="csrf-token" content="{{ csrf_token() }}">
   @if(!empty($loadbox)) <div class="loader-box"></div> @endif

    @guest 
    @else
       <div class="pageloader"><div class="vpl-player-loader dnnshow"></div></div>
    @endguest
   

   <div class="main-wrapper lyDark" >
         @include('layouts.header')
          
         @include('common.message')

         @yield('content')
   </div>
   @include('layouts.footer')

   <!-- JS ============================================ -->
   <script src="{{ asset('assets/js/vendor/sal.min.js') }}"></script>
   <script src="{{ asset('assets/js/vendor/slick.js') }}"></script>
   <script src="{{ asset('assets/js/vendor/imageloaded.js') }}"></script>
   <script src="{{ asset('assets/js/vendor/wow.js') }}"></script>

   <script src="{{ asset('js/main.js') }}?time={{ time() }}"></script>
   
   @yield('footer-script')
</body>
</html>