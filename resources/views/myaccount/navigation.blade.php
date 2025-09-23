	
   <ul>
	@php ($url=route('user.myaccount.index'))
<!-- Enable001 --> <li class="@if($url==\URL::current()) active  @endif"><a href="{{ $url }}">Overview</a></li>
	<!-- <li class="@if($url==\URL::current()) active  @endif"><a href="{{ $url }}">Profile</a></li> -->
	@php ($url=route('user.myaccount.membership'))
<!-- Enable001 --><li class="@if($url==\URL::current()) active  @endif"><a href="{{ $url }}">Membership</a></li>
	@php ($url=route('user.myaccount.profile'))
<!-- Enable001 --> <li class="@if($url==\URL::current()) active  @endif"><a href="{{ $url }}">Security</a></li>
	@php ($url=route('user.myaccount.devices'))
	<li class="@if($url==\URL::current()) active  @endif"><a href="{{ $url }}">Devices</a></li>
	@php ($url=route('user.myaccount.referfriend'))
<!-- Enable001 --><li class="@if($url==\URL::current()) active  @endif"><a href="{{ $url }}">Refer a Friend</a></li>
	<?php /*<li><a data-href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a><form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form></li> */?>
    
</ul>
    