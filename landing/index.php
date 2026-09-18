<?php
/**
 * MAKPC Enterprises S.A.C. - Landing Page Institucional
 * Arquitectura Modular y Responsiva
 * 
 * Layouts: landing/layouts/ (head, navbar, floating_menu, footer)
 * Secciones: landing/sections/ (hero, tienda, conocenos, servicios, convenios, datos)
 */

// Detección de URL base dinámica hacia el e-commerce y centro de servicios
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$appDir = preg_replace('#/landing(/[^/]*)*$#i', '', dirname($scriptName));
if ($appDir === '.' || $appDir === '/') {
    $appBase = '/';
} else {
    $appBase = rtrim($appDir, '/') . '/';
}

$ecommerceUrl = $appBase . 'ecommerce/';
$cartUrl      = $appBase . 'ecommerce/?action=carrito';
$builderUrl   = $appBase . 'ecommerce/?action=crear-pc';
$soporteUrl   = $appBase . 'ecommerce/?action=soporte';
$loginUrl     = $appBase . 'ecommerce/?action=login';

// 1. Cabecera HTML y Recursos Globales
require_once __DIR__ . '/layouts/head.php';

// 2. Banner Principal (Hero Header con Navegación)
require_once __DIR__ . '/sections/hero.php';
?>

<!-- CONTENIDO PRINCIPAL DE LA LANDING PAGE -->
<main>
    <?php
    // 3. Sección Tienda y Horarios
    require_once __DIR__ . '/sections/tienda.php';

    // 4. Sección Conócenos Más (Historia, Misión, Visión, Objetivos)
    require_once __DIR__ . '/sections/conocenos.php';

    // 5. Sección Servicios y Soporte Técnico
    require_once __DIR__ . '/sections/servicios.php';

    // 6. Sección Convenios Institucionales
    require_once __DIR__ . '/sections/convenios.php';

    // 7. Sección Ficha Legal de la Empresa y Mapa
    require_once __DIR__ . '/sections/datos.php';

    // 8. Menú Lateral Flotante de Redes Sociales / FAB Móvil
    require_once __DIR__ . '/layouts/floating_menu.php';
    ?>
</main>

<hr class="linea-divider" />

<?php
// 9. Pie de Página Corporativo y Scripts
require_once __DIR__ . '/layouts/footer.php';
