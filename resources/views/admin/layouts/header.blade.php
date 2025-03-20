
  <header>
    <div class="px-3 py-2 bg-light text-black">
      <div class="container-fluid">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
          <a href="{{ url('/') }}" class="d-flex align-items-center my-2 my-lg-0 me-lg-auto text-black text-decoration-none" target="_blank"><img src="{{ env('APP_URL').'/'.($core['general_header_logo']) }}" alt="{{ $core['global_seo_title'] }}" class="adminlogo" /></a>   
         @guest
         @else


          <ul class="nav col-12 col-lg-auto my-2 justify-content-center my-md-0 text-small icononly">
            @role('admin,subadmin')
               @if (Route::has('admin.notification.index'))

                  <li>
                    <a href="{{ route('admin.notification.index') }}" class="nav-link text-black d-block link-dark text-decoration-none position-relative notifyIcon">
                      <span class="iconG icon-notification"></span>
                      @php($notifyCount=App\Models\Notification::adminCount())
                      @if($notifyCount)
                        <span class="badge rounded-pill bg-danger" id="notifyCount">
                          {{ $notifyCount }}
                        </span>
                      @endif
                    </a>
                  </li>
  
                  <!-- <li>
                    <a href="{{ route('admin.notification.index') }}" class="nav-link text-black d-block link-dark text-decoration-none dropdown-toggle <?php if(URL::current()==route('admin.config.index')){ echo "current_page_item";} ?>"  id="dropdownSystem" data-bs-toggle="dropdown" aria-expanded="false" >
                      <span class="iconG icon-notification"></span>
                    </a>
                      <ul class="dropdown-menu text-small" aria-labelledby="dropdownSystem">
                            <li>To align right the dropdown menu with the given breakpoint</li>
                      </ul>
                  </li> -->
               @endif
               @if (Route::has('admin.config.index'))
                  <li>
                    <a href="{{ route('admin.config.index') }}" class="nav-link text-black d-block link-dark text-decoration-none dropdown-toggle <?php if(URL::current()==route('admin.config.index')){ echo "current_page_item";} ?>"  id="dropdownSystem" data-bs-toggle="dropdown" aria-expanded="false" >
                      <span class="iconG icon-setting"></span>
                    </a>
                      <ul class="dropdown-menu text-small" aria-labelledby="dropdownSystem">
                            <li><a class="dropdown-item" href="{{ route('admin.config.index') }}">{{ __('Configuration') }}</a></li>
                          @if (Route::has('admin.config.info'))
                            <!-- <li><a class="dropdown-item" href="{{ route('admin.config.info') }}">{{ __('Document') }}</a></li> -->
                          @endif
                      </ul>
                  </li>
               @endif
            @endrole
               <li class="dropdown ">
                 <a href="{{ url('/') }}/my-account" class="nav-link text-black d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                   <span class="iconG icon-profile"></span>
                 </a>
                   <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1">
                              @guest
                                 <li><a class="dropdown-item" href="{{ url('/login') }}">{{ __('Login') }}</a></li>
                                 <li><a class="dropdown-item" href="{{ url('/register') }}">{{ __('Register') }}</a></li>
                              @else
                                 <li><a class="dropdown-item" href="{{ route('admin.myaccount.index') }}">{{ __('My Account') }}</a></li>
                                 <li class="dnn"><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Logout') }}</a><form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form></li>
                              @endguest
                   </ul>
               </li>
          </ul>
         @endguest

        </div>
      </div>
    </div>
  </header>