-- ==============================================================================
-- SCRIPT DE MIGRACI�N: PARCHE FIX #3109 (Columnas Virtuales vs Auto Increment)
-- ==============================================================================
-- Este script aplica exclusivamente los cambios para solucionar el error de MySQL
-- donde una columna virtual no puede referenciar a un AUTO_INCREMENT.

USE mkpc_enterprises;

-- 1. Agregar notas_cliente (requerido por E-Commerce) si no existe
-- (En MySQL < 8.0 no hay ADD COLUMN IF NOT EXISTS de forma nativa, 
--  as� que si la columna ya existe lanzar� un error leve que puedes ignorar).
ALTER TABLE clientes ADD COLUMN notas_cliente TEXT NULL AFTER direccion;

-- 2. Eliminar las columnas virtuales conflictivas que causaron el Error #3109
ALTER TABLE usuarios DROP COLUMN id_usuario;
ALTER TABLE clientes DROP COLUMN id_cliente;
ALTER TABLE ordenes_servicio DROP COLUMN id_orden;
ALTER TABLE ordenes_servicio DROP COLUMN id_equipo;

-- Nota: No es necesario eliminar id_cliente de ordenes_servicio porque 
-- referenciaba a cliente_id (no auto_increment), por lo que es v�lida,
-- pero los modelos PHP ya fueron actualizados para usar id de forma nativa.
