@extends('layouts.frontend')

@section('header-script')
<!-- <link rel="stylesheet" href="{{ asset('css/video.css') }}"> -->
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/vpl.css" />
<link rel="stylesheet" type="text/css" href="{{ url('/') }}/vlite/aviva.css" />
<script src="{{ asset('js/auth/logout.js') }}?v=1" defer></script>
<script src="{{ asset('js/video-new.js') }}?v=1" defer></script>
<script>
    var webseries_id = {{ $webseries->id }};
    var base_url     = "{{ url('/') }}/";
</script>
<script src="{{ asset('js/webseries-new.js?1') }}?v=1" defer></script>
{{-- Hide More Info button --}}
<style>
    /*.moreInfo { display: none !important; }
    .bannerLogo { display: none !important; }*/
</style>
<!-- <script src="{{ asset('js/banner-custom.js') }}"></script> -->
@endsection

@section('content') 

  <div class="ajxProfile"></div>
  <div class="ajxDtPopup"></div>
  <div class="previewMovie"></div>
  @include('webseries.playermini')
  @include('webseries.player')

  @include('webseries.genre')
  <div class="bannerWrapper">
    <div class="ajxBanner"></div>
  </div>

   <div class="container-fluid blkCtr">
      <div class="row">
         <div class="col-lg-12">
                <div class="ajxBlocks"></div>
                <div class="loadingMore"></div>
         </div>
      </div>
   </div>
    <div id="ws-info-overlay" style="display:none;">
        <div id="ws-info-modal">
            <button id="ws-info-close">&#x00D7;</button>

            {{-- Hero banner --}}
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

            {{-- Tabs --}}
            <div id="ws-info-tabs">
                <button class="ws-tab active" data-tab="episodes">Episodes</button>
                <button class="ws-tab" data-tab="morelikethis">More Like This</button>
            </div>

            {{-- Tab content: Episodes --}}
            <div id="ws-tab-episodes" class="ws-tab-content active">
                <div id="ws-season-tabs"></div>
                <div id="ws-episode-list">
                    <div class="ws-loading">Loading…</div>
                </div>
            </div>

            {{-- Tab content: More Like This (placeholder) --}}
            <div id="ws-tab-morelikethis" class="ws-tab-content" style="display:none;">
                <div class="ws-loading" style="padding:60px 0;">More content coming soon.</div>
            </div>
        </div>
    </div>
<script>
(function() {
    'use strict';

    var overlay    = document.getElementById('ws-info-overlay');
    var modal      = document.getElementById('ws-info-modal');
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

    var currentData      = null;
    var currentWebseries = null;

    // ── Helper: auth headers ──────────────────────────────────────
    function authHeaders() {
        var token = localStorage.getItem('tokenEncrypted');
        var csrf  = document.querySelector('meta[name="csrf-token"]');
        return {
            'Authorization': 'Bearer ' + (token || ''),
            'X-CSRF-TOKEN': csrf ? csrf.getAttribute('content') : '',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        };
    }

    // ── Open modal ────────────────────────────────────────────────
    function openModal(webseriesId) {
        currentWebseries = webseriesId;
        overlay.style.display = 'flex';
        document.documentElement.classList.add('noscroll');
        resetModal();
        loadWebseriesDetail(webseriesId);
    }

    function resetModal() {
        heroImg.style.backgroundImage = '';
        titleEl.textContent = '';
        metaEl.innerHTML    = '';
        descEl.textContent  = '';
        genresEl.textContent= '';
        playLabel.textContent = 'Play';
        playBtn.dataset.epId = '';
        playBtn.classList.remove('loading');
        seasonTabs.innerHTML = '';
        epList.innerHTML = '<div class="ws-loading">Loading episodes…</div>';
        // Reset to episodes tab
        tabs.forEach(function(t){ t.classList.remove('active'); });
        tabContents.forEach(function(c){ c.style.display='none'; c.classList.remove('active'); });
        tabs[0].classList.add('active');
        document.getElementById('ws-tab-episodes').style.display = 'block';
        document.getElementById('ws-tab-episodes').classList.add('active');
    }

    function closeModal() {
        overlay.style.display = 'none';
        document.documentElement.classList.remove('noscroll');
        currentData = null; currentWebseries = null;
    }

    closeBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) closeModal();
    });

    // ── Tab switching ─────────────────────────────────────────────
    tabs.forEach(function(tab) {
        tab.addEventListener('click', function() {
            var target = this.dataset.tab;
            tabs.forEach(function(t){ t.classList.remove('active'); });
            tabContents.forEach(function(c){ c.style.display='none'; c.classList.remove('active'); });
            this.classList.add('active');
            var el = document.getElementById('ws-tab-' + target);
            if (el) { el.style.display='block'; el.classList.add('active'); }
        });
    });

    // ── Load webseries detail ─────────────────────────────────────
    function loadWebseriesDetail(id) {
        var profileId = (typeof getWithExpiry === 'function') ? (getWithExpiry('profileToken') || '') : '';
        fetch('/api/getwebseriesdetail/' + id + '?profile_id=' + profileId, {
            method: 'GET', headers: authHeaders()
        })
        .then(function(r){ return r.json(); })
        .then(function(json) {
            if (!json.data) {
                epList.innerHTML = '<div class="ws-loading">Failed to load.</div>';
                return;
            }
            currentData = json.data;
            renderHero(currentData);
            renderSeasons(currentData.seasons || []);
        })
        .catch(function(err) {
            epList.innerHTML = '<div class="ws-loading">Error loading data.</div>';
            console.error('ws-info error:', err);
        });
    }

    // ── Render hero section ───────────────────────────────────────
    function renderHero(data) {
        // Background image
        if (data.thumbnail) {
            heroImg.style.backgroundImage = 'url(' + data.thumbnail + ')';
        }

        titleEl.textContent = data.title || '';
        descEl.textContent  = data.content || '';
        genresEl.textContent= data.genres || '';

        // Meta: year · certificate
        var metaParts = [];
        if (data.published_date) metaParts.push('<span>' + data.published_date + '</span>');
        if (data.certificate)    metaParts.push('<span style="border:1px solid rgba(255,255,255,0.4);padding:1px 6px;border-radius:3px;font-size:11px;">' + data.certificate + '</span>');
        metaEl.innerHTML = metaParts.join('');

        // Play / Resume button
        if (data.resume_episode_id) {
            //playLabel.textContent    = 'Resume' + (data.resume_date ? '  ' + data.resume_date : '');
            playLabel.textContent    = 'Episode' + (data.episode_number ? '  ' + data.episode_number : '');

            playBtn.dataset.epId     = data.resume_episode_id;
        } else if (data.first_episode_id) {
            playLabel.textContent    = 'Play';
            playBtn.dataset.epId     = data.first_episode_id;
        } else {
            playLabel.textContent    = 'Play';
            playBtn.dataset.epId     = '';
        }
    }

    // ── Play button click ─────────────────────────────────────────
    playBtn.addEventListener('click', function() {
        var epId = this.dataset.epId;
        if (!epId) return;
        this.classList.add('loading');
        var refer = encodeURIComponent(window.location.href);
        window.location.href = '/webserieswatchepisode/' + epId + '?refer=' + refer;
    });

    // ── Render seasons + episodes ─────────────────────────────────
    function renderSeasons(seasons) {
        if (!seasons || !seasons.length) {
            epList.innerHTML = '<div class="ws-loading">No episodes found.</div>';
            return;
        }

        // Build season buttons
        var tabsHtml = '';
        seasons.forEach(function(season, i) {
            tabsHtml += '<button class="ws-season-btn' + (i===0?' active':'') + '" data-idx="' + i + '">'
                      + (season.title || ('Season ' + (i+1))) + '</button>';
        });
        seasonTabs.innerHTML = tabsHtml;

        seasonTabs.addEventListener('click', function(e) {
            var btn = e.target.closest('.ws-season-btn');
            if (!btn) return;
            document.querySelectorAll('.ws-season-btn').forEach(function(b){ b.classList.remove('active'); });
            btn.classList.add('active');
            renderEpisodes(seasons[parseInt(btn.dataset.idx)].episodes || []);
        });

        // Show first season episodes
        renderEpisodes(seasons[0].episodes || []);
    }

    function renderEpisodes(episodes) {
        if (!episodes.length) {
            epList.innerHTML = '<div class="ws-loading">No episodes in this season.</div>';
            return;
        }
        var html = '';
        episodes.forEach(function(ep, idx) {
            /*var pct    = ep.watched_percent || 0;
            var imgSrc = ep.thumbnail || '';
            var desc   = ep.content   || '';
            var date   = ep.release_date ? ep.release_date.substring(0,10) : '';
            var epUrl  = '/webserieswatchepisode/' + ep.id
                       + '?refer=' + encodeURIComponent(window.location.href);*/

            var pct      = ep.watched_percent || 0;
            var imgSrc   = ep.thumbnail ? (base_url + ep.thumbnail) : '';
            var date     = ep.release_date ? ep.release_date.substring(0, 10) : '';
            var dur      = ep.duration_text || '';
            var rawDesc  = ep.content ? ep.content.replace(/<[^>]+>/g, '') : '';
            var desc     = rawDesc.length > 180 ? rawDesc.substring(0, 180) + '…' : rawDesc;
            var epUrl    = '/webserieswatchepisode/' + ep.id + '?refer=' + encodeURIComponent(window.location.href);
            var epLabel  = 'E' + (idx + 1);

            html += '<div class="ws-ep-row" data-ep-url="' + epUrl + '">'
                  + '<div class="ws-ep-num">' + (idx + 1) + '</div>'
                  + '<div class="ws-ep-thumb">'
                  + (imgSrc ? '<img src="' + imgSrc + '" alt="" loading="lazy">' : '')
                  + '<div class="ws-ep-thumb-play"><svg viewBox="0 0 448 512"><path d="M424 214.7L72 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"/></svg></div>'
                  + (pct > 0 ? '<div class="ws-ep-thumb-bar"><div class="ws-ep-thumb-fill" style="width:' + pct + '%"></div></div>' : '')
                  + '</div>'
                  + '<div class="ws-ep-info">'
                  // Title
                  + '<div class="ws-ep-title">' + ep.title + '</div>'
                  // S1E1 · date · duration row
                  + '<div style="font-size:12px;color:rgba(255,255,255,0.45);margin:4px 0 6px;display:flex;align-items:center;gap:5px;flex-wrap:wrap;">'
                  + '<span>' + epLabel + '</span>'
                  + (date ? '<span style="color:rgba(255,255,255,0.2)">•</span><span>' + date + '</span>' : '')
                  + (dur  ? '<span style="color:rgba(255,255,255,0.2)">•</span><span>' + dur  + '</span>' : '')
                  + '</div>'
                  // Description
                  + (desc ? '<div class="ws-ep-desc">' + desc + '</div>' : '')
                  + '</div>'
                  + '</div>';
        });
        epList.innerHTML = html;

        // Episode row click
        epList.addEventListener('click', function(e) {
            var row = e.target.closest('.ws-ep-row');
            if (row && row.dataset.epUrl) {
                window.location.href = row.dataset.epUrl;
            }
        });
    }

    // ── Intercept .ppMore clicks (webseries_id in data-id) ────────
    // Uses MutationObserver since blocks load asynchronously
    function attachMoreInfoHandlers() {
        document.querySelectorAll('.ppMore, .ICdown, .moreInfo').forEach(function(el) {
            if (el.dataset.wsInfoBound) return;
            el.dataset.wsInfoBound = '1';
            el.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                var wsId = this.getAttribute('data-id');
                if (wsId) openModal(wsId);
            }, true);
        });

        // Also intercept the banner Play button for resume logic
        document.querySelectorAll('.bannerAction .playBtn').forEach(function(btn) {
            if (btn.dataset.resumeBound) return;
            btn.dataset.resumeBound = '1';
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                var wsId = this.getAttribute('data-id');
                if (!wsId) return;
                handlePlayClick(wsId, this);
            }, true);
        });
    }

    // ── Banner play: resolve episode then redirect ─────────────────
    function handlePlayClick(webseriesId, btnEl) {
        if (btnEl) { btnEl.style.opacity='0.6'; btnEl.style.pointerEvents='none'; }
        var profileId = (typeof getWithExpiry === 'function') ? (getWithExpiry('profileToken') || '') : '';
        fetch('/api/webseriesdetail/' + webseriesId + '?profile_id=' + profileId, {
            method: 'GET', headers: authHeaders()
        })
        .then(function(r){ return r.json(); })
        .then(function(json) {
            var epId = (json.data && json.data.resume_episode_id)
                     ? json.data.resume_episode_id
                     : (json.data && json.data.first_episode_id ? json.data.first_episode_id : null);
            if (epId) {
                var refer = encodeURIComponent(window.location.href);
                window.location.href = '/webserieswatchepisode/' + epId + '?refer=' + refer;
            } else {
                window.location.href = '/webserieswatch/' + webseriesId;
            }
        })
        .catch(function() {
            window.location.href = '/webserieswatch/' + webseriesId;
        });
    }

    // Poll + MutationObserver for async-loaded content
    var observer = new MutationObserver(attachMoreInfoHandlers);
    observer.observe(document.body, { childList: true, subtree: true });
    attachMoreInfoHandlers();
    setTimeout(attachMoreInfoHandlers, 800);
    setTimeout(attachMoreInfoHandlers, 2000);

})();
</script>
@endsection