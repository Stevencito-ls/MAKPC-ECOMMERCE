<?php
/**
 * Ecommerce MAKPC - Front Controller Autónomo
 * MAK-PC ENTERPRISES S.A.C.
 * 
 * Estructura de carpetas:
 * - ecommerce/controllers/ (Controladores MVC)
 * - ecommerce/models/ (Modelos de BD)
 * - ecommerce/views/ (Vistas de catálogo, producto, checkout, panel)
 * - ecommerce/layouts/ (Header, footer, sidebar admin)
 * - ecommerce/components/ (Ofertas flash, spotlight, marcas, garantías)
 * - ecommerce/assets/ (css/, js/, img/)
 * - ecommerce/core/ (Controller, Database, Model, Router)
 * - ecommerce/config/ (app, database)
 */

// Sesión segura
if (session_status() === PHP_SESSION_NONE) {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

    session_set_cookie_params([
        'lifetime' => 7200,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

// 1. Cargar Configuración de Ecommerce MAKPC
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';

// 2. Cargar Núcleo MVC
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/Controller.php';

// 3. Autocargar Modelos de Ecommerce MAKPC
foreach (glob(__DIR__ . '/models/*.php') as $modelFile) {
    require_once $modelFile;
}

// 4. Controladores
require_once __DIR__ . '/controllers/TiendaController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/PanelController.php';

$tienda = new TiendaController();

// 5. Detección Inteligente de Acción
$action = $_GET['action'] ?? $_GET['r'] ?? '';
$param  = $_GET['slug'] ?? $_GET['id'] ?? '';

if (empty($action) && !empty($_SERVER['PATH_INFO'])) {
    $parts = explode('/', trim($_SERVER['PATH_INFO'], '/'));
    $action = $parts[0] ?? '';
    $param  = $parts[1] ?? '';
}

if (empty($action)) {
    $action = 'index';
}

switch ($action) {
    // Carrito & Checkout
    case 'carrito':
        $tienda->carrito();
        break;
    case 'checkout':
        $tienda->checkout();
        break;
    case 'procesar-pago':
    case 'procesarPagoCulqi':
        $tienda->procesarPagoCulqi();
        break;
    case 'comprobante':
        $tienda->comprobante();
        break;
    case 'consulta-documento':
    case 'api-documento':
        $tienda->consultarDocumento();
        break;
    case 'verificar-stock':
    case 'verificarStock':
        $tienda->verificarStock();
        break;

    // Servicios & PC Builder
    case 'crear-pc':
    case 'crearPc':
    case 'armar-pc':
    case 'pc-builder':
        $tienda->crearPc();
        break;
    case 'soporte':
    case 'taller':
    case 'rastreo':
        $tienda->soporte();
        break;
    case 'consultar-orden':
    case 'consultarOrden':
        $tienda->consultarOrden();
        break;
    case 'crear-ticket':
    case 'crearTicket':
        $tienda->crearTicket();
        break;

    // Catálogo y Productos
    case 'producto':
        $slug = $param ?: ($_GET['slug'] ?? '');
        $tienda->producto($slug);
        break;

    // Autenticación & Panel
    case 'login':
        (new AuthController())->login();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;
    case 'panel':
    case 'admin':
    case 'dashboard':
        (new PanelController())->index();
        break;

    default:
        $tienda->index();
        break;
}
