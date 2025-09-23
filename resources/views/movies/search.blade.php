@extends('layouts.frontend')

@section('header-script')
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

   <div class="container-fluid blkCtr">
      <div class="row">
         <div class="col-lg-12">
         		@if(app('request')->input('search'))
         			<div class="page-title">Search : {{ app('request')->input('search') }}</div>
      			@elseif(app('request')->input('genre'))
      				@php($genreid=app('request')->input('genre'))
         			<div class="page-title">Movies @if($genreid) > {{ \App\Models\Genres::getTitle($genreid) }} @endif</div>
         		@else
         			<div class="page-title">Movies</div>
         		@endif
	         	<div class="ajxSearchList">
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
