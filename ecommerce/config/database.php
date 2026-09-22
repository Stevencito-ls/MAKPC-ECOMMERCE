<?php
/**
 * Configuración de conexión a base de datos
 * Con soporte para variables de entorno (Docker, AWS, VPS, Cloud Run)
 */

$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    $env = parse_ini_file($envPath);
    if ($env) {
        foreach ($env as $k => $v) {
            putenv("$k=$v");
        }
    }
}

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'makpc_enterprises_db');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');
