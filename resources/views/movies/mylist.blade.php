@extends('layouts.frontend')

@section('header-script')
<!-- <link rel="stylesheet" href="{{ asset('css/video.css') }}">
<script src="{{ asset('js/auth/logout.js') }}" defer></script>
<script src="{{ asset('js/video.js') }}" defer></script>
<script src="{{ asset('js/movies.js') }}" defer></script> -->
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/vpl.css" />
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/aviva.css" />
<script src="{{ asset('js/auth/logout.js') }}?v=1" defer></script>
<script src="{{ asset('js/video-new.js') }}?v=1" defer></script>
<script src="{{ asset('js/movies-new.js?1') }}?v=1" defer></script>
@endsection

@section('content') 
  <div class="ajxProfile"></div>
  <div class="ajxDtPopup"></div>

  <div class="previewMovie"></div>
  @include('movies.playermini')
  @include('movies.player')

   <div class="container-fluid blkCtr">
      <div class="row">
         <div class="col-lg-12">
      			<div class="page-title">My List</div>
	         	<div class="ajxMyList">
         			<div class="listing page-search">
			         	<div class="slideList">
			         		<div class="grid-slide edu-slick-button slick-activation-wrapper clearfix">
			         		</div>
			         	</div>
     				</div>
	         	</div>
	        	<div class="loadingMore"></div>
         </div>
      </div>
   </div>


@endsection
