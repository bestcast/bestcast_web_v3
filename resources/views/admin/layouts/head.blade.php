<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') {{ config('app.name', 'Administrator') }}</title>

    @if($core['general_website_favicon'])
    <link rel="shortcut icon" type="image/x-icon" href="{{ Lib::publicImgSrc($core['general_website_favicon']) }}" />
    @endif

    <!-- Pre Fetch -->
    <link rel='dns-prefetch' href='//www.google.com' />
    <link rel='dns-prefetch' href='//s.w.org' />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">



    <!-- Styles -->
    <link href="{{ asset('admin/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/bootstrap.min.css?ver=5.3.2') }}" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="{{ asset('admin/css/bootstrap-datepicker.css?ver=1.9') }}" rel="stylesheet" /> 
    <link href="{{ asset('admin/css/select2.min.css?ver=4.1.0-rc.0') }}" rel="stylesheet" /> 



    <!-- Scripts -->
    <script type='text/javascript' src="{{ asset('admin/js/jquery.min.js') }}"></script>
    <script type='text/javascript' src="{{ asset('admin/js/jquery.minicolors.js') }}"></script>
    <script type='text/javascript' src="{{ asset('admin/js/jquery-sortable.js') }}"></script>
    <script src="{{ asset('admin/js/jquery.validate.js') }}"></script>
    <script src="{{ asset('admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.tiny.cloud/1/xbgi67fw3w2trce2dulnod8ubxou2uai3cfgfx460mxs2ciw/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="{{ asset('admin/js/popper.min.js?ver=2.11.8') }}" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="{{ asset('admin/js/bootstrap.min.js?Ver=5.3.2') }}" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <script type='text/javascript' src="{{ asset('admin/js/bootstrap-datepicker.js?ver=1.9') }}"></script>
    <script src="{{ asset('admin/js/select2.min.js?ver=4.1.0-rc.0') }}"></script>


    <script type='text/javascript' src="{{ asset('admin/js/admin_custom.js') }}?ver=<?php echo "1.0";?>"></script>
    <link rel='stylesheet' href="{{ asset('admin/css/admin.css') }}?ver=<?php echo "1.0".time();?>" media='all' />

