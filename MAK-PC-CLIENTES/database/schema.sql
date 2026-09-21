-- ==============================================================================
-- SISTEMA DE ATENCIÓN AL CLIENTE Y CONTROL DE TALLER
-- Empresa: MAK-PC Enterprises S.A.C. (RUC: 20409456520)
-- Ubicación: Tumbes, Perú
-- Módulo: Gestión de Taller, Mostrador y Emisión de Recibos A5
-- Motor de Base de Datos: MySQL 8.0+ (InnoDB, UTF-8 MB4)
-- ==============================================================================

-- 1. CREACIÓN DE LA BASE DE DATOS
CREATE DATABASE IF NOT EXISTS makpc_taller_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE makpc_taller_db;

-- Desactivar temporalmente revisión de llaves foráneas para reinicio limpio si aplica
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS recibos;
DROP TABLE IF EXISTS ordenes_servicio;
DROP TABLE IF EXISTS clientes;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS configuracion_empresa;

SET FOREIGN_KEY_CHECKS = 1;

-- ==============================================================================
-- 2. TABLA: configuracion_empresa
-- Almacena la parametrización institucional, datos para el recibo A5 y correlativo
-- ==============================================================================
CREATE TABLE configuracion_empresa (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ruc CHAR(11) NOT NULL DEFAULT '20409456520',
    razon_social VARCHAR(150) NOT NULL DEFAULT 'MAK-PC ENTERPRISES S.A.C.',
    nombre_comercial VARCHAR(100) NOT NULL DEFAULT 'MAK-PC Soporte Tecnológico',
    telefono VARCHAR(30) NOT NULL DEFAULT '960 702 605',
    email VARCHAR(100) DEFAULT 'soporte@makpc.com.pe',
    direccion VARCHAR(255) NOT NULL DEFAULT 'Tumbes, Perú',
    dias_garantia_defecto INT UNSIGNED NOT NULL DEFAULT 90,
    clausula_garantia TEXT NOT NULL,
    ultimo_correlativo_recibo INT UNSIGNED NOT NULL DEFAULT 400,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ==============================================================================
-- 3. TABLA: usuarios
-- Gestiona al personal de recepción, técnicos de laboratorio y administradores
-- ==============================================================================
CREATE TABLE usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(120) NOT NULL,
    dni CHAR(8) UNIQUE NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('ADMIN', 'RECEPCION', 'TECNICO') NOT NULL DEFAULT 'TECNICO',
    telefono VARCHAR(20) NULL,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_usuarios_rol (rol),
    INDEX idx_usuarios_email (email)
) ENGINE=InnoDB;

-- ==============================================================================
-- 4. TABLA: clientes
-- Registro centralizado de clientes (personas naturales o jurídicas)
-- ==============================================================================
CREATE TABLE clientes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tipo_documento ENUM('DNI', 'RUC', 'CE', 'PASAPORTE') NOT NULL DEFAULT 'DNI',
    numero_documento VARCHAR(15) NOT NULL UNIQUE,
    nombres_razon_social VARCHAR(180) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    telefono_secundario VARCHAR(20) NULL,
    email VARCHAR(100) NULL,
    direccion VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_clientes_documento (numero_documento),
    INDEX idx_clientes_telefono (telefono),
    INDEX idx_clientes_nombres (nombres_razon_social)
) ENGINE=InnoDB;

-- ==============================================================================
-- 5. TABLA: ordenes_servicio
-- Control del flujo de laboratorio: recepción, diagnóstico, reparación y entrega
-- ==============================================================================
CREATE TABLE ordenes_servicio (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    codigo_orden VARCHAR(20) NOT NULL UNIQUE, -- Ej: ORD-2026-0001
    cliente_id INT UNSIGNED NOT NULL,
    recepcionista_id INT UNSIGNED NOT NULL,
    tecnico_id INT UNSIGNED NULL,
    
    -- Información del equipo ingresado
    tipo_equipo ENUM('LAPTOP', 'PC_ESCRITORIO', 'ALL_IN_ONE', 'IMPRESORA', 'MONITOR', 'SERVIDOR', 'OTRO') NOT NULL,
    marca VARCHAR(60) NOT NULL,
    modelo VARCHAR(80) NOT NULL,
    numero_serie VARCHAR(80) NULL,
    accesorios_dejados TEXT NULL, -- Ej: Cargador original Lenovo 65W, Cable HDMI, Funda negra
    password_equipo VARCHAR(100) NULL, -- Clave de usuario/BIOS para pruebas
    
    -- Estado técnico y descripciones
    motivo_ingreso TEXT NOT NULL, -- Falla reportada por el cliente
    diagnostico_tecnico TEXT NULL,
    solucion_tecnica TEXT NULL,
    observaciones_esteticas TEXT NULL, -- Rayones, golpes preexistentes, etc.
    
    estado ENUM(
        'RECEPCIONADO',
        'EN_DIAGNOSTICO',
        'ESPERA_APROBACION',
        'ESPERA_REPUESTOS',
        'EN_REPARACION',
        'REPARADO',
        'NO_REPARABLE',
        'ENTREGADO',
        'CANCELADO'
    ) NOT NULL DEFAULT 'RECEPCIONADO',
    
    prioridad ENUM('BAJA', 'MEDIA', 'ALTA', 'URGENTE') NOT NULL DEFAULT 'MEDIA',
    
    -- Trazabilidad de tiempos
    fecha_ingreso DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_diagnostico DATETIME NULL,
    fecha_reparacion DATETIME NULL,
    fecha_entrega DATETIME NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Llaves Foráneas
    CONSTRAINT fk_ordenes_cliente FOREIGN KEY (cliente_id) 
        REFERENCES clientes (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_ordenes_recepcionista FOREIGN KEY (recepcionista_id) 
        REFERENCES usuarios (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_ordenes_tecnico FOREIGN KEY (tecnico_id) 
        REFERENCES usuarios (id) ON DELETE SET NULL ON UPDATE CASCADE,
        
    -- Índices para búsqueda rápida en mostrador
    INDEX idx_ordenes_codigo (codigo_orden),
    INDEX idx_ordenes_estado (estado),
    INDEX idx_ordenes_cliente (cliente_id),
    INDEX idx_ordenes_tecnico (tecnico_id),
    INDEX idx_ordenes_fecha_ingreso (fecha_ingreso)
) ENGINE=InnoDB;

-- ==============================================================================
-- 6. TABLA: recibos
-- Comprobante físico de servicio (Talonario A5 idéntico al corporativo)
-- Soporta correlativo de 6 dígitos (Nº 000401) y desglose financiero
-- ==============================================================================
CREATE TABLE recibos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    numero_recibo CHAR(6) NOT NULL UNIQUE, -- Ej: '000401'
    numero_correlativo_int INT UNSIGNED NOT NULL UNIQUE, -- Valor numérico base para ordenamiento (401)
    
    orden_servicio_id INT UNSIGNED NOT NULL,
    cliente_id INT UNSIGNED NOT NULL,
    usuario_emisor_id INT UNSIGNED NOT NULL,
    
    -- Contenido central del comprobante
    concepto TEXT NOT NULL, -- "POR CONCEPTO DE:"
    
    -- Desglose Financiero (en Soles S/.)
    monto_total DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    monto_letras VARCHAR(255) NOT NULL, -- Texto autogenerado en FASE 3
    monto_a_cuenta DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    
    -- Saldo calculado de forma persistente y determinista
    monto_saldo DECIMAL(10, 2) GENERATED ALWAYS AS (monto_total - monto_a_cuenta) STORED,
    
    metodo_pago ENUM('EFECTIVO', 'YAPE', 'PLIN', 'TRANSFERENCIA', 'TARJETA', 'MIXTO') NOT NULL DEFAULT 'EFECTIVO',
    estado_pago ENUM('PENDIENTE', 'PAGO_PARCIAL', 'CANCELADO_TOTAL') NOT NULL DEFAULT 'CANCELADO_TOTAL',
    
    -- Cláusulas de garantía de 90 días (Ley 29571)
    fecha_emision DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    dias_garantia INT UNSIGNED NOT NULL DEFAULT 90,
    fecha_vencimiento_garantia DATE NOT NULL,
    clausula_legal TEXT NOT NULL,
    
    -- Control de anulación/reimpresión
    anulado BOOLEAN NOT NULL DEFAULT FALSE,
    motivo_anulacion VARCHAR(255) NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Llaves Foráneas
    CONSTRAINT fk_recibos_orden FOREIGN KEY (orden_servicio_id) 
        REFERENCES ordenes_servicio (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_recibos_cliente FOREIGN KEY (cliente_id) 
        REFERENCES clientes (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_recibos_usuario FOREIGN KEY (usuario_emisor_id) 
        REFERENCES usuarios (id) ON DELETE RESTRICT ON UPDATE CASCADE,
        
    -- Índices
    INDEX idx_recibos_numero (numero_recibo),
    INDEX idx_recibos_orden (orden_servicio_id),
    INDEX idx_recibos_cliente (cliente_id),
    INDEX idx_recibos_fecha (fecha_emision)
) ENGINE=InnoDB;

-- ==============================================================================
-- 7. VISTA PARA REPORTES Y MOSTRADOR: v_recibos_completos
-- Unifica datos para alimentar directamente el backend y la plantilla A5
-- ==============================================================================
CREATE OR REPLACE VIEW v_recibos_completos AS
SELECT 
    r.id AS recibo_id,
    r.numero_recibo,
    r.fecha_emision,
    DATE_FORMAT(r.fecha_emision, '%d/%m/%Y') AS fecha_emision_formateada,
    DATE_FORMAT(r.fecha_emision, '%H:%i') AS hora_emision_formateada,
    r.fecha_vencimiento_garantia,
    DATE_FORMAT(r.fecha_vencimiento_garantia, '%d/%m/%Y') AS fecha_garantia_formateada,
    r.dias_garantia,
    r.concepto,
    r.monto_total,
    r.monto_letras,
    r.monto_a_cuenta,
    r.monto_saldo,
    r.metodo_pago,
    r.estado_pago,
    r.clausula_legal,
    r.anulado,
    
    -- Datos del Cliente
    c.id AS cliente_id,
    c.tipo_documento,
    c.numero_documento,
    c.nombres_razon_social AS cliente_nombre,
    c.telefono AS cliente_telefono,
    c.direccion AS cliente_direccion,
    
    -- Datos de la Orden de Servicio y Equipo
    o.id AS orden_id,
    o.codigo_orden,
    o.tipo_equipo,
    o.marca,
    o.modelo,
    o.numero_serie,
    o.accesorios_dejados,
    o.solucion_tecnica,
    
    -- Emisor del Recibo
    u.id AS usuario_id,
    u.nombre_completo AS emisor_nombre,
    
    -- Datos Institucionales
    cfg.ruc AS empresa_ruc,
    cfg.razon_social AS empresa_razon_social,
    cfg.telefono AS empresa_telefono,
    cfg.direccion AS empresa_direccion
FROM recibos r
INNER JOIN clientes c ON r.cliente_id = c.id
INNER JOIN ordenes_servicio o ON r.orden_servicio_id = o.id
INNER JOIN usuarios u ON r.usuario_emisor_id = u.id
CROSS JOIN configuracion_empresa cfg
ORDER BY r.id DESC LIMIT 1000;

-- ==============================================================================
-- 8. POBLADO DE DATOS INICIALES (SEED DATA)
-- ==============================================================================

-- 8.1 Configuración institucional de MAK-PC
INSERT INTO configuracion_empresa (
    id, ruc, razon_social, nombre_comercial, telefono, email, direccion,
    dias_garantia_defecto, clausula_garantia, ultimo_correlativo_recibo
) VALUES (
    1,
    '20409456520',
    'MAK-PC ENTERPRISES S.A.C.',
    'MAK-PC Soporte Tecnológico',
    '960 702 605',
    'contacto@makpc.com.pe',
    'Tumbes, Perú',
    90,
    'La presente garantía cubre exclusivamente el servicio técnico realizado y los repuestos suministrados por un periodo de 90 días calendario a partir de la fecha de entrega, conforme al Código de Protección y Defensa del Consumidor (Ley Nº 29571). No cubre daños por fluctuaciones eléctricas, derrame de líquidos, manipulación no autorizada o golpes posteriores.',
    400
) ON DUPLICATE KEY UPDATE id=1;

-- 8.2 Usuarios del sistema (Password inicial de prueba: Admin123* -> hash bcrypt simulado)
INSERT INTO usuarios (id, nombre_completo, dni, email, password_hash, rol, telefono, activo) VALUES
(1, 'Administrador MAK-PC', '45892134', 'admin@makpc.pe', '$2a$12$e8Yy8s7j9eC2e9W9kL9x/.u3hX2tK3s9R8wV1aB4cD5eF6g7h8i9j', 'ADMIN', '960702605', TRUE),
(2, 'Carlos Técnico', '71234567', 'tecnico@makpc.pe', '$2a$12$e8Yy8s7j9eC2e9W9kL9x/.u3hX2tK3s9R8wV1aB4cD5eF6g7h8i9j', 'TECNICO', '972111222', TRUE),
(3, 'Ana Recepción', '73456789', 'recepcion@makpc.pe', '$2a$12$e8Yy8s7j9eC2e9W9kL9x/.u3hX2tK3s9R8wV1aB4cD5eF6g7h8i9j', 'RECEPCION', '973333444', TRUE)
ON DUPLICATE KEY UPDATE id=id;

-- 8.3 Clientes de prueba
INSERT INTO clientes (id, tipo_documento, numero_documento, nombres_razon_social, telefono, email, direccion) VALUES
(1, 'DNI', '70852963', 'Juan Pérez Morales', '972888999', 'juan.perez@gmail.com', 'Av. Tumbes 450, Tumbes'),
(2, 'RUC', '20123456789', 'INVERSIONES DEL NORTE E.I.R.L.', '965123456', 'contacto@inversionesnorte.pe', 'Jr. Bolívar 120, Tumbes')
ON DUPLICATE KEY UPDATE id=id;

-- 8.4 Orden de Servicio de demostración
INSERT INTO ordenes_servicio (
    id, codigo_orden, cliente_id, recepcionista_id, tecnico_id,
    tipo_equipo, marca, modelo, numero_serie, accesorios_dejados,
    motivo_ingreso, diagnostico_tecnico, solucion_tecnica,
    estado, prioridad, fecha_ingreso, fecha_diagnostico, fecha_reparacion, fecha_entrega
) VALUES (
    1,
    'ORD-2026-0001',
    1,
    3,
    2,
    'LAPTOP',
    'Lenovo',
    'IdeaPad 3 15ITL6',
    'PF2XYZ89',
    'Cargador original Lenovo 65W, Mouse inalámbrico Logitech',
    'Equipo no enciende, recalentamiento previo y lentitud extrema.',
    'Cortocircuito en línea secundaria de alimentación y pasta térmica cristalizada.',
    'Reparación de placa madre nivel componente, cambio de pasta térmica Artic MX-4 y mantenimiento preventivo general.',
    'ENTREGADO',
    'ALTA',
    '2026-09-01 09:30:00',
    '2026-09-01 14:00:00',
    '2026-09-02 11:30:00',
    '2026-09-02 17:00:00'
) ON DUPLICATE KEY UPDATE id=id;

-- 8.5 Recibo emitido de demostración (Nº 000401)
INSERT INTO recibos (
    id, numero_recibo, numero_correlativo_int, orden_servicio_id, cliente_id, usuario_emisor_id,
    concepto, monto_total, monto_letras, monto_a_cuenta,
    metodo_pago, estado_pago, fecha_emision, dias_garantia, fecha_vencimiento_garantia, clausula_legal
) VALUES (
    1,
    '000401',
    401,
    1,
    1,
    3,
    'Mantenimiento preventivo general, reparación de línea de carga en mainboard y cambio de compuesto térmico para Laptop Lenovo IdeaPad 3.',
    180.00,
    'SON CIENTO OCHENTA CON 00/100 SOLES',
    100.00,
    'EFECTIVO',
    'PAGO_PARCIAL',
    '2026-09-02 17:00:00',
    90,
    '2026-12-01',
    'La presente garantía cubre exclusivamente el servicio técnico realizado y los repuestos suministrados por un periodo de 90 días calendario a partir de la fecha de entrega, conforme al Código de Protección y Defensa del Consumidor (Ley Nº 29571). No cubre daños por fluctuaciones eléctricas, derrame de líquidos, manipulación no autorizada o golpes posteriores.'
) ON DUPLICATE KEY UPDATE id=id;
