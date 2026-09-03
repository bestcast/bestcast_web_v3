@extends('layouts.frontend')

@section('header-script')
<!-- <link rel="stylesheet" href="{{ asset('css/video.css') }}"> -->
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/vpl.css" />
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/aviva.css" />
<script src="{{ asset('js/auth/logout.js') }}?v=1" defer></script>
<script src="{{ asset('js/video-new.js') }}?v=1" defer></script>
<script>
    var webseries_id = {{ $webseries->id }};
    var base_url     = "{{ url('/') }}/";
</script>
<script src="{{ asset('js/webseries-new.js?1') }}?v=1" defer></script>
{{-- Hide More Info button --}}
<style>
    /*.moreInfo { display: none !important; }
    .bannerLogo { display: none !important; }*/
</style>
<!-- <script src="{{ asset('js/banner-custom.js') }}"></script> -->
@endsection

@section('content') 

  <div class="ajxProfile"></div>
  <div class="ajxDtPopup"></div>
  <div class="previewMovie"></div>
  @include('webseries.playermini')
  @include('webseries.player')

  @include('webseries.genre')

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
@include('webseries.info-modal')
@endsection