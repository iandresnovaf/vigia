<?php require_once __DIR__ . '/../src/Config.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VigIA — Vigilancia Ambiental y Seguridad con IA · Colombia</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
    window.DASHBOARD_SOURCES = <?= json_encode(
        array_map(fn($ds) => [
            'label'   => $ds['label'],
            'sources' => array_map(fn($s) => ['id' => $s['id'], 'label' => $s['label']], $ds['sources'] ?? []),
        ], Config::DATASETS),
        JSON_UNESCAPED_UNICODE
    ) ?>;
    </script>
</head>
<body>
<header class="topbar">
    <div class="brand">
        <div class="brand-wordmark"><span class="brand-vig">Vig</span><span class="brand-ia">IA</span></div>
        <div class="brand-tagline">Inteligencia artificial que vigila, protege y cuida tu entorno</div>
    </div>
    <nav class="temas">
        <?php foreach (Config::GRUPOS as $grupo): ?>
        <div class="dropdown">
            <button class="dropdown-toggle"><?= htmlspecialchars($grupo['label']) ?> ▾</button>
            <div class="dropdown-content">
                <?php foreach ($grupo['temas'] as $tKey): ?>
                <button class="tema-btn" data-tema="<?= htmlspecialchars($tKey) ?>">
                    <?= htmlspecialchars(Config::DATASETS[$tKey]['label']) ?>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </nav>
    <button id="btn-llm-config" class="btn-llm-icon" title="Asistente IA — configurar LLM">⚙️ IA</button>
</header>

<!-- Toast container -->
<div id="toast-container"></div>

<!-- Modal configuración LLM -->
<div id="llm-modal" style="display:none">
    <div id="llm-modal-overlay"></div>
    <div class="llm-modal-box">
        <div class="llm-modal-header">
            <span>⚙️ Configuración VigIA</span>
            <button id="llm-modal-close">✕</button>
        </div>
        <div class="cfg-tabs">
            <button class="cfg-tab-btn active" data-tab="llm">🤖 Asistente IA</button>
            <button class="cfg-tab-btn" data-tab="sensores">🛰️ Sensores & Datos</button>
        </div>
        <!-- Tab: Asistente IA -->
        <div id="cfg-tab-llm">
            <div class="llm-modal-body">
                <label class="llm-label">Proveedor LLM
                    <select id="llm-provider">
                        <option value="kimi">Kimi / Moonshot AI</option>
                        <option value="openai">OpenAI</option>
                        <option value="openrouter">OpenRouter (multi-modelo)</option>
                        <option value="gemini">Google Gemini</option>
                        <option value="claude">Anthropic Claude</option>
                        <option value="custom">Personalizado (OpenAI-compatible)</option>
                    </select>
                </label>
                <label class="llm-label">Modelo
                    <select id="llm-model"></select>
                </label>
                <div id="llm-model-custom-wrap" style="display:none">
                    <label class="llm-label">ID del modelo personalizado
                        <input type="text" id="llm-model-custom" placeholder="ej: kimi-k2-0711, gpt-4o, gemini-2.0-flash…">
                    </label>
                </div>
                <label class="llm-label">API Key
                    <span id="llm-key-status" class="llm-key-status"></span>
                    <input type="password" id="llm-key" placeholder="Dejar vacío para no cambiar la guardada">
                </label>
                <div id="llm-url-wrap" style="display:none">
                    <label class="llm-label">URL del API (OpenAI-compatible)
                        <input type="url" id="llm-url" placeholder="https://api.example.com/v1/chat/completions">
                    </label>
                </div>
                <p class="llm-hint">La API key se guarda en la base de datos local. Nunca sale del servidor.</p>
            </div>
            <div class="llm-modal-footer">
                <button id="btn-llm-test" class="btn">Probar conexión</button>
                <button id="btn-llm-save" class="btn btn-primary">Guardar</button>
            </div>
        </div>
        <!-- Tab: Sensores & Datos -->
        <div id="cfg-tab-sensores" style="display:none">
            <div class="llm-modal-body">
                <label class="llm-label">URL del sensor / dron (API JSON)
                    <span id="sensor-url-status" class="llm-key-status"></span>
                    <input type="url" id="cfg-sensor-url" placeholder="https://misensor.com/api_datos.php">
                </label>
                <p class="llm-hint">El endpoint debe responder: <code>{"ok":true,"rows":[{"municipio","estacion","fecha_formateada","diametro_aerodinamico","medicion"}…]}</code></p>
                <label class="llm-label">Socrata App Token (datos.gov.co)
                    <input type="text" id="cfg-socrata-token" placeholder="Opcional — aumenta el límite de llamadas a la API">
                </label>
                <p class="llm-hint">Token gratuito en <strong>data.socrata.com/profile</strong> · Permite hasta 1 000 llamadas/hora sin token, 10 000 con token.</p>
            </div>
            <div class="llm-modal-footer">
                <button id="btn-sensor-test" class="btn">Probar sensor</button>
                <button id="btn-sensor-save" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>

<main class="container">
    <section class="filtros">
        <label id="lbl-fuente" class="filtro-fuente" hidden>Fuente
            <select id="fuente"></select>
        </label>
        <label>Municipio
            <select id="municipio"><option value="">— Todos —</option></select>
        </label>
        <label>Desde <input type="date" id="desde"></label>
        <label>Hasta <input type="date" id="hasta"></label>
        <button id="aplicar" class="btn">Aplicar filtros</button>
        <span id="estado" class="estado"></span>
    </section>

    <nav class="vistas">
        <button class="vista-btn active" data-vista="abiertos">📂 Datos Abiertos</button>
        <button class="vista-btn" data-vista="dron">🛰️ Datos del Dron</button>
        <button class="vista-btn" data-vista="comparar">📊 Histórico / Comparación</button>
    </nav>

    <section class="panel">
        <div id="nota" class="nota" hidden></div>
        <div class="chart-wrap"><canvas id="grafico"></canvas></div>
        <div id="tabla" class="tabla"></div>
    </section>

    <!-- Panel de interpretación IA (visible solo cuando LLM está configurado) -->
    <section id="llm-interpretation" class="llm-panel" style="display:none">
        <div class="llm-panel-header">🤖 Interpretación IA</div>
        <p class="llm-texto"></p>
    </section>

    <!-- Recomendaciones personalizadas por municipio -->
    <section id="llm-recommendations" class="llm-reco-panel" style="display:none"></section>

    <!-- Seguridad en tiempo real: eventos del modelo de visión × SIEDCO -->
    <section id="seguridad-tiempo-real" class="seg-panel" style="display:none">
        <div class="seg-header">
            <span>🛰️ Seguridad en Tiempo Real <small>· visión IA × datos.gov.co (SIEDCO)</small></span>
            <span id="seg-status" class="seg-status"></span>
        </div>
        <div id="seg-eventos" class="seg-eventos"></div>
    </section>
</main>

<footer class="pie">
    <div>Fuentes: <a href="https://www.datos.gov.co/" target="_blank" rel="noopener">datos.gov.co</a>
    (Socrata SODA) + sensores del dron por API · Concurso Datos al Ecosistema 2026</div>
    <div class="pie-tags">
        <span class="pie-tag">Drones</span>
        <span class="pie-tag">Inteligencia Artificial</span>
        <span class="pie-tag">Datos Abiertos</span>
        <span class="pie-tag">Bienestar y Salud</span>
    </div>
</footer>

<!-- Chat flotante VigIA -->
<button id="btn-chat" class="btn-chat-fab" title="Pregunta a VigIA">💬</button>
<div id="chat-panel" style="display:none">
    <div class="chat-header">
        <span>💬 Pregunta a VigIA</span>
        <button id="chat-close">✕</button>
    </div>
    <div id="chat-messages"></div>
    <div class="chat-input-row">
        <input id="chat-input" type="text" placeholder="¿Cómo está el aire en mi municipio?">
        <button id="chat-send" class="btn">Enviar</button>
    </div>
</div>

<script src="assets/js/app.js?v=<?= filemtime(__DIR__ . '/assets/js/app.js') ?>"></script>
<script src="assets/js/app-llm.js?v=<?= filemtime(__DIR__ . '/assets/js/app-llm.js') ?>"></script>
<script src="assets/js/app-chat.js?v=<?= filemtime(__DIR__ . '/assets/js/app-chat.js') ?>"></script>
<script src="assets/js/app-seguridad.js?v=<?= filemtime(__DIR__ . '/assets/js/app-seguridad.js') ?>"></script>
</body>
</html>
