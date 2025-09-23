<?php
$menu=1;
$mobile_menu=1;
$search=0;
$footer=1;

$postid=empty($post->id)?0:$post->id;

$currentUrl=\URL::current();
$bodyclass=(request()->is('/') || request()->is('home'))?'home':'';
$bodyclass.=(!empty($loadbox))?'overflow-hidden':'';

$bgimg='';
$getRouteName=Route::currentRouteName();

$pagetitle=(request()->is('/') || request()->is('home')) ?env('APP_NAME')." ".$core['global_seo_title']:env('APP_NAME');
$pagetitle=empty($meta['seo_title'])?$pagetitle:$meta['seo_title'];
$seodesc=empty($meta['seo_description'])?$core['global_seo_description']:$meta['seo_description'];
$bodyclass.=' '.$getRouteName.' ';$bodyclass.=empty($meta['classname'])?'':' '.$meta['classname'];

if(!Session::has('profileToken')){
   $user=Auth::user();
   if(!empty($user->id)){
      $profile=App\Models\UsersProfile::getApiList($user)->first();
      if(!empty($profile->id)){
         sleep(1);
         Session::put('profileToken',$profile->id);
      }
   }
}

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
   <style type="text/css">
      .pfMenu .list .item.profile,.pfMenu .list .item.manage{display: none !important;}
   </style>

   @include('layouts.assets')

   @yield('header-script')
</head>

<body  class="{{ $bodyclass }}" >
   <div class="current-url" data-url="{{ $currentUrl }}" data-domain="{{ url('/') }}" data-appname="{{ env('APP_NAME') }}" ></div>
   <meta name="csrf-token" content="{{ csrf_token() }}">
   @if(!empty($loadbox)) <div class="loader-box"></div> @endif


   <div class="main-wrapper lyDark" >
         @include('layouts.header')
          

         @yield('content')
   </div>
   @include('layouts.footer')


   <!-- JS ============================================ -->
   <script src="{{ asset('assets/js/vendor/sal.min.js') }}"></script>
   <script src="{{ asset('assets/js/vendor/slick.js') }}"></script>
   <script src="{{ asset('assets/js/vendor/imageloaded.js') }}"></script>
   <script src="{{ asset('assets/js/vendor/wow.js') }}"></script>

   <script src="{{ asset('js/main.js') }}?v=1"></script>
   
   @yield('footer-script')
</body>
</html>