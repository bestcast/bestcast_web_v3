<header class="edu-header  header-sticky header-style-2 header-default @guest loggedout @else loggedin @endguest">
    <div class="container">
       <div class="row align-items-center">
           <!-- 
           <div class="col-lg-4 col-xl-3 col-md-6 col-6"> Logo
           <div class="col-lg-6 d-none d-xl-block"> Nav
           <div class="col-lg-8 col-xl-3 col-md-6 col-6"> Account
           -->
           <div class="col-lg-8 col-xl-9 col-md-9 col-9 p-0 d-flex">
               <div class="logo">
                   <a href="{{ url('/') }}">
                      <img class="hLTDark" src="{{ env('APP_URL').'/'.($core['general_header_logo']) }}" alt="{{ $core['global_seo_title'] }}" />
                   </a>
               </div>
               <nav class="mainmenu-nav d-none d-lg-block">
                   @if(!empty($menu))
                       @php ($postid=empty($postid)?0:$postid)
                       @php ($menulist=App\Models\Menu::menuList($postid))
                       {!! $menulist !!}
                   @endif
               </nav>
           </div>
           <div class="col-lg-4 col-xl-3 col-md-3 col-3 p-0">
               <div class="header-right d-flex justify-content-end">
                   <div class="header-menu-bar">
                        @if(!empty($menu))
                        <div class="inner searchBx">
                            @guest 
                                <form action="{{ route('guest.search') }}">
                            @else
                                <form action="{{ route('search') }}">
                            @endguest
                                <input type="text" name="search" class="search-field" placeholder="Search..." value="{{ app('request')->input('search') }}" />
                                @if(app('request')->input('search'))
                                <a href="{{ route('browse') }}" class="closebtn"></a>
                                @endif
                            </form>
                        </div>
                        @endif
                       
                               @guest 
                                    <div class="quote-icon quote-user d-md-block ml--15 ml_sm--5">
                                        <a class="edu-btn btn-medium left-icon header-button head-loginbtn" href="{{ url('/login') }}">Login</a>
                                    </div>
                                   @if(\URL::current()!=url('/login') || isset($_GET['email']))
                                   @endif
                                   <!-- <a class="edu-btn btn-medium left-icon header-button" href="{{ url('/register') }}"><i class="ri-user-line"></i>Register</a> -->
                               @else
                                  <div class="header-quote tight">
                                      @include('layouts.notification')
                                      @include('layouts.accountmenu')
                                  </div>
                               @endguest
                       
                       <?php /*
                       @if(!empty($menu))
                       <div class="quote-icon quote-user d-block d-md-none ml--15 ml_sm--5">
                           <a class="white-box-icon" href="{{ url('/register') }}"><i class="ri-user-line"></i></a>
                       </div>
                       @endif
                       */?>
                       @if(!empty($mobile_menu))
                       <div class="mobile-menu-bar ml--15 ml_sm--5 d-block d-lg-none">
                           <div class="hamberger">
                               <button class="white-box-icon hamberger-button header-menu">
                                   <i class="ri-menu-line"></i>
                               </button>
                           </div>
                       </div>
                       @endif
                   </div>

               </div>
           </div>
       </div>
    </div>
</header>


  @if(!empty($mobile_menu))
      <div class="popup-mobile-menu">
          <div class="inner">
                <div class="header-top">
                  <div class="logo">
                      <a href="index.html">
                          <img src="{{ env('APP_URL').'/'.($core['general_header_logo']) }}" alt="{{ $core['global_seo_title'] }}" />
                      </a>
                  </div>
                  <div class="close-menu">
                      <button class="close-button">
                          <i class="ri-close-line"></i>
                      </button>
                  </div>
                </div>

                @if(!empty($menu))
                    {!! $menulist !!}
                @endif
          </div>
      </div>
  @endif



@guest 
@else
<script>
    document.getElementById('logoutForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const storedToken = localStorage.getItem("tokenEncrypted");
        if (!storedToken) {
            console.error('Access token not found');
            //window.location.href="{{ url('/login') }}";
            //return;
        }
        if (localStorage.getItem('tokenEncrypted')) {
            localStorage.removeItem('tokenEncrypted');
            localStorage.removeItem('profileToken');
        }
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch('/api/logout?gettoken='+storedToken, {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + storedToken,
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
           window.location.href="{{ url('/login') }}";
            // Handle any additional actions after logout (e.g., redirecting to login page)
        })
        .catch(error => {
            alert('Error:', error);
            window.location.href="{{ url('/login') }}";
        });
    });
</script>
@endguest