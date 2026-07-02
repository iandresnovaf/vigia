<?php
/** Devuelve lecturas del sensor/dron. Si sensor_url está en DB, lo consulta directamente. */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../src/Config.php';
require_once __DIR__ . '/../../src/Db.php';
require_once __DIR__ . '/../../src/DronRepository.php';

function sensorUrlFromDb(): string
{
    try {
        $v = Db::conn()->query("SELECT cfg_val FROM llm_config WHERE cfg_key='sensor_url'")->fetchColumn();
        return $v ? trim($v) : '';
    } catch (Throwable) {
        return '';
    }
}

/**
 * Convierte las filas {fecha_formateada, diametro_aerodinamico, medicion, municipio, estacion}
 * a filas columnares {captured_at, municipio, estacion, pm10, pm25, no2, o3, ruido_db, …}.
 */
function transformarFilasSensor(array $rows): array
{
    $fieldMap = [
        'PM10'     => 'pm10',    'PM2.5'    => 'pm25', 'PM2,5' => 'pm25',
        'NO2'      => 'no2',     'NO₂'      => 'no2',
        'O3'       => 'o3',      'O₃'       => 'o3',
        'SO2'      => 'so2',     'SO₂'      => 'so2',
        'Ruido'    => 'ruido_db','ruido_dB' => 'ruido_db', 'dB' => 'ruido_db',
        'Temperatura' => 'temperatura', 'Humedad' => 'humedad',
    ];
    $grouped = [];
    foreach ($rows as $r) {
        $key = ($r['fecha_formateada'] ?? '') . '|' . ($r['municipio'] ?? '') . '|' . ($r['estacion'] ?? '');
        if (!isset($grouped[$key])) {
            $grouped[$key] = [
                'captured_at' => $r['fecha_formateada'] ?? '',
                'municipio'   => $r['municipio']  ?? '',
                'estacion'    => $r['estacion']   ?? '',
            ];
        }
        $nombre = trim($r['diametro_aerodinamico'] ?? '');
        $col    = $fieldMap[$nombre] ?? strtolower(str_replace(['.', ',', ' ', '₂', '₃'], ['', '', '_', '2', '3'], $nombre));
        if ($col !== '') {
            $grouped[$key][$col] = $r['medicion'] ?? null;
        }
    }
    return array_values($grouped);
}

function fetchExternalSensor(string $url, int $limit): array
{
    $sep  = str_contains($url, '?') ? '&' : '?';
    $ch   = curl_init($url . $sep . 'limit=' . $limit);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_SSL_VERIFYPEER => Config::CURL_SSL_VERIFY,
        CURLOPT_USERAGENT      => 'VigIA/1.0',
    ]);
    $body  = curl_exec($ch);
    $errno = curl_errno($ch);
    curl_close($ch);

    if ($errno || !$body) return [];
    $json = json_decode($body, true);
    if (empty($json['rows'])) return [];
    return transformarFilasSensor($json['rows']);
}

try {
    $tema      = $_GET['tema'] ?? 'aire';
    $municipio = trim($_GET['municipio'] ?? '');
    $limit     = min(max((int) ($_GET['limit'] ?? 200), 1), 2000);

    $sensorUrl = sensorUrlFromDb();

    if ($sensorUrl !== '') {
        $data = fetchExternalSensor($sensorUrl, $limit);
        if ($municipio !== '') {
            $data = array_values(array_filter(
                $data,
                fn($r) => mb_strtolower($r['municipio'] ?? '') === mb_strtolower($municipio)
            ));
        }
        $source = 'external';
    } else {
        $repo = new DronRepository();
        $data = ($tema === 'seguridad') ? $repo->eventos($limit) : $repo->lecturas($municipio, $limit);
        $source = 'mysql';
    }

    echo json_encode([
        'ok'     => true,
        'tema'   => $tema,
        'count'  => count($data),
        'source' => $source,
        'data'   => $data,
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
