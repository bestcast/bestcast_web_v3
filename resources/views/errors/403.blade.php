@extends('layouts.frontend')

@section('content')
<style type="text/css">.pageloader{display: none !important;}</style>
<div class="lostPage">
   <div class="container-fluid overlay-dark">
      <div class="row"><div class="col-lg-12">

            <div class="content">
                <h2>403 | Access Denied</h2>
                <p>Oops! That page can’t be found.<br>Nothing was found at this location.</p>
                <br>
                <div><a href="{{ url('home') }}"  class="whiteBtn">{{ env('APP_NAME') }} Home</a></div>
                <br>
                @if(!empty($exception))
                <h4>ERROR CODE: {{ $exception->getMessage() }}</h4>
                @endif
            </div>

        </div></div>
    </div>
</div>

@endsection
