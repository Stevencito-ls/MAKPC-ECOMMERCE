<?php
declare(strict_types=1);

require_once __DIR__ . '/../backend/config/Database.php';

use Config\Database;

try {
    $pdo = Database::getConnection();

    // Check if columns exist
    $cols = $pdo->query("SHOW COLUMNS FROM ordenes_servicio")->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('costo_estimado', $cols, true)) {
        $pdo->exec("ALTER TABLE ordenes_servicio ADD COLUMN costo_estimado DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER observaciones_esteticas;");
        echo "Columna costo_estimado agregada.\n";
    }

    if (!in_array('monto_adelanto', $cols, true)) {
        $pdo->exec("ALTER TABLE ordenes_servicio ADD COLUMN monto_adelanto DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER costo_estimado;");
        echo "Columna monto_adelanto agregada.\n";
    }

    echo "Estructura de ordenes_servicio actualizada correctamente.\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
