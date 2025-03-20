@include('admin.layouts.head')
<style>
</style>
  @yield('style-theme')
</head>


<body>

@include('admin.layouts.header')
  <main class="h-100">
    <div class="container-fluid h-100">
     <div class="row h-100 main-row">
       <div class="col-3 col-xl-2 col-xxl-2 leftmenu">
          @include('admin.layouts.sidemenu')
       </div>
       <div class="col-9 col-xl-10 col-xxl-10 contentarea">
         <div class="pgCont">
            <div class="container-fluid">
               @yield('content')
            </div>
         </div><!-- pgCont -->
       </div>
     </div>
    </div>
  </main>
@include('admin.layouts.media')
</body>
</html>
