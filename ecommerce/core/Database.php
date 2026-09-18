<?php
/**
 * Singleton PDO Database Connection
 */
class Database {
    /** @var Database|null */
    private static $instance = null;
    /** @var PDO */
    private $pdo;
    private function __construct() {
        $port = defined('DB_PORT') ? DB_PORT : '3306';
        $dsn = "mysql:host=" . DB_HOST . ";port=" . $port . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
        ];
        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Redirigir al instalador si la BD no existe
            if (strpos($e->getMessage(), 'Unknown database') !== false) {
                header('Location: ' . BASE_URL . 'install.php');
                exit;
            }
            die('Error de conexión: ' . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    // Prevenir clonación y deserialización
    private function __clone() {}
    public function __wakeup() { throw new Exception("Cannot unserialize singleton"); }
}
