<?php
/**
 * Eventos de seguridad del modelo de visión (TSN).
 *   POST → ingesta (webhook push): guarda el evento y lo cruza con SIEDCO.
 *   GET  → últimos eventos + su cruce, para el panel en tiempo real.
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../src/Config.php';
require_once __DIR__ . '/../../src/Db.php';
require_once __DIR__ . '/../../src/DronRepository.php';
require_once __DIR__ . '/../../src/SocrataClient.php';
require_once __DIR__ . '/../../src/CruceHurto.php';

/** Deriva el nivel de alerta con los umbrales del modelo (theft/risk score). */
function nivelAlerta(string $clase, float $confianza, ?float $theft, ?float $risk): string
{
    $c = CruceHurto::normalizarClase($clase);
    $theft = $theft ?? (in_array($c, Config::CV_THEFT_CLASSES, true) ? $confianza : 0.0);
    $risk  = $risk  ?? (in_array($c, Config::CV_RISK_CLASSES, true)  ? $confianza : 0.0);

    if ($theft >= 0.50) return 'alta';
    if ($risk  >= 0.40) return 'riesgo';
    if ($theft >= 0.25) return 'media';
    if ($c === 'normal') return 'normal';
    return 'baja';
}

/** Aplica el Socrata App Token configurado en DB, si existe. */
function aplicarTokenSocrata(): void
{
    try {
        $tok = Db::conn()->query("SELECT cfg_val FROM llm_config WHERE cfg_key='socrata_token'")->fetchColumn();
        if ($tok) SocrataClient::$overrideToken = (string) $tok;
    } catch (Throwable) {}
}

try {
    $repo = new DronRepository();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $body = json_decode(file_get_contents('php://input'), true) ?? [];

        // Validación en frontera.
        $tipo = trim((string) ($body['tipo_comportamiento'] ?? ''));
        if ($tipo === '') {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Falta tipo_comportamiento'], JSON_UNESCAPED_UNICODE);
            exit;
        }
        $municipio = substr(trim(strip_tags((string) ($body['municipio'] ?? ''))), 0, 120);
        $confianza = max(0.0, min(1.0, (float) ($body['confianza'] ?? 0)));
        $theft     = isset($body['theft_score']) ? (float) $body['theft_score'] : null;
        $risk      = isset($body['risk_score'])  ? (float) $body['risk_score']  : null;
        $deviceId  = substr(trim((string) ($body['device_id'] ?? 'dron-01')), 0, 64);
        $capturado = preg_replace('/[^0-9:\- ]/', '', (string) ($body['captured_at'] ?? date('Y-m-d H:i:s')));
        $mediaUrl  = filter_var($body['media_url'] ?? '', FILTER_VALIDATE_URL) ?: null;

        $nivel = nivelAlerta($tipo, $confianza, $theft, $risk);

        aplicarTokenSocrata();
        $cruce = CruceHurto::analizar($tipo, $municipio);

        $evento = [
            'captured_at'         => $capturado,
            'municipio'           => $municipio ?: null,
            'cod_muni'            => substr(trim((string) ($body['cod_muni'] ?? '')), 0, 10) ?: null,
            'lat'                 => isset($body['lat']) ? (float) $body['lat'] : null,
            'lng'                 => isset($body['lng']) ? (float) $body['lng'] : null,
            'tipo_comportamiento' => substr($tipo, 0, 80),
            'nivel_alerta'        => $nivel,
            'confianza'           => $confianza,
            'media_url'           => $mediaUrl,
            'ai_result_json'      => [
                'theft_score' => $theft,
                'risk_score'  => $risk,
                'cruce'       => $cruce,
                'origen'      => (string) ($body['origen'] ?? 'webhook'),
            ],
        ];
        $repo->guardarEvento($evento, $deviceId);

        echo json_encode([
            'ok'     => true,
            'evento' => [
                'captured_at'         => $evento['captured_at'],
                'municipio'           => $evento['municipio'],
                'tipo_comportamiento' => $evento['tipo_comportamiento'],
                'nivel_alerta'        => $nivel,
                'confianza'           => $confianza,
            ],
            'cruce' => $cruce,
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // GET → últimos eventos con su cruce (leído del ai_result_json guardado).
    $limit   = min(max((int) ($_GET['limit'] ?? 20), 1), 100);
    $eventos = $repo->eventos($limit);

    // Reconstruye el cruce desde el JSON almacenado (sin re-consultar SIEDCO en cada poll).
    $pdo = Db::conn();
    $st  = $pdo->prepare("SELECT id, ai_result_json FROM dron_eventos_seguridad ORDER BY captured_at DESC LIMIT " . (int) $limit);
    $st->execute();
    $jsonById = [];
    foreach ($st->fetchAll() as $r) {
        $j = json_decode((string) ($r['ai_result_json'] ?? ''), true);
        $jsonById[$r['id']] = is_array($j) ? $j : [];
    }
    foreach ($eventos as &$ev) {
        $j = $jsonById[$ev['id']] ?? [];
        $ev['cruce']       = $j['cruce']       ?? null;
        $ev['theft_score'] = $j['theft_score'] ?? null;
        $ev['risk_score']  = $j['risk_score']  ?? null;
    }
    unset($ev);

    echo json_encode(['ok' => true, 'count' => count($eventos), 'eventos' => $eventos], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
