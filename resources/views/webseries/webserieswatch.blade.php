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
{{-- Hide More Info button --}}
<style>
    .moreInfo { display: none !important; }
    .bannerLogo { display: none !important; }
</style>
@endsection

@section('content')
  <div class="ajxProfile"></div>
  <div class="ajxDtPopup"></div>
  <div class="previewMovie"></div>
  @include('webseries.playermini')
  @include('webseries.player')
  @include('webseries.genre')

  {{-- Banner --}}
  <div class="bannerWrapper">
    <div class="ajxBanner"></div>
  </div>

  {{-- Webseries Detail: seasons + episodes --}}
  <div class="container-fluid blkCtr">
    <div class="row">
      <div class="col-lg-12">

        {{-- Season tabs --}}
        <div class="ajxSeasonTabs" id="seasonTabs"></div>

        {{-- Episodes list for selected season --}}
        <div class="ajxEpisodeList" id="episodeList"></div>

      </div>
    </div>
  </div>

  <script>
document.addEventListener("DOMContentLoaded", function () {
    fetch('/api/webserieswatchdetail/' + webseries_id + '?profile_id=' + (localStorage.getItem('profileToken') || ''))
      .then(res => res.json())
      .then(json => {
        const data = json.data;
        if (!data || !data.seasons) return;

        const seasons = data.seasons;
        const tabsEl  = document.getElementById('seasonTabs');
        const listEl  = document.getElementById('episodeList');

        // Default to latest season or current season_id
        const defaultSeason = seasons.find(s => s.id == season_id) || seasons[seasons.length - 1];

        // Build season tabs
        let tabsHtml = '<div class="seasonTabList" style="display:flex;gap:16px;padding:16px 0;overflow-x:auto;">';
        seasons.forEach(season => {
            const active = season.id == defaultSeason.id ? 'style="color:#fff;border-bottom:2px solid #fff;font-weight:bold;"' : 'style="color:#aaa;"';
            tabsHtml += `<div class="seasonTab" data-season-id="${season.id}" ${active} 
                          style="cursor:pointer;padding:8px 16px;white-space:nowrap;font-size:15px;">
                          ${season.title}
                        </div>`;
        });
        tabsHtml += '</div>';
        tabsEl.innerHTML = tabsHtml;

        // Render episodes
        function renderEpisodes(seasonId) {
            const season = seasons.find(s => s.id == seasonId);
            if (!season) return;
            let html = '<div class="episodeList" style="padding:0 0 40px 0;">';
            season.episodes.forEach(ep => {
                const watchedPct = ep.episode_user ? ep.episode_user.watched_percent : 0;
                const img = base_url + (ep.thumbnail || ep.image || '');
                const date = ep.release_date ? ep.release_date.substring(0, 10) : '';
                html += `
                  <div class="episodeItem" data-id="${ep.id}" 
                       style="display:flex;align-items:center;gap:16px;padding:12px 0;border-bottom:1px solid #222;cursor:pointer;">
                    <div style="position:relative;width:180px;min-width:180px;height:100px;border-radius:6px;overflow:hidden;background:#111;">
                      <img src="${img}" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none'">
                      ${watchedPct > 0 
                        ? `<div style="position:absolute;bottom:0;left:0;right:0;height:4px;background:#555;">
                             <div style="width:${watchedPct}%;height:100%;background:#e50914;"></div>
                           </div>` 
                        : ''}
                    </div>
                    <div style="flex:1;">
                      <div style="font-size:15px;font-weight:600;color:#fff;margin-bottom:4px;">${ep.title}</div>
                      <div style="font-size:13px;color:#aaa;">${ep.duration} &bull; ${date}</div>
                    </div>
                  </div>`;
            });
            html += '</div>';
            listEl.innerHTML = html;
        }

        renderEpisodes(defaultSeason.id);

        // Tab click
        tabsEl.addEventListener('click', function(e) {
            const tab = e.target.closest('.seasonTab');
            if (!tab) return;
            document.querySelectorAll('.seasonTab').forEach(t => {
                t.style.color = '#aaa';
                t.style.borderBottom = 'none';
                t.style.fontWeight = 'normal';
            });
            tab.style.color = '#fff';
            tab.style.borderBottom = '2px solid #fff';
            tab.style.fontWeight = 'bold';
            renderEpisodes(tab.dataset.seasonId);
        });

        // Episode click → play
        listEl.addEventListener('click', function(e) {
            const ep = e.target.closest('.episodeItem');
            if (!ep) return;
            // correct URL pattern matching the route
            window.location.href = '/webserieswatchepisode/'+ ep.dataset.id;
        });
    });
});
</script>
@endsection