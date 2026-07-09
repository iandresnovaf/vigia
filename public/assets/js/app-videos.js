/* Modelo de Visión — reproductor de ejemplos "robbery" + redirect al modelo entrenado */
(function ($) {
    'use strict';

    const videos = Array.isArray(window.VIGIA_VIDEOS) ? window.VIGIA_VIDEOS : [];
    const modelUrl = window.VIGIA_MODEL_URL || '';

    function temaActual() {
        return $('.tema-btn.active').data('tema') || 'aire';
    }

    function seleccionar(idx) {
        const v = videos[idx];
        if (!v) return;
        $('#mv-video').attr('src', v.url)[0].load();
        $('#mv-caption').text('▶ ' + v.nombre);
        $('#mv-list li').removeClass('activo').eq(idx).addClass('activo');
    }

    function render() {
        // Redirect al modelo entrenado (Streamlit).
        if (modelUrl) $('#mv-modelo-link').attr('href', modelUrl);

        const $list = $('#mv-list').empty();

        if (!videos.length) {
            $('.mv-body').hide();
            $('#mv-empty').show().html(
                'Aún no hay videos de ejemplo. Coloca tus clips <strong>.mp4</strong> etiquetados como ' +
                '«robbery» en la carpeta <code>public/assets/videos/robbery/</code> y aparecerán aquí ' +
                'automáticamente. Mientras tanto, puedes abrir el <strong>modelo en vivo</strong> con el botón superior.'
            );
            return;
        }

        $('.mv-body').show();
        $('#mv-empty').hide();
        videos.forEach(function (v, i) {
            $('<li>')
                .text('🎬 ' + v.nombre)
                .on('click', function () { seleccionar(i); })
                .appendTo($list);
        });
        seleccionar(0);
    }

    function sincronizar() {
        if (temaActual() === 'seguridad') {
            $('#modelo-vision').show();
        } else {
            $('#modelo-vision').hide();
            const vid = $('#mv-video')[0];
            if (vid) vid.pause();
        }
    }

    $(function () {
        render();
        $('.tema-btn').on('click', function () { setTimeout(sincronizar, 0); });
        sincronizar();
    });

})(jQuery);
