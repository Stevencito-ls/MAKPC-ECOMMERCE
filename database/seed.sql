USE makpc_enterprises_db;
INSERT INTO configuracion_empresa (id, ruc, razon_social, nombre_comercial, telefono, email, direccion, dias_garantia_defecto, clausula_garantia, ultimo_correlativo_recibo) VALUES (1, '20409456520', 'MAK-PC ENTERPRISES S.A.C.', 'MAK-PC Soporte Tecnologico', '960 702 605', 'soporte@makpc.com.pe', 'Tumbes, Peru', 90, 'La garantia solo cubre defectos de fabrica y fallas en la reparacion realizada, no cubre daños por mal uso.', 400);

-- password for admin is 'admin123', generated via password_hash('admin123', PASSWORD_DEFAULT) -> $2y.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
INSERT INTO usuarios (id, nombre_completo, dni, email, usuario, password_hash, rol, telefono, activo) VALUES (1, 'Administrador del Sistema', '12345678', 'admin@makpc.com', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ADMIN', '999999999', 1);

