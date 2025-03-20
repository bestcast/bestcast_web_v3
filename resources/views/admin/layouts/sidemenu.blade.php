<div class="d-flex flex-column flex-shrink-0 p-3 bg-light">
  <ul class="nav nav-pills flex-column mb-auto">{!! Lib::cachegenerate(); !!}
    @if (Route::has('admin.dashboard.index'))
      <li class="icon-dashboard {{ request()->is('admin/dashboard') || request()->is('admin/dashboard/*') ? 'active' : '' }}">
        <a href="{{ route('admin.dashboard.index') }}" class="nav-link link-dark">          
          Dashboard
        </a>
      </li>
    @endif
    @if (Route::has('admin.genres.index'))
      <li class="icon-genre {{ request()->is('admin/genres') || request()->is('admin/genres/*') ? 'active' : '' }}">
        <a href="{{ route('admin.genres.index') }}" class="nav-link link-dark">          
          Genres
        </a>
      </li>
    @endif
    @if (Route::has('admin.languages.index'))
      <li class="icon-language {{ request()->is('admin/languages') || request()->is('admin/languages/*')  ? 'active' : '' }}">
        <a href="{{ route('admin.languages.index') }}" class="nav-link link-dark">          
          Languages
        </a>
      </li>
    @endif
    @if (Route::has('admin.movies.index'))
      <li class="icon-movie {{ request()->is('admin/movies') || request()->is('admin/movies/*') ? 'active' : '' }}">
        <a href="{{ route('admin.movies.index') }}" class="nav-link link-dark">          
          Movies
        </a>
      </li>
    @endif
    @if (Route::has('admin.shows.index')) <!--Tv Shows Extend-->
      <li class="icon-tvshows dnn {{ request()->is('admin/shows') || request()->is('admin/shows/*') || request()->is('admin/sessions') || request()->is('admin/sessions/*') || request()->is('admin/episodes') || request()->is('admin/episodes/*') ? 'active' : '' }}">
        <a href="{{ route('admin.shows.index') }}" class="nav-link link-dark">          
          Tv Shows
          <span class="arrow icon-arrow-right">&nbsp;</span>
        </a>
        <ul class="subnavpills"  style="{{ request()->is('admin/shows') || request()->is('admin/shows/*') || request()->is('admin/sessions') || request()->is('admin/sessions/*') || request()->is('admin/episodes') || request()->is('admin/episodes/*') ? 'display:block' : '' }}">
          <li class="icon-arrow-double-right {{ request()->is('admin/shows') || request()->is('admin/shows/*') ? 'active' : '' }}">
            <a href="{{ route('admin.shows.index') }}">Shows</a></li>
          <li class="icon-arrow-double-right {{ request()->is('admin/sessions') || request()->is('admin/sessions/*') ? 'active' : '' }}">
            <a href="{{ route('admin.shows.index') }}">Sessions</a></li>
          <li class="icon-arrow-double-right {{ request()->is('admin/episodes') || request()->is('admin/episodes/*') ? 'active' : '' }}">
            <a href="{{ route('admin.shows.index') }}">Episodes</a></li>
        </ul>
      </li>
    @endif
    @role('admin,subadmin')
      @if (Route::has('admin.menu.index'))
        <li class="icon-menu  {{ request()->is('admin/menu') || request()->is('admin/menu/*') ? 'active' : '' }}">
          <a href="{{ route('admin.menu.index') }}" class="nav-link link-dark">
            Menu
          </a>
        </li>
      @endif
      @if (Route::has('admin.page.index'))
        <li class="icon-page  {{ request()->is('admin/page') || request()->is('admin/page/*')  || request()->is('admin/banner') ||  request()->is('admin/banner/*') || request()->is('admin/blocks') ||  request()->is('admin/blocks/*') || request()->is('admin/profileicon') ||  request()->is('admin/profileicon/*') || request()->is('admin/appnotify') ||  request()->is('admin/appnotify/*') || request()->is('admin/post/*') ? 'active' : '' }}">
          <a href="{{ route('admin.page.index') }}" class="nav-link link-dark">          
            Content
              <span class="arrow icon-arrow-right">&nbsp;</span>
            </a>
            <ul class="subnavpills"  style="{{ request()->is('admin/page') || request()->is('admin/page/*')  || request()->is('admin/banner') ||  request()->is('admin/banner/*') || request()->is('admin/blocks') ||  request()->is('admin/blocks/*') || request()->is('admin/profileicon') ||  request()->is('admin/profileicon/*') || request()->is('admin/appnotify') ||  request()->is('admin/appnotify/*') || request()->is('admin/post/*') ? 'display:block' : '' }}">
              <li class="icon-arrow-double-right {{ request()->is('admin/page') || request()->is('admin/page/*')  || request()->is('admin/post/*') ? 'active' : '' }}">
                <a href="{{ route('admin.page.index') }}">Page</a></li>
              <li class="icon-arrow-double-right {{ request()->is('admin/banner') || request()->is('admin/banner/*') ? 'active' : '' }}">
                <a href="{{ route('admin.banner.index') }}">Banner</a></li>
              <li class="icon-arrow-double-right {{ request()->is('admin/blocks') || request()->is('admin/blocks/*') ? 'active' : '' }}">
                <a href="{{ route('admin.blocks.index') }}">Blocks</a></li>
              <li class="icon-arrow-double-right {{ request()->is('admin/profileicon') || request()->is('admin/profileicon/*') ? 'active' : '' }}">
                <a href="{{ route('admin.profileicon.index') }}">Profile Icon</a></li>
              <li class="icon-arrow-double-right {{ request()->is('admin/appnotify') || request()->is('admin/appnotify/*') ? 'active' : '' }}">
                <a href="{{ route('admin.appnotify.index') }}">App Notify</a></li>
            </ul>
        </li>
      @endif
    @endrole
    @if (Route::has('admin.media.index'))
      <li class="icon-image  {{ request()->is('admin/media') || request()->is('admin/media/*') ? 'active' : '' }}">
        <a href="{{ route('admin.media.index') }}" class="nav-link link-dark">
          Media
        </a>
      </li>
    @endif
    @role('admin,subadmin')
      @if (Route::has('admin.subscription.index'))
        <li class="icon-rupee {{ request()->is('admin/subscription') || request()->is('admin/subscription/*') ? 'active' : '' }}">
          <a href="{{ route('admin.subscription.index') }}" class="nav-link link-dark">          
            Subscription
          </a>
        </li>
      @endif
      @if (Route::has('admin.paymentgateway.index'))
        <li class="icon-payment {{ request()->is('admin/paymentgateway') || request()->is('admin/paymentgateway/*') ? 'active' : '' }}">
          <a href="{{ route('admin.paymentgateway.index') }}" class="nav-link link-dark">          
            Payment Gateway
          </a>
        </li>
      @endif
      @if (Route::has('admin.transactions.index'))
        <li class="icon-transactions {{ request()->is('admin/transactions') || request()->is('admin/transactions/*') ? 'active' : '' }}">
          <a href="{{ route('admin.transactions.index') }}" class="nav-link link-dark">          
            Transactions
          </a>
        </li>
      @endif
      @if (Route::has('admin.user.index'))
        <li class="icon-users  {{ request()->is('admin/user') || request()->is('admin/user/*') ? 'active' : '' }}">
            <a href="{{ route('admin.user.index') }}" class="nav-link link-dark">
              Users
              <span class="arrow icon-arrow-right">&nbsp;</span>
            </a>
            <ul class="subnavpills" style="{{ request()->is('admin/user') || request()->is('admin/user/*') ? 'display:block' : '' }}">
              <li class="icon-arrow-double-right {{ request()->is('admin/user/producer')?'active':'' }}">
                <a href="{{ route('admin.user.producer') }}">Producer</a>
              </li>
              <li class="icon-arrow-double-right {{ request()->is('admin/user/director')?'active':'' }}">
                <a href="{{ route('admin.user.director') }}">Director</a></li>
              <li class="icon-arrow-double-right {{ request()->is('admin/user/actor')?'active':'' }}">
                <a href="{{ route('admin.user.actor') }}">Actor</a></li>
              <li class="icon-arrow-double-right {{ request()->is('admin/user/actress')?'active':'' }}">
                <a href="{{ route('admin.user.actress') }}">Actress</a></li>
              <li class="icon-arrow-double-right {{ request()->is('admin/user/musicdirector')?'active':'' }}">
                <a href="{{ route('admin.user.musicdirector') }}">Music Director</a></li>
              @role('admin,subadmin')
                <!-- <li class="icon-arrow-double-right {{ request()->is('admin/user/editor')?'active':'' }}">
                  <a href="{{ route('admin.user.editor') }}">Editor</a></li> -->
                <li class="icon-arrow-double-right {{ request()->is('admin/user/admin')?'active':'' }}">
                  <a href="{{ route('admin.user.admin') }}">Admin</a></li>
              @endrole
            </ul>
        </li>
      @endif
      @if (Route::has('admin.mobileapp.index'))
        <li class="icon-mobileapp {{ request()->is('admin/mobileapp') || request()->is('admin/mobileapp/*') ? 'active' : '' }}">
          <a href="{{ route('admin.mobileapp.index') }}" class="nav-link link-dark">          
            Mobile App
          </a>
        </li>
      @endif
      @if (Route::has('admin.refer.index'))
        <li class="icon-refer {{ request()->is('admin/refer') || request()->is('admin/refer/*') ? 'active' : '' }}">
          <a href="{{ route('admin.refer.index') }}" class="nav-link link-dark">          
            Referral Program
          </a>
        </li>
      @endif
    @endrole
  </ul>
</div>
