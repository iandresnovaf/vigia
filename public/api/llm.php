<?php
/** Proxy LLM: interpreta datos del dashboard o verifica alertas en tiempo real. */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../src/Config.php';
require_once __DIR__ . '/../../src/Db.php';

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

    if (empty($cfg['api_key'])) {
        echo json_encode([
            'ok'    => false,
            'error' => 'LLM sin configurar. Haz clic en ⚙️ Asistente IA para agregar tu API key.',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $body  = json_decode(file_get_contents('php://input'), true) ?? [];
    $tipo  = $body['tipo'] ?? 'interpretar';
    $tema  = preg_replace('/[^a-z]/', '', (string) ($body['tema'] ?? 'aire'));
    $datos = array_slice((array) ($body['datos'] ?? []), 0, 20);
    $url   = apiUrl($cfg['provider'], $cfg['api_url']);

    if ($tipo === 'alertar') {
        $system = 'Eres un sistema de alerta temprana para Colombia. Analizas datos de sensores de un dron de monitoreo ambiental y de seguridad. Responde ÚNICAMENTE con JSON válido, sin texto ni markdown adicional.';
        $user   = 'Lectura más reciente del dron: ' . json_encode($datos, JSON_UNESCAPED_UNICODE) .
                  "\n\nUmbrales: PM2.5 > 150 µg/m³ = posible incendio; PM10 > 200 µg/m³ = posible incendio; " .
                  "ruido_db > 85 dB = exceso de ruido; tipo_comportamiento sospechoso = alerta de seguridad." .
                  "\n\nResponde con este JSON exacto: {\"alerta\":true|false,\"tipo\":\"incendio|ruido|seguridad|ninguna\",\"mensaje\":\"descripción breve\"}";
        $texto  = dispatchLLM($cfg, $url, $system, $user);
        $parsed = json_decode($texto, true);
        echo json_encode([
            'ok'     => true,
            'tipo'   => 'alertar',
            'alerta' => $parsed ?? ['alerta' => false, 'tipo' => 'ninguna', 'mensaje' => $texto],
        ], JSON_UNESCAPED_UNICODE);
    } else {
        $system = 'Eres un asistente amigable para ciudadanos colombianos. Analizas datos ambientales y de seguridad de Colombia y los explicas con claridad, sin tecnicismos. Responde siempre en español, máximo 3 oraciones, destacando tendencias o situaciones relevantes.';
        $user   = 'Analiza estos datos de ' . temaCtx($tema) . ' y explícalos a un ciudadano común: ' .
                  json_encode($datos, JSON_UNESCAPED_UNICODE);
        $texto  = dispatchLLM($cfg, $url, $system, $user);
        echo json_encode(['ok' => true, 'tipo' => 'interpretar', 'respuesta' => $texto], JSON_UNESCAPED_UNICODE);
    }
} catch (Throwable $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
