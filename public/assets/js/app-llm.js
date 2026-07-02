/* LLM — configuración, interpretación de datos y alertas en tiempo real */
(function ($) {
    'use strict';

    const POLL_MS = 60000; // alertas cada 60 s
    let alertTimer = null;
    let llmCfg = { provider: 'kimi', model: 'moonshot-v1-8k', has_key: false };

    const PROVIDERS = {
        kimi: {
            label:  'Kimi / Moonshot AI',
            models: ['moonshot-v1-8k', 'moonshot-v1-32k', 'moonshot-v1-128k', 'kimi-k2-0711', 'kimi-latest'],
        },
        openai: {
            label:  'OpenAI',
            models: ['gpt-4o-mini', 'gpt-4o', 'gpt-4-turbo'],
        },
        openrouter: {
            label:  'OpenRouter (multi-modelo)',
            models: [
                'google/gemini-2.0-flash-exp:free',
                'meta-llama/llama-3.3-70b-instruct:free',
                'deepseek/deepseek-chat-v3-0324:free',
                'anthropic/claude-3.5-sonnet',
                'openai/gpt-4o-mini',
            ],
            showUrl: true,
        },
        gemini: {
            label:  'Google Gemini',
            models: ['gemini-2.0-flash', 'gemini-2.5-flash-preview-05-20', 'gemini-1.5-flash', 'gemini-1.5-pro'],
        },
        claude: {
            label:  'Anthropic Claude',
            models: ['claude-haiku-4-5-20251001', 'claude-sonnet-4-6', 'claude-opus-4-8'],
        },
        custom: {
            label:  'Personalizado (compatible OpenAI)',
            models: [],
            showUrl: true,
        },
    };

    /* ── Cargar configuración desde DB ── */
    function loadConfig() {
        $.getJSON('api/config.php').done(function (res) {
            if (!res.ok) return;
            llmCfg = res.config;
            applyConfigToModal();
            applySensorConfigToModal(res.config);
            if (llmCfg.has_key) startAlertPolling();
        });
    }

    function applySensorConfigToModal(cfg) {
        if (cfg.sensor_url)    $('#cfg-sensor-url').val(cfg.sensor_url);
        if (cfg.socrata_token) $('#cfg-socrata-token').val(cfg.socrata_token);
        $('#sensor-url-status').text(cfg.sensor_url ? '✓ Configurada' : '');
    }

    /* ── Poblar modal con los valores guardados ── */
    function applyConfigToModal() {
        const p = llmCfg.provider || 'kimi';
        $('#llm-provider').val(p);
        updateModelOptions(p);
        if (llmCfg.model) {
            if ($('#llm-model option[value="' + llmCfg.model + '"]').length) {
                $('#llm-model').val(llmCfg.model);
            } else {
                $('#llm-model').val('__custom__');
                $('#llm-model-custom').val(llmCfg.model);
                $('#llm-model-custom-wrap').show();
            }
        }
        $('#llm-key-status').text(llmCfg.has_key ? '✓ Configurada (oculta)' : 'No configurada');
        $('#llm-url-wrap').toggle(!!(PROVIDERS[p] || {}).showUrl);
        if (llmCfg.api_url) $('#llm-url').val(llmCfg.api_url);
    }

    function updateModelOptions(provider) {
        const p    = PROVIDERS[provider] || PROVIDERS.kimi;
        const $sel = $('#llm-model');
        $sel.empty();
        p.models.forEach(function (m) { $sel.append($('<option>').val(m).text(m)); });
        $sel.append($('<option>').val('__custom__').text('Personalizado…'));
        $('#llm-model-custom-wrap').hide();
        $('#llm-url-wrap').toggle(!!p.showUrl);
    }

    /* ── Guardar configuración ── */
    function saveConfig() {
        const provider = $('#llm-provider').val();
        const rawModel = $('#llm-model').val();
        const model    = rawModel === '__custom__' ? $('#llm-model-custom').val().trim() : rawModel;
        const apiKey   = $('#llm-key').val().trim();
        const apiUrl   = $('#llm-url').val().trim();

        if (!model) { showToast('Indica el modelo a usar.', 'warn'); return; }

        const payload = { provider, model };
        if (apiKey) payload.api_key = apiKey;
        if (apiUrl) payload.api_url = apiUrl;

        $('#btn-llm-save').prop('disabled', true).text('Guardando…');
        $.ajax({ url: 'api/config.php', method: 'POST',
                 contentType: 'application/json', data: JSON.stringify(payload) })
         .done(function (res) {
            if (res.ok) {
                llmCfg.provider = provider; llmCfg.model = model;
                if (apiKey) llmCfg.has_key = true;
                $('#llm-modal').hide();
                showToast('✓ Configuración guardada', 'info');
                if (llmCfg.has_key) startAlertPolling();
            } else {
                showToast('Error al guardar: ' + (res.error || ''), 'warn');
            }
         })
         .always(function () { $('#btn-llm-save').prop('disabled', false).text('Guardar'); });
    }

    /* ── Interpretar datos (llamado desde app.js) ── */
    window.interpretarDatos = function (rows, tema) {
        if (!llmCfg.has_key) return;
        const $panel = $('#llm-interpretation');
        $panel.show().find('.llm-texto').html('<em>Analizando con IA…</em>');
        $.ajax({
            url: 'api/llm.php', method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ tipo: 'interpretar', tema: tema, datos: rows }),
        }).done(function (res) {
            $panel.find('.llm-texto').text(res.ok ? res.respuesta : '⚠️ ' + (res.error || 'Error'));
        }).fail(function () { $panel.hide(); });
    };

    /* ── Alertas en tiempo real del dron ── */
    function checkAlerts() {
        if (!llmCfg.has_key) return;
        $.getJSON('api/dron.php?tema=aire&limit=1').done(function (res) {
            if (!res.ok || !res.data || !res.data.length) return;
            const lectura = res.data[res.data.length - 1]; // más reciente
            $.ajax({
                url: 'api/llm.php', method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ tipo: 'alertar', tema: 'dron', datos: [lectura] }),
            }).done(function (r) {
                if (!r.ok || !r.alerta || !r.alerta.alerta) return;
                const icons = { incendio: '🔥', ruido: '🔊', seguridad: '🚨' };
                const icon  = icons[r.alerta.tipo] || '⚠️';
                showToast(icon + ' ALERTA: ' + r.alerta.mensaje, r.alerta.tipo);
            });
        });
    }

    function startAlertPolling() {
        if (alertTimer) clearInterval(alertTimer);
        checkAlerts();
        alertTimer = setInterval(checkAlerts, POLL_MS);
    }

    /* ── Toast de notificación ── */
    function showToast(msg, tipo) {
        const cls = (tipo === 'incendio' || tipo === 'seguridad') ? 'toast-danger'
                  : tipo === 'ruido' ? 'toast-warning' : 'toast-info';
        const $t = $('<div class="toast ' + cls + '">').text(msg);
        $('#toast-container').append($t);
        setTimeout(function () { $t.fadeOut(500, function () { $(this).remove(); }); }, 7000);
    }
    window.showToast = showToast;

    /* ── Guardar config sensores ── */
    function saveSensorConfig() {
        const sensorUrl    = $('#cfg-sensor-url').val().trim();
        const socrataToken = $('#cfg-socrata-token').val().trim();
        const payload      = {};
        if (sensorUrl    !== '') payload.sensor_url    = sensorUrl;
        if (socrataToken !== '') payload.socrata_token = socrataToken;
        if (!Object.keys(payload).length) { showToast('Ingresa al menos un valor.', 'warn'); return; }

        $('#btn-sensor-save').prop('disabled', true).text('Guardando…');
        $.ajax({ url: 'api/config.php', method: 'POST',
                 contentType: 'application/json', data: JSON.stringify(payload) })
         .done(function (res) {
            if (res.ok) {
                $('#sensor-url-status').text(sensorUrl ? '✓ Configurada' : '');
                $('#llm-modal').hide();
                showToast('✓ Configuración de sensores guardada', 'info');
            } else {
                showToast('Error: ' + (res.error || ''), 'warn');
            }
         })
         .always(function () { $('#btn-sensor-save').prop('disabled', false).text('Guardar'); });
    }

    /* ── Event handlers ── */
    $(function () {
        loadConfig();

        $('#btn-llm-config').on('click', function () {
            applyConfigToModal();
            $('#llm-modal').show();
        });
        $('#llm-modal-close, #llm-modal-overlay').on('click', function () { $('#llm-modal').hide(); });
        $(document).on('keydown', function (e) { if (e.key === 'Escape') $('#llm-modal').hide(); });

        /* Tabs del modal */
        $('.cfg-tab-btn').on('click', function () {
            const tab = $(this).data('tab');
            $('.cfg-tab-btn').removeClass('active');
            $(this).addClass('active');
            $('#cfg-tab-llm, #cfg-tab-sensores').hide();
            $('#cfg-tab-' + tab).show();
        });

        /* Guardar y probar sensores */
        $('#btn-sensor-save').on('click', saveSensorConfig);
        $('#btn-sensor-test').on('click', function () {
            const url = $('#cfg-sensor-url').val().trim();
            if (!url) { showToast('Ingresa la URL del sensor.', 'warn'); return; }
            const $btn = $(this).prop('disabled', true).text('Probando…');
            $.getJSON(url)
             .done(function (res) {
                if (res.ok && res.rows && res.rows.length) {
                    showToast('✓ Sensor responde — ' + res.rows.length + ' registros encontrados', 'info');
                } else {
                    showToast('⚠️ Respuesta inesperada: ' + JSON.stringify(res).slice(0, 80), 'warn');
                }
             })
             .fail(function () { showToast('✗ No se pudo conectar al sensor (CORS o URL inválida)', 'warn'); })
             .always(function () { $btn.prop('disabled', false).text('Probar sensor'); });
        });

        $('#llm-provider').on('change', function () { updateModelOptions($(this).val()); });

        $('#llm-model').on('change', function () {
            $('#llm-model-custom-wrap').toggle($(this).val() === '__custom__');
        });

        $('#btn-llm-save').on('click', saveConfig);

        $('#btn-llm-test').on('click', function () {
            const $btn = $(this).prop('disabled', true).text('Probando…');
            $.ajax({
                url: 'api/llm.php', method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ tipo: 'interpretar', tema: 'aire',
                                       datos: [{ prueba: 'conexión OK' }] }),
            }).done(function (res) {
                if (res.ok) showToast('✓ Conexión exitosa con el LLM', 'info');
                else        showToast('✗ ' + (res.error || 'Error desconocido'), 'warn');
            }).fail(function () {
                showToast('✗ Error de red', 'warn');
            }).always(function () { $btn.prop('disabled', false).text('Probar conexión'); });
        });
    });

})(jQuery);
