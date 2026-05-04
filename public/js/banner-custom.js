document.addEventListener("DOMContentLoaded", function () {

    const container = document.querySelector('.ajxBanner');

    if (!container) {
        console.warn("Banner container not found");
        return;
    }

    const token = localStorage.getItem('token');
    const profileId = localStorage.getItem('profileToken');

    if (!token || !profileId) {
        console.warn("Missing token/profile");
        return;
    }

    const API_URL = `/api/bannerlist?profile_id=${profileId}`;

    fetch(API_URL, {
        method: "GET",
        headers: {
            "Authorization": "Bearer " + token,
            "Content-Type": "application/json"
        }
    })
    .then(res => res.json())
    .then(response => {

        const banners = response.data || [];

        if (!banners.length) {
            container.innerHTML = "<p>No banners found</p>";
            return;
        }

        let html = `<div class="banner-wrapper">`;

        banners.forEach((banner, index) => {

            // ✅ SAFE IMAGE
            let image =
                banner.image ||
                banner.thumbnail ||
                banner.movies?.image ||
                '';

            image = image.replace(/^\/+/, '');
            const fullImage = window.location.origin + '/' + image;

            const trailer = banner.movies?.trailer;

            html += `
                <div class="banner-slide">
                    <div class="video-box" data-index="${index}" style="background-image:url('${fullImage}')">
                        ${banner.logo ? `<img class="banner-logo" src="${window.location.origin}/${banner.logo}" />` : ''}
                        <h2>${banner.movies?.title || ''}</h2>
                        <button class="playBtn" data-id="${banner.movies?.id}">Play</button>
                    </div>
                </div>
            `;
        });

        html += `</div>`;

        container.innerHTML = html;

        // 🎬 INIT VIDEOS
        setTimeout(() => {

            document.querySelectorAll('.video-box').forEach((el, index) => {

                const trailer = banners[index]?.movies?.trailer;
                if (!trailer) return;

                let type = checkVideoFormat(trailer);
                let path = trailer;

                if (type === 'video') {
                    path = [{
                        label: "Default",
                        mp4: trailer,
                        active: true
                    }];
                }

                new vpl(el, {
                    autoPlay: true,
                    loopingOn: false,
                    preload: "auto",
                    showPosterOnPause: true,
                    media: [{
                        type: type,
                        path: path
                    }]
                });

            });

        }, 300);

    })
    .catch(err => {
        console.error("Banner error:", err);
    });

});