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

    private static string $host = '127.0.0.1';
    private static string $dbName = 'makpc_enterprises_db';
    private static string $user = 'root';
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
        $envPath = __DIR__ . '/../../.env';
        if (file_exists($envPath)) {
            $env = parse_ini_file($envPath);
            if ($env) {
                self::$host = $env['DB_HOST'] ?? self::$host;
                self::$port = isset($env['DB_PORT']) ? (int)$env['DB_PORT'] : self::$port;
                self::$dbName = $env['DB_NAME'] ?? self::$dbName;
                self::$user = $env['DB_USER'] ?? self::$user;
                self::$pass = isset($env['DB_PASS']) ? $env['DB_PASS'] : self::$pass;
            }
        }

        if (self::$instance === null) {
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
                Response::json(500, false, null, "Error de conexión a la base de datos: " . $e->getMessage());
                exit;
            }
        }

        return self::$instance;
    }
}

