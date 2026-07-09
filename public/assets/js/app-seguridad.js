/* Seguridad en Tiempo Real — eventos del modelo de visión (TSN) × SIEDCO */
(function ($) {
    'use strict';

    const POLL_MS = 20000; // refresco cada 20 s
    let timer = null;
    let vistosIds = new Set();
    let primeraCarga = true;

    const NIVEL = {
        alta:   { icon: '🚨', label: 'Alerta alta',   cls: 'seg-alta' },
        riesgo: { icon: '🔥', label: 'Riesgo',        cls: 'seg-riesgo' },
        media:  { icon: '⚠️', label: 'Alerta media',  cls: 'seg-media' },
        baja:   { icon: '👁️', label: 'Baja',          cls: 'seg-baja' },
        normal: { icon: '✅', label: 'Normal',         cls: 'seg-normal' },
    };

    function temaActual() {
        return $('.tema-btn.active').data('tema') || 'aire';
    }

    function esc(s) {
        return $('<span>').text(s == null ? '' : String(s)).html();
    }

    function fmtFecha(s) {
        return s ? String(s).substr(0, 16).replace('T', ' ') : '';
    }

    function tendenciaTxt(t) {
        return { alza: '📈 al alza', baja: '📉 a la baja', estable: '➡️ estable', sin_datos: '' }[t] || '';
    }

    function render(eventos) {
        const $cont = $('#seg-eventos').empty();
        if (!eventos.length) {
            $cont.html('<p class="seg-vacio">Sin eventos de seguridad detectados todavía. ' +
                       'El modelo de visión enviará detecciones aquí en tiempo real.</p>');
            return;
        }
        eventos.forEach(function (ev) {
            const n = NIVEL[ev.nivel_alerta] || NIVEL.baja;
            const conf = ev.confianza != null ? Math.round(ev.confianza * 100) + '%' : '';
            const cruce = ev.cruce || {};
            let cruceHtml = '';
            if (cruce.modalidad) {
                const tend = tendenciaTxt(cruce.tendencia);
                cruceHtml = '<div class="seg-cruce">🔗 <strong>' + esc(cruce.modalidad) + '</strong> — ' +
                            esc(cruce.narrativa || '') + (tend ? ' <span class="seg-tend">' + tend + '</span>' : '') +
                            '</div>';
            }
            const $card = $(
                '<div class="seg-evento ' + n.cls + '">' +
                    '<div class="seg-evento-top">' +
                        '<span class="seg-badge">' + n.icon + ' ' + esc(n.label) + '</span>' +
                        '<span class="seg-tipo">' + esc(ev.tipo_comportamiento) + '</span>' +
                        (conf ? '<span class="seg-conf">' + conf + '</span>' : '') +
                    '</div>' +
                    '<div class="seg-meta">' +
                        (ev.municipio ? '📍 ' + esc(ev.municipio) + ' · ' : '') +
                        esc(fmtFecha(ev.captured_at)) +
                    '</div>' +
                    cruceHtml +
                '</div>'
            );
            $cont.append($card);
        });
    }

    function notificarNuevos(eventos) {
        if (primeraCarga) {
            eventos.forEach(function (ev) { if (ev.id != null) vistosIds.add(ev.id); });
            primeraCarga = false;
            return;
        }
        eventos.forEach(function (ev) {
            if (ev.id == null || vistosIds.has(ev.id)) return;
            vistosIds.add(ev.id);
            if ((ev.nivel_alerta === 'alta' || ev.nivel_alerta === 'riesgo') &&
                typeof window.showToast === 'function') {
                const n = NIVEL[ev.nivel_alerta] || NIVEL.baja;
                const donde = ev.municipio ? ' en ' + ev.municipio : '';
                const ctx = ev.cruce && ev.cruce.narrativa ? ' — ' + ev.cruce.narrativa : '';
                window.showToast(n.icon + ' ' + n.label + ': ' + ev.tipo_comportamiento + donde + ctx, 'seguridad');
            }
        });
    }

    function cargar() {
        $.getJSON('api/evento.php?limit=20')
            .done(function (res) {
                if (!res.ok) return;
                $('#seg-status').text(res.count + ' evento(s)');
                render(res.eventos);
                notificarNuevos(res.eventos);
            })
            .fail(function () { $('#seg-status').text('sin conexión'); });
    }

    function activar() {
        $('#seguridad-tiempo-real').show();
        cargar();
        if (timer) clearInterval(timer);
        timer = setInterval(cargar, POLL_MS);
    }

    function desactivar() {
        $('#seguridad-tiempo-real').hide();
        if (timer) { clearInterval(timer); timer = null; }
    }

    function sincronizar() {
        temaActual() === 'seguridad' ? activar() : desactivar();
    }

    $(function () {
        // Reaccionar a los cambios de tema (los botones ya existen desde app.js).
        $('.tema-btn').on('click', function () { setTimeout(sincronizar, 0); });
        sincronizar();
    });

})(jQuery);
