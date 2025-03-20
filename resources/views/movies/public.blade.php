@extends('layouts.frontend')

@section('header-script')
<!-- <link rel="stylesheet" href="{{ asset('css/video.css') }}"> -->
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/vpl.css" />
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/aviva.css" />
<script src="{{ asset('js/video-new.js') }}?v=1" defer></script>
<script src="{{ asset('js/movies-public.js') }}?v=1" defer></script>
@endsection

@section('content') 

  <div class="ajxProfile"></div>
  <div class="ajxDtPopup"></div>
  <div class="previewMovie"></div>
  @include('movies.playermini')
  @include('movies.player')

  @include('movies.genre')
  <div class="ajxBanner"></div>

   <div class="container-fluid blkCtr">
      <div class="row">
         <div class="col-lg-12">
	         	<div class="ajxBlocks"></div>
	        	<div class="loadingMore"></div>
         </div>
      </div>
   </div>


@endsection
