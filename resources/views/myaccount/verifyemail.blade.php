@extends('myaccount.app')

@section('contentarea')
	<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function(event) { 
		localStorage.setItem("tokenEncrypted","{{ $token }}");
		window.location.href="{{ url('/pricing') }}";
    });
	</script>
@endsection
