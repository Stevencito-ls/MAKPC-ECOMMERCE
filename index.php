<?php
/**
 * MAKPC - Enrutador Central del Ecosistema
 * 
 * Estructura del proyecto:
 * - /landing/    -> Landing Page Institucional
 * - /ecommerce/  -> Ecommerce MAKPC (MVC Completo)
 */

$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$appDir     = rtrim(dirname($scriptName), '/\\');
$basePath   = ($appDir === '' || $appDir === '.' || $appDir === '/') ? '/' : $appDir . '/';

// Parsear sub-ruta solicitada
$path    = parse_url($requestUri, PHP_URL_PATH) ?? '';
$subPath = trim(substr($path, strlen($appDir)), '/');

// Enrutamiento inteligente hacia Ecommerce MAKPC
if (
    strpos($subPath, 'ecommerce') === 0 ||
    strpos($subPath, 'tienda') === 0 ||
    strpos($subPath, 'carrito') === 0 ||
    strpos($subPath, 'checkout') === 0 ||
    strpos($subPath, 'crear-pc') === 0 ||
    strpos($subPath, 'armar-pc') === 0 ||
    strpos($subPath, 'pc-builder') === 0 ||
    strpos($subPath, 'soporte') === 0 ||
    strpos($subPath, 'taller') === 0 ||
    strpos($subPath, 'comprobante') === 0 ||
    strpos($subPath, 'producto') === 0 ||
    strpos($subPath, 'admin') === 0 ||
    strpos($subPath, 'panel') === 0 ||
    strpos($subPath, 'login') === 0
) {
    $target = $basePath . 'ecommerce/';
    if (strpos($subPath, 'carrito') === 0) {
        $target .= '?action=carrito';
    } elseif (strpos($subPath, 'checkout') === 0) {
        $target .= '?action=checkout';
    } elseif (strpos($subPath, 'crear-pc') === 0 || strpos($subPath, 'armar-pc') === 0 || strpos($subPath, 'pc-builder') === 0) {
        $target .= '?action=crear-pc';
    } elseif (strpos($subPath, 'soporte') === 0 || strpos($subPath, 'taller') === 0) {
        $target .= '?action=soporte';
    } elseif (strpos($subPath, 'comprobante') === 0) {
        $target .= '?action=comprobante';
    } elseif (strpos($subPath, 'login') === 0) {
        $target .= '?action=login';
    } elseif (strpos($subPath, 'panel') === 0 || strpos($subPath, 'admin') === 0) {
        $target .= '?action=panel';
    } elseif (strpos($subPath, 'producto') === 0) {
        $parts = explode('/', $subPath);
        $slug  = $parts[1] ?? '';
        $target .= '?action=producto' . ($slug ? '&slug=' . urlencode($slug) : '');
    }
    header('Location: ' . $target);
    exit;
}

// Por defecto, redirigir a la Landing Page institucional
header('Location: ' . $basePath . 'landing/');
exit;
