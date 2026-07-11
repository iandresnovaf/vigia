<?php
/** Chat conversacional multi-agente — Clasificador → Especialista. */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../src/Config.php';
require_once __DIR__ . '/../../src/Db.php';
require_once __DIR__ . '/../../src/SocrataClient.php';

function chatCfg(): array
{
    try {
        $rows = Db::conn()->query("SELECT cfg_key, cfg_val FROM llm_config")->fetchAll(PDO::FETCH_KEY_PAIR);
    } catch (Throwable) { return []; }
    return [
        'provider' => $rows['provider'] ?? 'kimi',
        'model'    => $rows['model']    ?? 'moonshot-v1-8k',
        'api_key'  => $rows['api_key']  ?? '',
        'api_url'  => $rows['api_url']  ?? '',
    ];
}

function chatCall(array $cfg, string $system, string $user, int $maxTokens = 500): string
{
    if ($cfg['provider'] === 'claude') {
        $payload = json_encode([
            'model' => $cfg['model'], 'max_tokens' => $maxTokens, 'system' => $system,
            'messages' => [['role' => 'user', 'content' => $user]],
        ], JSON_UNESCAPED_UNICODE);
        $ch = curl_init('https://api.anthropic.com/v1/messages');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'x-api-key: ' . $cfg['api_key'],
                                   'anthropic-version: 2023-06-01'],
            CURLOPT_TIMEOUT => 30, CURLOPT_SSL_VERIFYPEER => Config::CURL_SSL_VERIFY,
        ]);
        $body = curl_exec($ch); $errno = curl_errno($ch); curl_close($ch);
        if ($errno) throw new RuntimeException('cURL error');
        $json = json_decode($body, true);
        if (!isset($json['content'][0]['text'])) {
            throw new RuntimeException($json['error']['message'] ?? 'Anthropic error');
        }
        return trim($json['content'][0]['text']);
    }

    $url = match ($cfg['provider']) {
        'openai'     => 'https://api.openai.com/v1/chat/completions',
        'openrouter' => 'https://openrouter.ai/api/v1/chat/completions',
        'gemini'     => 'https://generativelanguage.googleapis.com/v1beta/openai/chat/completions',
        'custom'     => $cfg['api_url'],
        default      => 'https://api.moonshot.ai/v1/chat/completions',
    };
    $payload = json_encode([
        'model' => $cfg['model'], 'max_tokens' => $maxTokens, 'temperature' => 0.5,
        'messages' => [['role' => 'system', 'content' => $system], ['role' => 'user', 'content' => $user]],
    ], JSON_UNESCAPED_UNICODE);
    $headers = ['Content-Type: application/json', 'Authorization: Bearer ' . $cfg['api_key']];
    if ($cfg['provider'] === 'openrouter') {
        $headers[] = 'HTTP-Referer: https://github.com/iandresnovaf/vigia';
        $headers[] = 'X-Title: VigIA';
    }
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload, CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 30, CURLOPT_SSL_VERIFYPEER => Config::CURL_SSL_VERIFY,
    ]);
    $body = curl_exec($ch); $errno = curl_errno($ch); $err = curl_error($ch); curl_close($ch);
    if ($errno) throw new RuntimeException('cURL error: ' . $err);
    $json = json_decode($body, true);
    if (!isset($json['choices'][0]['message']['content'])) {
        throw new RuntimeException($json['error']['message'] ?? substr((string)$body, 0, 200));
    }
    return trim($json['choices'][0]['message']['content']);
}

const AGENTES = [
    'aire'      => 'Agente Ambiental',
    'incendios' => 'Agente Ambiental',
    'clima'     => 'Agente Ambiental',
    'seguridad' => 'Agente Seguridad',
    'prediccion'=> 'Agente Predictor',
    'simulacion'=> 'Agente Simulador',
    'general'   => 'Agente VigIA',
];

function agentSystem(string $dominio): string
{
    return match ($dominio) {
        'aire', 'incendios', 'clima' =>
            'Eres el Agente Ambiental de VigIA, experto en calidad del aire, incendios y clima en Colombia. ' .
            'Respondes a ciudadanos con claridad, contextualizando riesgos para la salud y recomendaciones preventivas. ' .
            'Máximo 4 oraciones, en español.',
        'seguridad' =>
            'Eres el Agente Seguridad de VigIA, experto en seguridad ciudadana en Colombia. ' .
            'Analizas datos de hurtos, homicidios y lesiones (SIEDCO) y, cuando existen, cruzas las ' .
            'detecciones en tiempo real del modelo de visión (hurto/riesgo por cámara) con esa estadística oficial. ' .
            'Das contexto local y recomendaciones de prevención. Máximo 4 oraciones, en español.',
        'prediccion' =>
            'Eres el Agente Predictor de VigIA. Analizas tendencias históricas de datos ambientales y de seguridad ' .
            'en Colombia y proyectas escenarios futuros indicando nivel de confianza. Máximo 4 oraciones, en español.',
        'simulacion' =>
            'Eres el Agente Simulador de VigIA. Cuando el ciudadano pregunta "¿qué pasaría si…?" analizas escenarios ' .
            'hipotéticos sobre calidad del aire, seguridad o clima en Colombia con base en patrones conocidos. ' .
            'Máximo 4 oraciones, en español.',
        default =>
            'Eres el Agente VigIA, asistente ciudadano de monitoreo ambiental y seguridad de Colombia. ' .
            'Ayudas a interpretar datos de calidad del aire, seguridad, incendios y clima de forma amigable. ' .
            'Máximo 4 oraciones, en español.',
    };
}

try {
    $cfg = chatCfg();
    if (empty($cfg['api_key'])) {
        echo json_encode(['ok' => false, 'error' => 'LLM sin configurar. Haz clic en ⚙️ IA para agregar tu API key.']);
        exit;
    }

    $body      = json_decode(file_get_contents('php://input'), true) ?? [];
    $mensaje   = substr(strip_tags((string)($body['mensaje']   ?? '')), 0, 500);
    $municipio = substr(strip_tags((string)($body['municipio'] ?? '')), 0, 100);
    $tema      = preg_replace('/[^a-z]/', '', (string)($body['tema'] ?? 'aire'));
    $contexto  = array_slice((array)($body['contexto'] ?? []), 0, 10);

    if ($mensaje === '') {
        echo json_encode(['ok' => false, 'error' => 'Mensaje vacío']); exit;
    }

    // Paso 1 — Agente Clasificador
    $sysClasif  = 'Eres el Agente Clasificador de VigIA (monitoreo ambiental y seguridad, Colombia). ' .
                  'Clasifica la intención del mensaje ciudadano. Responde SOLO con JSON válido, sin markdown: ' .
                  '{"dominio":"aire|seguridad|incendios|clima|prediccion|simulacion|general",' .
                  '"necesita_datos":true|false,"parametro":"pm25|pm10|hurtos|temperatura|..."}';
    $userClasif = "Municipio: $municipio\nTema actual: $tema\nPregunta: $mensaje";
    $rawClasif  = chatCall($cfg, $sysClasif, $userClasif, 100);
    $clasif     = json_decode($rawClasif, true) ?: ['dominio' => $tema, 'necesita_datos' => true];
    $dominio    = preg_replace('/[^a-z]/', '', (string)($clasif['dominio'] ?? $tema));
    $necesita   = (bool)($clasif['necesita_datos'] ?? false);

    // Paso 2 — Fetch datos si el agente los necesita
    $datosCtx    = '';
    $datosUsados = 0;
    $procedencia = null;
    if ($necesita && isset(Config::DATASETS[$dominio])) {
        try {
            $dbTok = Db::conn()->query("SELECT cfg_val FROM llm_config WHERE cfg_key='socrata_token'")->fetchColumn();
            if ($dbTok) SocrataClient::$overrideToken = (string)$dbTok;
            $ds  = Config::DATASETS[$dominio];
            $src = $ds['sources'][0] ?? null;
            if ($src && ($src['campo_municipio'] ?? '')) {
                $params = ['$order' => $src['campo_fecha'] . ' DESC', '$limit' => 20];
                if ($municipio !== '') {
                    $safe = str_replace("'", "''", strtoupper($municipio));
                    $params['$where'] = "upper({$src['campo_municipio']})='{$safe}'";
                }
                $filas       = SocrataClient::query($src['id'], $params);
                $datosUsados = count($filas);
                if ($datosUsados > 0) {
                    $datosCtx = "\n\nDatos recientes ($datosUsados registros): " .
                                json_encode(array_slice($filas, 0, 15), JSON_UNESCAPED_UNICODE);
                    $ultima = substr((string)($filas[0][$src['campo_fecha']] ?? ''), 0, 10);
                    $procedencia = [
                        'fuente'        => $src['label'] ?? '',
                        'dataset_id'    => $src['id'] ?? '',
                        'municipio'     => $municipio,
                        'registros'     => $datosUsados,
                        'ultima_fecha'  => $ultima,
                        'consultado_en' => date('Y-m-d H:i'),
                    ];
                }
            }
        } catch (Throwable) {}
    }

    // Paso 2b — Para seguridad, adjuntar eventos del modelo de visión (tiempo real) + su cruce.
    $eventosCtx = '';
    if ($dominio === 'seguridad') {
        try {
            $sql = "SELECT captured_at, municipio, tipo_comportamiento, nivel_alerta, confianza, ai_result_json
                    FROM dron_eventos_seguridad ";
            $args = [];
            if ($municipio !== '') {
                $sql .= "WHERE upper(municipio) = upper(:m) ";
                $args[':m'] = $municipio;
            }
            $sql .= "ORDER BY captured_at DESC LIMIT 5";
            $st = Db::conn()->prepare($sql);
            $st->execute($args);
            $evs = $st->fetchAll(PDO::FETCH_ASSOC);
            if ($evs) {
                $resumen = array_map(function ($e) {
                    $j     = json_decode((string)($e['ai_result_json'] ?? ''), true);
                    $cruce = is_array($j) && isset($j['cruce']['narrativa']) ? $j['cruce']['narrativa'] : '';
                    return [
                        'fecha'     => substr((string)$e['captured_at'], 0, 16),
                        'municipio' => $e['municipio'],
                        'tipo'      => $e['tipo_comportamiento'],
                        'nivel'     => $e['nivel_alerta'],
                        'cruce'     => $cruce,
                    ];
                }, $evs);
                $eventosCtx = "\n\nDetecciones recientes del modelo de visión (tiempo real) y su cruce con SIEDCO: " .
                              json_encode($resumen, JSON_UNESCAPED_UNICODE);
            }
        } catch (Throwable) {}
    }

    // Paso 3 — Agente Especialista
    $historial = '';
    foreach (array_slice($contexto, -4) as $msg) {
        $role       = ($msg['role'] ?? 'user') === 'user' ? 'Ciudadano' : 'VigIA';
        $historial .= $role . ': ' . substr(strip_tags((string)($msg['content'] ?? '')), 0, 200) . "\n";
    }
    $sysEspecialista  = agentSystem($dominio);
    $userEspecialista = ($historial ? "Conversación previa:\n$historial\n" : '') .
                        "Municipio: $municipio\nPregunta: $mensaje" . $datosCtx . $eventosCtx;
    $respuesta = chatCall($cfg, $sysEspecialista, $userEspecialista, 500);

    echo json_encode([
        'ok'           => true,
        'respuesta'    => $respuesta,
        'agente'       => AGENTES[$dominio] ?? 'Agente VigIA',
        'datos_usados' => $datosUsados,
        'dominio'      => $dominio,
        'procedencia'  => $procedencia,
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
