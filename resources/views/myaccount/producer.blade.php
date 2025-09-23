@extends('layouts.myaccount')

@section('header-script')
<script src="{{ asset('js/auth/logout.js') }}?v=1" defer></script>
@endsection

@section('content')


<section class="hLTSec account twocolmn accpage"><div class="hLTGrid">
        	<div class="hLTColm hLTColm100 content-area"><div class="in">

        		@include('common.message')
        	 	

        	<style>
        		.pfMenu > .icon{background-color:#000000 !important;background-image: url({{ url('/') }}/img/icon/website/account-white.png) !important;}
        		.item.backarrow,.item.manage,.item.account,.item.help{display: none !important;}
        		.edu-blog.blog-type-2,.edu-blog.blog-type-2:hover{background: #000;}
        		.edu-blog.blog-type-2 .inner .content .title{margin-top: 0px;}
        	</style>

            <div class="container">
                <div class="row g-5">
                    <div class="col-lg-12">
                    	<h1>Movies List</h1>
                    </div>
                    <div class="col-lg-12">
                        <div class="row g-5 mb--20">


                        	@foreach($movies as $movie)
                            <!-- Start Blog Grid  -->
                            <div class="col-lg-4 col-md-6 col-12" dataid="{{ $movie->id }}">
                                <div class="edu-blog blog-type-2 radius-small">
                                    <div class="inner">
                                        <div class="thumbnail">
                                                <img src="{{ Lib::img($movie->thumbnail->urlkey) }}" alt="">
                                        </div>
                                        <div class="content">
                                            <h5 class="title">{{ $movie->title }}</h5>
                                            <div class="blog-card-bottom">
                                            	Views: {{ App\Models\UsersMovies::getProducerMovieCount($movie->id) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Blog Grid  -->
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>

        	</div></div>
</div></section> 
@endsection


