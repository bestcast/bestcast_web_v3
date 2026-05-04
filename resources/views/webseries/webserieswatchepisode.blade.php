@extends('layouts.frontend')

@section('header-script')
<div id="bigPlayerOuter" class="bigPlayerOuter"><div class="vpl-player-loader dnnshow"></div></div>
<style>.vpl-lightbox-wrap .vpl-lightbox-close{display:none !important;}</style>
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/vpl.css" />
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/aviva.css" />
<script src="{{ asset('js/auth/logout.js') }}?v=1" defer></script>
<script src="{{ asset('js/video-new.js') }}?v=1" defer></script>
<script src="{{ asset('js/webseries-watch-new.js') }}?v=1" defer></script>
@endsection

@section('content')
<div class="ajxProfile"></div>
<div id="wrapper" class="vpl-skin-aviva vpl-customized"></div>
<style type="text/css">
    .vpl-settings-menu .vpl-quality-menu .vpl-menu-item.vpl-btn-reset{display: none;}
    .vpl-settings-menu .vpl-quality-menu .vpl-menu-item.vpl-btn-reset.vpl-menu-active{display: block;}
</style>
<?php
$referurl = empty($_GET['refer']) ? url('/webserieswatch/' . $webseries_id) : $_GET['refer'];

// ✅ Video quality selection
$plan = App\Models\Subscription::getPlan();
$video_url = '';
if (empty($plan->video_quality)) {
    $video_url = $episode->video_url_480p;
    $video_url = empty($video_url) ? $episode->video_url_720p : $video_url;
    $video_url = empty($video_url) ? $episode->video_url_1080p : $video_url;
    $video_url = empty($video_url) ? $episode->video_url : $video_url;
} else {
    if ($plan->video_quality == 1) {
        $video_url = $episode->video_url_720p;
        $video_url = empty($video_url) ? $episode->video_url_1080p : $video_url;
        $video_url = empty($video_url) ? $episode->video_url : $video_url;
    } elseif ($plan->video_quality == 2) {
        $video_url = $episode->video_url_1080p;
        $video_url = empty($video_url) ? $episode->video_url : $video_url;
    } else {
        $video_url = $episode->video_url;
    }
}
$video_url = empty($video_url) ? $episode->video_url : $video_url;
?>

<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function(event) {

    function apiFetchOptions(method, storedToken, csrfToken) {
        return {
            method: method,
            headers: {
                'Authorization': 'Bearer ' + storedToken,
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        };
    }

    var profileToken    = getWithExpiry('profileToken');
    var profileTokenWeb = "{{ $profileToken }}";

    @if(empty($userEpisode))
        window.location.href = "{{ url('/') }}/lost?message=profileidempty";
    @endif

    if (profileToken != profileTokenWeb) {
        localStorage.removeItem('profileToken');
        window.location.href = '{{ url('/') }}/browse';
        return false;
    }

    var storedToken = localStorage.getItem("tokenEncrypted");
    var csrfToken   = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    if (!storedToken || !csrfToken) {
        window.location.href = '{{ url('/') }}/browse';
        return false;
    }

    document.querySelectorAll('.bigPlayerOuter').forEach(function(el) {
        el.classList.remove('dnn');
    });
    document.documentElement.classList.add('noscroll');

    let playbacktime = '{{ empty($userEpisode) ? 0 : $userEpisode->watch_time }}';

    var settings = {
        useShare: false,
        instanceName: "player1",
        playerRatio: "1.777777",
        activeItem: 0,
        volume: 0.7,
        autoPlay: true,
        preload: 'auto',
        skipPoster: true,
        showPosterOnPause: false,
        displayPosterOnMobile: false,
        mediaEndAction: "rewind",
        seekTime: "10",
        useResumeScreen: false,
        playbackPositionTime: playbacktime,
        aspectRatio: 1,
        wrapperMaxWidth: "100%",
        randomPlay: false,
        rightClickContextMenu: "browser",
        useKeyboardNavigationForPlayback: true,
        playerType: 'lightbox',
        media: [{
            type: 'hls',
            path: "{!! $video_url !!}"
        }]
    };

    fetch("{{ url('/') }}/vlite/skin/aviva.txt")
    .then(response => response.text())
    .then(content => {
        var wrapper = document.getElementById("wrapper");
        content = content.replace(
            '<div class="vpl-player-controls-bottom">',
            '<div class="vpl-back-refer ICineLeft">{{ $episode->title }}</div><div class="vpl-player-controls-bottom">'
        );
        wrapper.innerHTML = content;
        player = new vpl(wrapper, settings);

        if (player) {
            var isPaused = 0;
            player.addEventListener("mediaPause", function() { isPaused = 1; });
            player.addEventListener("mediaPlay",  function() { isPaused = 0; });
            player.setVolume(0.7);

            let secondsWatched  = 0;
            let percentageWatched = '{{ empty($userEpisode) ? 0 : $userEpisode->watched_percent }}';
            let intervalSecond  = 15;

            let episodePlayInterval = setInterval(function() {
                if (!isPaused) {
                    let getCurrentTime  = parseInt(player.getCurrentTime(), 10);
                    let movieDuration   = parseInt(player.getDuration(), 10);

                    if (getCurrentTime && playbacktime) {
                        percentageWatched = parseInt(((getCurrentTime / '{{ empty($episode->duration) ? 7000 : $episode->duration }}') * 100), 10);
                    }
                    if (getCurrentTime == 0 && secondsWatched > 1) {
                        percentageWatched = 100;
                    }

                    let data = {
                        watch_time:       getCurrentTime,
                        watching:         1,
                        watched_percent:  percentageWatched,
                        movieDuration:    movieDuration
                    };

                    secondsWatched++;
                    if (secondsWatched > 1 && percentageWatched != 100) {
                        let watchedFinal = parseInt({{ empty($userEpisode) ? 0 : $userEpisode->watched }}) + ((parseInt(secondsWatched) - 1) * intervalSecond);
                        if (watchedFinal != 0) data.watched = watchedFinal;
                    } else {
                        data.watching = 0;
                    }

                    // ✅ Save to episode user tracking API
                    const url = '{{ url("/") }}/api/setuserepisode/' + {{ $episode->id }} + '?profile_id=' + getWithExpiry('profileToken');
                    const options = apiFetchOptions('POST', storedToken, csrfToken);
                    options.body  = JSON.stringify(data);
                    fetchDataWithRetry(url, options, 1).then(r => r.json());
                }
            }, (intervalSecond * 1000));

            // ✅ Back button → return to webseries watch page
            $('.vpl-back-refer').on('click', function() {
                clearInterval(episodePlayInterval);
                player.cleanMedia();
                window.location.href = decodeURI("{{ $referurl }}");
            });
        }
    });
});
</script>
@endsection