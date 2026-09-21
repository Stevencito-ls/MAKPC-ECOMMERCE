<?php
declare(strict_types=1);

/**
 * ==============================================================================
 * MAK-PC Enterprises S.A.C. - API REST Router & Front Controller
 * ==============================================================================
 */

// 1. Manejo de CORS y Preflight OPTIONS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 2. Autoloader PSR-4 para clases internas
spl_autoload_register(function ($class) {
    $prefixMap = [
        'Config\\'      => __DIR__ . '/config/',
        'Controllers\\' => __DIR__ . '/controllers/',
        'Services\\'    => __DIR__ . '/services/',
        'Utils\\'       => __DIR__ . '/utils/'
    ];

    foreach ($prefixMap as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) === 0) {
            $relativeClass = substr($class, $len);
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});

use Config\Response;
use Controllers\ClienteController;
use Controllers\OrdenController;
use Controllers\ReciboController;
use Controllers\DashboardController;

// 3. Normalizar URI y Método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Detección robusta de la ruta del endpoint
if (!empty($_SERVER['PATH_INFO'])) {
    $uri = $_SERVER['PATH_INFO'];
} elseif (!empty($_GET['r']) || !empty($_GET['route'])) {
    $uri = $_GET['r'] ?? $_GET['route'];
} else {
    $rawPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
    
    // Si contiene index.php en la ruta (ej: /MAK-PC/backend/index.php/api/health)
    $posIndex = strpos($rawPath, 'index.php');
    if ($posIndex !== false) {
        $uri = substr($rawPath, $posIndex + strlen('index.php'));
    } else {
        // Remover prefijo del script si se ejecuta en subcarpeta sin index.php (ej. /MAK-PC/backend/api/health)
        $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
        if ($scriptDir !== '/' && $scriptDir !== '\\' && strpos($rawPath, $scriptDir) === 0) {
            $uri = substr($rawPath, strlen($scriptDir));
        } else {
            $uri = $rawPath;
        }
    }
}

$uri = '/' . trim((string)$uri, '/');
if (empty($uri) || $uri === '/') {
    $uri = '/api/health';
}

// Router Manual Eficiente
try {
    // ---- ENDPOINTS DE DASHBOARD ----
    if ($uri === '/api/dashboard/resumen' && $method === 'GET') {
        (new DashboardController())->getResumen();
    }

    // ---- ENDPOINTS DE CLIENTES ----
    elseif (($uri === '/api/clientes' || $uri === '/api/clientes/buscar') && $method === 'GET') {
        (new ClienteController())->search();
    }
    elseif ($uri === '/api/clientes' && $method === 'POST') {
        (new ClienteController())->create();
    }
    elseif (preg_match('#^/api/clientes/(\d+)$#', $uri, $matches) && $method === 'GET') {
        (new ClienteController())->getById((int)$matches[1]);
    }
    elseif (preg_match('#^/api/clientes/documento/([a-zA-Z0-9_-]+)$#', $uri, $matches) && $method === 'GET') {
        (new ClienteController())->getByDocumento($matches[1]);
    }

    // ---- ENDPOINTS DE ÓRDENES DE SERVICIO ----
    elseif ($uri === '/api/ordenes' && $method === 'GET') {
        (new OrdenController())->index();
    }
    elseif ($uri === '/api/ordenes' && $method === 'POST') {
        (new OrdenController())->create();
    }
    elseif (preg_match('#^/api/ordenes/(\d+)$#', $uri, $matches) && $method === 'GET') {
        (new OrdenController())->getById((int)$matches[1]);
    }
    elseif (preg_match('#^/api/ordenes/(\d+)/estado$#', $uri, $matches) && $method === 'PUT') {
        (new OrdenController())->updateEstado((int)$matches[1]);
    }

    // ---- ENDPOINTS DE RECIBOS Y COMPROBANTES A5 ----
    elseif ($uri === '/api/recibos/siguiente-correlativo' && $method === 'GET') {
        (new ReciboController())->getSiguienteCorrelativo();
    }
    elseif ($uri === '/api/recibos' && $method === 'POST') {
        (new ReciboController())->create();
    }
    elseif (preg_match('#^/api/recibos/(\d+)$#', $uri, $matches) && $method === 'GET') {
        (new ReciboController())->getById((int)$matches[1]);
    }
    elseif (preg_match('#^/api/recibos/orden/(\d+)$#', $uri, $matches) && $method === 'GET') {
        (new ReciboController())->getByOrdenId((int)$matches[1]);
    }
    elseif (preg_match('#^/api/recibos/(\d+)/liquidar-saldo$#', $uri, $matches) && $method === 'PUT') {
        (new ReciboController())->liquidarSaldo((int)$matches[1]);
    }
    elseif (preg_match('#^/api/recibos/(\d+)/anular$#', $uri, $matches) && $method === 'PUT') {
        (new ReciboController())->anular((int)$matches[1]);
    }

    // ---- UTILIDADES DE APOYO EN MOSTRADOR (FASE 3) ----
    elseif ($uri === '/api/util/numero-a-letras' && $method === 'GET') {
        $monto = $_GET['monto'] ?? 0.00;
        $letras = \Utils\NumeroALetrasHelper::convertir((float)$monto);
        Response::ok(['monto' => (float)$monto, 'monto_letras' => $letras]);
    }
    elseif ($uri === '/api/util/validar-garantia' && $method === 'GET') {
        $vencimiento = $_GET['vencimiento'] ?? date('Y-m-d');
        $eval = \Utils\GarantiaHelper::validarEstadoGarantia($vencimiento);
        Response::ok($eval);
    }

    // ---- ESTADO DE LA API / SALUD ----
    elseif ($uri === '/' || $uri === '/api' || $uri === '/api/health') {
        Response::ok([
            'sistema' => 'MAK-PC Enterprises S.A.C. - API REST Taller',
            'version' => '1.0.0',
            'estado'  => 'ONLINE',
            'timestamp' => date('Y-m-d H:i:s')
        ], 'API activa y operativa');
    }

    // ---- RUTA NO ENCONTRADA ----
    else {
        Response::notFound("Ruta no encontrada: [{$method}] {$uri}");
    }

} catch (Throwable $e) {
    Response::serverError("Excepción no controlada en el servidor: " . $e->getMessage(), [
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
