@extends('layouts.frontend')
@section('header-script')
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/vpl.css" />
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/aviva.css" />
<script src="{{ asset('js/auth/logout.js') }}?v=1" defer></script>
<script src="{{ asset('js/video-new.js') }}?v=1" defer></script>
<script>
    var webseries_id = {{ $webseries->id }};
    var episode_id   = {{ $episode->id ?? 0 }};
    var season_id    = {{ $season->id ?? 0 }};
    var base_url     = "{{ url('/') }}/";
</script>
<script src="{{ asset('js/webseries-new.js?1') }}?v=1" defer></script>
@endsection

@section('content')
<div class="ajxProfile"></div>
<div class="ajxDtPopup"></div>
<div class="previewMovie"></div>
@include('webseries.playermini')
@include('webseries.player')
@include('webseries.genre')

{{-- Banner --}}
<div class="bannerWrapper" style="position:relative; z-index:1;">
    <div class="ajxBanner"></div>
</div>

{{-- Main content below banner --}}
<div id="ws-main-content" style="background:#141414; padding:0; position:relative; z-index:2;">

    {{-- Page tabs: Episodes / Cast / Details --}}
    <div id="ws-page-tabs" style="border-bottom:2px solid rgba(255,255,255,0.1); padding:0 4%;">
        <button class="ws-page-tab active" data-page="episodes">Episodes</button>
        <button class="ws-page-tab" data-page="cast">Cast</button>
        <button class="ws-page-tab" data-page="details">Details</button>
    </div>

    {{-- Episodes tab --}}
    <div id="ws-page-episodes" class="ws-page-pane" style="padding:24px 4% 60px;">
        {{-- Season dropdown --}}
        <div id="ws-season-dropdown-wrap" style="position:relative; display:inline-block; margin-bottom:24px;">
            <button id="ws-season-dropdown-btn" style="
                display:flex; align-items:center; gap:10px;
                background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.2);
                color:#fff; padding:10px 18px; border-radius:6px;
                font-size:15px; font-weight:600; font-family:sans-serif;
                cursor:pointer; min-width:150px; justify-content:space-between;">
                <span id="ws-season-label">Season 1</span>
                <svg id="ws-season-arrow" viewBox="0 0 24 24" style="width:18px;height:18px;fill:currentColor;transition:transform 0.2s;flex-shrink:0;">
                    <path d="M7 10l5 5 5-5z"/>
                </svg>
            </button>
            <div id="ws-season-dropdown" style="
                display:none; position:absolute; top:calc(100% + 6px); left:0;
                background:#1e1e1e; border:1px solid rgba(255,255,255,0.15);
                border-radius:8px; min-width:160px; z-index:100;
                box-shadow:0 8px 32px rgba(0,0,0,0.6); overflow:hidden;">
            </div>
        </div>

        {{-- Episode list --}}
        <div id="episodeList" style="max-width:1000px;"></div>
    </div>

    {{-- Cast tab --}}
    <div id="ws-page-cast" class="ws-page-pane" style="padding:32px 4% 60px; display:none;">
        <div id="ws-cast-list" style="display:grid; grid-template-columns:repeat(auto-fill,minmax(140px,1fr)); gap:24px; max-width:1000px;">
            <div style="color:rgba(255,255,255,0.35); font-family:sans-serif; font-size:14px;">Loading cast…</div>
        </div>
    </div>

    {{-- Details tab --}}
    <div id="ws-page-details" class="ws-page-pane" style="padding:32px 4% 60px; display:none;">
        <div id="ws-details-content" style="max-width:700px; color:rgba(255,255,255,0.7); font-family:sans-serif; font-size:14px; line-height:1.7;">
            Loading details…
        </div>
    </div>
</div>

{{-- More Info Modal --}}
<div id="ws-info-overlay" style="display:none;">
    <div id="ws-info-modal">
        <button id="ws-info-close">&#x00D7;</button>
        <div id="ws-info-hero">
            <div id="ws-info-hero-img"></div>
            <div id="ws-info-hero-fade"></div>
            <div id="ws-info-hero-content">
                <div id="ws-info-title"></div>
                <div id="ws-info-meta"></div>
                <div id="ws-info-desc"></div>
                <div id="ws-info-actions">
                    <button id="ws-info-play-btn">
                        <svg viewBox="0 0 448 512"><path d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"/></svg>
                        <span id="ws-info-play-label">Play</span>
                    </button>
                </div>
                <div id="ws-info-genres"></div>
            </div>
        </div>
        <div id="ws-info-tabs">
            <button class="ws-tab active" data-tab="episodes">Episodes</button>
            <button class="ws-tab" data-tab="morelikethis">More Like This</button>
        </div>
        <div id="ws-tab-episodes" class="ws-tab-content active">
            <div id="ws-season-tabs"></div>
            <div id="ws-episode-list"><div class="ws-loading">Loading…</div></div>
        </div>
        <div id="ws-tab-morelikethis" class="ws-tab-content" style="display:none;">
            <div class="ws-loading" style="padding:60px 0;">More content coming soon.</div>
        </div>
    </div>
</div>

<style>
/* ── Page tabs ──────────────────────────────────────────── */
.ws-page-tab {
    background: none; border: none; color: rgba(255,255,255,0.5);
    font-size: 15px; font-weight: 600; font-family: sans-serif;
    padding: 16px 20px 14px; cursor: pointer;
    border-bottom: 3px solid transparent; margin-bottom: -2px;
    transition: color 0.2s, border-color 0.2s;
}
.ws-page-tab.active { color: #fff; border-bottom-color: #e50914; }
.ws-page-tab:hover:not(.active) { color: rgba(255,255,255,0.8); }

/* ── Episode rows (main page) ─────────────────────────── */
.ep-item {
    display: flex; align-items: flex-start; gap: 20px;
    padding: 20px 0; border-bottom: 1px solid rgba(255,255,255,0.08);
    cursor: pointer; transition: background 0.15s, padding-left 0.15s;
    border-radius: 4px;
}
.ep-item:hover { background: rgba(255,255,255,0.04); padding-left: 8px; }
.ep-item:last-child { border-bottom: none; }
.ep-num {
    flex-shrink: 0; width: 36px; text-align: center;
    font-size: 20px; font-weight: 700; color: rgba(255,255,255,0.2);
    font-family: sans-serif; padding-top: 6px;
}
.ep-thumb {
    position: relative; width: 200px; min-width: 200px; height: 112px;
    border-radius: 6px; overflow: hidden; background: #1a1a1a; flex-shrink: 0;
}
.ep-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
.ep-thumb-overlay {
    position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
    background: rgba(0,0,0,0); opacity: 0; transition: opacity 0.2s, background 0.2s;
}
.ep-item:hover .ep-thumb-overlay { opacity: 1; background: rgba(0,0,0,0.4); }
.ep-thumb-overlay svg { width: 36px; height: 36px; fill: #fff; }
.ep-prog { position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background: rgba(255,255,255,0.2); }
.ep-prog-fill { height: 100%; background: #e50914; }
.ep-info { flex: 1; min-width: 0; padding-top: 2px; }
.ep-title {
    font-size: 16px; font-weight: 700; color: #fff; font-family: sans-serif;
    margin-bottom: 5px;
}
.ep-sub {
    font-size: 13px; color: rgba(255,255,255,0.45); font-family: sans-serif;
    display: flex; align-items: center; gap: 6px; flex-wrap: wrap;
    margin-bottom: 8px;
}
.ep-sub-dot { color: rgba(255,255,255,0.2); }
.ep-desc {
    font-size: 13px; color: rgba(255,255,255,0.5); font-family: sans-serif;
    line-height: 1.6;
    display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
}

/* ── Cast grid ────────────────────────────────────────── */
.cast-card { text-align: center; }
.cast-photo {
    width: 100%; aspect-ratio: 1; border-radius: 50%;
    object-fit: cover; background: #1a1a1a; margin-bottom: 8px; display: block;
}
.cast-name { font-size: 13px; font-weight: 600; color: #fff; font-family: sans-serif; margin-bottom: 2px; }
.cast-role { font-size: 12px; color: rgba(255,255,255,0.4); font-family: sans-serif; }

/* ── More Info Modal ──────────────────────────────────── */
#ws-info-overlay {
    position: fixed; inset: 0; z-index: 9000;
    background: rgba(0,0,0,0.75); backdrop-filter: blur(4px);
    overflow-y: auto; display: flex; align-items: flex-start;
    justify-content: center; padding: 40px 16px 80px;
}
#ws-info-modal {
    position: relative; width: 100%; max-width: 860px;
    background: #181818; border-radius: 12px; overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.85);
    animation: wsIn 0.3s cubic-bezier(0.16,1,0.3,1);
}
@keyframes wsIn {
    from { opacity:0; transform:scale(0.94) translateY(20px); }
    to   { opacity:1; transform:scale(1) translateY(0); }
}
#ws-info-close {
    position: absolute; top: 14px; right: 14px; z-index: 20;
    width: 36px; height: 36px; border-radius: 50%;
    background: rgba(20,20,20,0.9); border: none; color: #fff;
    font-size: 20px; cursor: pointer; line-height: 36px; text-align: center;
}
#ws-info-hero { position: relative; height: 420px; overflow: hidden; background: #111; }
#ws-info-hero-img { position: absolute; inset: 0; background-size: cover; background-position: center top; }
#ws-info-hero-fade {
    position: absolute; inset: 0;
    background: linear-gradient(to bottom, rgba(24,24,24,0) 15%, rgba(24,24,24,0.65) 55%, #181818 100%);
}
#ws-info-hero-content { position: absolute; bottom: 0; left: 0; right: 0; padding: 0 32px 28px; }
#ws-info-title { font-size: 28px; font-weight: 800; color: #fff; font-family: sans-serif; margin-bottom: 8px; }
#ws-info-meta { display: flex; gap: 12px; font-size: 13px; color: rgba(255,255,255,0.6); font-family: sans-serif; margin-bottom: 10px; }
#ws-info-desc {
    font-size: 14px; color: rgba(255,255,255,0.82); font-family: sans-serif;
    line-height: 1.55; max-width: 520px; margin-bottom: 18px;
    display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
}
#ws-info-actions { margin-bottom: 12px; }
#ws-info-play-btn {
    display: inline-flex; align-items: center; gap: 8px;
    background: #fff; color: #000; border: none; border-radius: 5px;
    padding: 10px 24px; font-size: 15px; font-weight: 700; font-family: sans-serif;
    cursor: pointer; transition: opacity 0.15s;
}
#ws-info-play-btn:hover { opacity: 0.85; }
#ws-info-play-btn svg { width: 15px; height: 15px; fill: #000; }
#ws-info-genres { font-size: 12px; color: rgba(255,255,255,0.4); font-family: sans-serif; }
#ws-info-tabs { display: flex; border-bottom: 1px solid rgba(255,255,255,0.1); padding: 0 32px; }
.ws-tab {
    background: none; border: none; color: rgba(255,255,255,0.45);
    font-size: 15px; font-weight: 600; font-family: sans-serif;
    padding: 14px 20px 12px; cursor: pointer;
    border-bottom: 3px solid transparent; transition: color 0.2s, border-color 0.2s;
}
.ws-tab.active { color: #fff; border-bottom-color: #fff; }
.ws-tab-content { padding: 24px 32px 32px; }
#ws-season-tabs { display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap; }
.ws-season-btn {
    padding: 6px 18px; border-radius: 4px;
    border: 1px solid rgba(255,255,255,0.25);
    background: transparent; color: rgba(255,255,255,0.55);
    font-size: 14px; font-family: sans-serif; cursor: pointer; transition: all 0.2s;
}
.ws-season-btn.active { background: #fff; color: #000; border-color: #fff; font-weight: 700; }
.ws-ep-row {
    display: flex; gap: 16px; align-items: flex-start;
    padding: 12px 8px; border-bottom: 1px solid rgba(255,255,255,0.06);
    cursor: pointer; border-radius: 4px; transition: background 0.15s;
}
.ws-ep-row:hover { background: rgba(255,255,255,0.06); }
.ws-ep-num {
    width: 28px; min-width: 28px; font-size: 18px; font-weight: 700;
    color: rgba(255,255,255,0.25); font-family: sans-serif;
    text-align: center; padding-top: 20px; flex-shrink: 0;
}
.ws-ep-thumb {
    width: 130px; min-width: 130px; height: 73px;
    border-radius: 5px; overflow: hidden; background: #111; position: relative; flex-shrink: 0;
}
.ws-ep-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
.ws-ep-thumb-play {
    position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
    opacity: 0; background: rgba(0,0,0,0); transition: opacity 0.2s;
}
.ws-ep-row:hover .ws-ep-thumb-play { opacity: 1; background: rgba(0,0,0,0.45); }
.ws-ep-thumb-play svg { width: 26px; height: 26px; fill: #fff; }
.ws-ep-thumb-bar { position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background: rgba(255,255,255,0.2); }
.ws-ep-thumb-fill { height: 100%; background: #e50914; }
.ws-ep-info { flex: 1; min-width: 0; }
.ws-ep-title {
    font-size: 14px; font-weight: 600; color: #fff; font-family: sans-serif;
    margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.ws-ep-sub {
    font-size: 12px; color: rgba(255,255,255,0.45); font-family: sans-serif;
    display: flex; align-items: center; gap: 5px; flex-wrap: wrap; margin-bottom: 5px;
}
.ws-ep-desc {
    font-size: 12px; color: rgba(255,255,255,0.48); font-family: sans-serif; line-height: 1.5;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.ws-loading { text-align: center; padding: 48px; color: rgba(255,255,255,0.3); font-family: sans-serif; font-size: 14px; }

.cast-block{
    margin-bottom:15px;
}

.cast-role-title{
    font-weight:600;
    color:#fff;
    font-size:15px;
}

.cast-name-list{
    color:rgba(255,255,255,0.7);
    font-size:14px;
    margin-top:4px;
}
</style>

<script>
/* ═══════════════════════════════════════════════════════
   MAIN PAGE — Season dropdown + Episode list + Cast
   ═══════════════════════════════════════════════════════ */
document.addEventListener("DOMContentLoaded", function () {

    // ── Page tab switching ────────────────────────────────
    document.querySelectorAll('.ws-page-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.ws-page-tab').forEach(function(t){ t.classList.remove('active'); });
            document.querySelectorAll('.ws-page-pane').forEach(function(p){ p.style.display='none'; });
            this.classList.add('active');
            var pane = document.getElementById('ws-page-' + this.dataset.page);
            if (pane) pane.style.display = 'block';
        });
    });

    var token     = localStorage.getItem('tokenEncrypted') || '';
    var csrf      = document.querySelector('meta[name="csrf-token"]');
    var csrfVal   = csrf ? csrf.getAttribute('content') : '';
    var profileId = (typeof getWithExpiry === 'function') ? (getWithExpiry('profileToken') || '') : '';
    var hdrs      = { 'Authorization': 'Bearer ' + token, 'X-CSRF-TOKEN': csrfVal, 'Accept': 'application/json' };

    var allSeasons    = [];
    var currentSeason = null;

    // ── Fetch episode data ────────────────────────────────
    fetch('/api/webserieswatchdetail/' + webseries_id + '?profile_id=' + profileId, { headers: hdrs })
    .then(function(r){ return r.json(); })
    .then(function(json) {
        var data = json.data;
        if (!data || !data.seasons) return;
        allSeasons = data.seasons;

        // Pick default season
        currentSeason = allSeasons.find(function(s){ return s.id == season_id; })
                     || allSeasons[allSeasons.length - 1];

        buildDropdown(allSeasons, currentSeason);
        renderEpisodes(currentSeason);
    })
    .catch(function(err){ console.error('Episode load error:', err); });

    // ── Fetch webseries detail for cast + details ─────────
    fetch('/api/getwebseriesdetail/' + webseries_id + '?profile_id=' + profileId, {
        headers: Object.assign({}, hdrs, { 'Content-Type': 'application/json' })
    })
    .then(function(r){ return r.json(); })
    .then(function(json) {
        var d = json.data;
        if (!d) return;
        renderCast(d.casts || []);
        renderDetails(d);
    })
    .catch(function(err){ console.error('Detail load error:', err); });

    // ── Season dropdown ───────────────────────────────────
    var dropBtn   = document.getElementById('ws-season-dropdown-btn');
    var dropMenu  = document.getElementById('ws-season-dropdown');
    var dropLabel = document.getElementById('ws-season-label');
    var dropArrow = document.getElementById('ws-season-arrow');

    dropBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        var open = dropMenu.style.display !== 'none';
        dropMenu.style.display = open ? 'none' : 'block';
        dropArrow.style.transform = open ? '' : 'rotate(180deg)';
    });
    document.addEventListener('click', function() {
        dropMenu.style.display = 'none';
        dropArrow.style.transform = '';
    });

    function buildDropdown(seasons, activeSeason) {
        // Hide dropdown button if only 1 season
        if (seasons.length <= 1) {
            document.getElementById('ws-season-dropdown-wrap').style.display = 'none';
            return;
        }
        dropLabel.textContent = activeSeason.title || ('Season ' + activeSeason.id);
        var html = '';
        seasons.forEach(function(s, i) {
            var isActive = s.id == activeSeason.id;
            html += '<div class="ws-dd-item" data-idx="' + i + '" style="'
                  + 'padding:12px 18px; cursor:pointer; font-family:sans-serif; font-size:14px;'
                  + 'color:' + (isActive ? '#fff' : 'rgba(255,255,255,0.6)') + ';'
                  + 'background:' + (isActive ? 'rgba(255,255,255,0.08)' : 'transparent') + ';'
                  + 'transition:background 0.15s;'
                  + '" onmouseover="this.style.background=\'rgba(255,255,255,0.08)\'"'
                  + ' onmouseout="this.style.background=\'' + (isActive ? 'rgba(255,255,255,0.08)' : 'transparent') + '\'">'
                  + (s.title || ('Season ' + (i + 1)))
                  + '</div>';
        });
        dropMenu.innerHTML = html;

        dropMenu.addEventListener('click', function(e) {
            var item = e.target.closest('.ws-dd-item');
            if (!item) return;
            var idx = parseInt(item.dataset.idx);
            currentSeason = allSeasons[idx];
            dropLabel.textContent = currentSeason.title || ('Season ' + (idx + 1));
            dropMenu.style.display = 'none';
            dropArrow.style.transform = '';
            buildDropdown(allSeasons, currentSeason); // rebuild to update active highlight
            renderEpisodes(currentSeason);
        });
    }

    // ── Render episodes ───────────────────────────────────
    function renderEpisodes(season) {
        var listEl = document.getElementById('episodeList');
        if (!season || !season.episodes || !season.episodes.length) {
            listEl.innerHTML = '<p style="color:rgba(255,255,255,0.35);font-family:sans-serif;padding:20px 0;">No episodes.</p>';
            return;
        }

        // Derive season index for "S1 E1" label
        var sIdx = allSeasons.findIndex(function(s){ return s.id == season.id; });
        var sNum = (season.season_number) ? season.season_number : (sIdx + 1);

        var html = '';
        season.episodes.forEach(function(ep, idx) {
            var watchedPct = ep.episode_user ? (ep.episode_user.watched_percent || 0) : 0;
            var img        = ep.thumbnail ? (base_url + ep.thumbnail) : (ep.image ? (base_url + ep.image) : '');
            var date       = ep.release_date ? ep.release_date.substring(0, 10) : '';
            var dur        = ep.duration_text || '';
            var desc       = ep.content_plain || (ep.content ? ep.content.replace(/<[^>]+>/g,'') : '');
            if (desc.length > 300) desc = desc.substring(0, 300) + '…';
            var epLabel    = 'Episode ' + (idx + 1);

            html += '<div class="ep-item" data-ep-id="' + ep.id + '">'
                  + '<div class="ep-num">' + (idx + 1) + '.</div>'
                  + '<div class="ep-thumb">'
                  + (img ? '<img src="' + img + '" alt="" loading="lazy" onerror="this.style.display=\'none\'">' : '')
                  + '<div class="ep-thumb-overlay"><svg viewBox="0 0 448 512"><path d="M424 214.7L72 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"/></svg></div>'
                  + (watchedPct > 0 ? '<div class="ep-prog"><div class="ep-prog-fill" style="width:' + watchedPct + '%"></div></div>' : '')
                  + '</div>'
                  + '<div class="ep-info">'
                  + '<div class="ep-title">' + ep.title + '</div>'
                  + '<div class="ep-sub">'
                  + '<span>' + epLabel + '</span>'
                  + (dur  ? '<span class="ep-sub-dot">|</span><span>' + dur  + '</span>' : '')
                  + '</div>'
                  + (desc ? '<div class="ep-desc">' + desc + '</div>' : '')
                  + '</div>'
                  + '</div>';
        });
        listEl.innerHTML = html;

        listEl.onclick = function(e) {
            var item = e.target.closest('.ep-item');
            if (item && item.dataset.epId) {
                window.location.href = '/webserieswatchepisode/' + item.dataset.epId
                    + '?refer=' + encodeURIComponent(window.location.href);
            }
        };
    }

    // ── Render cast ───────────────────────────────────────
    /*function renderCast(casts) {
        var castEl = document.getElementById('ws-cast-list');
        if (!casts || !casts.length) {
            castEl.innerHTML = '<div style="color:rgba(255,255,255,0.35);font-family:sans-serif;font-size:14px;">No cast information available.</div>';
            return;
        }
        var html = '';
        casts.forEach(function(c) {
            var name  = c.cast ? c.cast.name : (c.name || '');
            var role  = c.group_name || c.group_name || '';
            var photo = c.cast && c.cast.image ? (base_url + c.cast.image) : '';
            html += '<div class="cast-name">' + name + '</div>'
                  + (role ? '<div class="cast-role">' + role + '</div>' : '')
                  + '</div>';
        });
        castEl.innerHTML = html;
    }*/
    function renderCast(casts) {
        var castEl = document.getElementById('ws-cast-list');

        if (!casts || !casts.length) {
            castEl.innerHTML =
                '<div style="color:rgba(255,255,255,0.35);font-family:sans-serif;font-size:14px;">No cast information available.</div>';
            return;
        }

        // Group by role
        var grouped = {};

        casts.forEach(function(c) {
            var name = c.cast ? c.cast.name : (c.name || '');
            var role = c.group_name || 'Others';

            if (!grouped[role]) {
                grouped[role] = [];
            }

            grouped[role].push(name);
        });

        // Render UI
        var html = '';

        Object.keys(grouped).forEach(function(role) {

            html += '<div class="cast-block">';

            html += '<div class="cast-role-title">' + role + '</div>';

            html += '<div class="cast-name-list">' +
                        grouped[role].join(', ') +
                    '</div>';

            html += '</div>';
        });

        castEl.innerHTML = html;
    }
    /*function renderCast(casts) {
        var castEl = document.getElementById('ws-cast-list');
        if (!casts || !casts.length) {
            castEl.innerHTML = '<div style="color:rgba(255,255,255,0.35);font-family:sans-serif;font-size:14px;">No cast information available.</div>';
            return;
        }

        // Group cast members by role
        var grouped = {};
        var roleOrder = ['Director', 'Producer', 'Actor', 'Actress', 'Music Director'];

        casts.forEach(function(c) {
            var name = c.cast ? c.cast.name : (c.name || '');
            var role = c.group_name || '';
            var photo = c.cast && c.cast.image ? (base_url + c.cast.image) : (c.photo ? (base_url + c.photo) : '');
            if (!name) return;
            if (!grouped[role]) grouped[role] = [];
            grouped[role].push({ name: name, photo: photo });
        });

        // Build HTML — one section per role
        var html = '';

        // Render in defined order first, then any remaining roles
        var renderedRoles = [];
        roleOrder.forEach(function(role) {
            if (grouped[role]) {
                html += renderRoleSection(role, grouped[role]);
                renderedRoles.push(role);
            }
        });
        // Any roles not in roleOrder
        Object.keys(grouped).forEach(function(role) {
            if (renderedRoles.indexOf(role) === -1) {
                html += renderRoleSection(role, grouped[role]);
            }
        });

        castEl.innerHTML = html;
    }

    function renderRoleSection(role, members) {
        var html = '<div style="margin-bottom:28px;">';
        // Role header
        if (role) {
            html += '<div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;'
                  + 'color:rgba(255,255,255,0.35);font-family:sans-serif;margin-bottom:12px;">'
                  + role + '</div>';
        }
        // Members in a flex row
        html += '<div style="display:flex;flex-wrap:wrap;gap:16px;">';
        members.forEach(function(m) {
            html += '<div style="display:flex;align-items:center;gap:10px;min-width:160px;">';
            // Avatar circle
            if (m.photo) {
                html += '<img src="' + m.photo + '" alt="' + m.name + '" '
                      + 'style="width:40px;height:40px;border-radius:50%;object-fit:cover;background:#2a2a2a;flex-shrink:0;" '
                      + 'onerror="this.style.display=\'none\'">';
            } else {
                html += '<div style="width:40px;height:40px;border-radius:50%;background:#2a2a2a;flex-shrink:0;'
                      + 'display:flex;align-items:center;justify-content:center;font-size:16px;color:rgba(255,255,255,0.3);">&#9654;</div>';
            }
            html += '<div style="font-size:14px;color:#fff;font-family:sans-serif;">' + m.name + '</div>';
            html += '</div>';
        });
        html += '</div></div>';
        return html;
    }*/
    // ── Render details ────────────────────────────────────
    function renderDetails(d) {
        var el = document.getElementById('ws-details-content');
        var rows = [];
        if (d.published_date) rows.push('<div style="margin-bottom:12px;"><span style="color:rgba(255,255,255,0.4);margin-right:8px;">Year</span>' + d.published_date + '</div>');
        if (d.certificate)    rows.push('<div style="margin-bottom:12px;"><span style="color:rgba(255,255,255,0.4);margin-right:8px;">Certificate</span>' + d.certificate + '</div>');
        if (d.tag_text)       rows.push('<div style="margin-bottom:12px;"><span style="color:rgba(255,255,255,0.4);margin-right:8px;">Genres</span>' + d.tag_text + '</div>');
        if (d.content)        rows.push('<div style="margin-top:16px;line-height:1.7;">' + d.content + '</div>');
        el.innerHTML = rows.length ? rows.join('') : 'No details available.';
    }
});

/* ═══════════════════════════════════════════════════════
   MORE INFO MODAL
   ═══════════════════════════════════════════════════════ */
(function() {
    'use strict';

    var overlay    = document.getElementById('ws-info-overlay');
    var closeBtn   = document.getElementById('ws-info-close');
    var heroImg    = document.getElementById('ws-info-hero-img');
    var titleEl    = document.getElementById('ws-info-title');
    var metaEl     = document.getElementById('ws-info-meta');
    var descEl     = document.getElementById('ws-info-desc');
    var genresEl   = document.getElementById('ws-info-genres');
    var playBtn    = document.getElementById('ws-info-play-btn');
    var playLabel  = document.getElementById('ws-info-play-label');
    var seasonTabs = document.getElementById('ws-season-tabs');
    var epList     = document.getElementById('ws-episode-list');
    var tabs       = document.querySelectorAll('.ws-tab');
    var tabContents= document.querySelectorAll('.ws-tab-content');

    function authHeaders() {
        var token = localStorage.getItem('tokenEncrypted');
        var csrf  = document.querySelector('meta[name="csrf-token"]');
        return {
            'Authorization': 'Bearer ' + (token || ''),
            'X-CSRF-TOKEN': csrf ? csrf.getAttribute('content') : '',
            'Accept': 'application/json', 'Content-Type': 'application/json'
        };
    }

    function openModal(wsId) {
        overlay.style.display = 'flex';
        document.documentElement.classList.add('noscroll');
        resetModal();
        loadDetail(wsId);
    }
    function closeModal() {
        overlay.style.display = 'none';
        document.documentElement.classList.remove('noscroll');
    }
    function resetModal() {
        heroImg.style.backgroundImage = '';
        titleEl.textContent = metaEl.innerHTML = descEl.textContent = genresEl.textContent = '';
        playLabel.textContent = 'Play'; playBtn.dataset.epId = '';
        seasonTabs.innerHTML = '';
        epList.innerHTML = '<div class="ws-loading">Loading episodes…</div>';
        tabs.forEach(function(t){ t.classList.remove('active'); });
        tabContents.forEach(function(c){ c.style.display='none'; c.classList.remove('active'); });
        tabs[0].classList.add('active');
        document.getElementById('ws-tab-episodes').style.display = 'block';
        document.getElementById('ws-tab-episodes').classList.add('active');
    }

    closeBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', function(e){ if(e.target===overlay) closeModal(); });

    tabs.forEach(function(tab) {
        tab.addEventListener('click', function() {
            tabs.forEach(function(t){ t.classList.remove('active'); });
            tabContents.forEach(function(c){ c.style.display='none'; c.classList.remove('active'); });
            this.classList.add('active');
            var el = document.getElementById('ws-tab-' + this.dataset.tab);
            if (el) { el.style.display='block'; el.classList.add('active'); }
        });
    });

    function loadDetail(id) {
        var pid = (typeof getWithExpiry === 'function') ? (getWithExpiry('profileToken') || '') : '';
        fetch('/api/getwebseriesdetail/' + id + '?profile_id=' + pid, { method:'GET', headers: authHeaders() })
        .then(function(r){ return r.json(); })
        .then(function(json) {
            if (!json.data) { epList.innerHTML = '<div class="ws-loading">Failed to load.</div>'; return; }
            var d = json.data;
            if (d.thumbnail) heroImg.style.backgroundImage = 'url(' + base_url + d.thumbnail + ')';
            titleEl.textContent  = d.title   || '';
            descEl.textContent   = d.content  || '';
            genresEl.textContent = d.tag_text || '';
            var metaParts = [];
            if (d.published_date) metaParts.push('<span>' + d.published_date + '</span>');
            if (d.certificate)    metaParts.push('<span style="border:1px solid rgba(255,255,255,0.4);padding:1px 6px;border-radius:3px;font-size:11px;">' + d.certificate + '</span>');
            metaEl.innerHTML = metaParts.join('');
            if (d.resume_episode_id) {
                playLabel.textContent = 'Episode ' + (d.episode_number || '');
                playBtn.dataset.epId  = d.resume_episode_id;
            } else if (d.first_episode_id) {
                playLabel.textContent = 'Play'; playBtn.dataset.epId = d.first_episode_id;
            }
            renderModalSeasons(d.seasons || []);
        })
        .catch(function(err){ epList.innerHTML = '<div class="ws-loading">Error.</div>'; console.error(err); });
    }

    playBtn.addEventListener('click', function() {
        var epId = this.dataset.epId;
        if (!epId) return;
        window.location.href = '/webserieswatchepisode/' + epId + '?refer=' + encodeURIComponent(window.location.href);
    });

    function renderModalSeasons(seasons) {
        if (!seasons.length) { epList.innerHTML = '<div class="ws-loading">No episodes.</div>'; return; }
        if (seasons.length > 1) {
            var html = '';
            seasons.forEach(function(s,i){ html += '<button class="ws-season-btn'+(i===0?' active':'')+'" data-idx="'+i+'">'+(s.title||'Season '+(i+1))+'</button>'; });
            seasonTabs.innerHTML = html;
            seasonTabs.onclick = function(e) {
                var btn = e.target.closest('.ws-season-btn'); if(!btn) return;
                document.querySelectorAll('.ws-season-btn').forEach(function(b){ b.classList.remove('active'); });
                btn.classList.add('active');
                renderModalEpisodes(seasons[parseInt(btn.dataset.idx)].episodes || [], parseInt(btn.dataset.idx) + 1);
            };
        } else { seasonTabs.innerHTML = ''; }
        renderModalEpisodes(seasons[0].episodes || [], 1);
    }

    function renderModalEpisodes(episodes, seasonNum) {
        if (!episodes.length) { epList.innerHTML = '<div class="ws-loading">No episodes.</div>'; return; }
        var html = '';
        episodes.forEach(function(ep, idx) {
            var pct    = ep.watched_percent || 0;
            var imgSrc = ep.thumbnail ? (base_url + ep.thumbnail) : '';
            var date   = ep.release_date ? ep.release_date.substring(0,10) : '';
            var dur    = ep.duration_text || '';
            var rawDesc = ep.content ? ep.content.replace(/<[^>]+>/g,'') : '';
            var desc   = rawDesc.length > 180 ? rawDesc.substring(0,180)+'…' : rawDesc;
            var epUrl  = '/webserieswatchepisode/' + ep.id + '?refer=' + encodeURIComponent(window.location.href);
            var epLabel = 'Episode ' + (idx + 1);

            html += '<div class="ws-ep-row" data-ep-url="'+epUrl+'">'
                  + '<div class="ws-ep-num">'+(idx+1)+'</div>'
                  + '<div class="ws-ep-thumb">'
                  + (imgSrc?'<img src="'+imgSrc+'" alt="" loading="lazy">':'')
                  + '<div class="ws-ep-thumb-play"><svg viewBox="0 0 448 512"><path d="M424 214.7L72 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"/></svg></div>'
                  + (pct>0?'<div class="ws-ep-thumb-bar"><div class="ws-ep-thumb-fill" style="width:'+pct+'%"></div></div>':'')
                  + '</div>'
                  + '<div class="ws-ep-info">'
                  + '<div class="ws-ep-title">'+ep.title+'</div>'
                  + '<div class="ws-ep-sub">'
                  + '<span>'+epLabel+'</span>'
                  + (dur?'<span style="color:rgba(255,255,255,0.2)">|</span><span>'+dur+'</span>':'')
                  + '</div>'
                  + (desc?'<div class="ws-ep-desc">'+desc+'</div>':'')
                  + '</div>'
                  + '</div>';
        });
        epList.innerHTML = html;
        epList.onclick = function(e) {
            var row = e.target.closest('.ws-ep-row');
            if (row && row.dataset.epUrl) window.location.href = row.dataset.epUrl;
        };
    }

    // ── Intercept clicks ──────────────────────────────────
    function attachHandlers() {
        document.querySelectorAll('.ppMore[data-id], .ICdown[data-id], .moreInfo[data-id]').forEach(function(el) {
            if (el.dataset.wsInfoBound) return;
            el.dataset.wsInfoBound = '1';
            el.addEventListener('click', function(e) {
                e.preventDefault(); e.stopImmediatePropagation();
                var wsId = this.getAttribute('data-id');
                if (wsId) openModal(wsId);
            }, true);
        });
        document.querySelectorAll('.bannerAction .playBtn').forEach(function(btn) {
            if (btn.dataset.resumeBound) return;
            btn.dataset.resumeBound = '1';
            btn.addEventListener('click', function(e) {
                e.preventDefault(); e.stopImmediatePropagation();
                var wsId = this.getAttribute('data-id');
                if (!wsId) return;
                var self = this;
                self.style.opacity='0.6'; self.style.pointerEvents='none';
                var pid = (typeof getWithExpiry==='function')?(getWithExpiry('profileToken')||''):'';
                fetch('/api/getwebseriesdetail/'+wsId+'?profile_id='+pid,{method:'GET',headers:authHeaders()})
                .then(function(r){return r.json();})
                .then(function(json){
                    var d=json.data||{};
                    var epId=d.resume_episode_id||d.first_episode_id||null;
                    if(epId) window.location.href='/webserieswatchepisode/'+epId+'?refer='+encodeURIComponent(window.location.href);
                    else window.location.href='/webserieswatch/'+wsId;
                }).catch(function(){window.location.href='/webserieswatch/'+wsId;});
            }, true);
        });
    }
    var mo = new MutationObserver(attachHandlers);
    mo.observe(document.body, {childList:true,subtree:true});
    attachHandlers();
    setTimeout(attachHandlers,800);
    setTimeout(attachHandlers,2000);
})();
</script>
@endsection