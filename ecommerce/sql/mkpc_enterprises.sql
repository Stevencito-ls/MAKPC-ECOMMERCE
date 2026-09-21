-- ==============================================================================
-- BASE DE DATOS UNIFICADA: MAK-PC ENTERPRISES S.A.C.
-- Ecosistema Completo: Taller de Soporte, E-Commerce, PC Builder y Admin Panel
-- Motor: MySQL 8.0+ (InnoDB, UTF-8 MB4)
-- ==============================================================================

CREATE DATABASE IF NOT EXISTS makpc_enterprises_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE makpc_enterprises_db;

SET FOREIGN_KEY_CHECKS = 0;

-- ==============================================================================
-- 1. CONFIGURACIÓN INSTITUCIONAL (Desde Taller)
-- ==============================================================================
DROP TABLE IF EXISTS configuracion_empresa;
CREATE TABLE configuracion_empresa (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ruc CHAR(11) NOT NULL DEFAULT '20409456520',
    razon_social VARCHAR(150) NOT NULL DEFAULT 'MAK-PC ENTERPRISES S.A.C.',
    nombre_comercial VARCHAR(100) NOT NULL DEFAULT 'MAK-PC Soporte Tecnológico',
    telefono VARCHAR(30) NOT NULL DEFAULT '960 702 605',
    telefono_gerencia VARCHAR(30) NOT NULL DEFAULT '960 702 605',
    email VARCHAR(100) DEFAULT 'soporte@makpc.com.pe',
    direccion VARCHAR(255) NOT NULL DEFAULT 'Tumbes, Perú',
    dias_garantia_defecto INT UNSIGNED NOT NULL DEFAULT 90,
    clausula_garantia TEXT NOT NULL,
    ultimo_correlativo_recibo INT UNSIGNED NOT NULL DEFAULT 400,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ==============================================================================
-- 2. SISTEMA R.B.A.C. (ROLE-BASED ACCESS CONTROL) Y USUARIOS
-- ==============================================================================
DROP TABLE IF EXISTS permissions;
CREATE TABLE permissions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE, -- Ej: 'manage_users', 'create_order', 'view_reports'
    description VARCHAR(255) NULL,
    module ENUM('TALLER', 'ECOMMERCE', 'INVENTARIO', 'FINANZAS', 'SISTEMA') NOT NULL
) ENGINE=InnoDB;

DROP TABLE IF EXISTS roles;
CREATE TABLE roles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE, -- Ej: 'Super Admin', 'Técnico', 'Vendedor', 'Cajero'
    description VARCHAR(255) NULL
) ENGINE=InnoDB;

DROP TABLE IF EXISTS role_permissions;
CREATE TABLE role_permissions (
    role_id INT UNSIGNED NOT NULL,
    permission_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    CONSTRAINT fk_rp_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    CONSTRAINT fk_rp_perm FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabla Unificada de Usuarios (Personal MAK-PC)
-- Se mantiene el ENUM 'rol' original por compatibilidad (legacy), pero el sistema usará RBAC
DROP TABLE IF EXISTS usuarios;
CREATE TABLE usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(120) NOT NULL,
    dni CHAR(8) UNIQUE NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('ADMIN', 'RECEPCION', 'TECNICO', 'VENDEDOR') NOT NULL DEFAULT 'TECNICO', -- Legacy compatibilidad
    telefono VARCHAR(20) NULL,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_usuarios_email (email)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS user_roles;
CREATE TABLE user_roles (
    user_id INT UNSIGNED NOT NULL,
    role_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (user_id, role_id),
    CONSTRAINT fk_ur_user FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_ur_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ==============================================================================
-- 3. CLIENTES UNIFICADOS (Taller + E-commerce)
-- ==============================================================================
DROP TABLE IF EXISTS clientes;
CREATE TABLE clientes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tipo_documento ENUM('DNI', 'RUC', 'CE', 'PASAPORTE') NOT NULL DEFAULT 'DNI',
    numero_documento VARCHAR(15) NOT NULL UNIQUE,
    nombres_razon_social VARCHAR(180) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    telefono_secundario VARCHAR(20) NULL,
    email VARCHAR(100) NULL,
    password_hash VARCHAR(255) NULL, -- Añadido para que el cliente haga login en el E-commerce / Portal
    direccion VARCHAR(255) NULL,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_clientes_documento (numero_documento),
    INDEX idx_clientes_email (email)
) ENGINE=InnoDB;

-- ==============================================================================
-- 4. E-COMMERCE: CATÁLOGO Y PC BUILDER
-- ==============================================================================
DROP TABLE IF EXISTS categorias;
CREATE TABLE categorias (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NULL,
    es_componente_pc BOOLEAN NOT NULL DEFAULT FALSE, -- Identifica si va al PC Builder
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

DROP TABLE IF EXISTS productos;
CREATE TABLE productos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT UNSIGNED NOT NULL,
    sku VARCHAR(50) NOT NULL UNIQUE,
    slug VARCHAR(150) NOT NULL UNIQUE,
    nombre VARCHAR(150) NOT NULL,
    descripcion_corta TEXT NULL,
    descripcion_html TEXT NULL,
    precio_regular DECIMAL(10,2) NOT NULL,
    precio_oferta DECIMAL(10,2) NULL,
    stock INT NOT NULL DEFAULT 0,
    imagen_principal VARCHAR(255) NULL,
    estado ENUM('ACTIVO', 'INACTIVO', 'OCULTO') NOT NULL DEFAULT 'ACTIVO',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_productos_cat FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Detalles técnicos para el "PC Builder"
DROP TABLE IF EXISTS componentes_pc;
CREATE TABLE componentes_pc (
    producto_id INT UNSIGNED PRIMARY KEY,
    tipo_componente ENUM('PROCESADOR', 'PLACA_MADRE', 'RAM', 'ALMACENAMIENTO', 'TARJETA_VIDEO', 'FUENTE', 'CASE', 'REFRIGERACION') NOT NULL,
    socket VARCHAR(50) NULL,      -- Ej: LGA1700, AM5
    tipo_ram VARCHAR(20) NULL,    -- Ej: DDR4, DDR5
    watts_requeridos INT NULL,    -- Ej: 65, 300
    formato VARCHAR(50) NULL,     -- Ej: ATX, Micro-ATX
    CONSTRAINT fk_componentes_prod FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ==============================================================================
-- 5. E-COMMERCE: VENTAS Y PEDIDOS ONLINE
-- ==============================================================================
DROP TABLE IF EXISTS pedidos;
CREATE TABLE pedidos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    codigo_pedido VARCHAR(20) NOT NULL UNIQUE, -- Ej: PED-2026-0001
    cliente_id INT UNSIGNED NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    igv DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    metodo_pago ENUM('CULQI_TARJETA', 'CULQI_YAPE', 'TRANSFERENCIA') NOT NULL,
    estado_pago ENUM('PENDIENTE', 'PAGADO', 'FALLIDO', 'REEMBOLSADO') NOT NULL DEFAULT 'PENDIENTE',
    estado_envio ENUM('PREPARANDO', 'ENVIADO', 'ENTREGADO') NOT NULL DEFAULT 'PREPARANDO',
    transaccion_id VARCHAR(100) NULL, -- ID devuelto por Culqi
    direccion_envio TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_pedidos_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS pedido_detalles;
CREATE TABLE pedido_detalles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT UNSIGNED NOT NULL,
    producto_id INT UNSIGNED NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_detalle_pedido FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    CONSTRAINT fk_detalle_producto FOREIGN KEY (producto_id) REFERENCES productos(id)
) ENGINE=InnoDB;

-- ==============================================================================
-- 6. MÓDULO DE TALLER DE SERVICIO TÉCNICO (Mantenido intacto + Relaciones unificadas)
-- ==============================================================================
DROP TABLE IF EXISTS ordenes_servicio;
CREATE TABLE ordenes_servicio (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    codigo_orden VARCHAR(20) NOT NULL UNIQUE, -- Ej: ORD-2026-0001
    cliente_id INT UNSIGNED NOT NULL,
    recepcionista_id INT UNSIGNED NOT NULL,
    tecnico_id INT UNSIGNED NULL,
    
    tipo_equipo ENUM('LAPTOP', 'PC_ESCRITORIO', 'ALL_IN_ONE', 'IMPRESORA', 'MONITOR', 'SERVIDOR', 'OTRO') NOT NULL,
    marca VARCHAR(60) NOT NULL,
    modelo VARCHAR(80) NOT NULL,
    numero_serie VARCHAR(80) NULL,
    accesorios_dejados TEXT NULL,
    ubicacion_taller VARCHAR(60) NOT NULL DEFAULT 'MESA 1',
    password_equipo VARCHAR(100) NULL,
    
    motivo_ingreso TEXT NOT NULL,
    diagnostico_tecnico TEXT NULL,
    solucion_tecnica TEXT NULL,
    repuestos_utilizados TEXT NULL,
    observaciones_esteticas TEXT NULL,
    costo_estimado DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    monto_adelanto DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    
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
    
    fecha_ingreso DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_diagnostico DATETIME NULL,
    fecha_reparacion DATETIME NULL,
    fecha_entrega DATETIME NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_ordenes_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE RESTRICT,
    CONSTRAINT fk_ordenes_recep FOREIGN KEY (recepcionista_id) REFERENCES usuarios(id) ON DELETE RESTRICT,
    CONSTRAINT fk_ordenes_tecnico FOREIGN KEY (tecnico_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    
    INDEX idx_ordenes_codigo (codigo_orden),
    INDEX idx_ordenes_estado (estado)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS recibos;
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
    
    CONSTRAINT fk_recibos_orden FOREIGN KEY (orden_servicio_id) REFERENCES ordenes_servicio(id) ON DELETE RESTRICT,
    CONSTRAINT fk_recibos_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE RESTRICT,
    CONSTRAINT fk_recibos_usuario FOREIGN KEY (usuario_emisor_id) REFERENCES usuarios(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- VISTA: v_recibos_completos
CREATE OR REPLACE VIEW v_recibos_completos AS
SELECT 
    r.id AS recibo_id, r.numero_recibo, r.fecha_emision,
    DATE_FORMAT(r.fecha_emision, '%d/%m/%Y') AS fecha_emision_formateada,
    DATE_FORMAT(r.fecha_emision, '%H:%i') AS hora_emision_formateada,
    r.fecha_vencimiento_garantia, r.dias_garantia, r.concepto,
    r.monto_total, r.monto_letras, r.monto_a_cuenta, r.monto_saldo,
    r.metodo_pago, r.estado_pago, r.clausula_legal, r.anulado,
    
    c.id AS cliente_id, c.tipo_documento, c.numero_documento,
    c.nombres_razon_social AS cliente_nombre, c.telefono AS cliente_telefono,
    
    o.id AS orden_id, o.codigo_orden, o.tipo_equipo, o.marca, o.modelo, o.numero_serie,
    
    u.id AS usuario_id, u.nombre_completo AS emisor_nombre,
    
    cfg.ruc AS empresa_ruc, cfg.razon_social AS empresa_razon_social
FROM recibos r
INNER JOIN clientes c ON r.cliente_id = c.id
INNER JOIN ordenes_servicio o ON r.orden_servicio_id = o.id
INNER JOIN usuarios u ON r.usuario_emisor_id = u.id
CROSS JOIN configuracion_empresa cfg;

-- ==============================================================================
-- 7. SEED DATA (DATOS INICIALES PARA EL SISTEMA RBAC Y PRUEBAS)
-- ==============================================================================

-- 7.1 Permisos del Sistema
INSERT INTO permissions (name, description, module) VALUES
('manage_users', 'Crear, editar y eliminar usuarios', 'SISTEMA'),
('manage_roles', 'Asignar y gestionar roles RBAC', 'SISTEMA'),
('create_order', 'Crear órdenes de servicio en el taller', 'TALLER'),
('update_repair_status', 'Actualizar estado de equipos y diagnósticos', 'TALLER'),
('view_reports', 'Ver reportes financieros y KPIs', 'FINANZAS'),
('manage_products', 'Crear y editar catálogo e-commerce', 'ECOMMERCE');

-- 7.2 Roles Base
INSERT INTO roles (id, name, description) VALUES
(1, 'Super Admin', 'Control total del ecosistema'),
(2, 'Técnico', 'Acceso exclusivo al módulo de Taller y diagnósticos'),
(3, 'Recepción / Ventas', 'Puede crear órdenes y gestionar ventas E-commerce');

-- 7.3 Asignación de Permisos a Roles
-- Super Admin (Todo)
INSERT INTO role_permissions (role_id, permission_id) 
SELECT 1, id FROM permissions;

-- Técnico (Taller)
INSERT INTO role_permissions (role_id, permission_id) VALUES 
(2, (SELECT id FROM permissions WHERE name = 'update_repair_status'));

-- Recepción (Crear órdenes, productos)
INSERT INTO role_permissions (role_id, permission_id) VALUES 
(3, (SELECT id FROM permissions WHERE name = 'create_order')),
(3, (SELECT id FROM permissions WHERE name = 'manage_products'));

-- 7.4 Usuarios y Asignación de Roles (Password: Admin123* simulación)
INSERT INTO usuarios (id, nombre_completo, email, password_hash, rol, activo) VALUES
(1, 'Administrador MAK-PC', 'admin@makpc.pe', '$2y$12$e8Y... (hash seguro bcrypt)', 'ADMIN', TRUE),
(2, 'Carlos Técnico', 'tecnico@makpc.pe', '$2y$12$e8Y... (hash seguro bcrypt)', 'TECNICO', TRUE);

INSERT INTO user_roles (user_id, role_id) VALUES
(1, 1), -- Admin es Super Admin
(2, 2); -- Carlos es Técnico

SET FOREIGN_KEY_CHECKS = 1;
