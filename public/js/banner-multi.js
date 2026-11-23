(function ($) {
  'use strict';

  $(function () {
    const $box = $('.ajxMultiBanner');
    if (!$box.length) return;

    const $cur   = $('.current-url');
    const home   = $cur.attr('data-domain') || $cur.attr('data-url') || window.location.origin;
    const token  = localStorage.getItem('tokenEncrypted');
    const csrf   = document.querySelector('meta[name="csrf-token"]');
    const profile = (typeof getWithExpiry === 'function') ? getWithExpiry('profileToken') : null;
    if (!token || !csrf || !profile) return;

    const q = new URLSearchParams({ list: 1, profile_id: profile });
    const page = $cur.attr('data-page');      if (page)  q.set('page_id', page);
    const genre = $cur.attr('data-genre');    if (genre) q.set('genre_id', genre);
    const lang = $cur.attr('data-language');  if (lang)  q.set('language_id', lang);

    fetch('/api/bannerlist?' + q.toString(), {
      method: 'GET',
      headers: {
        'Authorization': 'Bearer ' + token,
        'X-CSRF-TOKEN': csrf.getAttribute('content'),
        'Accept': 'application/json'
      }
    })
    .then(r => r.json())
    .then(res => {
      if (!Array.isArray(res.data) || !res.data.length) return;

      const slides = res.data.map(b => {
        const m   = b.movies || {};
        const img = b.image || b.thumbnail || m.image || '';
        const id  = m.id;
        const ws  = b.is_webseries ? 1 : 0;
        if (!img || !id) return '';
        return `
          <div class="mbSlide" style="background-image:url('${home}/${img}')">
            <div class="mbShade"></div>
            ${m.certificate ? `<div class="mbCert">${m.certificate}</div>` : ''}
            <div class="mbContent">
              ${b.logo ? `<img class="mbLogo" src="${home}/${b.logo}" alt="">` : `<h2>${$('<i>').text(b.title || '').html()}</h2>`}
              <div class="bannerAction">
                <div class="playBtn" data-id="${id}" data-webseries="${ws}">Play</div>
                <div class="moreInfo" data-id="${id}" data-webseries="${ws}">More Info</div>
              </div>
            </div>
          </div>`;
      }).join('');

      if (!slides) return;

      $box.html(slides).slick({
        fade: true, dots: true, arrows: false, infinite: true,
        autoplay: true, autoplaySpeed: 5000, speed: 800, pauseOnHover: true
      });
    })
    .catch(e => console.log('Banner error: ' + e));
    $box.on('click', '.playBtn', function () {
      window.location.href = home + '/watch/' + $(this).data('id');
    });
    $box.on('click', '.moreInfo', function (e) {
      const id = $(this).attr('data-id');
      const ws = $(this).attr('data-webseries') == '1';
      //console.log('moreInfo click', { id, ws, hasBC: !!window.__bc });
      if (ws) return;                              // webseries: handled elsewhere
      if (!window.__bc) { console.warn('window.__bc missing'); return; }
      window.__bc.detailsPopup(id);
      setTimeout(function () { window.dispatchEvent(new Event('resize')); }, 600);
    });
  });
})(jQuery);