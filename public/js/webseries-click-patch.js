(function () {
    function isWebseries(el) {
        var own = el.getAttribute('data-webseries');          // banner buttons
        if (own !== null) return own === '1';
        var popup = el.closest('.previewMovie');              // hover popup
        if (popup) {
            var card = popup.querySelector('.thumbImg');      // cloned card carries the flag
            return !!card && card.getAttribute('data-webseries') === '1';
        }
        return false;
    }

    document.addEventListener('click', function (e) {
        var target = e.target.closest('.ppPlay, .playBtn, .moreInfo, .ppMore, .ICdown');
        if (!target) return;
        var id = target.getAttribute('data-id');
        if (!id || !isWebseries(target)) return;

        e.preventDefault();
        e.stopImmediatePropagation();

        if (target.matches('.moreInfo, .ppMore, .ICdown')) {
            if (typeof window.openWebseriesInfoModal === 'function') window.openWebseriesInfoModal(id);
        } else {
            window.location.href = window.location.origin + '/webserieswatch/' + id
                + '?refer=' + encodeURIComponent(window.location.href);
        }
    }, true);
})();