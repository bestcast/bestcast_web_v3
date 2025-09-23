@extends('myaccount.app')

@section('contentarea')
	<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function(event) { 
		localStorage.setItem("tokenEncrypted","{{ $token }}");
		setWithExpiry('profileToken', "{{ $profileid }}", 44486400); 
		window.location.href="{{ url('/my-account') }}";
    });
	</script>
@endsection
