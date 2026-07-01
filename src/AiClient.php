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
}
