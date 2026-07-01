<?php
/** Configuración del LLM — lee y guarda en tabla llm_config de MySQL. */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../src/Config.php';
require_once __DIR__ . '/../../src/Db.php';

function ensureLlmTable(PDO $pdo): void
{
    $pdo->exec("CREATE TABLE IF NOT EXISTS llm_config (
        cfg_key    VARCHAR(64) PRIMARY KEY,
        cfg_val    TEXT        NOT NULL,
        updated_at TIMESTAMP   DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

try {
    $pdo = Db::conn();
    ensureLlmTable($pdo);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $body    = json_decode(file_get_contents('php://input'), true) ?? [];
        $allowed = ['provider', 'model', 'api_key', 'api_url'];
        $stmt    = $pdo->prepare(
            "INSERT INTO llm_config (cfg_key, cfg_val) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE cfg_val = VALUES(cfg_val), updated_at = CURRENT_TIMESTAMP"
        );
        foreach ($allowed as $k) {
            if (array_key_exists($k, $body)) {
                $val = trim((string) $body[$k]);
                if ($k === 'api_key' && $val === '') {
                    continue; // no sobreescribir key vacía
                }
                $stmt->execute([$k, $val]);
            }
        }
        echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
    } else {
        $rows = $pdo->query("SELECT cfg_key, cfg_val FROM llm_config")
                    ->fetchAll(PDO::FETCH_KEY_PAIR);
        echo json_encode([
            'ok'     => true,
            'config' => [
                'provider' => $rows['provider'] ?? 'kimi',
                'model'    => $rows['model']    ?? 'moonshot-v1-8k',
                'api_url'  => $rows['api_url']  ?? '',
                'has_key'  => !empty($rows['api_key']),
            ],
        ], JSON_UNESCAPED_UNICODE);
    }
} catch (Throwable $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
