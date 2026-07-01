<?php
/** Series diarias alineadas: datos abiertos vs dron, por tema y fuente. */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../src/Config.php';
require_once __DIR__ . '/../../src/SocrataClient.php';
require_once __DIR__ . '/../../src/DronRepository.php';

try {
    $tema = $_GET['tema'] ?? 'aire';
    if (!isset(Config::DATASETS[$tema])) {
        throw new InvalidArgumentException('Tema inválido');
    }
    $ds        = Config::DATASETS[$tema];
    $municipio = trim($_GET['municipio'] ?? '');
    $srcIdx    = (int) ($_GET['src'] ?? 0);

    // ── Serie de DATOS ABIERTOS (agregada por día en PHP) ──
    $serieAbiertos = [];
    $srcLabel      = '';
    $datasetId     = null;

    if (!empty($ds['sources'])) {
        $srcIdx = max(0, min($srcIdx, count($ds['sources']) - 1));
        $src    = $ds['sources'][$srcIdx];
        $srcLabel  = $src['label'];
        $datasetId = $src['id'];

        $params = ['$order' => $src['campo_fecha'] . ' DESC', '$limit' => 5000];
        $where  = [];
        if ($municipio !== '' && ($src['campo_municipio'] ?? null)) {
            $safe    = str_replace("'", "''", strtoupper($municipio));
            $where[] = "upper({$src['campo_municipio']})='{$safe}'";
        }
        if ($where) {
            $params['$where'] = implode(' AND ', $where);
        }
        $rows = SocrataClient::query($src['id'], $params);

        $acum = [];
        $esAire = ($tema === 'aire');

        if ($esAire && ($src['formato'] ?? 'long') === 'wide') {
            // Para formato wide usamos la primera columna de valor para comparar.
            $primerCol = array_key_first($src['valores_wide']);
            foreach ($rows as $r) {
                $dia = substr((string) ($r[$src['campo_fecha']] ?? ''), 0, 10);
                if ($dia === '' || !isset($r[$primerCol])) {
                    continue;
                }
                $val = (float) $r[$primerCol];
                if (!isset($acum[$dia])) {
                    $acum[$dia] = [0.0, 0];
                }
                $acum[$dia][0] += $val;
                $acum[$dia][1] += 1;
            }
        } else {
            $campoFecha = $src['campo_fecha'];
            $campoValor = $src['campo_valor'];
            foreach ($rows as $r) {
                $dia = substr((string) ($r[$campoFecha] ?? ''), 0, 10);
                if ($dia === '') {
                    continue;
                }
                $val = (float) ($r[$campoValor] ?? 0);
                if (!isset($acum[$dia])) {
                    $acum[$dia] = [0.0, 0];
                }
                $acum[$dia][0] += $val;
                $acum[$dia][1] += 1;
            }
        }
        ksort($acum);
        foreach ($acum as $dia => $p) {
            $valor = ($tema === 'seguridad') ? $p[0] : round($p[0] / max($p[1], 1), 2);
            $serieAbiertos[] = ['dia' => $dia, 'valor' => $valor];
        }
    }

    // ── Serie del DRON ──
    $repo = new DronRepository();
    if ($tema === 'seguridad') {
        $serieDron = $repo->serieDiaria('__eventos__', $municipio);
    } elseif ($ds['dron_campo'] !== null) {
        $serieDron = $repo->serieDiaria($ds['dron_campo'], $municipio);
    } else {
        $serieDron = []; // tema sin sensor de dron (incendios, clima)
    }
    $serieDron = array_map(fn($r) => ['dia' => $r['dia'], 'valor' => (float) $r['valor']], $serieDron);

    echo json_encode([
        'ok'         => true,
        'tema'       => $tema,
        'unidad'     => $ds['unidad'],
        'label'      => $ds['label'],
        'fuente'     => $srcLabel,
        'dataset_id' => $datasetId,
        'abiertos'   => $serieAbiertos,
        'dron'       => $serieDron,
        'nota'       => empty($ds['sources']) ? ($ds['nota'] ?? '') : ($ds['sources'][$srcIdx]['nota'] ?? ''),
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
