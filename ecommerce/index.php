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
$routeString = $_GET['action'] ?? $_GET['r'] ?? '';
if (empty($routeString) && !empty($_SERVER['PATH_INFO'])) {
    $routeString = $_SERVER['PATH_INFO'];
}

$parts = explode('/', trim($routeString, '/'));
$action = $parts[0] ?? '';
$param  = $parts[1] ?? '';

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
        $sub = $param ?: ($_GET['slug'] ?? '');
        $adminActions = ['crear', 'editar', 'eliminar', 'toggle'];
        
        // Determinar si es una ruta administrativa
        if (($sub === '' || in_array($sub, $adminActions)) && isLoggedIn() && hasRole(['admin', 'vendedor'])) {
            require_once __DIR__ . '/controllers/ProductoController.php';
            $controller = new ProductoController();
            $method = $sub === '' ? 'index' : $sub;
            $id = isset($parts[2]) ? $parts[2] : ($_GET['id'] ?? null);
            if ($method !== 'index' && $id !== null) {
                $controller->$method($id);
            } else {
                $controller->$method();
            }
        } else {
            $tienda->producto($sub);
        }
        break;

    // Controladores Administrativos
    case 'pedido':
    case 'orden':
    case 'cliente':
    case 'equipo':
    case 'componente':
    case 'usuario':
        $controllersMap = [
            'pedido' => 'PedidoController',
            'orden' => 'OrdenController',
            'cliente' => 'ClienteController',
            'equipo' => 'EquipoController',
            'componente' => 'ComponenteController',
            'usuario' => 'UsuarioController'
        ];
        $controllerName = $controllersMap[$action];
        require_once __DIR__ . '/controllers/' . $controllerName . '.php';
        $controller = new $controllerName();
        $method = $param ?: 'index';
        if (!method_exists($controller, $method)) {
            $method = 'index';
        }
        $id = isset($parts[2]) ? $parts[2] : ($_GET['id'] ?? null);
        if ($method !== 'index' && $id !== null) {
            $controller->$method($id);
        } else {
            $controller->$method();
        }
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
