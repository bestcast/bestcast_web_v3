(function () {
    var hoveredOriginIsWebseries = false;

    document.addEventListener('mouseover', function (e) {
        var card = e.target.closest('.thumbImg');
        if (!card) return;
        var flag = card.getAttribute('data-webseries') === '1';
        if (flag !== hoveredOriginIsWebseries) {
            console.log('[ws-patch] hover changed ->', flag, card.className);
        }
        hoveredOriginIsWebseries = flag;
    }, true);

    document.addEventListener('click', function (e) {
        var target = e.target.closest('.ppPlay, .playBtn, .moreInfo, .ppMore, .ICdown');
        if (!target) return;

        var id = target.getAttribute('data-id');
        console.log('[ws-patch] click on', target.className, 'id=', id, 'hoveredOriginIsWebseries=', hoveredOriginIsWebseries);

        if (!id) return;
        if (!hoveredOriginIsWebseries) return;

        e.preventDefault();
        e.stopImmediatePropagation();

        if (target.classList.contains('moreInfo') || target.classList.contains('ppMore') || target.classList.contains('ICdown')) {
            if (typeof window.openWebseriesInfoModal === 'function') {
                window.openWebseriesInfoModal(id);
            }
        } else {
            var refer = encodeURIComponent(window.location.href);
            window.location.href = window.location.origin + '/webserieswatch/' + id + '?refer=' + refer;
        }
    }, true);
})();