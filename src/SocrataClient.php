<?php
require_once __DIR__ . '/Config.php';

/** Cliente HTTP mínimo para la API SODA de datos.gov.co (Socrata). */
class SocrataClient
{
    /**
     * @param string $datasetId  ID 4x4 del dataset (ej: 53gx-j5pc)
     * @param array  $params      Parámetros SoQL ($where, $order, $limit, $select, $group...)
     * @return array              Filas decodificadas
     * @throws RuntimeException
     */
    public static function query(string $datasetId, array $params = []): array
    {
        $url = Config::SOCRATA_BASE . rawurlencode($datasetId) . '.json';
        if ($params) {
            $url .= '?' . http_build_query($params);
        }

        $headers = ['Accept: application/json'];
        if (Config::SOCRATA_APP_TOKEN !== '') {
            $headers[] = 'X-App-Token: ' . Config::SOCRATA_APP_TOKEN;
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 25,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_SSL_VERIFYPEER => Config::CURL_SSL_VERIFY,
            CURLOPT_SSL_VERIFYHOST => Config::CURL_SSL_VERIFY ? 2 : 0,
            CURLOPT_USERAGENT      => 'dashboard-entorno/1.0',
        ]);
        $body = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($body === false) {
            throw new RuntimeException("Error de red al consultar Socrata: $err");
        }
        $json = json_decode($body, true);
        if ($code >= 400 || (is_array($json) && isset($json['error']))) {
            $msg = (is_array($json) && isset($json['message'])) ? $json['message'] : "HTTP $code";
            throw new RuntimeException("Socrata [$datasetId]: $msg");
        }
        return is_array($json) ? $json : [];
    }

    /** GET genérico de una URL JSON (usado para consultar el dron). */
    public static function getJson(string $url): array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 25,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_HTTPHEADER     => ['Accept: application/json'],
            CURLOPT_SSL_VERIFYPEER => Config::CURL_SSL_VERIFY,
            CURLOPT_SSL_VERIFYHOST => Config::CURL_SSL_VERIFY ? 2 : 0,
            CURLOPT_USERAGENT      => 'dashboard-entorno/1.0',
        ]);
        $body = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($body === false) {
            throw new RuntimeException("Error de red al consultar el dron: $err");
        }
        if ($code >= 400) {
            throw new RuntimeException("El dron respondió HTTP $code");
        }
        $json = json_decode($body, true);
        if (!is_array($json)) {
            throw new RuntimeException('El dron no devolvió un JSON válido');
        }
        return $json;
    }
}
