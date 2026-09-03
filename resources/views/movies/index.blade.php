@extends('layouts.frontend')

@section('header-script')
<!-- <link rel="stylesheet" href="{{ asset('css/video.css') }}"> -->
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/vpl.css" />
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/aviva.css" />
<script src="{{ asset('js/auth/logout.js') }}?v=1" defer></script>
<script src="{{ asset('js/video-new.js') }}?v=1" defer></script>
<script src="{{ asset('js/movies-new.js?1') }}?v=1" defer></script>
<script src="{{ asset('js/webseries-click-patch.js') }}?v=1" defer></script>
@include('webseries.info-modal')
<!-- <script src="{{ asset('js/banner-custom.js') }}"></script> -->
@endsection

@section('content') 

  <div class="ajxProfile"></div>
  <div class="ajxDtPopup"></div>
  <div class="previewMovie"></div>
  @include('movies.playermini')
  @include('movies.player')

  @include('movies.genre')
  <div class="bannerWrapper">
    <div class="ajxBanner"></div>
  </div>

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
/*document.addEventListener("DOMContentLoaded", function () {

    const container = document.querySelector('.bannerWrapper');

    setInterval(() => {
        if (!container) return;

        container.scrollBy({
            left: window.innerWidth,
            behavior: 'smooth'
        });

        // loop back
        if (container.scrollLeft + container.clientWidth >= container.scrollWidth) {
            setTimeout(() => {
                container.scrollTo({ left: 0, behavior: 'smooth' });
            }, 500);
        }

    }, 5000); // every 5 sec
});*/
/*document.addEventListener("DOMContentLoaded", function () {

    const waitForBanner = setInterval(() => {

        const slider = document.querySelector('.ajxBanner');
        const slides = document.querySelectorAll('.loadBanner');

        if (slides.length > 0) {

            clearInterval(waitForBanner);

            let index = 0;

            setInterval(() => {
                index++;
                if (index >= slides.length) index = 0;

                slider.style.transform = `translateX(-${index * 100}%)`;

            }, 5000);
        }

    }, 500);

});
document.querySelector('.bannerNext')?.addEventListener('click', () => {
    document.querySelector('.bannerWrapper').scrollBy({ left: window.innerWidth, behavior: 'smooth' });
});

document.querySelector('.bannerPrev')?.addEventListener('click', () => {
    document.querySelector('.bannerWrapper').scrollBy({ left: -window.innerWidth, behavior: 'smooth' });
});*/
</script>

@endsection