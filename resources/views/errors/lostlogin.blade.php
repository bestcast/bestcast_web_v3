@extends('layouts.frontend')

@section('content')

<style type="text/css">.pageloader{display: none !important;}</style>
<div class="lostPage" @if(!empty($post->image) && !empty($post->image->urlkey)) style="background:url({{ Lib::img($post->image->urlkey) }})" @endif>
   <div class="container-fluid overlay-dark">
      <div class="row"><div class="col-lg-12">

            <div class="content">
               <script type="text/javascript">
                 if (localStorage.getItem('tokenEncrypted')) { localStorage.removeItem('tokenEncrypted'); }
                 if (localStorage.getItem('profileToken')) { localStorage.removeItem('profileToken'); }
               </script>
                <h2>Login session expired.</h2>
                <div><a href="{{ url('login') }}"  class="whiteBtn">Login</a></div>
            </div>

        </div></div>
    </div>
</div>

@endsection
