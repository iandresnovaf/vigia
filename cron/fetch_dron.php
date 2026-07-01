<?php
/**
 * Se ejecuta CADA HORA (Programador de tareas de Windows / cron).
 * Consulta la URL JSON del dron (Config::DRON_URL) y guarda lecturas + eventos en MySQL.
 * Si DRON_URL está vacía, usa el archivo de ejemplo (Config::DRON_SAMPLE) para desarrollo.
 *
 * Uso manual:  php cron/fetch_dron.php
 */
require_once __DIR__ . '/../src/Config.php';
require_once __DIR__ . '/../src/SocrataClient.php';
require_once __DIR__ . '/../src/DronRepository.php';

function log_line(string $msg): void
{
    echo '[' . date('Y-m-d H:i:s') . "] $msg\n";
}

try {
    // 1) Obtener el JSON del dron
    if (Config::DRON_URL !== '') {
        log_line('Consultando dron: ' . Config::DRON_URL);
        $payload = SocrataClient::getJson(Config::DRON_URL);
    } else {
        log_line('DRON_URL vacía; usando archivo de ejemplo.');
        $raw = @file_get_contents(Config::DRON_SAMPLE);
        if ($raw === false) {
            throw new RuntimeException('No se encontró el archivo de ejemplo: ' . Config::DRON_SAMPLE);
        }
        $payload = json_decode($raw, true);
        if (!is_array($payload)) {
            throw new RuntimeException('El archivo de ejemplo no es un JSON válido.');
        }
    }

    $deviceId = $payload['device_id'] ?? Config::DRON_DEVICE_ID;
    $repo     = new DronRepository();

    // 2) Guardar lecturas de sensores
    $nLect = 0;
    foreach (($payload['lecturas'] ?? []) as $l) {
        $nLect += $repo->guardarLectura($l, $deviceId);
    }

    // 3) Guardar eventos de seguridad (ya clasificados por la IA del dron / microservicio)
    $nEv = 0;
    foreach (($payload['eventos_seguridad'] ?? []) as $e) {
        $nEv += $repo->guardarEvento($e, $deviceId);
    }

    log_line("OK. Lecturas nuevas: $nLect | Eventos nuevos: $nEv");
    exit(0);
} catch (Throwable $e) {
    log_line('ERROR: ' . $e->getMessage());
    exit(1);
}
