@extends('layouts.frontend')

@section('header-script')
    <div id="bigPlayerOuter" class="bigPlayerOuter"><div class="vpl-player-loader dnnshow"></div></div>

    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/vpl.css" />
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/aviva.css" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/video-new.js') }}?v=1" defer></script>
    <script src="{{ asset('js/movies-public.js') }}?v=1" defer></script>

@endsection

@section('content') 
    <div id="wrapper" class="vpl-skin-aviva vpl-customized"></div>

    <style type="text/css">
        .vpl-settings-menu .vpl-quality-menu .vpl-btn-reset { display: none; }
        .vpl-lightbox-close {
            display: none !important;
        }
        
    </style>
    @php
        $subtitles=[]; $issetActive=0;
        if(!empty($movie->subtitle)){
            foreach($movie->subtitle as $item){
                $list = [];
                $list['label'] = $item->label;
                $list['src']   = $item->url;
                if(!empty($item->is_active) && empty($issetActive)){
                    $issetActive=1;
                    $list['active']='true';
                }
                $subtitles[]=$list;
            }
        }
        $video_url = $movie->trailer_url;
    @endphp

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        var settings = {
            useShare:false,
            instanceName:"trailerPlayer",
            playerRatio: "1.777777",
            activeItem:0,
            volume:0.7,
            autoPlay:true,
            preload:'auto',
            skipPoster: true,
            showPosterOnPause: false,
            displayPosterOnMobile:false,
            mediaEndAction:"rewind",
            seekTime: "10",
            wrapperMaxWidth:"100%",
            rightClickContextMenu: "browser",
            useKeyboardNavigationForPlayback:true,
            playerType:'lightbox',
            media:[
                {
                    type: "{{ Str::endsWith($video_url, '.m3u8') ? 'hls' : 'mp4' }}",
                    path:"{{ $video_url }}",
                    @if(count($subtitles))
                        subtitles:[
                            @foreach($subtitles as $subtitle)
                                {
                                    label: '{{ $subtitle['label'] }}',
                                    src: '{{ $subtitle['src'] }}'
                                    @if(isset($subtitle['active'])), active:true @endif
                                }@if(!$loop->last),@endif
                            @endforeach
                        ]
                    @endif
                }
            ]
        };

        fetch("{{ url('/') }}/vlite/skin/aviva.txt")
        .then(response => response.text())
        .then(content => {
            let wrapper = document.getElementById("wrapper");
            let updatedContent = content.replace(
                '<div class="vpl-player-controls-bottom">',
                '<div class="vpl-back-refer ICineLeft">{{ $movie->title }} (Trailer)</div><div class="vpl-player-controls-bottom">'
            );
            wrapper.innerHTML = updatedContent;
            let player = new vpl(wrapper, settings);

            setTimeout(() => {
                let video = wrapper.querySelector("video");
                if (video) {
                    video.muted = false;
                    video.volume = 0.7;

                    const playPromise = video.play();
                    if (playPromise !== undefined) {
                        playPromise.catch(err => console.log("Autoplay blocked:", err));
                    }

                    if (player && typeof player.toggleMute === "function") {
                        player.toggleMute(false);
                    }
                }
            }, 500);

            // back button to browse
            $('.vpl-back-refer').on('click',function(){
                if (player && typeof player.cleanMedia === 'function') {
                    player.cleanMedia();
                }
                window.location.href="{{ url('/') }}";
            });
        });
    });
</script>
@endsection