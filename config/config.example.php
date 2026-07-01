<?php
/**
 * Plantilla de configuración SIN secretos.
 * Copiala a src/Config.php y ajusta credenciales, token y URL del dron.
 * (src/Config.php ya viene con valores por defecto de XAMPP; edítalo directamente si prefieres.)
 */
return [
    'db' => [
        'host' => '127.0.0.1',
        'port' => '3306',
        'name' => 'dashboard_entorno',
        'user' => 'root',
        'pass' => '',        // <-- tu contraseña de MySQL
    ],
    'socrata_app_token' => '', // opcional: https://dev.socrata.com/register
    'dron_url'          => '', // URL del dron que devuelve JSON (se consulta cada hora)
    'ai_service_url'    => '', // microservicio de IA (fase 2)
];
