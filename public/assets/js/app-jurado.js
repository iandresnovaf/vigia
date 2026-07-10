/* Modo jurado — guía "Evalúa VigIA en 5 minutos" que maneja el dashboard vía DOM */
(function ($) {
    'use strict';

    function clickTema(tema) {
        const $btn = $('.tema-btn[data-tema="' + tema + '"]');
        if ($btn.length) { $btn[0].click(); }
    }
    function setMunicipio(nombre) {
        const $sel = $('#municipio');
        const match = $sel.find('option').filter(function () {
            return $(this).text().trim().toUpperCase() === nombre.toUpperCase();
        }).first();
        if (match.length) { $sel.val(match.val()); } else { $sel.val(nombre); }
        $sel.trigger('change');
    }
    function aplicar() { $('#aplicar')[0] && $('#aplicar')[0].click(); }

    function preguntarChat(texto) {
        if (!$('#chat-panel').is(':visible')) { $('#btn-chat')[0].click(); }
        $('#chat-input').val(texto);
        setTimeout(function () { $('#chat-send')[0] && $('#chat-send')[0].click(); }, 200);
    }

    // Cada paso: texto + acción opcional que ejecuta el dashboard.
    const PASOS = [
        { t: 'Cargar Calidad del Aire en Pereira (dato oficial datos.gov.co).',
          a: function () { clickTema('aire'); setTimeout(function () { setMunicipio('Pereira'); setTimeout(aplicar, 150); }, 150); } },
        { t: 'Observa la línea de <strong>procedencia</strong> bajo la interpretación: dataset, ID del recurso, nº de registros y última fecha (trazabilidad).',
          a: null },
        { t: 'Revisa la <strong>predicción a 7 días</strong>: línea punteada + banda de confianza y métricas reales (R², MAE, RMSE, MAPE) por backtesting — analítica estadística, no generada por IA.',
          a: null },
        { t: 'Pregunta al asistente por el aire de Pereira (IA de apoyo con datos verificables).',
          a: function () { preguntarChat('¿Cómo está la calidad del aire en Pereira?'); } },
        { t: 'Cambia al tema <strong>Seguridad</strong> para ver el panel en tiempo real.',
          a: function () { clickTema('seguridad'); } },
        { t: 'Pregunta por la tendencia de hurtos en Bogotá (cruce con SIEDCO).',
          a: function () { preguntarChat('¿Cuál es la tendencia de hurtos en Bogotá?'); } },
        { t: 'En el panel <strong>Seguridad en Tiempo Real</strong>, revisa un evento del modelo de visión y su <strong>cruce con SIEDCO</strong> (modalidad de hurto + casos del municipio).',
          a: null },
        { t: 'Abre el <strong>Modelo de Visión</strong> (botón «Abrir modelo en vivo») para ver la detección de eventos de seguridad en video.',
          a: null },
    ];

    function render() {
        const $ol = $('#jurado-steps').empty();
        PASOS.forEach(function (p, i) {
            const $li = $('<li class="jurado-step">');
            $li.append($('<span class="jurado-step-txt">').html(p.t));
            if (p.a) {
                $('<button class="btn jurado-do">▶ Hacer</button>')
                    .on('click', function () { p.a(); })
                    .appendTo($li);
            }
            $ol.append($li);
        });
    }

    $(function () {
        render();
        $('#btn-jurado').on('click', function () { $('#jurado-modal').show(); });
        $('#jurado-close, #jurado-overlay').on('click', function () { $('#jurado-modal').hide(); });
        $(document).on('keydown', function (e) { if (e.key === 'Escape') $('#jurado-modal').hide(); });
    });

})(jQuery);
