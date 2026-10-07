-- ==============================================================================
-- ACTUALIZACIÓN ERP: MAK-PC ENTERPRISES S.A.C.
-- Módulos: Inventario (Kardex y Series) y Finanzas (Caja y Movimientos)
-- ==============================================================================

USE makpc_enterprises_db;

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Control de Series y Trazabilidad
CREATE TABLE IF NOT EXISTS productos_series (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_producto INT UNSIGNED NOT NULL,
    numero_serie VARCHAR(100) NOT NULL UNIQUE,
    estado ENUM('DISPONIBLE', 'VENDIDO', 'USADO_TALLER', 'GARANTIA') NOT NULL DEFAULT 'DISPONIBLE',
    referencia_salida VARCHAR(100) NULL, -- ej: "ORDEN-504" o "PEDIDO-200"
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_series_producto FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 2. Movimientos del Kardex General
CREATE TABLE IF NOT EXISTS kardex_movimientos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_producto INT UNSIGNED NOT NULL,
    tipo_movimiento ENUM('ENTRADA', 'SALIDA', 'AJUSTE') NOT NULL,
    cantidad INT NOT NULL,
    origen_destino VARCHAR(150) NOT NULL,
    usuario_id INT UNSIGNED NOT NULL,
    notas TEXT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_kardex_prod FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 3. Módulo de Finanzas y Caja
CREATE TABLE IF NOT EXISTS caja_diaria (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    estado ENUM('ABIERTA', 'CERRADA') NOT NULL DEFAULT 'ABIERTA',
    usuario_apertura INT UNSIGNED NOT NULL,
    usuario_cierre INT UNSIGNED NULL,
    monto_apertura DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    monto_cierre_calculado DECIMAL(10,2) NULL,
    monto_cierre_real DECIMAL(10,2) NULL,
    diferencia DECIMAL(10,2) NULL,
    fecha_apertura DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_cierre DATETIME NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS caja_movimientos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_caja INT UNSIGNED NOT NULL,
    tipo ENUM('INGRESO', 'EGRESO') NOT NULL,
    metodo_pago ENUM('EFECTIVO', 'YAPE', 'PLIN', 'TRANSFERENCIA', 'TARJETA') NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    concepto VARCHAR(255) NOT NULL,
    usuario_id INT UNSIGNED NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_caja_mov_caja FOREIGN KEY (id_caja) REFERENCES caja_diaria(id) ON DELETE CASCADE
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;
