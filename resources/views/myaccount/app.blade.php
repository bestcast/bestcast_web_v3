@extends('layouts.myaccount')

@section('header-script')
<script src="{{ asset('js/auth/logout.js') }}?v=1" defer></script>
@endsection

@section('content')


<section class="hLTSec account twocolmn accpage"><div class="hLTGrid">

        @guest
        	<div class="hLTColm hLTColm100 content-area"><div class="in">
        @else
			<div class="hLTColm hLTColm28 left-sidebar"><div class="in">
				<a href="{{ url('/browse') }}" class="backbtn">Back to {{ env('APP_NAME') }}</a>
				<div class="mHead">
					My Account
				</div>
				<div class="mBody">
					@include('myaccount.navigation')
				</div>
			</div></div>
			
			
	        <div class="hLTColm hLTColm72 content-area"><div class="in">
        @endguest

        		@include('common.message')
        	 	@yield('contentarea')
        	</div></div>

</div></section> 
@endsection
