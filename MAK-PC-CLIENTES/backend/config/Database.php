<?php
declare(strict_types=1);

namespace Config;

use PDO;
use PDOException;

/**
 * Clase Database - Manejador de Conexión PDO Singleton
 * Compatible con MySQL 8.0+ / MariaDB
 */
class Database {
    private static ?PDO $instance = null;

    private static string $host = '';
    private static string $dbName = '';
    private static string $user = '';
    private static string $pass = '';
    private static string $charset = 'utf8mb4';
    private static int $port = 3306;

    /**
     * Permite sobreescribir las credenciales en tiempo de ejecución o desde variables de entorno
     */
    public static function configure(
        ?string $host = null,
        ?string $dbName = null,
        ?string $user = null,
        ?string $pass = null,
        ?int $port = null
    ): void {
        if ($host !== null) self::$host = $host;
        if ($dbName !== null) self::$dbName = $dbName;
        if ($user !== null) self::$user = $user;
        if ($pass !== null) self::$pass = $pass;
        if ($port !== null) self::$port = $port;
        self::$instance = null; // Reiniciar instancia si se reconfigura
    }

    /**
     * Obtiene la instancia activa de PDO con configuración optimizada
     */
    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $envPath = __DIR__ . '/../../../.env';
            if (file_exists($envPath) && is_readable($envPath)) {
                $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                $env = [];
                foreach ($lines as $line) {
                    $line = trim($line);
                    if ($line === '' || str_starts_with($line, '#') || str_starts_with($line, ';')) continue;
                    if (strpos($line, '=') !== false) {
                        list($k, $v) = explode('=', $line, 2);
                        $env[trim($k)] = trim($v, " \t\n\r\0\x0B\"'");
                    }
                }
                if (!empty($env)) {
                    self::$host = $env['DB_HOST'] ?? getenv('DB_HOST') ?: (self::$host ?: 'localhost');
                    self::$port = isset($env['DB_PORT']) ? (int)$env['DB_PORT'] : (getenv('DB_PORT') ? (int)getenv('DB_PORT') : self::$port);
                    self::$dbName = $env['DB_NAME'] ?? getenv('DB_NAME') ?: (self::$dbName ?: 'makpc_enterprises_db');
                    self::$user = $env['DB_USER'] ?? getenv('DB_USER') ?: (self::$user ?: 'root');
                    self::$pass = isset($env['DB_PASS']) ? $env['DB_PASS'] : (getenv('DB_PASS') !== false ? getenv('DB_PASS') : self::$pass);
                }
            }

            if (empty(self::$host)) self::$host = getenv('DB_HOST') ?: 'localhost';
            if (empty(self::$dbName)) self::$dbName = getenv('DB_NAME') ?: 'makpc_enterprises_db';
            if (empty(self::$user)) self::$user = getenv('DB_USER') ?: 'root';


            $dsn = sprintf(
                "mysql:host=%s;port=%d;dbname=%s;charset=%s",
                self::$host,
                self::$port,
                self::$dbName,
                self::$charset
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ];

            try {
                self::$instance = new PDO($dsn, self::$user, self::$pass, $options);
            } catch (PDOException $e) {
                // No exponer $e->getMessage() en producción
                Response::json(500, false, null, "Error de conexión a la base de datos.");
                exit;
            }
        }

        return self::$instance;
    }
}

