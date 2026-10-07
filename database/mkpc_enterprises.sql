-- ==============================================================================
-- BASE DE DATOS UNIFICADA: MAK-PC ENTERPRISES S.A.C.
-- Ecosistema Completo: Taller de Soporte, E-Commerce, PC Builder y Admin Panel
-- Motor: MySQL 8.0+ (InnoDB, UTF-8 MB4)
--
-- ESTRATEGIA DE COMPATIBILIDAD (OPCIÓN C - PROFESIONAL):
-- Se utiliza el esquema normalizado de MAK-PC-CLIENTES como base real.
-- Para mantener compatibilidad total con el E-commerce sin tocar el código PHP,
-- se emplean "Columnas Generadas Virtuales" (VIRTUAL GENERATED COLUMNS) y Vistas.
-- Esto permite que el E-commerce siga leyendo `id_cliente`, `nombres_apellidos`, 
-- etc., mientras MAK-PC-CLIENTES lee `id`, `nombres_razon_social`, etc.
-- Cero riesgo de ruptura, 100% interoperable.
-- ==============================================================================

CREATE DATABASE IF NOT EXISTS makpc_enterprises_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE makpc_enterprises_db;

SET FOREIGN_KEY_CHECKS = 0;

-- Tablas base de E-commerce
DROP TABLE IF EXISTS detalle_componentes;
DROP TABLE IF EXISTS tickets_soporte;
DROP TABLE IF EXISTS comprobantes_pago;
DROP TABLE IF EXISTS pedidos_tienda;
DROP TABLE IF EXISTS productos;
DROP TABLE IF EXISTS categorias_productos;

-- Vistas de compatibilidad
DROP VIEW IF EXISTS equipos;
DROP VIEW IF EXISTS v_recibos_completos;

-- Tablas base de MAK-PC-CLIENTES
DROP TABLE IF EXISTS recibos;
DROP TABLE IF EXISTS ordenes_servicio;
DROP TABLE IF EXISTS clientes;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS configuracion_empresa;

SET FOREIGN_KEY_CHECKS = 1;

-- ==============================================================================
-- 1. CONFIGURACIÓN INSTITUCIONAL
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
-- 2. USUARIOS (Soporte Dual: Admin Panel, Taller, E-commerce)
-- ==============================================================================
CREATE TABLE usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    nombre_completo VARCHAR(120) NOT NULL,
    dni CHAR(8) UNIQUE NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    usuario VARCHAR(50) NULL UNIQUE, -- Login E-commerce
    password_hash VARCHAR(255) NOT NULL,
    rol VARCHAR(20) NOT NULL DEFAULT 'TECNICO', -- VARCHAR en lugar de ENUM para soportar todos los roles
    telefono VARCHAR(20) NULL,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    creado_en TIMESTAMP GENERATED ALWAYS AS (created_at) VIRTUAL, -- Compatibilidad E-commerce
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_usuarios_rol (rol),
    INDEX idx_usuarios_email (email)
) ENGINE=InnoDB;

-- ==============================================================================
-- 3. CLIENTES UNIFICADOS
-- ==============================================================================
CREATE TABLE clientes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    
    tipo_documento ENUM('DNI', 'RUC', 'CE', 'PASAPORTE') NOT NULL DEFAULT 'DNI',
    numero_documento VARCHAR(15) NOT NULL UNIQUE,
    dni VARCHAR(15) GENERATED ALWAYS AS (numero_documento) VIRTUAL, -- Compatibilidad E-commerce
    
    nombres_razon_social VARCHAR(180) NOT NULL,
    nombres_apellidos VARCHAR(180) GENERATED ALWAYS AS (nombres_razon_social) VIRTUAL, -- Compatibilidad E-commerce
    
    telefono VARCHAR(20) NOT NULL,
    telefono_secundario VARCHAR(20) NULL,
    
    email VARCHAR(100) NULL,
    correo VARCHAR(100) GENERATED ALWAYS AS (email) VIRTUAL, -- Compatibilidad E-commerce
    
    direccion VARCHAR(255) NULL,
    notas_cliente TEXT NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    creado_en TIMESTAMP GENERATED ALWAYS AS (created_at) VIRTUAL, -- Compatibilidad E-commerce
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_clientes_documento (numero_documento),
    INDEX idx_clientes_telefono (telefono),
    INDEX idx_clientes_nombres (nombres_razon_social)
) ENGINE=InnoDB;

-- ==============================================================================
-- 4. ORDENES DE SERVICIO (Taller Unificado)
-- ==============================================================================
CREATE TABLE ordenes_servicio (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    
    codigo_orden VARCHAR(20) NOT NULL UNIQUE, 
    
    cliente_id INT UNSIGNED NOT NULL,
    id_cliente INT UNSIGNED GENERATED ALWAYS AS (cliente_id) VIRTUAL, -- Compatibilidad E-commerce
    
    recepcionista_id INT UNSIGNED NOT NULL,
    tecnico_id INT UNSIGNED NULL,
    
    -- El equipo embebido ahora actúa como id_equipo=id para E-commerce

    tipo_equipo ENUM('LAPTOP', 'PC_ESCRITORIO', 'ALL_IN_ONE', 'IMPRESORA', 'MONITOR', 'SERVIDOR', 'OTRO') NOT NULL,
    marca VARCHAR(60) NOT NULL,
    modelo VARCHAR(80) NOT NULL,
    numero_serie VARCHAR(80) NULL,
    accesorios_dejados TEXT NULL,
    password_equipo VARCHAR(100) NULL,
    
    motivo_ingreso TEXT NOT NULL, 
    diagnostico_tecnico TEXT NULL,
    solucion_tecnica TEXT NULL,
    observaciones_esteticas TEXT NULL, 
    
    estado VARCHAR(30) NOT NULL DEFAULT 'RECEPCIONADO', -- VARCHAR para abarcar ambos sistemas
    prioridad ENUM('BAJA', 'MEDIA', 'ALTA', 'URGENTE') NOT NULL DEFAULT 'MEDIA',
    
    costo_estimado DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    costo_total DECIMAL(10,2) GENERATED ALWAYS AS (costo_estimado) VIRTUAL, -- Compatibilidad E-commerce
    
    monto_adelanto DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    adelanto DECIMAL(10,2) GENERATED ALWAYS AS (monto_adelanto) VIRTUAL, -- Compatibilidad E-commerce
    
    fecha_ingreso DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_recepcion DATETIME GENERATED ALWAYS AS (fecha_ingreso) VIRTUAL, -- Compatibilidad E-commerce
    
    fecha_diagnostico DATETIME NULL,
    fecha_reparacion DATETIME NULL,
    fecha_entrega DATETIME NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_ordenes_cliente FOREIGN KEY (cliente_id) REFERENCES clientes (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_ordenes_recepcionista FOREIGN KEY (recepcionista_id) REFERENCES usuarios (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_ordenes_tecnico FOREIGN KEY (tecnico_id) REFERENCES usuarios (id) ON DELETE SET NULL ON UPDATE CASCADE,
        
    INDEX idx_ordenes_codigo (codigo_orden),
    INDEX idx_ordenes_estado (estado),
    INDEX idx_ordenes_cliente (cliente_id),
    INDEX idx_ordenes_fecha (fecha_ingreso)
) ENGINE=InnoDB;

-- ==============================================================================
-- 5. RECIBOS A5 (Taller Real)
-- ==============================================================================
CREATE TABLE recibos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    numero_recibo CHAR(6) NOT NULL UNIQUE, 
    numero_correlativo_int INT UNSIGNED NOT NULL UNIQUE, 
    
    orden_servicio_id INT UNSIGNED NOT NULL,
    cliente_id INT UNSIGNED NOT NULL,
    usuario_emisor_id INT UNSIGNED NOT NULL,
    
    concepto TEXT NOT NULL, 
    
    monto_total DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    monto_letras VARCHAR(255) NOT NULL, 
    monto_a_cuenta DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    
    monto_saldo DECIMAL(10, 2) GENERATED ALWAYS AS (monto_total - monto_a_cuenta) STORED,
    
    metodo_pago ENUM('EFECTIVO', 'YAPE', 'PLIN', 'TRANSFERENCIA', 'TARJETA', 'MIXTO') NOT NULL DEFAULT 'EFECTIVO',
    estado_pago ENUM('PENDIENTE', 'PAGO_PARCIAL', 'CANCELADO_TOTAL') NOT NULL DEFAULT 'CANCELADO_TOTAL',
    
    fecha_emision DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    dias_garantia INT UNSIGNED NOT NULL DEFAULT 90,
    fecha_vencimiento_garantia DATE NOT NULL,
    clausula_legal TEXT NOT NULL,
    
    anulado BOOLEAN NOT NULL DEFAULT FALSE,
    motivo_anulacion VARCHAR(255) NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_recibos_orden FOREIGN KEY (orden_servicio_id) REFERENCES ordenes_servicio (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_recibos_cliente FOREIGN KEY (cliente_id) REFERENCES clientes (id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_recibos_usuario FOREIGN KEY (usuario_emisor_id) REFERENCES usuarios (id) ON DELETE RESTRICT ON UPDATE CASCADE,
        
    INDEX idx_recibos_numero (numero_recibo),
    INDEX idx_recibos_orden (orden_servicio_id),
    INDEX idx_recibos_cliente (cliente_id),
    INDEX idx_recibos_fecha (fecha_emision)
) ENGINE=InnoDB;

-- ==============================================================================
-- 6. CATÁLOGO E-COMMERCE (Módulo nuevo)
-- ==============================================================================
CREATE TABLE categorias_productos (
    id_categoria INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT NULL,
    icono VARCHAR(50) NULL,
    orden INT NOT NULL DEFAULT 0,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE productos (
    id_producto INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_categoria INT UNSIGNED NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    marca VARCHAR(50) NULL,
    descripcion TEXT NULL,
    descripcion_corta TEXT NULL,
    precio DECIMAL(10,2) NOT NULL,
    precio_anterior DECIMAL(10,2) NULL,
    stock INT NOT NULL DEFAULT 0,
    imagen VARCHAR(255) NULL,
    etiqueta VARCHAR(50) NULL,
    destacado BOOLEAN NOT NULL DEFAULT FALSE,
    veces_vendido INT NOT NULL DEFAULT 0,
    dias_garantia INT UNSIGNED NOT NULL DEFAULT 90, -- Añadido para el filtro dinámico de días
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_productos_cat FOREIGN KEY (id_categoria) REFERENCES categorias_productos(id_categoria) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ==============================================================================
-- 7. VENTAS E-COMMERCE
-- ==============================================================================
CREATE TABLE pedidos_tienda (
    id_pedido INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    codigo_pedido VARCHAR(20) NOT NULL UNIQUE,
    
    tipo_comprobante VARCHAR(20) NOT NULL,
    tipo_documento VARCHAR(20) NOT NULL,
    numero_documento VARCHAR(20) NOT NULL,
    cliente_nombre VARCHAR(150) NOT NULL,
    cliente_telefono VARCHAR(20) NOT NULL,
    cliente_correo VARCHAR(100) NOT NULL,
    
    direccion_departamento VARCHAR(50) NOT NULL,
    direccion_distrito VARCHAR(50) NOT NULL,
    direccion_calle VARCHAR(200) NOT NULL,
    direccion_referencia VARCHAR(200) NULL,
    
    metodo_envio VARCHAR(50) NOT NULL,
    metodo_pago VARCHAR(50) NOT NULL,
    estado_pago VARCHAR(50) NOT NULL DEFAULT 'Pendiente',
    estado_despacho VARCHAR(50) NOT NULL DEFAULT 'En preparación',
    
    subtotal DECIMAL(10,2) NOT NULL,
    costo_envio DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    descuento DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    op_gravadas DECIMAL(10,2) NOT NULL,
    igv DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    
    culqi_charge_id VARCHAR(100) NULL,
    culqi_authorization_code VARCHAR(100) NULL,
    culqi_brand VARCHAR(50) NULL,
    
    items_json JSON NOT NULL,
    
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_pedidos_codigo (codigo_pedido)
) ENGINE=InnoDB;

CREATE TABLE comprobantes_pago (
    id_comprobante INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT UNSIGNED NOT NULL,
    tipo VARCHAR(20) NOT NULL,
    serie VARCHAR(4) NOT NULL,
    correlativo INT NOT NULL,
    numero_completo VARCHAR(20) NOT NULL UNIQUE,
    fecha_emision DATETIME NOT NULL,
    
    ruc_emisor VARCHAR(20) NOT NULL,
    razon_social_emisor VARCHAR(150) NOT NULL,
    direccion_emisor VARCHAR(200) NOT NULL,
    
    tipo_doc_cliente VARCHAR(20) NOT NULL,
    num_doc_cliente VARCHAR(20) NOT NULL,
    nombre_cliente VARCHAR(150) NOT NULL,
    direccion_cliente VARCHAR(200) NOT NULL,
    
    moneda VARCHAR(5) NOT NULL DEFAULT 'PEN',
    op_gravadas DECIMAL(10,2) NOT NULL,
    igv DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    
    codigo_hash VARCHAR(100) NULL,
    estado_sunat VARCHAR(50) NOT NULL,
    
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_comprobantes_pedido FOREIGN KEY (id_pedido) REFERENCES pedidos_tienda(id_pedido) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ==============================================================================
-- 8. COMPLEMENTOS TALLER / E-COMMERCE
-- ==============================================================================
CREATE TABLE tickets_soporte (
    id_ticket INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    codigo_ticket VARCHAR(20) NOT NULL UNIQUE,
    id_cliente INT UNSIGNED NULL, -- Relacionado opcionalmente
    nombre_solicitante VARCHAR(150) NOT NULL,
    telefono_solicitante VARCHAR(20) NOT NULL,
    correo_solicitante VARCHAR(100) NULL,
    asunto VARCHAR(150) NOT NULL,
    descripcion TEXT NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    prioridad VARCHAR(20) NOT NULL DEFAULT 'Media',
    estado VARCHAR(30) NOT NULL DEFAULT 'Abierto',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE detalle_componentes (
    id_detalle INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_orden INT UNSIGNED NOT NULL,
    id_equipo INT UNSIGNED NOT NULL,
    tipo_componente VARCHAR(50) NOT NULL,
    serie_retirada VARCHAR(100) NULL,
    serie_instalada VARCHAR(100) NULL,
    observaciones TEXT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ==============================================================================
-- 9. VISTAS DE COMPATIBILIDAD (EL SECRETO DE LA OPCIÓN C)
-- ==============================================================================

-- Vista que reemplaza a la antigua tabla `equipos` del e-commerce
CREATE VIEW equipos AS
SELECT 
    id AS id_equipo,
    cliente_id AS id_cliente,
    tipo_equipo,
    marca,
    modelo,
    numero_serie,
    created_at AS creado_en
FROM ordenes_servicio;

-- Vista para recibos completos requerida por MAK-PC-CLIENTES
CREATE VIEW v_recibos_completos AS
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
    
    c.id AS cliente_id,
    c.tipo_documento,
    c.numero_documento,
    c.nombres_razon_social AS cliente_nombre,
    c.telefono AS cliente_telefono,
    c.direccion AS cliente_direccion,
    
    o.id AS orden_id,
    o.codigo_orden,
    o.tipo_equipo,
    o.marca,
    o.modelo,
    o.numero_serie,
    o.accesorios_dejados,
    o.solucion_tecnica,
    
    u.id AS usuario_id,
    u.nombre_completo AS emisor_nombre,
    
    cfg.ruc AS empresa_ruc,
    cfg.razon_social AS empresa_razon_social,
    cfg.telefono AS empresa_telefono,
    cfg.direccion AS empresa_direccion
FROM recibos r
INNER JOIN clientes c ON r.cliente_id = c.id
INNER JOIN ordenes_servicio o ON r.orden_servicio_id = o.id
INNER JOIN usuarios u ON r.usuario_emisor_id = u.id
CROSS JOIN configuracion_empresa cfg
ORDER BY r.id DESC;
