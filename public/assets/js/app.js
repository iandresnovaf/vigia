/* Dashboard de Entorno — lógica de UI con jQuery + Chart.js */
(function ($) {
    'use strict';

    const state = { tema: 'aire', vista: 'abiertos', src: 0 };
    let chart = null;
    const PALETA = ['#2e86de', '#e74c3c', '#27ae60', '#f39c12', '#8e44ad', '#16a085'];

    /* ---------- helpers ---------- */
    function api(url) {
        return $.getJSON(url).fail(() => estado('Error de red al llamar ' + url, true));
    }
    function estado(msg, err) {
        $('#estado').text(msg || '').css('color', err ? '#c0392b' : '#555');
    }
    function fmt(s) {
        return s ? String(s).substr(0, 16).replace('T', ' ') : '';
    }
    function num(v) { const n = parseFloat(v); return isNaN(n) ? null : n; }

    function destruirChart() { if (chart) { chart.destroy(); chart = null; } }

    function lineChart(labels, datasets, unidad) {
        destruirChart();
        chart = new Chart($('#grafico'), {
            type: 'line',
            data: { labels, datasets },
            options: {
                responsive: true, maintainAspectRatio: false, spanGaps: true,
                interaction: { mode: 'index', intersect: false },
                scales: { y: { title: { display: !!unidad, text: unidad } } },
                plugins: { legend: { position: 'top' } }
            }
        });
    }
    function barChart(labels, data, label, unidad) {
        destruirChart();
        chart = new Chart($('#grafico'), {
            type: 'bar',
            data: { labels, datasets: [{ label, data, backgroundColor: PALETA[0] }] },
            options: {
                responsive: true, maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, title: { display: !!unidad, text: unidad } } }
            }
        });
    }

    function tabla(rows, columnas) {
        if (!rows || !rows.length) { $('#tabla').html('<p class="vacio">Sin datos para mostrar.</p>'); return; }
        const cols = columnas || Object.keys(rows[0]);
        let h = '<table><thead><tr>' + cols.map(c => '<th>' + c + '</th>').join('') + '</tr></thead><tbody>';
        rows.slice(0, 300).forEach(r => {
            h += '<tr>' + cols.map(c => '<td>' + (r[c] == null ? '' : String(r[c])) + '</td>').join('') + '</tr>';
        });
        h += '</tbody></table>';
        if (rows.length > 300) h += '<p class="vacio">Mostrando 300 de ' + rows.length + ' filas.</p>';
        $('#tabla').html(h);
    }

    function mostrarNota(txt) {
        if (txt) { $('#nota').text(txt).prop('hidden', false); } else { $('#nota').prop('hidden', true); }
    }

    function filtros() {
        return {
            municipio: $('#municipio').val() || '',
            desde:     $('#desde').val() || '',
            hasta:     $('#hasta').val() || '',
        };
    }

    /* ---------- selector de fuente ---------- */
    function populateSources() {
        const info    = (window.DASHBOARD_SOURCES || {})[state.tema] || {};
        const sources = info.sources || [];
        const $f      = $('#fuente');
        const $lbl    = $('#lbl-fuente');
        $f.empty();
        sources.forEach(function (s, i) {
            $f.append($('<option>').val(i).text(s.label));
        });
        state.src = 0;
        $f.val(0);
        // Mostrar solo cuando hay 2+ fuentes para elegir.
        if (sources.length >= 2) { $lbl.show(); } else { $lbl.hide(); }
    }

    /* ---------- municipios ---------- */
    function cargarMunicipios() {
        $('#municipio').html('<option value="">— Todos —</option>');
        api('api/datos_abiertos.php?accion=municipios&tema=' + state.tema + '&src=' + state.src).done(function (res) {
            if (res.ok && res.municipios) {
                res.municipios.forEach(function (m) {
                    $('#municipio').append($('<option>').val(m).text(m));
                });
            }
        });
    }

    /* ---------- vista: datos abiertos ---------- */
    function cargarAbiertos() {
        const f = filtros();
        const q = $.param({ tema: state.tema, src: state.src, municipio: f.municipio, desde: f.desde, hasta: f.hasta, limit: 500 });
        estado('Cargando datos abiertos…');
        api('api/datos_abiertos.php?' + q).done(function (res) {
            if (!res.ok) { estado(res.error, true); return; }
            if (res.sin_api) { mostrarNota(res.nota); destruirChart(); tabla([]); estado('Sin API tabular para este tema'); return; }
            mostrarNota(res.nota || '');
            estado(res.count + ' registros · ' + (res.fuente || res.dataset_id));
            const rows = res.data;

            if (state.tema === 'aire') {
                const cats = {}, fechas = new Set();
                rows.forEach(function (r) {
                    const dia = (r.fecha || '').substr(0, 10); if (!dia) return;
                    const cat = r.categoria || 'PM';
                    const v = num(r.medicion);
                    // Filtra valores atípicos/erróneos de la fuente (la tabla queda cruda).
                    if (v === null || v < 0 || v > 1000) return;
                    fechas.add(dia); (cats[cat] = cats[cat] || {})[dia] = v;
                });
                const labels = [...fechas].sort();
                const ds = Object.keys(cats).map(function (c, i) {
                    return { label: c, data: labels.map(function (d) { return cats[c][d] ?? null; }), borderColor: PALETA[i % PALETA.length], tension: 0.2 };
                });
                lineChart(labels, ds, 'µg/m³');
                tabla(rows, ['fecha', 'municipio', 'estacion', 'categoria', 'medicion']);
            } else if (state.tema === 'seguridad') {
                const acc = {};
                rows.forEach(function (r) { const d = (r.fecha_hecho || '').substr(0, 10); if (d) acc[d] = (acc[d] || 0) + (parseInt(r.cantidad) || 0); });
                const labels = Object.keys(acc).sort();
                barChart(labels, labels.map(function (d) { return acc[d]; }), 'Casos por día', 'casos');
                tabla(rows, ['fecha_hecho', 'departamento', 'municipio', 'cantidad']);
            } else if (state.tema === 'incendios') {
                const acc = {};
                rows.forEach(function (r) {
                    const tipo = r.categoria || 'Sin clasificar';
                    const ha = num(r.medicion);
                    if (ha !== null) acc[tipo] = (acc[tipo] || 0) + ha;
                });
                const labels = Object.keys(acc).sort();
                barChart(labels, labels.map(function (t) { return +acc[t].toFixed(2); }), 'Área total (ha)', 'ha');
                tabla(rows, ['fecha', 'municipio', 'categoria', 'medicion']);
            } else if (state.tema === 'clima') {
                const meses = ['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];
                const etiquetas = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
                const params = {};
                rows.forEach(function (r) {
                    const p = r.par_metro || 'N/A';
                    if (!params[p]) params[p] = new Array(12).fill(null);
                    meses.forEach(function (m, i) { const v = num(r[m]); if (v !== null) params[p][i] = v; });
                });
                const ds = Object.keys(params).slice(0, 6).map(function (p, i) {
                    return { label: p, data: params[p], borderColor: PALETA[i % PALETA.length], tension: 0.2 };
                });
                lineChart(etiquetas, ds, '');
                tabla(rows, ['periodo', 'par_metro', 'municipio', 'estaci_n', 'anual']);
            } else {
                destruirChart(); tabla(rows);
            }

            if (typeof window.interpretarDatos === 'function') {
                window.interpretarDatos(rows.slice(0, 20), state.tema);
            }
        });
    }

    /* ---------- vista: dron ---------- */
    function cargarDron() {
        const f = filtros();
        const q = $.param({ tema: state.tema, municipio: f.municipio, limit: 500 });
        estado('Cargando datos del dron…');
        mostrarNota('');
        api('api/dron.php?' + q).done(function (res) {
            if (!res.ok) { estado(res.error, true); return; }
            estado(res.count + ' registros del dron');
            const rows = res.data.slice().reverse();

            if (state.tema === 'seguridad') {
                const acc = {};
                rows.forEach(function (r) { acc[r.tipo_comportamiento] = (acc[r.tipo_comportamiento] || 0) + 1; });
                const labels = Object.keys(acc);
                barChart(labels, labels.map(function (k) { return acc[k]; }), 'Eventos por tipo', 'eventos');
                tabla(res.data, ['captured_at', 'tipo_comportamiento', 'confianza', 'lat', 'lng', 'media_url']);
            } else if (state.tema === 'ruido') {
                const labels = rows.map(function (r) { return fmt(r.captured_at); });
                lineChart(labels, [{ label: 'Ruido (dB)', data: rows.map(function (r) { return num(r.ruido_db); }), borderColor: PALETA[3], tension: 0.2 }], 'dB');
                tabla(res.data, ['captured_at', 'municipio', 'ruido_db', 'bateria']);
            } else {
                const labels = rows.map(function (r) { return fmt(r.captured_at); });
                lineChart(labels, [
                    { label: 'PM2.5', data: rows.map(function (r) { return num(r.pm25); }), borderColor: PALETA[0], tension: 0.2 },
                    { label: 'PM10',  data: rows.map(function (r) { return num(r.pm10); }),  borderColor: PALETA[1], tension: 0.2 }
                ], 'µg/m³');
                tabla(res.data, ['captured_at', 'municipio', 'pm25', 'pm10', 'no2', 'o3', 'bateria']);
            }
        });
    }

    /* ---------- vista: comparación ---------- */
    function cargarComparar() {
        const f = filtros();
        const q = $.param({ tema: state.tema, src: state.src, municipio: f.municipio });
        estado('Comparando fuentes…');
        api('api/comparar.php?' + q).done(function (res) {
            if (!res.ok) { estado(res.error, true); return; }
            mostrarNota(res.nota || '');
            const dias = new Set();
            (res.abiertos || []).forEach(function (p) { dias.add(p.dia); });
            (res.dron || []).forEach(function (p) { dias.add(p.dia); });
            const labels = [...dias].sort();
            const mapA = {}; (res.abiertos || []).forEach(function (p) { mapA[p.dia] = p.valor; });
            const mapD = {}; (res.dron || []).forEach(function (p) { mapD[p.dia] = p.valor; });
            lineChart(labels, [
                { label: (res.fuente || 'Datos Abiertos') + ' (' + res.unidad + ')', data: labels.map(function (d) { return mapA[d] ?? null; }), borderColor: PALETA[0], tension: 0.2 },
                { label: 'Dron (' + res.unidad + ')', data: labels.map(function (d) { return mapD[d] ?? null; }), borderColor: PALETA[2], tension: 0.2 }
            ], res.unidad);
            const filas = labels.map(function (d) { return { dia: d, datos_abiertos: mapA[d] ?? '', dron: mapD[d] ?? '' }; });
            tabla(filas, ['dia', 'datos_abiertos', 'dron']);
            estado('Abiertos: ' + (res.abiertos || []).length + ' días · Dron: ' + (res.dron || []).length + ' días');
        });
    }

    function cargar() {
        if (state.vista === 'abiertos') cargarAbiertos();
        else if (state.vista === 'dron') cargarDron();
        else cargarComparar();
    }

    /* ---------- init ---------- */
    $(function () {
        const $primerBtn = $('.tema-btn').first();
        $primerBtn.addClass('active');
        $primerBtn.closest('.dropdown').find('.dropdown-toggle').addClass('active');

        $('.tema-btn').on('click', function () {
            $('.tema-btn').removeClass('active'); $(this).addClass('active');
            $('.dropdown-toggle').removeClass('active');
            $(this).closest('.dropdown').find('.dropdown-toggle').addClass('active');
            state.tema = $(this).data('tema');
            populateSources();
            cargarMunicipios();
            cargar();
        });

        $('.vista-btn').on('click', function () {
            $('.vista-btn').removeClass('active'); $(this).addClass('active');
            state.vista = $(this).data('vista');
            cargar();
        });

        $('#fuente').on('change', function () {
            state.src = parseInt($(this).val(), 10) || 0;
            cargarMunicipios();
            cargar();
        });

        $('#aplicar').on('click', cargar);

        populateSources();
        cargarMunicipios();
        cargar();
    });
})(jQuery);
