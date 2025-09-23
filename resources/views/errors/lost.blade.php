@extends('layouts.frontend')

@section('content')

<style type="text/css">.pageloader{display: none !important;}</style>
<div class="lostPage" @if(!empty($post->image) && !empty($post->image->urlkey)) style="background:url({{ Lib::img($post->image->urlkey) }})" @endif>
   <div class="container-fluid overlay-dark">
      <div class="row"><div class="col-lg-12">

            <div class="content">
                <h2>{{ empty($title)?$post->title:$title }}</h2>
                {!! $post->excerpt !!}
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
