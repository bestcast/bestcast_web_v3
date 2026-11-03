@extends('layouts.frontend')

@section('header-script')
<div id="bigPlayerOuter" class="bigPlayerOuter"><div class="vpl-player-loader dnnshow"></div></div>
<style>
   .vpl-lightbox-wrap .vpl-lightbox-close{display: none !important;}
</style>
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/vpl.css" />
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/aviva.css" />
<script src="{{ asset('js/auth/logout.js') }}?v=1" defer></script>
<script src="{{ asset('js/video-new.js') }}?v=1" defer></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="ajxProfile"></div>
<div id="wrapper" class="vpl-skin-aviva vpl-customized"></div>

<?php
$referurl = empty($_GET['refer']) ? url('/browse') : $_GET['refer'];
?>

<script type="text/javascript">
var player;
document.addEventListener("DOMContentLoaded", function () {

    var profileToken    = getWithExpiry('profileToken');
    var profileTokenWeb = "{{ $profileToken }}";
    if (profileToken != profileTokenWeb) {
        localStorage.removeItem('profileToken');
        window.location.href = '{{ url('/') }}/browse';
        return false;
    }

    var storedToken = localStorage.getItem("tokenEncrypted");
    if (!storedToken) {
        window.location.href = '{{ url('/') }}/browse';
        return false;
    }

    document.querySelectorAll('.bigPlayerOuter').forEach(function (el) { el.classList.remove('dnn'); });
    document.documentElement.classList.add('noscroll');

    var wsTitle     = @json($webseries->title);
    var releaseText = @json($releaseText);
    var referUrl    = @json($referurl);

    var settings = {
        useShare: false, instanceName: "player1", playerRatio: "1.777777",
        activeItem: 0, volume: 0.7, autoPlay: true, preload: 'auto',
        skipPoster: true, showPosterOnPause: false, displayPosterOnMobile: false,
        mediaEndAction: "rewind", seekTime: "10", useResumeScreen: false,
        playbackPositionTime: 0, aspectRatio: 1, wrapperMaxWidth: "100%",
        randomPlay: false, rightClickContextMenu: "browser",
        useKeyboardNavigationForPlayback: true, playerType: 'lightbox',
        media: [{ type: 'hls', path: @json($trailer) }]
    };

    fetch("{{ url('/') }}/vlite/skin/aviva.txt")
    .then(function (r) { return r.text(); })
    .then(function (content) {
        var wrapper = document.getElementById("wrapper");
        var titleDiv = document.createElement('div');
        titleDiv.textContent = wsTitle;
        content = content.replace(
            '<div class="vpl-player-controls-bottom">',
            '<div class="vpl-back-refer ICineLeft">' + titleDiv.innerHTML + '</div> <div class="vpl-player-controls-bottom">'
        );
        wrapper.innerHTML = content;
        player = new vpl(wrapper, settings);

        if (player) {
            var notice = document.createElement('div');
            notice.className = 'upcoming-notice-inplayer';
            notice.style.cssText = 'position:absolute; top:10px; left:0; width:100%; text-align:center; padding:12px; background:rgba(0,0,0,0.8); color:#fff; z-index:99999; font-size:14px;';
            var strong = document.createElement('strong');
            strong.textContent = wsTitle;
            notice.appendChild(strong);
            notice.appendChild(document.createTextNode(' releases on ' + releaseText + ". You're watching the trailer."));
            wrapper.appendChild(notice);

            player.setVolume(0.7);

            $('.vpl-back-refer').on('click', function () {
                player.cleanMedia();
                window.location.href = decodeURI(referUrl);
            });
        }
    });
});
</script>
@endsection