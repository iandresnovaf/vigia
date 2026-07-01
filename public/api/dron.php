<?php
/** Devuelve las últimas lecturas/eventos del dron desde MySQL. Consumido por jQuery. */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../src/Config.php';
require_once __DIR__ . '/../../src/DronRepository.php';

try {
    $tema      = $_GET['tema'] ?? 'aire';
    $municipio = trim($_GET['municipio'] ?? '');
    $limit     = min(max((int) ($_GET['limit'] ?? 200), 1), 2000);

    $repo = new DronRepository();
    if ($tema === 'seguridad') {
        $data = $repo->eventos($limit);
    } else {
        $data = $repo->lecturas($municipio, $limit);
    }

    echo json_encode([
        'ok'    => true,
        'tema'  => $tema,
        'count' => count($data),
        'data'  => $data,
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
