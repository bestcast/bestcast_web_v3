@if (session('warning'))
    @php ($warning=session('warning'))
    @if(is_array($warning))
        <div class="container alertmsg"><div class="row">
            <div class="alert alert-warning" role="alert">
                @foreach ($warning as $item)
                    <div>{{ Lib::trans($item) }}</div>
                @endforeach
            </div>
        </div></div>
    @else
        <div class="container alertmsg"><div class="row">
            <div class="alert alert-warning" role="alert">
                {{ Lib::trans($warning) }}
            </div>
        </div></div>
    @endif
@elseif (!empty($warning))
    <div class="container alertmsg"><div class="row">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ Lib::trans($warning) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div></div>
@endif
@if (session('error'))
    @php ($error=session('error'))
    @if(is_array($error))
        <div class="container alertmsg"><div class="row">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                @foreach ($error as $item)
                    <div>{{ Lib::trans($item) }}</div>
                @endforeach
            </div>
        </div></div>
    @else
        <div class="container alertmsg"><div class="row">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ Lib::trans($error) }}
            </div>
        </div></div>
    @endif
@elseif (!empty($error))
    <div class="container alertmsg"><div class="row">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ Lib::trans($error) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div></div>
@endif
@if (!empty($success))
    <div class="container alertmsg"><div class="row">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Lib::trans($success) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div></div>
@elseif (session('success'))
    @php ($success=session('success'))
    @if(is_array($success))
        <div class="container alertmsg"><div class="row">
            <div class="alert alert-success" role="alert">
                @foreach ($success as $item)
                    <div>{{ Lib::trans($item) }}</div>
                @endforeach
            </div>
        </div></div>
    @else
        <div class="container alertmsg"><div class="row">
            <div class="alert alert-success" role="alert">
                {{ Lib::trans($success) }}
            </div>
        </div></div>
    @endif
@elseif (session('status'))
    <div class="container alertmsg"><div class="row">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ Lib::trans(session('status')) }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div></div>
@endif

@if ($errors->any())
    <div class="container alertmsg"><div class="row">
        <div class="alert alert-danger alert-dismissible fade show">
            @foreach ($errors->all() as $error)
                <div>{{ Lib::trans($error) }}</div>
            @endforeach
        </div>
    </div></div>
@endif
