-- ==============================================================================
-- SISTEMA MAKPC ENTERPRISES S.A.C.
-- Gestión de Taller Informático + E-commerce + Soporte al Cliente
-- Base de Datos: taller_servicios
-- Motor: InnoDB | Cotejamiento: utf8mb4_unicode_ci
-- ==============================================================================

CREATE DATABASE IF NOT EXISTS `taller_servicios`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE `taller_servicios`;

SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------------------------
-- 1. USUARIOS DEL SISTEMA (login admin/técnicos)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
    `id_usuario` INT AUTO_INCREMENT PRIMARY KEY,
    `usuario` VARCHAR(50) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `nombre_completo` VARCHAR(150) NOT NULL,
    `rol` ENUM('admin','tecnico','vendedor') NOT NULL DEFAULT 'tecnico',
    `activo` TINYINT(1) NOT NULL DEFAULT 1,
    `creado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `actualizado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cuentas iniciales seguras:
-- admin: admin123
-- tecnico: tecnico123
-- vendedor: vendedor123
INSERT INTO `usuarios` (`usuario`, `password_hash`, `nombre_completo`, `rol`) VALUES
('admin', '$2y$12$dCBgtWXYJRkZTSloSwNxz.Acll32vym9ugIbFoHWhmz8c/jNOeXC2', 'Administrador General MAKPC', 'admin'),
('tecnico', '$2y$12$wpO0OUQOa8OBP02VDqxO1u9sXpw5NuXt6Ot2CiNZMxl/vW.oL5GYO', 'Especialista Técnico de Taller', 'tecnico'),
('vendedor', '$2y$12$0ULR2MFVxLC2DWDn0wC3be/vubjAkYzUn8ywkPSM3JDYwCZvQLASC', 'Asesor Comercial & Ventas', 'vendedor');

-- ------------------------------------------------------------------------------
-- 2. CLIENTES
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `clientes`;
CREATE TABLE `clientes` (
    `id_cliente` INT AUTO_INCREMENT PRIMARY KEY,
    `dni` VARCHAR(20) NULL UNIQUE COMMENT 'DNI/RUC opcional',
    `nombres_apellidos` VARCHAR(150) NOT NULL,
    `telefono` VARCHAR(25) NOT NULL COMMENT 'Teléfono principal (WhatsApp)',
    `telefono_secundario` VARCHAR(25) NULL,
    `correo` VARCHAR(120) NULL,
    `direccion` VARCHAR(255) NULL,
    `notas_cliente` TEXT NULL,
    `creado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `actualizado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_clientes_telefono` (`telefono`),
    INDEX `idx_clientes_dni` (`dni`),
    INDEX `idx_clientes_nombres` (`nombres_apellidos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 3. EQUIPOS
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `equipos`;
CREATE TABLE `equipos` (
    `id_equipo` INT AUTO_INCREMENT PRIMARY KEY,
    `id_cliente` INT NOT NULL,
    `tipo_equipo` ENUM('Laptop','Computadora Torre','All-in-One','Impresora','Consola / Otro') NOT NULL DEFAULT 'Laptop',
    `marca` VARCHAR(60) NOT NULL,
    `modelo` VARCHAR(100) NOT NULL,
    `numero_serie` VARCHAR(100) NULL,
    `codigo_patrimonial` VARCHAR(80) NULL,
    `color_detalles` VARCHAR(80) NULL,
    `creado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `actualizado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_equipos_cliente` (`id_cliente`),
    INDEX `idx_equipos_serie` (`numero_serie`),
    CONSTRAINT `fk_equipos_cliente`
        FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 4. ÓRDENES DE SERVICIO
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `ordenes_servicio`;
CREATE TABLE `ordenes_servicio` (
    `id_orden` INT AUTO_INCREMENT PRIMARY KEY,
    `codigo_orden` VARCHAR(30) NOT NULL UNIQUE,
    `id_equipo` INT NOT NULL,
    `es_inmediato` TINYINT(1) NOT NULL DEFAULT 0,
    `tecnico_responsable` VARCHAR(100) NOT NULL DEFAULT 'Técnico de Turno',
    `fecha_recepcion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `fecha_entrega` DATETIME NULL,
    `accesorios_entregados` TEXT NULL,
    `estado_recepcion_fisico` TEXT NOT NULL,
    `falla_reportada` TEXT NOT NULL,
    `servicio_solicitado` TEXT NULL,
    `diagnostico` TEXT NULL,
    `solucion_aplicada` TEXT NULL,
    `costo_mano_obra` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `costo_repuestos` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `costo_total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `adelanto` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `garantia_meses` INT NOT NULL DEFAULT 0,
    `estado` ENUM('Pendiente','En Reparacion','Terminado','Entregado','Cancelado') NOT NULL DEFAULT 'Pendiente',
    `observaciones_internas` TEXT NULL,
    `creado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `actualizado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_ordenes_codigo` (`codigo_orden`),
    INDEX `idx_ordenes_equipo` (`id_equipo`),
    INDEX `idx_ordenes_estado` (`estado`),
    INDEX `idx_ordenes_fecha_rec` (`fecha_recepcion`),
    CONSTRAINT `fk_ordenes_equipo`
        FOREIGN KEY (`id_equipo`) REFERENCES `equipos` (`id_equipo`)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 5. DETALLE DE COMPONENTES (TRAZABILIDAD)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `detalle_componentes`;
CREATE TABLE `detalle_componentes` (
    `id_detalle` INT AUTO_INCREMENT PRIMARY KEY,
    `id_orden` INT NOT NULL,
    `tipo_componente` VARCHAR(80) NOT NULL,
    `serie_retirada` VARCHAR(100) NULL,
    `pieza_instalada` VARCHAR(180) NULL,
    `serie_instalada` VARCHAR(100) NULL,
    `precio` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `observacion` TEXT NULL,
    `creado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_det_orden` (`id_orden`),
    INDEX `idx_det_serie_retirada` (`serie_retirada`),
    INDEX `idx_det_serie_instalada` (`serie_instalada`),
    CONSTRAINT `fk_detalle_orden`
        FOREIGN KEY (`id_orden`) REFERENCES `ordenes_servicio` (`id_orden`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 6. CATEGORÍAS DE PRODUCTOS (E-commerce)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `categorias_productos`;
CREATE TABLE `categorias_productos` (
    `id_categoria` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(120) NOT NULL UNIQUE,
    `icono` VARCHAR(60) NULL COMMENT 'Clase FontAwesome ej: fas fa-laptop',
    `orden` INT NOT NULL DEFAULT 0,
    `activo` TINYINT(1) NOT NULL DEFAULT 1,
    `creado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 7. PRODUCTOS (E-commerce)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `productos`;
CREATE TABLE `productos` (
    `id_producto` INT AUTO_INCREMENT PRIMARY KEY,
    `id_categoria` INT NOT NULL,
    `nombre` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(220) NOT NULL UNIQUE,
    `descripcion` TEXT NULL,
    `precio` DECIMAL(10,2) NOT NULL,
    `precio_anterior` DECIMAL(10,2) NULL COMMENT 'Precio tachado para mostrar descuento',
    `stock` INT NOT NULL DEFAULT 0,
    `marca` VARCHAR(80) NULL,
    `imagen` VARCHAR(255) NULL,
    `etiqueta` ENUM('Nuevo','Hot','Oferta','-10%','-15%','-20%','-25%','-30%') NULL,
    `destacado` TINYINT(1) NOT NULL DEFAULT 0,
    `activo` TINYINT(1) NOT NULL DEFAULT 1,
    `veces_vendido` INT NOT NULL DEFAULT 0,
    `calificacion` DECIMAL(2,1) NOT NULL DEFAULT 0.0,
    `num_resenas` INT NOT NULL DEFAULT 0,
    `creado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `actualizado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_prod_categoria` (`id_categoria`),
    INDEX `idx_prod_destacado` (`destacado`),
    INDEX `idx_prod_activo` (`activo`),
    CONSTRAINT `fk_prod_categoria`
        FOREIGN KEY (`id_categoria`) REFERENCES `categorias_productos` (`id_categoria`)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 8. TICKETS DE SOPORTE (Atención al cliente)
-- ------------------------------------------------------------------------------
DROP TABLE IF EXISTS `tickets_soporte`;
CREATE TABLE `tickets_soporte` (
    `id_ticket` INT AUTO_INCREMENT PRIMARY KEY,
    `codigo_ticket` VARCHAR(30) NOT NULL UNIQUE,
    `id_cliente` INT NULL,
    `nombre_solicitante` VARCHAR(150) NOT NULL,
    `telefono_solicitante` VARCHAR(25) NOT NULL,
    `correo_solicitante` VARCHAR(120) NULL,
    `asunto` VARCHAR(200) NOT NULL,
    `descripcion` TEXT NOT NULL,
    `tipo` ENUM('Consulta','Reclamo','Garantia','Soporte Tecnico','Otro') NOT NULL DEFAULT 'Consulta',
    `prioridad` ENUM('Baja','Media','Alta','Urgente') NOT NULL DEFAULT 'Media',
    `estado` ENUM('Abierto','En Proceso','Resuelto','Cerrado') NOT NULL DEFAULT 'Abierto',
    `respuesta` TEXT NULL,
    `atendido_por` VARCHAR(100) NULL,
    `creado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `actualizado_en` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_ticket_estado` (`estado`),
    INDEX `idx_ticket_cliente` (`id_cliente`),
    CONSTRAINT `fk_ticket_cliente`
        FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------------------------
-- 9. VISTA DE AUDITORÍA
-- ------------------------------------------------------------------------------
CREATE OR REPLACE VIEW `vw_auditoria_trazabilidad` AS
SELECT
    o.id_orden, o.codigo_orden, o.es_inmediato, o.estado AS estado_orden,
    o.tecnico_responsable, o.fecha_recepcion, o.fecha_entrega, o.garantia_meses,
    c.id_cliente, c.nombres_apellidos AS cliente_nombre,
    c.telefono AS cliente_telefono, c.dni AS cliente_dni,
    e.id_equipo, e.tipo_equipo,
    CONCAT(e.marca, ' ', e.modelo) AS equipo_modelo,
    e.numero_serie AS equipo_serie,
    d.id_detalle, d.tipo_componente, d.serie_retirada,
    d.pieza_instalada, d.serie_instalada,
    d.precio AS precio_repuesto, d.observacion AS observacion_repuesto
FROM `ordenes_servicio` o
INNER JOIN `equipos` e ON o.id_equipo = e.id_equipo
INNER JOIN `clientes` c ON e.id_cliente = c.id_cliente
LEFT JOIN `detalle_componentes` d ON o.id_orden = d.id_orden;

-- ------------------------------------------------------------------------------
-- 10. DATOS REALES DEL MERCADO PERUANO (HARDWARE & TALLER)
-- ------------------------------------------------------------------------------
INSERT INTO `clientes` (`id_cliente`, `dni`, `nombres_apellidos`, `telefono`, `correo`, `direccion`) VALUES
(1, '72483920', 'Carlos Eduardo Mendoza Ruiz', '972654321', 'carlos.mendoza.ruiz@gmail.com', 'Calle Bolívar 412, Cercado, Tumbes'),
(2, '45192837', 'María Elena Fernández Silva', '972345678', 'maria.fernandez.s@outlook.com', 'Av. Tumbes Norte 850, Barrio San José, Tumbes'),
(3, '10458792', 'Ing. Juan Manuel Pérez Torres', '972781234', 'juan.perez.ing@gmail.com', 'Jr. Huáscar 240, Barrio Buenos Aires, Tumbes'),
(4, '20608492015', 'Constructora & Servicios del Norte S.A.C.', '072523456', 'logistica@constructoranorte.pe', 'Av. Panamericana Norte Km. 1270, Zarumilla, Tumbes'),
(5, '70384915', 'Dra. Valeria Andrea Benavides Castro', '972234567', 'valeria.benavides@essalud.gob.pe', 'Malecón Benavides 310, Puerto Pizarro, Tumbes');

INSERT INTO `equipos` (`id_equipo`, `id_cliente`, `tipo_equipo`, `marca`, `modelo`, `numero_serie`, `codigo_patrimonial`, `color_detalles`) VALUES
(1, 1, 'Laptop', 'Lenovo', 'Legion Pro 5 16IRX8 Core i7-13700HX RTX 4060', 'PF4B9Z1K', 'MAK-EQ-2026-001', 'Gris Tormenta con retroiluminación RGB'),
(2, 2, 'Computadora Torre', 'Asus', 'PC Gamer Ensamblada ASUS TUF B550M Ryzen 5', 'SN-TUF-884219', 'MAK-EQ-2026-002', 'Case Antryx FX vidrio templado 4 fans ARGB'),
(3, 3, 'Laptop', 'HP', 'ProBook 450 G9 Intel Core i7-1255U 16GB', '5CD3128Z0M', 'MAK-EQ-2026-003', 'Plateado Ceniza cuerpo de aluminio'),
(4, 4, 'Impresora', 'Epson', 'EcoTank L3250 Wi-Fi Tanque Continuo', 'X8YZ491823', 'MAK-EQ-2026-004', 'Negro Mate original'),
(5, 5, 'Laptop', 'Apple', 'MacBook Pro 14 M2 Pro 16GB 512GB SSD', 'C02K98ZXMD6R', 'MAK-EQ-2026-005', 'Space Gray con cargador MagSafe 3');

INSERT INTO `ordenes_servicio` (`id_orden`, `codigo_orden`, `id_equipo`, `es_inmediato`, `tecnico_responsable`, `fecha_recepcion`, `fecha_entrega`, `accesorios_entregados`, `estado_recepcion_fisico`, `falla_reportada`, `servicio_solicitado`, `diagnostico`, `solucion_aplicada`, `costo_mano_obra`, `costo_repuestos`, `costo_total`, `adelanto`, `garantia_meses`, `estado`) VALUES
(1, 'ORD-2026-0001', 1, 0, 'Steve - Especialista Técnico', '2026-09-01 10:30:00', '2026-09-03 16:00:00', 'Cargador original Lenovo Slim Tip 300W, Mochila Legion', 'Equipo operativo. Leve desgaste en bordes. Bisagras firmes.', 'Laptop se apaga súbitamente al ejecutar juegos o renderizado 3D en Blender. Ventilador emite chirrido.', 'Mantenimiento térmico profundo y reemplazo de ventilador disipador.', 'Pasta térmica de fábrica totalmente cristalizada. Ventilador de turbina izquierdo trabado por desgaste de rodamiento. Throttling a 98°C.', 'Limpieza ultrasónica de disipador de cobre, aplicación de pasta térmica Thermal Grizzly Kryonaut y sustitución de ventilador original 12V.', 80.00, 130.00, 210.00, 100.00, 6, 'Terminado'),
(2, 'ORD-2026-0002', 2, 1, 'Steve - Especialista Técnico', '2026-09-02 09:15:00', '2026-09-02 11:30:00', 'Solo torre sin cables de poder', 'Chasis limpio. Filtros antipolvo con suciedad leve.', 'Cliente requiere ampliación urgente a 32GB RAM y almacenamiento M.2 NVMe de 1TB para edición 4K.', 'Upgrade de hardware express y configuración de perfil XMP en BIOS.', 'Placa madre ASUS TUF Gaming B550M-PLUS compatible con PCIe 4.0. Slots 2 y 4 disponibles.', 'Instalación de kit 32GB (2x16GB) Corsair Vengeance LPX DDR4 3200MHz y SSD Kingston KC3000 PCIe 4.0 1TB (7000MB/s). Perfil DOCP activado.', 60.00, 520.00, 580.00, 580.00, 12, 'Entregado'),
(3, 'ORD-2026-0003', 3, 0, 'Steve - Especialista Técnico', '2026-09-03 11:00:00', NULL, 'Cargador USB-C HP 65W original', 'Tapa superior con rayón leve. Bisagra derecha dura al abrir.', 'Pantalla parpadea con líneas horizontales de colores y se va a negro al ajustar el ángulo de apertura.', 'Diagnóstico y cambio de flex o pantalla.', 'Display IPS intacto. Arnés de cable flex eDP de 40 pines con líneas cortadas por fricción contra el soporte metálico de bisagra derecha.', 'Sustitución de cable flex de video eDP original HP y calibración de tensión en ambas bisagras.', 90.00, 120.00, 210.00, 100.00, 6, 'En Reparacion'),
(4, 'ORD-2026-0004', 4, 1, 'Steve - Especialista Técnico', '2026-09-04 08:30:00', '2026-09-04 10:00:00', 'Cable USB, cable de poder, tanques con tinta al 60%', 'Equipo en excelente estado estético exterior.', 'Luces parpadean en rojo. La computadora arroja: Las almohadillas de tinta han llegado al final de su vida útil.', 'Mantenimiento de depósito y reseteo de contador EEPROM.', 'Contador interno al 100.8% de capacidad de drenado (6,840 copias). Almohadillas saturadas de desecho.', 'Reemplazo de kit de almohadillas absorbentes originales Epson, purgado de inyectores y reseteo de software autorizado.', 40.00, 35.00, 75.00, 75.00, 3, 'Entregado'),
(5, 'ORD-2026-0005', 5, 0, 'Steve - Especialista Técnico', '2026-09-05 14:20:00', NULL, 'Cargador Apple MagSafe 3 96W', 'Equipo intacto sin abolladuras. Restos de café seco en rejillas laterales.', 'MacBook no enciende ni muestra luz de carga tras derrame de líquido.', 'Diagnóstico microscópico de placa madre y rescate de archivos.', 'Cortocircuito en línea principal PPBUS_G3H (0.3 ohms a tierra) por corrosión en controlador ISL9240.', NULL, 350.00, 180.00, 530.00, 250.00, 6, 'Pendiente');

INSERT INTO `detalle_componentes` (`id_orden`, `tipo_componente`, `serie_retirada`, `pieza_instalada`, `serie_instalada`, `precio`, `observacion`) VALUES
(1, 'Ventilador CPU Cooler', 'FCN-DFS5K121154911-DEF', 'Ventilador Turbina Original Lenovo Legion 12V 0.5A', 'FCN-DFS5K12115491M', 130.00, 'Pieza retirada con buje desgastado. Se entrega pieza antigua al cliente.'),
(2, 'SSD M.2 NVMe', 'N/A (Slot M.2 libre)', 'SSD Kingston KC3000 1024GB PCIe 4.0 NVMe M.2', '50026B7685938411', 340.00, 'Lectura real alcanzada en CrystalDiskMark: 7,040 MB/s.'),
(2, 'Memoria RAM', 'N/A (Slot 2 y 4 libres)', 'Kit Corsair Vengeance LPX 32GB (2x16GB) DDR4 3200MHz', 'COR-LPX32G-9948214', 180.00, 'Dual channel activo y estable con prueba MemTest86 a 0 errores.'),
(3, 'Cable Flex Display', 'FOX-DD0X8MLC010-DAMAGED', 'Cable Flex de Video eDP 40-Pin FHD HP ProBook', 'FOX-DD0X8MLC010-NEW', 120.00, 'Cable dañado presentaba 3 filamentos expuestos en codo de bisagra.'),
(4, 'Almohadillas de Tinta', 'EPS-PAD-OLD-SATURATED', 'Pack Almohadillas de Desecho Original Epson EcoTank', 'EPS-PAD-1830528', 35.00, 'Almohadillas viejas desechadas bajo protocolo de reciclaje.');

-- Categorías de productos
INSERT INTO `categorias_productos` (`id_categoria`, `nombre`, `slug`, `icono`, `orden`) VALUES
(1, 'Laptops', 'laptops', 'fas fa-laptop', 1),
(2, 'PCs de Escritorio', 'pcs-escritorio', 'fas fa-desktop', 2),
(3, 'Monitores', 'monitores', 'fas fa-tv', 3),
(4, 'Teclados', 'teclados', 'fas fa-keyboard', 4),
(5, 'Mouse', 'mouse', 'fas fa-mouse', 5),
(6, 'Audio', 'audio', 'fas fa-headphones', 6),
(7, 'Componentes', 'componentes', 'fas fa-microchip', 7),
(8, 'Impresoras', 'impresoras', 'fas fa-print', 8),
(9, 'Redes', 'redes', 'fas fa-wifi', 9),
(10, 'Accesorios', 'accesorios', 'fas fa-plug', 10);

-- 12 Productos Reales del Mercado Tecnológico Peruano
INSERT INTO `productos` (`id_categoria`, `nombre`, `slug`, `descripcion`, `precio`, `precio_anterior`, `stock`, `marca`, `imagen`, `etiqueta`, `destacado`, `calificacion`, `num_resenas`) VALUES
(1, 'Laptop Gamer Lenovo Legion Pro 5 16IRX8 Core i7-13700HX RTX 4060', 'laptop-gamer-lenovo-legion-pro-5-16irx8', 'Laptop gaming de alto rendimiento equipada con procesador Intel Core i7-13700HX de 16 núcleos (hasta 5.0GHz), 16GB memoria RAM DDR5 4800MHz, almacenamiento ultrarrápido SSD 1TB PCIe 4.0 NVMe, pantalla 16 pulgadas WQXGA (2560x1600) IPS 165Hz 100% sRGB con Dolby Vision y tarjeta gráfica NVIDIA GeForce RTX 4060 8GB GDDR6 con TGP de 140W.', 5499.00, 5999.00, 6, 'Lenovo', 'prod_1.jpg', 'Hot', 1, 4.9, 48),
(3, 'Monitor Gaming LG UltraGear 27GR75Q-B 27" QHD 165Hz 1ms IPS', 'monitor-gaming-lg-ultragear-27gr75q-b-27-qhd-165hz', 'Monitor gamer con panel IPS de 27 pulgadas y resolución QHD 2K (2560x1440), tasa de refresco fluida de 165Hz y tiempo de respuesta ultra veloz de 1ms MBR. Compatible con NVIDIA G-Sync y AMD FreeSync Premium, cobertura de color 99% sRGB, HDR10 y soporte ergonómico regulable en altura e inclinación.', 1199.00, 1399.00, 9, 'LG', 'prod_2.jpg', '-15%', 1, 4.8, 35),
(4, 'Teclado Mecánico Inalámbrico Keychron K6 RGB Hot-Swap Gateron Brown', 'teclado-mecanico-inalambrico-keychron-k6-rgb', 'Teclado mecánico compacto formato 65% con switches mecánicos táctiles Gateron G Pro Brown intercambiables en caliente (Hot-swappable). Conectividad híbrida dual: Bluetooth 5.1 multidispositivo (hasta 3 equipos) y cable USB-C desmontable. Retroiluminación RGB de 18 modos, compatible con Windows y macOS.', 389.00, 449.00, 14, 'Keychron', 'prod_3.jpg', 'Nuevo', 1, 4.9, 26),
(5, 'Mouse Inalámbrico Avanzado Logitech MX Master 3S Graphite 8000 DPI', 'mouse-inalambrico-logitech-mx-master-3s-graphite', 'Mouse profesional ergonómico de máxima precisión con sensor Darkfield de 8,000 DPI con capacidad de rastreo en cualquier superficie (incluyendo cristal). Clics silenciosos Quiet Clicks con 90% menos ruido, rueda electromagnética MagSpeed que desplaza hasta 1,000 líneas por segundo y batería recargable USB-C hasta 70 días.', 399.00, 459.00, 22, 'Logitech', 'prod_4.jpg', 'Hot', 0, 4.9, 64),
(6, 'Audífonos Gamer HyperX Cloud II Red Sonido Envolvente 7.1', 'audifonos-gamer-hyperx-cloud-ii-red-71', 'Auriculares de esports profesionales con sonido envolvente virtual 7.1 controlado por tarjeta DSP USB. Altavoces dinámicos de 53 mm con imanes de neodimio para agudos nítidos y graves contundentes. Almohadillas de espuma viscoelástica y cuero sintético prémium. Micrófono desmontable con cancelación de ruido.', 289.00, 349.00, 16, 'HyperX', 'prod_5.jpg', '-20%', 1, 4.8, 52),
(7, 'SSD M.2 NVMe Samsung 980 PRO 1TB PCIe 4.0 con Disipador Térmico', 'ssd-m2-nvme-samsung-980-pro-1tb-pcie-4', 'Unidad de estado sólido NVMe M.2 2280 PCIe Gen 4.0 x4 de grado profesional con disipador térmico integrado de perfil bajo compatible con Sony PlayStation 5 y placas base de gama alta. Velocidad de lectura secuencial de hasta 7,000 MB/s y escritura de 5,000 MB/s con controlador Samsung Elpis.', 469.00, 549.00, 18, 'Samsung', 'prod_6.jpg', '-15%', 1, 5.0, 71),
(1, 'Laptop Ultrabook ASUS ZenBook 14 OLED UX3402VA Core i7 1TB SSD', 'laptop-ultrabook-asus-zenbook-14-oled-ux3402va', 'Portátil ultra liviano de 1.39 kg con chasis de aluminio militar MIL-STD 810H. Equipado con procesador Intel Core i7-1360P (12 núcleos), 16GB RAM LPDDR5, 1TB SSD PCIe 4.0 NVMe, pantalla NanoEdge OLED de 14" 2.8K (2880x1800) a 90Hz, 100% DCI-P3 600 nits HDR y batería de 75Wh con hasta 18 horas de autonomía.', 4299.00, 4699.00, 4, 'ASUS', 'prod_7.jpg', 'Nuevo', 0, 4.7, 19),
(2, 'PC Gamer Ensamblada MAKPC Master Gaming Ryzen 5 5600 RTX 4060', 'pc-gamer-ensamblada-makpc-master-gaming-rtx-4060', 'Computadora gaming optimizada para 1080p y 1440p: Procesador AMD Ryzen 5 5600 (6C/12T 4.4GHz), Placa ASUS Prime B550M-A WiFi II, 32GB RAM DDR4 3200MHz Kingston Fury (2x16GB), Tarjeta de video Gigabyte GeForce RTX 4060 8GB GDDR6, SSD NVMe 1TB PCIe 4.0, Fuente Corsair CV650 650W 80 Plus Bronze y Case Antryx FX ARGB.', 3699.00, 4099.00, 5, 'Custom MAKPC', 'prod_8.jpg', 'Hot', 1, 4.9, 38),
(7, 'Memoria RAM Kingston Fury Beast RGB 32GB (2x16GB) DDR5 6000MHz', 'memoria-ram-kingston-fury-beast-rgb-32gb-ddr5-6000', 'Kit de memoria RAM de alto rendimiento dual channel 32GB (2 módulos de 16GB) DDR5 a 6000 MT/s, latencia CL36 con disipador de calor de aluminio negro e iluminación RGB patentada Infrared Sync. Certificada con perfiles Intel XMP 3.0 y AMD EXPO para overclocking seguro con un solo clic.', 549.00, 620.00, 12, 'Kingston', 'prod_9.jpg', 'Nuevo', 0, 4.8, 22),
(8, 'Impresora Multifuncional Epson EcoTank L3250 Tanque Continuo Wi-Fi', 'impresora-multifuncional-epson-ecotank-l3250-wifi', 'Impresora multifuncional 3 en 1 (imprime, copia y escanea) con sistema original de tanque de tinta continuo EcoTank 100% sin cartuchos. Conectividad inalámbrica Wi-Fi Direct para imprimir desde el celular con la app Epson Smart Panel. Rendimiento de hasta 4,500 páginas en negro y 7,500 páginas a color.', 699.00, 799.00, 8, 'Epson', 'prod_10.jpg', '-15%', 0, 4.5, 41),
(9, 'Router Inalámbrico Wi-Fi 6 Doble Banda TP-Link Archer AX23 AX1800', 'router-inalambrico-wifi-6-tp-link-archer-ax23-ax1800', 'Router inalámbrico de última generación Wi-Fi 6 con velocidades combinadas de hasta 1.8 Gbps (1201 Mbps en 5GHz y 574 Mbps en 2.4GHz). Dispone de 4 antenas de alta ganancia con tecnología Beamforming, tecnología OFDMA y 4 puertos Gigabit Ethernet para conexiones estables sin latencia.', 199.00, 239.00, 25, 'TP-Link', 'prod_11.jpg', NULL, 0, 4.6, 29),
(10, 'Adaptador Hub Multipuerto UGREEN Revodok 7 en 1 USB-C 4K 100W PD', 'adaptador-hub-multipuerto-ugreen-revodok-7en1-usbc', 'Concentrador USB-C multipuerto 7 en 1 fabricado en aleación de aluminio: Puerto HDMI 4K a 60Hz ultra nítido, puerto de carga rápida USB-C Power Delivery de 100W, 2 puertos USB 3.0 de alta velocidad (5Gbps), ranuras para tarjetas SD y MicroSD (TF) y puerto de datos USB-C adicional. Plug and play en Windows, Mac y iPad.', 139.00, 169.00, 35, 'Ugreen', 'prod_12.jpg', NULL, 0, 4.7, 33);

-- Tickets de Soporte Reales
INSERT INTO `tickets_soporte` (`codigo_ticket`, `id_cliente`, `nombre_solicitante`, `telefono_solicitante`, `correo_solicitante`, `asunto`, `descripcion`, `tipo`, `prioridad`, `estado`, `respuesta`, `atendido_por`) VALUES
('TKT-2026-0001', 1, 'Carlos Eduardo Mendoza Ruiz', '987654321', 'carlos.mendoza.ruiz@gmail.com', 'Consulta sobre prueba de temperatura en Lenovo Legion Pro 5', 'Buenas tardes, quisiera consultar los resultados de las pruebas de estrés en FurMark y Cinebench tras el cambio de ventilador que realizaron hoy.', 'Consulta', 'Media', 'Resuelto', 'Estimado Carlos, el equipo completó 45 minutos de test continuo con temperatura máxima estabilizada en 74°C en CPU y 68°C en GPU. Ventilador nuevo al 100% silencioso. Ya se encuentra disponible para entrega en taller.', 'Steve - Especialista Técnico'),
('TKT-2026-0002', 3, 'Ing. Juan Manuel Pérez Torres', '956781234', 'juan.perez.ing@gmail.com', 'Comprobante de garantía de memoria RAM y SSD Kingston', 'Estimados, favor enviarme a mi correo la constancia de garantía de 12 meses por la memoria RAM Corsair de 32GB y el SSD Kingston de 1TB instalados en mi PC.', 'Garantia', 'Baja', 'Resuelto', 'Estimado Ing. Pérez, se le ha remitido a su correo juan.perez.ing@gmail.com el certificado de garantía digital con los números de serie 50026B7685938411 y COR-LPX32G-9948214.', 'Steve - Especialista Técnico'),
('TKT-2026-0003', 4, 'Constructora & Soluciones Digitales S.A.C.', '014567890', 'logistica@techsolutions.pe', 'Solicitud de Factura Electrónica SUNAT por servicio técnico', 'Solicitamos la emisión de la Factura Electrónica con RUC 20608492015 correspondiente a la Orden de Servicio ORD-2026-0004 por S/ 75.00 con detracción aplicable si corresponde.', 'Consulta', 'Media', 'Abierto', NULL, NULL);

-- ------------------------------------------------------------------------------
-- 11. TABLAS DE E-COMMERCE & FACTURACIÓN ELECTRÓNICA SUNAT
-- ------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pedidos_tienda` (
  `id_pedido` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_pedido` varchar(50) NOT NULL,
  `tipo_comprobante` enum('boleta','factura') NOT NULL DEFAULT 'boleta',
  `tipo_documento` varchar(10) NOT NULL DEFAULT 'DNI',
  `numero_documento` varchar(20) NOT NULL,
  `cliente_nombre` varchar(150) NOT NULL,
  `cliente_telefono` varchar(25) NOT NULL,
  `cliente_correo` varchar(120) NOT NULL,
  `direccion_departamento` varchar(50) NOT NULL DEFAULT 'Tumbes',
  `direccion_distrito` varchar(80) NOT NULL DEFAULT 'Tumbes',
  `direccion_calle` varchar(255) NOT NULL,
  `direccion_referencia` varchar(255) DEFAULT NULL,
  `metodo_envio` varchar(50) NOT NULL,
  `metodo_pago` varchar(50) NOT NULL,
  `estado_pago` enum('Pendiente','Pagado','Rechazado') NOT NULL DEFAULT 'Pendiente',
  `estado_despacho` enum('Pendiente','En preparación','Enviado','Entregado') NOT NULL DEFAULT 'En preparación',
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `costo_envio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `descuento` decimal(10,2) NOT NULL DEFAULT 0.00,
  `op_gravadas` decimal(10,2) NOT NULL DEFAULT 0.00,
  `igv` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `culqi_charge_id` varchar(100) DEFAULT NULL,
  `culqi_authorization_code` varchar(100) DEFAULT NULL,
  `culqi_brand` varchar(50) DEFAULT NULL,
  `items_json` longtext NOT NULL,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_pedido`),
  UNIQUE KEY `uk_codigo_pedido` (`codigo_pedido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `comprobantes_pago` (
  `id_comprobante` int(11) NOT NULL AUTO_INCREMENT,
  `id_pedido` int(11) NOT NULL,
  `tipo` enum('Boleta','Factura') NOT NULL,
  `serie` varchar(10) NOT NULL,
  `correlativo` int(11) NOT NULL,
  `numero_completo` varchar(30) NOT NULL,
  `fecha_emision` datetime NOT NULL DEFAULT current_timestamp(),
  `ruc_emisor` varchar(20) NOT NULL DEFAULT '20409456520',
  `razon_social_emisor` varchar(150) NOT NULL DEFAULT 'MAK-PC ENTERPRISES S.A.C.',
  `direccion_emisor` varchar(255) NOT NULL DEFAULT 'Cal. Simón Bolívar Nro. 461 Int. 001, Cercado de Tumbes, Tumbes',
  `tipo_doc_cliente` varchar(10) NOT NULL,
  `num_doc_cliente` varchar(20) NOT NULL,
  `nombre_cliente` varchar(150) NOT NULL,
  `direccion_cliente` varchar(255) DEFAULT NULL,
  `moneda` varchar(5) NOT NULL DEFAULT 'PEN',
  `op_gravadas` decimal(10,2) NOT NULL DEFAULT 0.00,
  `igv` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `codigo_hash` varchar(64) NOT NULL,
  `estado_sunat` varchar(50) NOT NULL DEFAULT 'Aceptado / Emitido',
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_comprobante`),
  UNIQUE KEY `uk_numero_comprobante` (`numero_completo`),
  KEY `fk_comprobante_pedido` (`id_pedido`),
  CONSTRAINT `fk_comprobante_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos_tienda` (`id_pedido`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
