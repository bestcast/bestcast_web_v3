document.addEventListener("DOMContentLoaded", function () {

    var styleTag = document.createElement('style');
    styleTag.innerHTML = `
        .continueWatchingBlk .section-title {
            margin-bottom: 16px;
        }
        .continueWatchingBlk .cwList {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }
        .continueWatchingBlk .cwCard {
            position: relative;
            width: 280px;
            flex-shrink: 0;
            border-radius: 6px;
            overflow: hidden;
        }
        .continueWatchingBlk .cwCard .thumb {
            width: 100%;
            height: 157px;
            background-size: cover;
            background-position: center;
            cursor: pointer;
            display: block;
        }
        .continueWatchingBlk .cwCard .percent.out {
            width: 100%;
            height: 3px;
            background: rgba(255,255,255,0.25);
            margin: 0;
        }
        .continueWatchingBlk .cwCard .percent.out .in {
            height: 100%;
            background: #e50914;
        }
        .continueWatchingBlk .cwCard .cwActions {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            background: linear-gradient(to top, rgba(0,0,0,0.85), transparent);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease;
            pointer-events: none;
        }
        .continueWatchingBlk .cwCard:hover .cwActions {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }
        .continueWatchingBlk .cwActions a {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            cursor: pointer;
            flex-shrink: 0;
        }
        .continueWatchingBlk .cwActions .cwPlayBtn { background: #fff; }
        .continueWatchingBlk .cwActions .cwMylistBtn,
        .continueWatchingBlk .cwActions .cwLikeBtn,
        .continueWatchingBlk .cwActions .cwMoreInfoBtn {
            border: 1.5px solid rgba(255,255,255,0.7);
            background: rgba(0,0,0,0.4);
        }
        .continueWatchingBlk .cwActions .cwMylistBtn[data-active="1"],
        .continueWatchingBlk .cwActions .cwLikeBtn[data-active="1"] {
            background: #e50914;
            border-color: #e50914;
        }
    `;
    document.head.appendChild(styleTag);

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

    var storedToken = localStorage.getItem('tokenEncrypted');
    var csrfToken = document.querySelector('meta[name="csrf-token"]')
        ? document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        : null;
    var profileId = getWithExpiry('profileToken');

    if (!storedToken || !csrfToken || !profileId) return;

    var apiUrl = base_url + 'api/userwebserieslist?watching=1&profile_id=' + profileId;
    var options = apiFetchOptions('GET', storedToken, csrfToken);

    fetch(apiUrl, options)
        .then(function (res) { return res.json(); })
        .then(function (json) {
            if (!json.data || !Array.isArray(json.data) || !json.data.length) return;

            var cardsHtml = '';
            json.data.forEach(function (item) {
                cardsHtml += buildCard(item);
            });

            var blockHtml =
                '<div class="listing continueWatchingBlk">' +
                    '<div class="section-title"><h4 class="title">Continue Watching</h4></div>' +
                    '<div class="ajxMovies">' +
                        '<div class="cwList">' +   // carousel-slide இல்லாம cwList
                            cardsHtml +
                        '</div>' +
                    '</div>' +
                '</div>';

            var container = document.querySelector('.continueWatchingWebseries');
            if (container) {
                container.innerHTML = blockHtml;

                /*if (window.jQuery && jQuery.fn.slick) {
                    jQuery('.continueWatchingBlk .carousel-slide').slick({
                        lazyLoad: 'ondemand',
                        dots: false,
                        infinite: false,
                        arrows: true,
                        speed: 1000,
                        slidesToShow: 6,
                        slidesToScroll: 6,
                        responsive: [
                            { breakpoint: 1580, settings: { slidesToShow: 5, slidesToScroll: 5 } },
                            { breakpoint: 1360, settings: { slidesToShow: 4, slidesToScroll: 4 } },
                            { breakpoint: 1050, settings: { slidesToShow: 3, slidesToScroll: 3 } },
                            { breakpoint: 700,  settings: { slidesToShow: 2, slidesToScroll: 2 } },
                            { breakpoint: 520,  settings: { slidesToShow: 1, slidesToScroll: 1 } }
                        ]
                    });
                }*/

                container.querySelectorAll('.thumb').forEach(function (el) {
                    el.addEventListener('click', function () {
                        var url = el.closest('.moviesThumb').getAttribute('data-url');
                        if (url) window.location.href = url;
                    });
                });

                container.querySelectorAll('.cwPlayBtn').forEach(function (btn) {
                    btn.addEventListener('click', function (e) {
                        e.stopPropagation();
                    });
                });

                container.querySelectorAll('.cwMylistBtn').forEach(function (btn) {
                    btn.addEventListener('click', function (e) {
                        e.stopPropagation();
                        var episodeId = btn.getAttribute('data-episodeid');
                        var isActive = btn.getAttribute('data-active') === '1';

                        fetch(base_url + 'api/setuserepisode/' + episodeId + '?profile_id=' + profileId, {
                            method: 'POST',
                            headers: {
                                'Authorization': 'Bearer ' + storedToken,
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ mylist: isActive ? 0 : 1 })
                        })
                        .then(function (r) { return r.json(); })
                        .then(function () {
                            btn.setAttribute('data-active', isActive ? '0' : '1');
                        });
                    });
                });

                container.querySelectorAll('.cwLikeBtn').forEach(function (btn) {
                    btn.addEventListener('click', function (e) {
                        e.stopPropagation();
                        var episodeId = btn.getAttribute('data-episodeid');
                        var isActive = btn.getAttribute('data-active') === '1';

                        fetch(base_url + 'api/setuserepisode/' + episodeId + '?profile_id=' + profileId, {
                            method: 'POST',
                            headers: {
                                'Authorization': 'Bearer ' + storedToken,
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ likes: isActive ? 0 : 1 })
                        })
                        .then(function (r) { return r.json(); })
                        .then(function () {
                            btn.setAttribute('data-active', isActive ? '0' : '1');
                        });
                    });
                });

                container.querySelectorAll('.cwMoreInfoBtn').forEach(function (btn) {
                    btn.addEventListener('click', function (e) {
                        e.stopPropagation();
                        var webseriesId = btn.getAttribute('data-webseriesid');
                        window.location.href = base_url + 'webserieswatch/' + webseriesId;
                    });
                });
            }
        })
        .catch(function (err) {
            console.log('Continue Watching (webseries) error:', err);
        });

    function buildCard(item) {
        var watchedPercent = item.watched_percent || 0;
        var thumbUrl = item.image ? base_url + item.image : '';
        var watchUrl = item.watch_url || '#';
        var isMylist = item.usermovies && item.usermovies.mylist ? 1 : 0;
        var isLiked = item.usermovies && item.usermovies.likes ? 1 : 0;

        var percentBar = watchedPercent
            ? '<div class="percent out"><div class="in" style="width:' + watchedPercent + '%"></div></div>'
            : '';

        return (
            '<div class="edu-event event-grid-1 bg-shade moviesThumb cwCard moviesThumb-' + item.episode_id + '" ' +
                'data-id="' + item.id + '" data-episodeid="' + item.episode_id + '" data-url="' + watchUrl + '">' +

                '<div class="thumb" style="background-image:url(\'' + thumbUrl + '\');"></div>' +
                percentBar +

                '<div class="cwActions">' +
                    '<a href="' + watchUrl + '" class="cwPlayBtn">' +
                        '<svg width="14" height="14" viewBox="0 0 24 24" fill="#000"><path d="M8 5v14l11-7z"/></svg>' +
                    '</a>' +
                    '<a href="javascript:void(0)" class="cwMylistBtn" data-episodeid="' + item.episode_id + '" data-active="' + isMylist + '">' +
                        '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>' +
                    '</a>' +
                    '<a href="javascript:void(0)" class="cwLikeBtn" data-episodeid="' + item.episode_id + '" data-active="' + isLiked + '">' +
                        '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M7 10v11M2 10h5v11H2zM22 10h-7l1-8h-4l-3 8v11h10z"/></svg>' +
                    '</a>' +
                    '<a href="javascript:void(0)" class="cwMoreInfoBtn" data-webseriesid="' + item.id + '">' +
                        '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>' +
                    '</a>' +
                '</div>' +
            '</div>'
        );
    }
});