<?php
/**
 * ==============================================================================
 * MAKPC ENTERPRISES S.A.C. - ENRUTADOR MAESTRO Y PUERTA DE ENLACE UNIFICADA
 * ==============================================================================
 * Arquitectura Empresarial Integrada:
 * 1. Landing Page Corporativa  -> /landing/, /, /inicio, /empresa, /conocenos
 * 2. Tienda Online E-commerce  -> /ecommerce/, /tienda, /catalogo, /producto, /carrito, /checkout, /crear-pc
 * 3. Intranet & Panel Admin    -> 
 * /login, /logout, /panel, /admin, /dashboard, /pedido, /orden, /caja, /inventario
 * 4. Taller & Mostrador POS    -> /taller, /mostrador, /taller-pos, /recepcion, /MAK-PC-CLIENTES/
 * 5. API REST Empresarial      -> /api/*
 * ==============================================================================
 */

// 1. Detección Inteligente de URL Base del Ecosistema
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$appDir     = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
$basePath   = ($appDir === '' || $appDir === '.' || $appDir === '/') ? '/' : $appDir . '/';

// 2. Extraer la ruta solicitada (soporte para Apache mod_rewrite ?url=, PATH_INFO y REQUEST_URI)
$requestedRoute = '';
if (!empty($_GET['url'])) {
    $requestedRoute = trim((string)$_GET['url'], '/');
} elseif (!empty($_GET['r'])) {
    $requestedRoute = trim((string)$_GET['r'], '/');
} elseif (!empty($_GET['action'])) {
    $requestedRoute = trim((string)$_GET['action'], '/');
} elseif (!empty($_SERVER['PATH_INFO'])) {
    $requestedRoute = trim((string)$_SERVER['PATH_INFO'], '/');
} else {
    $rawPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
    if ($appDir !== '' && $appDir !== '/' && strpos($rawPath, $appDir) === 0) {
        $requestedRoute = trim(substr($rawPath, strlen($appDir)), '/');
    } else {
        $requestedRoute = trim($rawPath, '/');
    }
}

// Limpiar prefijo de script si existe en la ruta (ej. index.php/tienda)
if (str_starts_with($requestedRoute, 'index.php')) {
    $requestedRoute = trim(substr($requestedRoute, strlen('index.php')), '/');
}

$segments = explode('/', $requestedRoute);
$primaryRoute = strtolower($segments[0] ?? '');
$secondaryRoute = $segments[1] ?? '';

// Definir constante del ecosistema
if (!defined('MAKPC_ECOSYSTEM_ROOT')) {
    define('MAKPC_ECOSYSTEM_ROOT', __DIR__);
}
if (!defined('MAKPC_BASE_PATH')) {
    define('MAKPC_BASE_PATH', $basePath);
}

// ==============================================================================
// 3. ENRUTAMIENTO INTELIGENTE POR MÓDULOS
// ==============================================================================

// --- MÓDULO A: API REST TALLER & CLIENTES (/api/*) ---
if ($primaryRoute === 'api') {
    // Pasar la sub-ruta a la API
    $_GET['r'] = '/' . $requestedRoute;
    require_once __DIR__ . '/MAK-PC-CLIENTES/backend/index.php';
    exit;
}

// --- MÓDULO B: TALLER Y MOSTRADOR OPERATIVO POS (/taller, /mostrador, /MAK-PC-CLIENTES) ---
$tallerAliases = ['taller', 'mostrador', 'taller-pos', 'recepcion', 'taller-operaciones', 'taller-atencion', 'mak-pc-clientes'];
if (in_array($primaryRoute, $tallerAliases, true)) {
    // Si la petición es directa a un archivo estático o subcarpeta, dejar o redirigir
    if ($primaryRoute === 'mak-pc-clientes' && isset($segments[1]) && $segments[1] === 'backend') {
        require_once __DIR__ . '/MAK-PC-CLIENTES/backend/index.php';
        exit;
    }
    require_once __DIR__ . '/MAK-PC-CLIENTES/frontend/index.php';
    exit;
}

// --- MÓDULO C: E-COMMERCE Y TIENDA ONLINE ---
// Rutas que pertenecen al flujo de compra y catálogo
$ecommerceStoreRoutes = [
    'ecommerce', 'tienda', 'catalogo', 'productos', 'producto',
    'carrito', 'checkout', 'procesar-pago', 'procesarpagoculqi',
    'comprobante', 'consulta-documento', 'api-documento', 'verificar-stock',
    'crear-pc', 'crearpc', 'armar-pc', 'pc-builder',
    'soporte', 'consultar-orden', 'consultarorden', 'crear-ticket', 'crearticket'
];

// Rutas de administración y autenticación
$ecommerceAdminRoutes = [
    'login', 'logout', 'panel', 'admin', 'dashboard',
    'pedido', 'pedidos', 'orden', 'ordenes', 'cliente', 'clientes', 
    'equipo', 'equipos', 'componente', 'componentes',
    'usuario', 'usuarios', 'caja', 'cajas', 'inventario', 'inventarios'
];

if (in_array($primaryRoute, $ecommerceStoreRoutes, true) || in_array($primaryRoute, $ecommerceAdminRoutes, true)) {
    // Normalizar la acción para el controlador de ecommerce
    $ecommerceAction = $requestedRoute;
    if ($primaryRoute === 'ecommerce' || $primaryRoute === 'tienda') {
        // Remover el prefijo ecommerce/ o tienda/
        $ecommerceAction = trim(substr($requestedRoute, strlen($primaryRoute)), '/');
        if (empty($ecommerceAction)) {
            $ecommerceAction = 'index';
        }
    }
    $_GET['action'] = $ecommerceAction;
    $_GET['r'] = $ecommerceAction;

    require_once __DIR__ . '/ecommerce/index.php';
    exit;
}

// --- MÓDULO D: LANDING PAGE INSTITUCIONAL (Ruta por Defecto /) ---
$landingAliases = ['', 'landing', 'inicio', 'home', 'empresa', 'nosotros', 'conocenos'];
if (in_array($primaryRoute, $landingAliases, true)) {
    require_once __DIR__ . '/landing/index.php';
    exit;
}

// --- FALLBACK 404: Si no coincide con ningún módulo, renderizar 404 del Ecommerce ---
$_GET['action'] = '404';
$_GET['r'] = '404';
require_once __DIR__ . '/ecommerce/index.php';
exit;
