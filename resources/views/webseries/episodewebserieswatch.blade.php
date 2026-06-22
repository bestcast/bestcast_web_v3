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

    /* ── Next Episode Overlay ─────────────────────────────── */
    #next-episode-overlay {
        display: none;
        position: fixed;
        bottom: 100px;
        right: 24px;
        z-index: 2147483647;
    }
    #next-episode-overlay.visible {
        display: block;
        animation: nepSlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    @keyframes nepSlideIn {
        from { opacity: 0; transform: translateX(60px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    .next-ep-card {
        background: rgba(10,10,10,0.96);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 12px;
        padding: 14px 16px;
        display: flex; align-items: center; gap: 14px;
        cursor: pointer; min-width: 260px; max-width: 320px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.7);
        transition: border-color 0.2s, transform 0.2s;
    }
    .next-ep-card:hover { border-color: rgba(229,9,20,0.8); transform: translateY(-2px); }
    .next-ep-icon {
        width: 44px; height: 44px; background: #e50914; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; position: relative;
    }
    .next-ep-icon > svg:first-child {
        width: 16px; height: 16px; fill: #fff;
        margin-left: 3px; position: relative; z-index: 1;
    }
    .next-ep-countdown {
        position: absolute; top: -3px; left: -3px;
        width: calc(100% + 6px); height: calc(100% + 6px);
    }
    .next-ep-countdown circle { fill: none; stroke: rgba(255,255,255,0.25); stroke-width: 2.5; }
    .next-ep-countdown .nep-progress {
        stroke: #fff; stroke-linecap: round;
        stroke-dasharray: 138; stroke-dashoffset: 138;
        transform: rotate(-90deg); transform-origin: 25px 25px;
        transition: stroke-dashoffset 10s linear;
    }
    .next-ep-text { flex: 1; overflow: hidden; }
    .next-ep-label {
        font-size: 10px; text-transform: uppercase; letter-spacing: 1.2px;
        color: rgba(255,255,255,0.5); font-family: sans-serif; margin-bottom: 4px;
    }
    .next-ep-title {
        font-size: 13px; font-weight: 600; color: #fff; font-family: sans-serif;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .next-ep-close {
        background: none; border: none; color: rgba(255,255,255,0.4);
        cursor: pointer; padding: 2px 6px; font-size: 20px; line-height: 1;
        flex-shrink: 0; transition: color 0.2s;
    }
    .next-ep-close:hover { color: #fff; }

    /* ── Episodes Panel ───────────────────────────────────── */
    #ep-panel-overlay {
        display: none;
        position: fixed; inset: 0;
        z-index: 2147483646;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(4px);
    }
    #ep-panel-overlay.open { display: block; }
    #ep-panel {
        position: fixed; top: 0; right: 0; bottom: 0;
        width: 480px; max-width: 100vw;
        background: #0f0f0f;
        border-left: 1px solid rgba(255,255,255,0.08);
        z-index: 2147483647;
        display: flex; flex-direction: column;
        transform: translateX(100%);
        transition: transform 0.35s cubic-bezier(0.16,1,0.3,1);
        overflow: hidden;
    }
    #ep-panel-overlay.open #ep-panel { transform: translateX(0); }
    .ep-panel-head {
        padding: 20px 20px 12px;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        flex-shrink: 0; position: relative;
    }
    .ep-panel-head h3 {
        margin: 0 0 14px; font-size: 18px; font-weight: 700;
        color: #fff; font-family: sans-serif;
    }
    .ep-panel-close {
        position: absolute; top: 16px; right: 16px;
        background: rgba(255,255,255,0.08); border: none; color: #fff; cursor: pointer;
        width: 32px; height: 32px; border-radius: 50%;
        font-size: 18px; line-height: 32px; text-align: center; transition: background 0.2s;
    }
    .ep-panel-close:hover { background: rgba(255,255,255,0.18); }
    .ep-season-tabs {
        display: flex; gap: 6px; overflow-x: auto; padding: 0 0 2px;
        scrollbar-width: none;
    }
    .ep-season-tabs::-webkit-scrollbar { display: none; }
    .ep-season-tab {
        flex-shrink: 0; padding: 6px 16px; border-radius: 20px;
        background: transparent; border: 1px solid rgba(255,255,255,0.2);
        color: rgba(255,255,255,0.6); font-size: 13px; font-family: sans-serif;
        cursor: pointer; transition: background 0.2s, color 0.2s, border-color 0.2s;
        white-space: nowrap;
    }
    .ep-season-tab.active { background: #fff; color: #000; border-color: #fff; font-weight: 600; }
    .ep-season-tab:hover:not(.active) { border-color: rgba(255,255,255,0.5); color: #fff; }
    .ep-list-wrap {
        flex: 1; overflow-y: auto; padding: 8px 0;
        scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.15) transparent;
    }
    .ep-list-wrap::-webkit-scrollbar { width: 4px; }
    .ep-list-wrap::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 2px; }
    .ep-item {
        display: flex; gap: 14px; align-items: center;
        padding: 10px 20px; cursor: pointer;
        transition: background 0.15s; border-left: 3px solid transparent;
    }
    .ep-item:hover { background: rgba(255,255,255,0.05); }
    .ep-item.ep-current { background: rgba(229,9,20,0.08); border-left-color: #e50914; }
    .ep-thumb {
        width: 140px; min-width: 140px; height: 79px;
        border-radius: 6px; overflow: hidden; background: #1a1a1a;
        position: relative; flex-shrink: 0;
    }
    .ep-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .ep-thumb-play {
        position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
        background: rgba(0,0,0,0.35); opacity: 0; transition: opacity 0.2s;
    }
    .ep-item:hover .ep-thumb-play { opacity: 1; }
    .ep-item.ep-current .ep-thumb-play { opacity: 1; background: rgba(229,9,20,0.4); }
    .ep-thumb-play svg { width: 28px; height: 28px; fill: #fff; }
    .ep-progress-bar {
        position: absolute; bottom: 0; left: 0; right: 0;
        height: 3px; background: rgba(255,255,255,0.2);
    }
    .ep-progress-fill { height: 100%; background: #e50914; }
    .ep-meta { flex: 1; overflow: hidden; }
    .ep-num { font-size: 11px; color: rgba(255,255,255,0.4); font-family: sans-serif; margin-bottom: 3px; }
    .ep-title {
        font-size: 14px; font-weight: 600; color: #fff; font-family: sans-serif;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 4px;
    }
    .ep-duration { font-size: 12px; color: rgba(255,255,255,0.45); font-family: sans-serif; }
    .ep-loading {
        display: flex; align-items: center; justify-content: center;
        padding: 60px 20px; color: rgba(255,255,255,0.4); font-family: sans-serif; font-size: 14px;
    }

    /* ── Episodes button injected into player bar ─────────── */
    .vpl-episodes-btn {
        display: inline-flex; align-items: center; gap: 5px;
        background: none; border: none; color: rgba(255,255,255,0.8);
        cursor: pointer; padding: 0 8px; font-size: 11px;
        font-family: sans-serif; font-weight: 700; letter-spacing: 0.6px;
        text-transform: uppercase; transition: color 0.2s; vertical-align: middle;
    }
    .vpl-episodes-btn:hover { color: #fff; }
    .vpl-episodes-btn svg { width: 17px; height: 17px; fill: currentColor; flex-shrink: 0; }

    @media (max-width: 600px) {
        #ep-panel { width: 100vw; }
        .ep-thumb { width: 100px; min-width: 100px; height: 56px; }
    }
</style>

<?php
$referurl = empty($_GET['refer']) ? url('/webserieswatch/' . $webseries_id) : $_GET['refer'];
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

{{-- ── Next Episode Overlay ──────────────────────────────── --}}
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

{{-- ── Episodes Side Panel ─────────────────────────────────── --}}
<div id="ep-panel-overlay">
    <div id="ep-panel">
        <div class="ep-panel-head">
            <h3>Episodes</h3>
            <button class="ep-panel-close" id="ep-panel-close" title="Close">&#x00D7;</button>
            <div class="ep-season-tabs" id="ep-season-tabs"></div>
        </div>
        <div class="ep-list-wrap" id="ep-list">
            <div class="ep-loading">Loading episodes…</div>
        </div>
    </div>
</div>

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

    let playbacktime     = '{{ empty($userEpisode) ? 0 : $userEpisode->watch_time }}';
    var nextEpisodeId    = {{ $nextEpisode ? $nextEpisode->id : 0 }};
    var nextEpisodeUrl   = "{{ $nextEpisode ? url('/webserieswatchepisode/' . $nextEpisode->id) . '?refer=' . url('/webserieswatch/' . $webseries_id) : '' }}";
    var currentEpisodeId = {{ $episode->id }};
    var baseUrl          = "{{ url('/') }}";
    var webseriesId      = {{ $webseries_id }};

    var settings = {
        useShare: false, instanceName: "player1", playerRatio: "1.777777",
        activeItem: 0, volume: 0.7, autoPlay: true, preload: 'auto',
        skipPoster: true, showPosterOnPause: false, displayPosterOnMobile: false,
        mediaEndAction: "ondemand", seekTime: "10", useResumeScreen: false,
        playbackPositionTime: playbacktime, aspectRatio: 1, wrapperMaxWidth: "100%",
        randomPlay: false, rightClickContextMenu: "browser",
        useKeyboardNavigationForPlayback: true, playerType: 'lightbox',
        media: [{ type: 'hls', path: "{!! $video_url !!}" }]
    };

    fetch("{{ url('/') }}/vlite/skin/aviva.txt")
    .then(response => response.text())
    .then(content => {
        var wrapper = document.getElementById("wrapper");

        // Inject back-button title
        content = content.replace(
            '<div class="vpl-player-controls-bottom">',
            '<div class="vpl-back-refer ICineLeft">{{ $episode->title }}</div><div class="vpl-player-controls-bottom">'
        );

        // ✅ Inject "Episodes" button into the right-side controls bar
        content = content.replace(
            '<div class="vpl-player-controls-bottom-right">',
            '<div class="vpl-player-controls-bottom-right">'
            + '<button type="button" class="vpl-episodes-btn" id="vpl-ep-btn" title="Episodes">'
            + '<svg viewBox="0 0 24 24"><path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"/></svg>'
            + 'Episodes</button>'
        );

        wrapper.innerHTML = content;
        player = new vpl(wrapper, settings);

        if (player) {
            var isPaused = 0;
            player.addEventListener("mediaPause", function() { isPaused = 1; });
            player.addEventListener("mediaPlay",  function() { isPaused = 0; });
            player.setVolume(0.7);

            // ─── Declare episodePlayInterval early so goToNextEpisode can clearInterval it ───
            let secondsWatched    = 0;
            let percentageWatched = '{{ empty($userEpisode) ? 0 : $userEpisode->watched_percent }}';
            let intervalSecond    = 15;
            let episodePlayInterval;   // declared here, assigned below

            // ─── Next Episode Logic ───────────────────────────
            var nextEpCountdownTimer = null;
            var nextEpShown          = false;
            var timeCheckInterval    = null;

            function showNextEpisodeOverlay() {
                if (!nextEpisodeId || !nextEpisodeUrl || nextEpShown) return;
                nextEpShown = true;
                var overlay = document.getElementById('next-episode-overlay');
                if (!overlay) return;
                if (overlay.parentNode !== document.body) document.body.appendChild(overlay);
                overlay.classList.add('visible');
                var progressCircle = overlay.querySelector('.nep-progress');
                if (progressCircle) {
                    progressCircle.getBoundingClientRect();
                    progressCircle.style.strokeDashoffset = '0';
                }
                nextEpCountdownTimer = setTimeout(function() {
                    var ov = document.getElementById('next-episode-overlay');
                    if (ov && ov.classList.contains('visible')) goToNextEpisode();
                }, 10000);
            }

            function goToNextEpisode() {
                clearTimeout(nextEpCountdownTimer);
                clearInterval(episodePlayInterval);
                clearInterval(timeCheckInterval);
                player.cleanMedia();
                window.location.href = nextEpisodeUrl;
            }

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
                    if (!nextEpCountdownTimer) {
                        nextEpCountdownTimer = setTimeout(goToNextEpisode, 3000);
                    }
                }
            });

            var nextBtn = document.getElementById('next-ep-btn');
            if (nextBtn) {
                nextBtn.addEventListener('click', function(e) {
                    if (e.target.closest('#next-ep-close')) return;
                    goToNextEpisode();
                });
            }
            var closeNepBtn = document.getElementById('next-ep-close');
            if (closeNepBtn) {
                closeNepBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    clearTimeout(nextEpCountdownTimer);
                    nextEpCountdownTimer = null;
                    var overlay = document.getElementById('next-episode-overlay');
                    if (overlay) overlay.classList.remove('visible');
                });
            }
            // ─── End Next Episode Logic ───────────────────────

            // ─── Episodes Panel Logic ─────────────────────────
            var epPanelOverlay = document.getElementById('ep-panel-overlay');
            var epPanelClose   = document.getElementById('ep-panel-close');
            var epSeasonTabs   = document.getElementById('ep-season-tabs');
            var epList         = document.getElementById('ep-list');
            var epDataCache    = null;

            // Move panel to <body> to escape player stacking context
            document.body.appendChild(epPanelOverlay);

            function openEpPanel() {
                epPanelOverlay.classList.add('open');
                if (!epDataCache) fetchEpisodeData();
            }
            function closeEpPanel() {
                epPanelOverlay.classList.remove('open');
            }

            // Episodes button in player bar
            document.addEventListener('click', function(e) {
                if (e.target.closest('#vpl-ep-btn')) openEpPanel();
            });
            epPanelClose.addEventListener('click', closeEpPanel);
            epPanelOverlay.addEventListener('click', function(e) {
                if (e.target === epPanelOverlay) closeEpPanel();
            });

            function fetchEpisodeData() {
                epList.innerHTML = '<div class="ep-loading">Loading episodes…</div>';
                var token = localStorage.getItem('tokenEncrypted');
                var csrf  = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                var apiUrl = baseUrl + '/api/webserieswatchdetail/' + webseriesId
                           + '?profile_id=' + getWithExpiry('profileToken');
                var opts = apiFetchOptions('GET', token, csrf);

                fetchDataWithRetry(apiUrl, opts, 1)
                    .then(function(r) { return r.json(); })
                    .then(function(json) {
                        if (!json.data || !json.data.seasons) {
                            epList.innerHTML = '<div class="ep-loading">No episodes found.</div>';
                            return;
                        }
                        epDataCache = json.data.seasons;

                        // Build season tabs — highlight season containing current episode
                        var defaultIdx = 0;
                        epDataCache.forEach(function(season, i) {
                            if (season.episodes && season.episodes.some(function(ep) {
                                return ep.id == currentEpisodeId;
                            })) { defaultIdx = i; }
                        });

                        var tabsHtml = '';
                        epDataCache.forEach(function(season, i) {
                            tabsHtml += '<button class="ep-season-tab' + (i === defaultIdx ? ' active' : '') + '" '
                                      + 'data-season-idx="' + i + '">' + season.title + '</button>';
                        });
                        epSeasonTabs.innerHTML = tabsHtml;
                        renderEpisodes(defaultIdx);
                    })
                    .catch(function(err) {
                        epList.innerHTML = '<div class="ep-loading">Failed to load episodes.</div>';
                        console.error('Episodes fetch error:', err);
                    });
            }

            function setActiveTab(idx) {
                document.querySelectorAll('.ep-season-tab').forEach(function(t) {
                    t.classList.toggle('active', parseInt(t.dataset.seasonIdx) === idx);
                });
            }

            function renderEpisodes(seasonIdx) {
                var season = epDataCache[seasonIdx];
                if (!season || !season.episodes) {
                    epList.innerHTML = '<div class="ep-loading">No episodes.</div>';
                    return;
                }
                var html = '';
                season.episodes.forEach(function(ep, idx) {
                    var isCurrent  = ep.id == currentEpisodeId;
                    var watchedPct = ep.episode_user ? (ep.episode_user.watched_percent || 0) : 0;
                    var imgSrc     = ep.thumbnail ? baseUrl + '/' + ep.thumbnail
                                   : (ep.image   ? baseUrl + '/' + ep.image : '');
                    var dur        = ep.duration_text || ep.duration || '';
                    var epUrl      = baseUrl + '/webserieswatchepisode/' + ep.id
                                   + '?refer=' + baseUrl + '/webserieswatch/' + webseriesId;

                    html += '<div class="ep-item' + (isCurrent ? ' ep-current' : '') + '" '
                          + 'data-ep-url="' + epUrl + '" data-ep-id="' + ep.id + '">'

                          // thumbnail
                          + '<div class="ep-thumb">'
                          + (imgSrc ? '<img src="' + imgSrc + '" alt="" loading="lazy">' : '')
                          + '<div class="ep-thumb-play">'
                          + '<svg viewBox="0 0 373 373"><path d="M62 3C65 1 68 0 71 0c4 0 7 1 10 3l230 167c6 3 10 10 10 17s-4 13-10 17L81 370c-6 3-13 3-19 0-6-3-10-10-10-17V19C52 12 56 6 62 3z"/></svg>'
                          + '</div>'
                          + (watchedPct > 0
                              ? '<div class="ep-progress-bar"><div class="ep-progress-fill" style="width:' + watchedPct + '%"></div></div>'
                              : '')
                          + '</div>'  // .ep-thumb

                          // meta
                          + '<div class="ep-meta">'
                          + '<div class="ep-num">E' + (idx + 1) + '</div>'
                          + '<div class="ep-title">' + ep.title + '</div>'
                          + (dur ? '<div class="ep-duration">' + dur + '</div>' : '')
                          + '</div>'  // .ep-meta

                          + '</div>'; // .ep-item
                });
                epList.innerHTML = html;

                // Scroll current into view
                var currentEl = epList.querySelector('.ep-current');
                if (currentEl) {
                    setTimeout(function() {
                        currentEl.scrollIntoView({ block: 'center', behavior: 'smooth' });
                    }, 120);
                }
            }

            // Season tab click
            epSeasonTabs.addEventListener('click', function(e) {
                var tab = e.target.closest('.ep-season-tab');
                if (!tab) return;
                var idx = parseInt(tab.dataset.seasonIdx);
                setActiveTab(idx);
                renderEpisodes(idx);
            });

            // Episode item click → navigate
            epList.addEventListener('click', function(e) {
                var item = e.target.closest('.ep-item');
                if (!item) return;
                if (item.dataset.epId == currentEpisodeId) { closeEpPanel(); return; }
                clearInterval(episodePlayInterval);
                clearInterval(timeCheckInterval);
                player.cleanMedia();
                window.location.href = item.dataset.epUrl;
            });
            // ─── End Episodes Panel Logic ─────────────────────

            // ─── Watch progress tracking interval ────────────
            episodePlayInterval = setInterval(function() {
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