@extends('layouts.frontend')

@section('header-script')
<!-- <link rel="stylesheet" href="{{ asset('css/video.css') }}"> -->
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
<script>
document.addEventListener("DOMContentLoaded", function () {
    const observer = new MutationObserver(function () {

        const firstProfile = document.querySelector('.ajxProfile .item');
        if (firstProfile) {
            firstProfile.click(); // auto select profile
        }

        // hide popup after selection
        const modal = document.querySelector('.ajxProfile .prfModal');
        if (modal) {
            modal.style.display = 'none';
        }

    });

    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});
</script>
@endsection
