   <!-- Pre Fetch -->
   @if($core['general_website_googlefont'])
   <!-- Pre Fetch -->
   <link rel='dns-prefetch' href='//www.google.com' />
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family={{ $core['general_website_googlefont'] }}&display=swap" rel="stylesheet" />
   @endif
   @if($core['general_website_favicon'])
   <link rel="shortcut icon" type="image/x-icon" href="{{ Lib::img($core['general_website_favicon']) }}" />
   @endif

    <!-- CSS
   ============================================ --> 
   <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/css/vendor/bootstrap.min.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/css/vendor/slick.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/css/vendor/animation.css') }}">
   <link rel="stylesheet" href="{{ asset('css/style.css') }}">
   <link rel='stylesheet' href="{{ asset('css/custom.css') }}?time={{ time() }}" media='all' />


   <!-- CSRF Token -->
   <meta name="csrf-token" content="{{ csrf_token() }}">


   <!-- Scripts -->
   <script type="text/javascript">var domain='{{ url('/') }}';</script>
   <script src="{{ asset('assets/js/vendor/modernizr.min.js') }}"></script>
   <script src="{{ asset('assets/js/vendor/jquery.js') }}"></script>
   <script src="{{ asset('assets/js/vendor/bootstrap.min.js') }}"></script>

