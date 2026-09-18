<?php
/**
 * Router - Enrutamiento basado en URL segments
 * URL: /MAKPC/controlador/accion/param1/param2
 */
class Router {
    private $controller = 'HomeController';
    private $method = 'index';
    private $params = [];

    public function dispatch() {
        $url = $this->parseUrl();

        // Cargar modelos necesarios
        $this->loadModels();

        // Determinar controlador
        if (!empty($url[0])) {
            $alias = strtolower($url[0]);
            if ($alias === 'admin' || $alias === 'dashboard') {
                $url[0] = 'panel';
            } elseif ($alias === 'login') {
                $url[0] = 'auth';
                $url[1] = 'login';
            } elseif ($alias === 'logout') {
                $url[0] = 'auth';
                $url[1] = 'logout';
            } elseif ($alias === 'crear-pc' || $alias === 'armar-pc' || $alias === 'pc-builder') {
                $url[0] = 'tienda';
                $url[1] = 'crearPc';
            } elseif ($alias === 'ecommerce') {
                $url[0] = 'tienda';
            } elseif ($alias === 'carrito') {
                $url[0] = 'tienda';
                $url[1] = 'carrito';
            } elseif ($alias === 'checkout') {
                $url[0] = 'tienda';
                $url[1] = 'checkout';
            } elseif ($alias === 'taller' || $alias === 'soporte' || $alias === 'seguimiento' || $alias === 'rastreo' || $alias === 'rastrear-orden') {
                $url[0] = 'tienda';
                $url[1] = 'soporte';
            } elseif ($alias === 'comprobante') {
                $url[0] = 'tienda';
                $url[1] = 'comprobante';
            } elseif ($alias === 'consulta-documento' || $alias === 'api-documento') {
                $url[0] = 'tienda';
                $url[1] = 'consultarDocumento';
            } elseif ($alias === 'pedidos' || $alias === 'ventas') {
                $url[0] = 'pedido';
            } elseif ($alias === 'ordenes') {
                $url[0] = 'orden';
            } elseif ($alias === 'clientes') {
                $url[0] = 'cliente';
            } elseif ($alias === 'equipos') {
                $url[0] = 'equipo';
            } elseif ($alias === 'componentes') {
                $url[0] = 'componente';
            } elseif ($alias === 'productos') {
                $url[0] = 'producto';
            } elseif ($alias === 'usuarios') {
                $url[0] = 'usuario';
            } elseif ($alias === 'landing' || $alias === 'institucional') {
                header('Location: ' . (defined('BASE_URL') ? BASE_URL . '/landing/' : 'landing/'));
                exit;
            }

            $controllerName = ucfirst($url[0]) . 'Controller';
            $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';
            
            if (!file_exists($controllerFile)) {
                // Resolución inteligente de plurales en español (ej. ordenes -> orden, clientes -> cliente)
                $singular = null;
                if (str_ends_with(strtolower($url[0]), 'es')) {
                    $singular = substr($url[0], 0, -2);
                } elseif (str_ends_with(strtolower($url[0]), 's')) {
                    $singular = substr($url[0], 0, -1);
                }
                if ($singular) {
                    $singularName = ucfirst($singular) . 'Controller';
                    $singularFile = __DIR__ . '/../controllers/' . $singularName . '.php';
                    if (file_exists($singularFile)) {
                        $controllerName = $singularName;
                        $controllerFile = $singularFile;
                    }
                }
            }

            if (file_exists($controllerFile)) {
                $this->controller = $controllerName;
                unset($url[0]);
            } else {
                // 404
                http_response_code(404);
                $this->controller = 'HomeController';
                $this->method = 'notFound';
            }
        }

        // Cargar controlador
        require_once __DIR__ . '/../controllers/' . $this->controller . '.php';
        $controllerInstance = new $this->controller();

        // Determinar método (soporta camelCase y kebab-case ej: crear-pc -> crearPc)
        if (isset($url[1])) {
            $actionCamel = lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $url[1]))));
            if (method_exists($controllerInstance, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            } elseif (method_exists($controllerInstance, $actionCamel)) {
                $this->method = $actionCamel;
                unset($url[1]);
            }
        }

        // Parámetros restantes
        $this->params = $url ? array_values($url) : [];

        // Ejecutar
        call_user_func_array([$controllerInstance, $this->method], $this->params);
    }

    private function parseUrl() {
        $url = $_GET['url'] ?? '';
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        return $url ? explode('/', $url) : [];
    }

    private function loadModels() {
        $modelDir = __DIR__ . '/../models/';
        if (is_dir($modelDir)) {
            foreach (glob($modelDir . '*.php') as $model) {
                require_once $model;
            }
        }
    }
}
