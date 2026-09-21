<?php
declare(strict_types=1);

require_once __DIR__ . '/../backend/config/Database.php';

use Config\Database;

try {
    $pdo = Database::getConnection();

    echo "Iniciando limpieza de base de datos makpc_taller_db...\n";

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    $pdo->exec("TRUNCATE TABLE recibos;");
    $pdo->exec("TRUNCATE TABLE ordenes_servicio;");
    $pdo->exec("TRUNCATE TABLE clientes;");
    
    $pdo->exec("ALTER TABLE recibos AUTO_INCREMENT = 1;");
    $pdo->exec("ALTER TABLE ordenes_servicio AUTO_INCREMENT = 1;");
    $pdo->exec("ALTER TABLE clientes AUTO_INCREMENT = 1;");

    $stmt = $pdo->prepare("UPDATE configuracion_empresa SET ultimo_correlativo_recibo = 400 WHERE id = 1;");
    $stmt->execute();

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

    // Verificar conteos
    $cntClientes = (int)$pdo->query("SELECT COUNT(*) FROM clientes")->fetchColumn();
    $cntOrdenes = (int)$pdo->query("SELECT COUNT(*) FROM ordenes_servicio")->fetchColumn();
    $cntRecibos = (int)$pdo->query("SELECT COUNT(*) FROM recibos")->fetchColumn();
    $cntUsuarios = (int)$pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
    $correlativo = (int)$pdo->query("SELECT ultimo_correlativo_recibo FROM configuracion_empresa WHERE id = 1")->fetchColumn();

    echo "----------------------------------------\n";
    echo "RESULTADOS DE LA LIMPIEZA:\n";
    echo "  - Clientes: $cntClientes\n";
    echo "  - Ordenes de Servicio: $cntOrdenes\n";
    echo "  - Recibos: $cntRecibos\n";
    echo "  - Usuarios (Intactos): $cntUsuarios\n";
    echo "  - Último Correlativo de Recibo: $correlativo (El próximo será Nº " . str_pad((string)($correlativo + 1), 6, '0', STR_PAD_LEFT) . ")\n";
    echo "----------------------------------------\n";
    echo "¡Base de datos lista para operar desde cero!\n";

} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
