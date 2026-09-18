<?php

/**
 * Configuración general de la aplicación MAKPC Enterprises S.A.C.
 * Preparada para entorno local y despliegue en la nube (Cloud-Ready)
 */

// Cargar archivo .env si existe en la raíz
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile) && is_readable($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, " \t\n\r\0\x0B\"'");
            if (!getenv($key)) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}

// Constantes Generales
define('APP_NAME', getenv('APP_NAME') ?: 'Ecommerce MAKPC');
define('APP_SLOGAN', 'Tienda Oficial & Soluciones Informáticas');
define('APP_VERSION', '1.2.0');
define('APP_ENV', getenv('APP_ENV') ?: 'development');

// Configuración de Errores según entorno
if (APP_ENV === 'production') {
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

// Detección Inteligente de BASE_URL (Compatible con Nginx, Cloudflare, AWS, Apache)
if (!defined('BASE_URL')) {
    $configuredBase = getenv('BASE_URL');
    if (!empty($configuredBase)) {
        define('BASE_URL', rtrim($configuredBase, '/') . '/');
    } else {
        if (php_sapi_name() === 'cli') {
            define('BASE_URL', '/MAKPC/');
        } else {
            $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
            $scriptDir  = rtrim(dirname($scriptName), '/\\');
            if ($scriptDir !== '' && $scriptDir !== '.' && $scriptDir !== '/') {
                define('BASE_URL', $scriptDir . '/');
            } else {
                define('BASE_URL', '/');
            }
        }
    }
}

if (!defined('ASSETS_URL')) {
    define('ASSETS_URL', BASE_URL . 'assets/');
}

// Colores corporativos
define('COLOR_YELLOW', '#FCC827');
define('COLOR_BLUE', '#161D45');
define('COLOR_CELESTE', '#05A9E9');
define('COLOR_WHITE', '#FAFAFA');
define('COLOR_LAVENDER', '#E7E9F7');
define('COLOR_SHADOW', '#8E9394');

// Pasarela de Pagos Culqi (Modo Sandbox de Pruebas)
define('CULQI_PUBLIC_KEY', getenv('CULQI_PUBLIC_KEY') ?: 'pk_test_b8e5dbad0a0ff6a2');
define('CULQI_PRIVATE_KEY', getenv('CULQI_PRIVATE_KEY') ?: 'sk_test_615bc063ca8bb233');

// Zona horaria
date_default_timezone_set('America/Lima');

// Helper URLs
/**
 * Generar URL absoluta de la aplicación
 * @param string $path
 * @return string
 */
function url($path = '')
{
    return BASE_URL . ltrim($path, '/');
}

/**
 * Generar URL absoluta para assets estáticos
 * @param string $path
 * @return string
 */
function asset($path = '')
{
    return ASSETS_URL . ltrim($path, '/');
}

// ========================================================
// SEGURIDAD & TOKENS CSRF
// ========================================================

/**
 * Obtener o generar token CSRF para la sesión activa
 * @return string
 */
function csrf_token()
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        @session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Generar campo oculto con token CSRF para formularios
 * @return string
 */
function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

/**
 * Verificar token CSRF recibido en la petición
 * @param string|null $token
 * @return bool
 */
function verify_csrf($token = null)
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        @session_start();
    }
    if ($token === null) {
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    }
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

// ========================================================
// FLASH MESSAGES
// ========================================================

/**
 * Establecer mensaje flash en sesión
 * @param string $type
 * @param string $message
 * @return void
 */
function setFlash($type, $message)
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        @session_start();
    }
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Obtener y limpiar mensaje flash de sesión
 * @return array|null
 */
function getFlash()
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        @session_start();
    }
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Escapar cadenas HTML de forma segura contra XSS
 * @param mixed $string
 * @return string
 */
function e($string)
{
    return htmlspecialchars((string)($string ?? ''), ENT_QUOTES, 'UTF-8');
}

// ========================================================
// HELPERS DE AUTENTICACIÓN Y ROLES
// ========================================================

/**
 * Obtener datos o campo específico del usuario autenticado
 * @param string|null $field
 * @return mixed
 */
function auth($field = null)
{
    if (!isset($_SESSION['usuario_id'])) return null;
    if ($field) return $_SESSION[$field] ?? null;
    return [
        'id' => $_SESSION['usuario_id'],
        'usuario' => $_SESSION['usuario'] ?? '',
        'nombre_completo' => $_SESSION['nombre_completo'] ?? '',
        'rol' => $_SESSION['rol'] ?? ''
    ];
}

/**
 * Verificar si existe una sesión de usuario activa
 * @return bool
 */
function isLoggedIn()
{
    return isset($_SESSION['usuario_id']);
}

/**
 * Verificar si el usuario autenticado posee uno de los roles permitidos
 * @param array|string $roles
 * @return bool
 */
function hasRole($roles)
{
    if (!isset($_SESSION['usuario_id'])) return false;
    $allowed = is_array($roles) ? $roles : [$roles];
    return in_array($_SESSION['rol'] ?? '', $allowed);
}

/**
 * Formatear precio en Soles (PEN)
 * @param float|int|string|null $precio
 * @return string
 */
function formatPrecio($precio)
{
    return 'S/ ' . number_format((float)($precio ?? 0), 2, '.', ',');
}

/**
 * Generar código correlativo de orden de servicio
 * @param PDO $pdo
 * @return string
 */
function generarCodigoOrden($pdo)
{
    $year = date('Y');
    $stmt = $pdo->query("SELECT COUNT(*) + 1 as next FROM ordenes_servicio WHERE YEAR(creado_en) = $year");
    $next = $stmt->fetch(PDO::FETCH_ASSOC)['next'];
    return "ORD-{$year}-" . str_pad($next, 4, '0', STR_PAD_LEFT);
}

/**
 * Generar código correlativo de ticket de atención
 * @param PDO $pdo
 * @return string
 */
function generarCodigoTicket($pdo)
{
    $year = date('Y');
    $stmt = $pdo->query("SELECT COUNT(*) + 1 as next FROM tickets_soporte WHERE YEAR(creado_en) = $year");
    $next = $stmt->fetch(PDO::FETCH_ASSOC)['next'];
    return "TKT-{$year}-" . str_pad($next, 4, '0', STR_PAD_LEFT);
}

/**
 * Representar un monto monetario en letras para comprobantes SUNAT
 * @param float|int $monto
 * @return string
 */
if (!function_exists('montoEnLetrasSoles')) {
    function montoEnLetrasSoles($monto) {
        $monto = (float)$monto;
        $enteros = floor($monto);
        $centavos = str_pad((string)round(($monto - $enteros) * 100), 2, '0', STR_PAD_LEFT);
        return "SON: " . number_format($enteros, 0, '', ',') . " Y {$centavos}/100 SOLES";
    }
}
