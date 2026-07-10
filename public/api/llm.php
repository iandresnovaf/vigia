<?php
/** Proxy LLM: interpreta datos del dashboard o verifica alertas en tiempo real. */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../src/Config.php';
require_once __DIR__ . '/../../src/Db.php';
require_once __DIR__ . '/../../src/Analitica.php';
require_once __DIR__ . '/../../src/Alertas.php';

/**
 * Construye el objeto de procedencia (trazabilidad) a partir de los datos consultados.
 * @return array{fuente:string, dataset_id:string, municipio:string, registros:int,
 *               ultima_fecha:string, consultado_en:string}
 */
function procedencia(array $body, array $datos): array
{
    $ultima = '';
    foreach ($datos as $r) {
        foreach (['fecha', 'fecha_hecho', 'captured_at', 'fecha_lectura', 'med_fecha_inicio'] as $cf) {
            if (!empty($r[$cf])) { $f = substr((string) $r[$cf], 0, 10); if ($f > $ultima) $ultima = $f; break; }
        }
    }
    return [
        'fuente'        => substr(strip_tags((string) ($body['fuente'] ?? '')), 0, 120),
        'dataset_id'    => preg_replace('/[^a-z0-9\-]/i', '', (string) ($body['dataset_id'] ?? '')),
        'municipio'     => substr(strip_tags((string) ($body['municipio'] ?? '')), 0, 100),
        'registros'     => (int) ($body['registros'] ?? count($datos)),
        'ultima_fecha'  => $ultima,
        'consultado_en' => date('Y-m-d H:i'),
    ];
}

function llmConfig(PDO $pdo): array
{
    try {
        $rows = $pdo->query("SELECT cfg_key, cfg_val FROM llm_config")->fetchAll(PDO::FETCH_KEY_PAIR);
    } catch (Throwable $e) {
        return [];
    }
    return [
        'provider' => $rows['provider'] ?? 'kimi',
        'model'    => $rows['model']    ?? 'moonshot-v1-8k',
        'api_key'  => $rows['api_key']  ?? '',
        'api_url'  => $rows['api_url']  ?? '',
    ];
}

function apiUrl(string $provider, string $customUrl): string
{
    return match ($provider) {
        'openai'     => 'https://api.openai.com/v1/chat/completions',
        'openrouter' => 'https://openrouter.ai/api/v1/chat/completions',
        'gemini'     => 'https://generativelanguage.googleapis.com/v1beta/openai/chat/completions',
        'claude'     => '', // Anthropic usa callAnthropic(), esta URL no se usa
        'custom'     => $customUrl,
        default      => 'https://api.moonshot.cn/v1/chat/completions',
    };
}

function callLLM(string $url, string $apiKey, string $model, string $system, string $user, array $extraHeaders = []): string
{
    $payload = json_encode([
        'model'       => $model,
        'messages'    => [
            ['role' => 'system', 'content' => $system],
            ['role' => 'user',   'content' => $user],
        ],
        'max_tokens'  => 450,
        'temperature' => 0.4,
    ], JSON_UNESCAPED_UNICODE);

    $headers = array_merge(['Content-Type: application/json', 'Authorization: Bearer ' . $apiKey], $extraHeaders);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => Config::CURL_SSL_VERIFY,
    ]);
    $body  = curl_exec($ch);
    $errno = curl_errno($ch);
    $err   = curl_error($ch);
    curl_close($ch);

    if ($errno) throw new RuntimeException('cURL error: ' . $err);
    $json = json_decode($body, true);
    if (!isset($json['choices'][0]['message']['content'])) {
        $detail = $json['error']['message'] ?? substr((string) $body, 0, 200);
        throw new RuntimeException('LLM error: ' . $detail);
    }
    return trim($json['choices'][0]['message']['content']);
}

function callAnthropic(string $apiKey, string $model, string $system, string $user): string
{
    $payload = json_encode([
        'model'      => $model,
        'max_tokens' => 450,
        'system'     => $system,
        'messages'   => [['role' => 'user', 'content' => $user]],
    ], JSON_UNESCAPED_UNICODE);

    $ch = curl_init('https://api.anthropic.com/v1/messages');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'x-api-key: ' . $apiKey,
            'anthropic-version: 2023-06-01',
        ],
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => Config::CURL_SSL_VERIFY,
    ]);
    $body  = curl_exec($ch);
    $errno = curl_errno($ch);
    $err   = curl_error($ch);
    curl_close($ch);

    if ($errno) throw new RuntimeException('cURL error: ' . $err);
    $json = json_decode($body, true);
    if (!isset($json['content'][0]['text'])) {
        $detail = $json['error']['message'] ?? substr((string) $body, 0, 200);
        throw new RuntimeException('Claude API error: ' . $detail);
    }
    return trim($json['content'][0]['text']);
}

function dispatchLLM(array $cfg, string $url, string $system, string $user): string
{
    if ($cfg['provider'] === 'claude') {
        return callAnthropic($cfg['api_key'], $cfg['model'], $system, $user);
    }
    $extra = $cfg['provider'] === 'openrouter'
        ? ['HTTP-Referer: https://github.com/iandresnovaf/vigia', 'X-Title: VigIA']
        : [];
    return callLLM($url, $cfg['api_key'], $cfg['model'], $system, $user, $extra);
}

function temaCtx(string $tema): string
{
    return match ($tema) {
        'aire'      => 'calidad del aire y contaminantes atmosféricos (PM2.5, PM10, SO₂, NO₂, O₃)',
        'ruido'     => 'niveles de ruido ambiental en decibeles (dB)',
        'seguridad' => 'incidentes de seguridad ciudadana: hurtos, homicidios y lesiones personales',
        'incendios' => 'incendios de cobertura vegetal (área afectada en hectáreas, tipo de incendio)',
        'clima'     => 'normales climatológicas (temperatura, precipitación y otros parámetros mensuales)',
        default     => $tema,
    };
}

try {
    $pdo = Db::conn();
    $cfg = llmConfig($pdo);

    $body   = json_decode(file_get_contents('php://input'), true) ?? [];
    $tipo   = $body['tipo'] ?? 'interpretar';
    $tema   = preg_replace('/[^a-z]/', '', (string) ($body['tema'] ?? 'aire'));
    $datos  = array_slice((array) ($body['datos'] ?? []), 0, 20);
    $hasKey = !empty($cfg['api_key']);
    $url    = $hasKey ? apiUrl($cfg['provider'], $cfg['api_url']) : '';

    // predecir y alertar NO dependen del LLM: la analítica es determinística.
    // interpretar/recomendar sí requieren API key.
    if (!$hasKey && !in_array($tipo, ['predecir', 'alertar'], true)) {
        echo json_encode([
            'ok'    => false,
            'error' => 'LLM sin configurar. Haz clic en ⚙️ Asistente IA para agregar tu API key.',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($tipo === 'alertar') {
        // Decisión por regla determinística (auditable); el LLM solo redacta el mensaje.
        $lectura = $datos[count($datos) - 1] ?? [];
        $ev      = Alertas::evaluar($lectura);
        if ($ev['alerta']) {
            $mensaje = "Se superó el umbral de {$ev['parametro']}: {$ev['valor']} (límite {$ev['umbral']}).";
            if ($hasKey) {
                try {
                    $system = 'Eres un sistema de alerta temprana para Colombia. Redacta en 1 frase clara y ' .
                              'sin tecnicismos una alerta ciudadana. Responde solo el texto, sin markdown.';
                    $user   = "Alerta de tipo {$ev['tipo']}: {$ev['parametro']}={$ev['valor']} superó el umbral {$ev['umbral']}. " .
                              'Explica el riesgo y una acción preventiva.';
                    $mensaje = dispatchLLM($cfg, $url, $system, $user);
                } catch (Throwable) { /* se mantiene el mensaje por plantilla */ }
            }
            $ev['mensaje'] = $mensaje;
        } else {
            $ev['mensaje'] = 'Sin alertas: los parámetros están dentro de rangos seguros.';
        }
        echo json_encode(['ok' => true, 'tipo' => 'alertar', 'alerta' => $ev], JSON_UNESCAPED_UNICODE);
    } elseif ($tipo === 'predecir') {
        $municipio = substr(strip_tags((string)($body['municipio'] ?? '')), 0, 100);
        // Analítica determinística sobre más historial (no solo 20 filas).
        $historial = array_slice((array) ($body['datos'] ?? []), 0, 180);
        $serie     = Analitica::serieDiaria($historial);

        if (count($serie) < 5) {
            echo json_encode([
                'ok'    => true, 'tipo' => 'predecir', 'suficiente' => false,
                'error' => 'Datos insuficientes para una predicción estadística (se requieren ≥5 días).',
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $pron = Analitica::pronostico($serie, 7);
        $bt   = Analitica::backtest($serie, 7);
        $conf = Analitica::nivelConfianza($pron['r2'], $bt['mape']);

        // El LLM SOLO narra los resultados ya calculados; no genera cifras.
        $narrativa = 'Proyección por regresión lineal sobre ' . count($serie) . ' días de datos oficiales.';
        if ($hasKey) {
            try {
                $system = 'Eres el Agente Predictor de VigIA. Te doy una predicción YA CALCULADA por un ' .
                          'modelo estadístico (regresión lineal). NO inventes ni cambies cifras: solo explícalas ' .
                          'a un ciudadano en 2 oraciones, en español, mencionando la tendencia y el nivel de confianza.';
                $user   = "Tema: " . temaCtx($tema) . "\nMunicipio: $municipio\n" .
                          "Tendencia: {$pron['tendencia']}\nPredicción 7 días: " . json_encode($pron['valores']) .
                          "\nR²: {$pron['r2']} · MAPE: " . ($bt['mape'] ?? 'n/d') . "% · confianza: $conf";
                $narrativa = dispatchLLM($cfg, $url, $system, $user);
            } catch (Throwable) { /* se mantiene la narrativa por plantilla */ }
        }

        echo json_encode([
            'ok'               => true,
            'tipo'             => 'predecir',
            'suficiente'       => true,
            'metodo'           => 'regresion_lineal',
            'tendencia'        => $pron['tendencia'],
            'prediccion_7dias' => $pron['valores'],
            'intervalo_confianza' => $pron['intervalo'],
            'confianza'        => $conf,
            'metricas'         => ['mae' => $bt['mae'], 'rmse' => $bt['rmse'], 'mape' => $bt['mape'], 'r2' => $pron['r2']],
            'dias_datos'       => count($serie),
            'narrativa'        => $narrativa,
            'procedencia'      => procedencia($body, $historial),
        ], JSON_UNESCAPED_UNICODE);
    } elseif ($tipo === 'recomendar') {
        $municipio = substr(strip_tags((string)($body['municipio'] ?? '')), 0, 100);
        $system = 'Eres el Agente Recomendador de VigIA. Genera 3 recomendaciones concretas y prácticas para ' .
                  "ciudadanos de $municipio, Colombia, basadas ÚNICAMENTE en los datos actuales de " . temaCtx($tema) . '. ' .
                  'Considera grupos vulnerables (niños, adultos mayores) y el contexto colombiano. ' .
                  'No inventes cifras que no estén en los datos. ' .
                  'Responde ÚNICAMENTE con JSON válido: {"recomendaciones":["rec1","rec2","rec3"]}';
        $user   = "Municipio: $municipio\nDatos actuales: " . json_encode($datos, JSON_UNESCAPED_UNICODE);
        $texto  = dispatchLLM($cfg, $url, $system, $user);
        $parsed = json_decode($texto, true);
        $recos  = is_array($parsed['recomendaciones'] ?? null) ? $parsed['recomendaciones'] : [];
        if (empty($recos)) {
            $recos = array_values(array_filter(explode("\n", strip_tags($texto))));
        }
        echo json_encode([
            'ok'              => true,
            'tipo'            => 'recomendar',
            'recomendaciones' => array_values(array_slice($recos, 0, 3)),
            'procedencia'     => procedencia($body, $datos),
        ], JSON_UNESCAPED_UNICODE);
    } else {
        $system = 'Eres un asistente amigable para ciudadanos colombianos. Analizas datos ambientales y de seguridad de Colombia y los explicas con claridad, sin tecnicismos. Responde siempre en español, máximo 3 oraciones. Básate SOLO en los datos entregados; si son insuficientes, dilo; nunca inventes cifras.';
        $user   = 'Analiza estos datos de ' . temaCtx($tema) . ' y explícalos a un ciudadano común: ' .
                  json_encode($datos, JSON_UNESCAPED_UNICODE);
        $texto  = dispatchLLM($cfg, $url, $system, $user);
        echo json_encode([
            'ok'          => true,
            'tipo'        => 'interpretar',
            'respuesta'   => $texto,
            'procedencia' => procedencia($body, $datos),
        ], JSON_UNESCAPED_UNICODE);
    }
} catch (Throwable $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
