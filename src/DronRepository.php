<?php
require_once __DIR__ . '/Db.php';

/** Persistencia y consulta de datos del dron (lecturas de sensores + eventos de seguridad). */
class DronRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Db::conn();
    }

    /** Inserta una lectura de sensores. Idempotente por (device_id, captured_at). */
    public function guardarLectura(array $l, string $deviceId): int
    {
        $sql = "INSERT IGNORE INTO dron_lecturas
            (device_id, captured_at, municipio, cod_muni, lat, lng, pm25, pm10, no2, o3, ruido_db, bateria, raw_json)
            VALUES (:device_id, :captured_at, :municipio, :cod_muni, :lat, :lng, :pm25, :pm10, :no2, :o3, :ruido_db, :bateria, :raw_json)";
        $st = $this->db->prepare($sql);
        $st->execute([
            ':device_id'   => $deviceId,
            ':captured_at' => $l['captured_at'] ?? date('Y-m-d H:i:s'),
            ':municipio'   => $l['municipio']   ?? null,
            ':cod_muni'    => $l['cod_muni']    ?? null,
            ':lat'         => $l['lat']         ?? null,
            ':lng'         => $l['lng']         ?? null,
            ':pm25'        => $l['pm25']        ?? null,
            ':pm10'        => $l['pm10']        ?? null,
            ':no2'         => $l['no2']         ?? null,
            ':o3'          => $l['o3']          ?? null,
            ':ruido_db'    => $l['ruido_db']    ?? null,
            ':bateria'     => $l['bateria']     ?? null,
            ':raw_json'    => json_encode($l, JSON_UNESCAPED_UNICODE),
        ]);
        return $st->rowCount();
    }

    /** Inserta un evento de seguridad. Idempotente por (device_id, captured_at, tipo). */
    public function guardarEvento(array $e, string $deviceId): int
    {
        $sql = "INSERT IGNORE INTO dron_eventos_seguridad
            (device_id, captured_at, municipio, cod_muni, lat, lng, tipo_comportamiento, nivel_alerta, confianza, media_url, ai_result_json)
            VALUES (:device_id, :captured_at, :municipio, :cod_muni, :lat, :lng, :tipo, :nivel, :confianza, :media_url, :ai_json)";
        $st = $this->db->prepare($sql);
        $st->execute([
            ':device_id'   => $deviceId,
            ':captured_at' => $e['captured_at'] ?? date('Y-m-d H:i:s'),
            ':municipio'   => $e['municipio']   ?? null,
            ':cod_muni'    => $e['cod_muni']    ?? null,
            ':lat'         => $e['lat']  ?? null,
            ':lng'         => $e['lng']  ?? null,
            ':tipo'        => $e['tipo_comportamiento'] ?? 'desconocido',
            ':nivel'       => $e['nivel_alerta'] ?? null,
            ':confianza'   => $e['confianza'] ?? null,
            ':media_url'   => $e['media_url'] ?? null,
            ':ai_json'     => isset($e['ai_result_json'])
                                ? json_encode($e['ai_result_json'], JSON_UNESCAPED_UNICODE)
                                : json_encode($e, JSON_UNESCAPED_UNICODE),
        ]);
        return $st->rowCount();
    }

    /** Últimas lecturas (opcionalmente filtradas por municipio). */
    public function lecturas(string $municipio = '', int $limit = 200): array
    {
        $sql = "SELECT captured_at, municipio, cod_muni, lat, lng, pm25, pm10, no2, o3, ruido_db, bateria
                FROM dron_lecturas";
        $args = [];
        if ($municipio !== '') {
            $sql .= " WHERE UPPER(municipio) = UPPER(:m)";
            $args[':m'] = $municipio;
        }
        $sql .= " ORDER BY captured_at DESC LIMIT " . (int) $limit;
        $st = $this->db->prepare($sql);
        $st->execute($args);
        return $st->fetchAll();
    }

    /** Últimos eventos de seguridad. */
    public function eventos(int $limit = 200): array
    {
        $sql = "SELECT id, captured_at, municipio, cod_muni, lat, lng,
                       tipo_comportamiento, nivel_alerta, confianza, media_url
                FROM dron_eventos_seguridad
                ORDER BY captured_at DESC LIMIT " . (int) $limit;
        return $this->db->query($sql)->fetchAll();
    }

    /** Serie diaria del dron para comparación: promedio de una columna o conteo de eventos. */
    public function serieDiaria(string $temaCampo, string $municipio = ''): array
    {
        if ($temaCampo === '__eventos__') {
            $sql = "SELECT DATE(captured_at) AS dia, COUNT(*) AS valor
                    FROM dron_eventos_seguridad
                    GROUP BY DATE(captured_at) ORDER BY dia ASC";
            return $this->db->query($sql)->fetchAll();
        }
        // whitelist de columnas permitidas
        $cols = ['pm25', 'pm10', 'no2', 'o3', 'ruido_db'];
        if (!in_array($temaCampo, $cols, true)) {
            return [];
        }
        $sql = "SELECT DATE(captured_at) AS dia, ROUND(AVG($temaCampo), 2) AS valor
                FROM dron_lecturas WHERE $temaCampo IS NOT NULL";
        $args = [];
        if ($municipio !== '') {
            $sql .= " AND UPPER(municipio) = UPPER(:m)";
            $args[':m'] = $municipio;
        }
        $sql .= " GROUP BY DATE(captured_at) ORDER BY dia ASC";
        $st = $this->db->prepare($sql);
        $st->execute($args);
        return $st->fetchAll();
    }
}
