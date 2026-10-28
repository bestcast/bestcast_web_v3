(function () {
    function isWebseries(el) {
        var own = el.getAttribute('data-webseries');
        if (own !== null) return own === '1';

        // hover popup: look for the flag on the popup's own icons or cloned card
        var popup = el.closest('.previewMovie') || el.closest('.modal');
        if (popup) {
            var flagged = popup.querySelector('[data-webseries="1"]');
            if (flagged) return true;
            var card = popup.querySelector('.thumbImg');
            return !!card && card.getAttribute('data-webseries') === '1';
        }
        return false;
    }

    function getId(el) {
        return el.getAttribute('data-id')
            || (el.closest('[data-id]') && el.closest('[data-id]').getAttribute('data-id'));
    }

    document.addEventListener('click', function (e) {
        var target = e.target.closest(
            '.ppPlay, .playBtn, .moreInfo, .ppMore, .ICdown, #ppImg, .ppImg, .vpl-player'
        );
        if (!target) return;

        // ignore clicks on the volume control inside the preview
        if (e.target.closest('.video-sound')) return;

        var id = getId(target);
        if (!id || !isWebseries(target)) return;

        e.preventDefault();
        e.stopImmediatePropagation();

        if (target.matches('.moreInfo, .ppMore, .ICdown')) {
            if (typeof window.openWebseriesInfoModal === 'function') {
                window.openWebseriesInfoModal(id);
            }
        } else {
            window.location.href = window.location.origin + '/webserieswatch/' + id
                + '?refer=' + encodeURIComponent(window.location.origin + '/browse');
        }
    }, true);
})();