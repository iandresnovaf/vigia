<?php
/** Proxy a datos.gov.co por tema + fuente + municipio + rango de fechas. */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../src/Config.php';
require_once __DIR__ . '/../../src/SocrataClient.php';

/* ── normalización de filas para tema=aire ── */
function normalizar(array $rows, array $src): array
{
    $out = [];
    if (($src['formato'] ?? 'long') === 'wide') {
        foreach ($rows as $r) {
            $fecha = substr((string) ($r[$src['campo_fecha']] ?? ''), 0, 10);
            $mun   = $src['municipio_fijo'] ?? '';
            $est   = isset($src['campo_estacion']) ? ($r[$src['campo_estacion']] ?? '') : '';
            foreach ($src['valores_wide'] as $col => $cat) {
                if (!array_key_exists($col, $r)) {
                    continue;
                }
                $out[] = [
                    'fecha'    => $fecha,
                    'municipio'=> $mun,
                    'estacion' => $est,
                    'categoria'=> $cat,
                    'medicion' => $r[$col],
                ];
            }
        }
    } else {
        foreach ($rows as $r) {
            $out[] = [
                'fecha'    => substr((string) ($r[$src['campo_fecha']] ?? ''), 0, 10),
                'municipio'=> $r[$src['campo_municipio'] ?? ''] ?? '',
                'estacion' => isset($src['campo_estacion']) ? ($r[$src['campo_estacion']] ?? '') : '',
                'categoria'=> isset($src['campo_categoria']) ? ($r[$src['campo_categoria']] ?? '') : '',
                'medicion' => $r[$src['campo_valor']] ?? '',
            ];
        }
    }
    return $out;
}

try {
    $tema = $_GET['tema'] ?? 'aire';
    if (!isset(Config::DATASETS[$tema])) {
        throw new InvalidArgumentException('Tema inválido');
    }
    $ds     = Config::DATASETS[$tema];
    $accion = $_GET['accion'] ?? 'datos';

    // Temas sin sources (ruido): responder con la nota informativa.
    if (empty($ds['sources'])) {
        echo json_encode([
            'ok'      => true,
            'tema'    => $tema,
            'sin_api' => true,
            'nota'    => $ds['nota'] ?? 'Sin fuente de API para este tema.',
            'data'    => [],
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Seleccionar fuente (0-indexed).
    $srcIdx = (int) ($_GET['src'] ?? 0);
    $srcIdx = max(0, min($srcIdx, count($ds['sources']) - 1));
    $src    = $ds['sources'][$srcIdx];

    $esAire      = ($tema === 'aire');
    $normalizar_ = ($esAire || $tema === 'incendios');

    // Lista de municipios disponibles.
    if ($accion === 'municipios') {
        if (!$src['campo_municipio']) {
            echo json_encode(['ok' => true, 'municipios' => []], JSON_UNESCAPED_UNICODE);
            exit;
        }
        $rows = SocrataClient::query($src['id'], [
            '$select' => $src['campo_municipio'],
            '$group'  => $src['campo_municipio'],
            '$order'  => $src['campo_municipio'],
            '$limit'  => 2000,
        ]);
        $muns = [];
        foreach ($rows as $r) {
            $v = trim((string) ($r[$src['campo_municipio']] ?? ''));
            if ($v !== '') {
                $muns[] = $v;
            }
        }
        echo json_encode(['ok' => true, 'municipios' => $muns], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Datos filtrados.
    $municipio = trim($_GET['municipio'] ?? '');
    $limit     = min(max((int) ($_GET['limit'] ?? 500), 1), 5000);

    $params = [
        '$order' => $src['campo_fecha'] . ' DESC',
        '$limit' => $limit,
    ];
    $where = [];
    if ($municipio !== '' && ($src['campo_municipio'] ?? null)) {
        $safe    = str_replace("'", "''", strtoupper($municipio));
        $where[] = "upper({$src['campo_municipio']})='{$safe}'";
    }
    $desde = preg_replace('/[^0-9\-]/', '', $_GET['desde'] ?? '');
    $hasta = preg_replace('/[^0-9\-]/', '', $_GET['hasta'] ?? '');
    if ($desde !== '') {
        $where[] = "{$src['campo_fecha']} >= '{$desde}T00:00:00'";
    }
    if ($hasta !== '') {
        $where[] = "{$src['campo_fecha']} <= '{$hasta}T23:59:59'";
    }
    if ($where) {
        $params['$where'] = implode(' AND ', $where);
    }

    $rows = SocrataClient::query($src['id'], $params);

    // Normalizar para temas con campo_categoria/campo_valor (aire e incendios).
    if ($normalizar_) {
        $rows = normalizar($rows, $src);
    }

    echo json_encode([
        'ok'         => true,
        'tema'       => $tema,
        'src'        => $srcIdx,
        'dataset_id' => $src['id'],
        'fuente'     => $src['label'],
        'nota'       => $src['nota'] ?? '',
        'count'      => count($rows),
        'data'       => $rows,
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
