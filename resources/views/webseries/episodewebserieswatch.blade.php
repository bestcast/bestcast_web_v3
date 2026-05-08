@extends('layouts.frontend')

@section('header-script')
<div id="bigPlayerOuter" class="bigPlayerOuter"><div class="vpl-player-loader dnnshow"></div></div>
<style>.vpl-lightbox-wrap .vpl-lightbox-close{display:none !important;}</style>
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/vpl.css" />
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/aviva.css" />
<script src="{{ asset('js/auth/logout.js') }}?v=1" defer></script>
<script src="{{ asset('js/video-new.js') }}?v=1" defer></script>
<script>
    var webseries_id = {{ $webseries_id }};
</script>
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

// Video quality selection
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

{{-- Overlay HTML placed directly in body-level markup, outside any player wrapper --}}
@if($nextEpisode)
<div id="next-episode-overlay">
    <div class="next-ep-card" id="next-ep-btn">
        <div class="next-ep-icon">
            <svg viewBox="0 0 448 512"><path d="M384 44v424c0 6.6-5.4 12-12 12h-48c-6.6 0-12-5.4-12-12V291.6l-195.5 181C95.9 489.7 64 475.4 64 448V64c0-27.4 31.9-41.7 52.5-24.6L312 219.3V44c0-6.6 5.4-12 12-12h48c6.6 0 12 5.4 12 12z"/></svg>
            <svg class="next-ep-countdown" viewBox="0 0 50 50">
                <circle cx="25" cy="25" r="22"/>
                <circle class="nep-progress" cx="25" cy="25" r="22"/>
            </svg>
        </div>
        <div class="next-ep-text">
            <div class="next-ep-label">Up Next</div>
            <div class="next-ep-title">{{ $nextEpisode->title }}</div>
        </div>
        <button class="next-ep-close" id="next-ep-close" title="Dismiss">&#x00D7;</button>
    </div>
</div>
@endif

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

    var nextEpisodeId  = {{ $nextEpisode ? $nextEpisode->id : 0 }};
    var nextEpisodeUrl = "{{ $nextEpisode ? url('/webserieswatchepisode/' . $nextEpisode->id) . '?refer=' . url('/webserieswatch/' . $webseries_id) : '' }}";

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
        mediaEndAction: "ondemand",
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

            // ─── Next Episode Logic ───────────────────────────────────────
            var nextEpCountdownTimer = null;
            var nextEpShown          = false;
            var timeCheckInterval    = null;

            function showNextEpisodeOverlay() {
                if (!nextEpisodeId || !nextEpisodeUrl || nextEpShown) return;
                nextEpShown = true;

                var overlay = document.getElementById('next-episode-overlay');
                if (!overlay) {
                    console.warn('next-episode-overlay element not found in DOM');
                    return;
                }

                // Move overlay to <body> at show-time to escape any stacking context
                if (overlay.parentNode !== document.body) {
                    document.body.appendChild(overlay);
                }

                overlay.classList.add('visible');

                // Start countdown ring animation
                var progressCircle = overlay.querySelector('.nep-progress');
                if (progressCircle) {
                    // Force reflow so transition triggers from dashoffset=138
                    progressCircle.getBoundingClientRect();
                    progressCircle.style.strokeDashoffset = '0';
                }

                // Auto-navigate after 10s
                nextEpCountdownTimer = setTimeout(function() {
                    var ov = document.getElementById('next-episode-overlay');
                    if (ov && ov.classList.contains('visible')) {
                        goToNextEpisode();
                    }
                }, 10000);
            }

            function goToNextEpisode() {
                clearTimeout(nextEpCountdownTimer);
                clearInterval(episodePlayInterval);
                clearInterval(timeCheckInterval);
                player.cleanMedia();
                window.location.href = nextEpisodeUrl;
            }

            // Use setInterval instead of mediaTimeUpdate (more reliable across players)
            if (nextEpisodeId) {
                timeCheckInterval = setInterval(function() {
                    if (isPaused) return;
                    var current  = parseInt(player.getCurrentTime(), 10);
                    var duration = parseInt(player.getDuration(), 10);
                    if (duration > 0 && current > 0 && (duration - current) <= 10) {
                        showNextEpisodeOverlay();
                    }
                }, 1000);
            }

            player.addEventListener("mediaEnd", function() {
                clearInterval(episodePlayInterval);
                clearInterval(timeCheckInterval);
                if (nextEpisodeId) {
                    showNextEpisodeOverlay();
                    // Safety fallback if overlay was dismissed
                    if (!nextEpCountdownTimer) {
                        nextEpCountdownTimer = setTimeout(goToNextEpisode, 3000);
                    }
                }
            });

            // Card click → go now
            var nextBtn = document.getElementById('next-ep-btn');
            if (nextBtn) {
                nextBtn.addEventListener('click', function(e) {
                    if (e.target.closest('#next-ep-close')) return;
                    goToNextEpisode();
                });
            }

            // Dismiss (×) → cancel auto-navigate
            var closeBtn = document.getElementById('next-ep-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    clearTimeout(nextEpCountdownTimer);
                    nextEpCountdownTimer = null;
                    var overlay = document.getElementById('next-episode-overlay');
                    if (overlay) overlay.classList.remove('visible');
                });
            }
            // ─── End Next Episode Logic ───────────────────────────────────

            let secondsWatched    = 0;
            let percentageWatched = '{{ empty($userEpisode) ? 0 : $userEpisode->watched_percent }}';
            let intervalSecond    = 15;

            let episodePlayInterval = setInterval(function() {
                if (!isPaused) {
                    let getCurrentTime = parseInt(player.getCurrentTime(), 10);
                    let movieDuration  = parseInt(player.getDuration(), 10);

                    if (getCurrentTime && playbacktime) {
                        percentageWatched = parseInt(((getCurrentTime / '{{ empty($episode->duration) ? 7000 : $episode->duration }}') * 100), 10);
                    }
                    if (getCurrentTime == 0 && secondsWatched > 1) {
                        percentageWatched = 100;
                    }

                    let data = {
                        watch_time:      getCurrentTime,
                        watching:        1,
                        watched_percent: percentageWatched,
                        movieDuration:   movieDuration
                    };

                    secondsWatched++;
                    if (secondsWatched > 1 && percentageWatched != 100) {
                        let watchedFinal = parseInt({{ empty($userEpisode) ? 0 : $userEpisode->watched }}) + ((parseInt(secondsWatched) - 1) * intervalSecond);
                        if (watchedFinal != 0) data.watched = watchedFinal;
                    } else {
                        data.watching = 0;
                    }

                    const url = '{{ url("/") }}/api/setuserepisode/' + {{ $episode->id }} + '?profile_id=' + getWithExpiry('profileToken');
                    const options = apiFetchOptions('POST', storedToken, csrfToken);
                    options.body  = JSON.stringify(data);
                    fetchDataWithRetry(url, options, 1).then(r => r.json());
                }
            }, (intervalSecond * 1000));

            $('.vpl-back-refer').on('click', function() {
                clearInterval(episodePlayInterval);
                clearInterval(timeCheckInterval);
                player.cleanMedia();
                window.location.href = decodeURI("{{ $referurl }}");
            });
        }
    });
});
</script>
@endsection