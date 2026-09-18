<?php
/**
 * Layout: Encabezado HTML y Carga de Estilos (head.php)
 * MAKPC Enterprises S.A.C.
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="MAK-PC Enterprises SAC - Tecnología de vanguardia en Tumbes. Soporte técnico, venta de repuestos y servicios informáticos especializados." />
    <title>MAK-PC Enterprises SAC &bull; Tecnología de Vanguardia en Tumbes</title>
    
    <!-- Frameworks y Librerías Externas -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Google Fonts: Inter & Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Estilos Modulares Oficiales Netlify -->
    <link rel="stylesheet" href="css/base.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="css/header.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="css/tienda.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="css/conocenos.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="css/servicios.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="css/convenios.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="css/datos.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="css/footer.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="css/responsive.css?v=<?= time() ?>" />
    <link rel="stylesheet" href="css/mobile.css?v=<?= time() ?>" />
    
    <!-- Menú Lateral Flotante de Redes Sociales -->
    <link rel="stylesheet" href="css/MenuFlotante.css?v=<?= time() ?>" />
    
    <link rel="icon" href="imagenes/logo.png" type="image/png" />

    <style>
      /* Botón de acción hacia la Tienda Online */
      .nav-tienda-btn {
        background: #fcc827 !important;
        color: #1e3a8a !important;
        font-weight: 700 !important;
        padding: 0.55rem 1.15rem !important;
        border-radius: 8px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        box-shadow: 0 4px 10px rgba(252,200,39,0.3) !important;
        transition: all 0.3s ease !important;
        margin-left: 0.5rem;
        text-decoration: none !important;
      }
      .nav-tienda-btn:hover {
        background: #1e3a8a !important;
        color: #ffffff !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 14px rgba(30,58,138,0.3) !important;
      }
      .btn-visita-online {
        display: inline-block;
        background: #fcc827;
        color: #1e3a8a !important;
        font-weight: 800;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        text-decoration: none !important;
        margin-top: 1rem;
        box-shadow: 0 4px 12px rgba(252,200,39,0.35);
        transition: all 0.3s ease;
      }
      .btn-visita-online:hover {
        background: #1e3a8a;
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(30,58,138,0.3);
      }
    </style>
</head>
<body>
