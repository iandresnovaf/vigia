<?php
require_once __DIR__ . '/Config.php';

/**
 * (Fase 2) Cliente del microservicio de inferencia de visión.
 * PHP no ejecuta visión por computador; envía la imagen al microservicio y recibe la clasificación.
 * Ver ai-service/app.py para el servidor de ejemplo (Python + FastAPI).
 */
class AiClient
{
    /**
     * @param string $rutaImagen Ruta local del frame/imagen a analizar.
     * @return array{tipo_comportamiento:string, confianza:float, raw:array}
     */
    public static function clasificar(string $rutaImagen): array
    {
        if (Config::AI_SERVICE_URL === '') {
            throw new RuntimeException('AI_SERVICE_URL no configurado (fase 2 desactivada).');
        }
        if (!is_file($rutaImagen)) {
            throw new RuntimeException("No existe la imagen: $rutaImagen");
        }

        $ch = curl_init(rtrim(Config::AI_SERVICE_URL, '/') . '/clasificar');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_POSTFIELDS     => ['imagen' => new CURLFile($rutaImagen)],
        ]);
        $body = curl_exec($ch);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($body === false) {
            throw new RuntimeException("Error al llamar al microservicio de IA: $err");
        }
        $json = json_decode($body, true);
        if (!is_array($json)) {
            throw new RuntimeException('Respuesta inválida del microservicio de IA.');
        }
        return [
            'tipo_comportamiento' => $json['tipo_comportamiento'] ?? 'desconocido',
            'confianza'           => (float) ($json['confianza'] ?? 0),
            'raw'                 => $json,
        ];
    }

    /**
     * (Camino pull) Envía un video al microservicio TSN (ai-service/app.py, POST /detectar)
     * y devuelve la clasificación de hurto/riesgo.
     *
     * @param string $rutaVideo Ruta local del video MP4 a analizar.
     * @return array{tipo:string, nivel_alerta:string, theft_score:float, risk_score:float, top5:array, raw:array}
     */
    public static function detectar(string $rutaVideo): array
    {
        if (Config::AI_SERVICE_URL === '') {
            throw new RuntimeException('AI_SERVICE_URL no configurado (microservicio de visión desactivado).');
        }
        if (!is_file($rutaVideo)) {
            throw new RuntimeException("No existe el video: $rutaVideo");
        }

        $ch = curl_init(rtrim(Config::AI_SERVICE_URL, '/') . '/detectar');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_TIMEOUT        => 180, // la inferencia de video puede tardar
            CURLOPT_POSTFIELDS     => ['video' => new CURLFile($rutaVideo)],
        ]);
        $body = curl_exec($ch);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($body === false) {
            throw new RuntimeException("Error al llamar al microservicio de visión: $err");
        }
        $json = json_decode($body, true);
        if (!is_array($json)) {
            throw new RuntimeException('Respuesta inválida del microservicio de visión.');
        }
        if (($json['ok'] ?? true) === false) {
            throw new RuntimeException('Microservicio de visión: ' . ($json['error'] ?? 'error desconocido'));
        }
        return [
            'tipo'         => $json['tipo']         ?? 'desconocido',
            'nivel_alerta' => $json['nivel_alerta'] ?? 'baja',
            'theft_score'  => (float) ($json['theft_score'] ?? 0),
            'risk_score'   => (float) ($json['risk_score']  ?? 0),
            'top5'         => $json['top5'] ?? [],
            'raw'          => $json,
        ];
    }
}
