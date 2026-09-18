<?php
/**
 * Controlador Base
 */
class Controller {
    
    /**
     * @param string $view
     * @param array $data
     */
    protected function view($view, $data = []) {
        // Extraer variables para la vista
        extract($data);
        
        // Determinar ruta de vista dentro de ecommerce/views/
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        if (!file_exists($viewPath) && strpos($view, 'tienda/') === 0) {
            $ecomSubView = substr($view, 7);
            $candidate = __DIR__ . '/../views/' . $ecomSubView . '.php';
            if (file_exists($candidate)) {
                $viewPath = $candidate;
            }
        } elseif (!file_exists($viewPath)) {
            $candidate = __DIR__ . '/../views/tienda/' . $view . '.php';
            if (file_exists($candidate)) {
                $viewPath = $candidate;
            }
        }
        
        if (!file_exists($viewPath)) {
            die("Vista no encontrada en Ecommerce MAKPC: $view");
        }

        // Cargar layout con la vista incluida
        $content = $viewPath;
        
        // Si la vista es de autenticación, renderizarla de forma autónoma
        if (strpos($view, 'auth/') === 0) {
            require $viewPath;
        } elseif (strpos($view, 'tienda/') === 0 || !isLoggedIn()) {
            // Vistas públicas de tienda o usuarios no autenticados (incluyendo 404 público)
            require __DIR__ . '/../layouts/header.php';
            require $viewPath;
            require __DIR__ . '/../layouts/footer.php';
        } else {
            // Vistas de administración / intranet
            require __DIR__ . '/../layouts/admin_header.php';
            require __DIR__ . '/../layouts/sidebar.php';
            echo '<main class="main-content">';
            require $viewPath;
            echo '</main>';
            require __DIR__ . '/../layouts/admin_footer.php';
        }
    }

    /**
     * @param string $path
     */
    protected function redirect($path) {
        header('Location: ' . url($path));
        exit;
    }

    /**
     * @param mixed $data
     * @param int $code
     */
    protected function json($data, $code = 200) {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function isPost() {
        return ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST';
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function input($key, $default = '') {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    /**
     * Exigir que el usuario esté autenticado
     */
    protected function requireAuth() {
        if (!isset($_SESSION['usuario_id'])) {
            $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'] ?? '';
            setFlash('warning', 'Debe iniciar sesión para acceder a esta sección.');
            $this->redirect('login');
        }
    }

    /**
     * Exigir que el usuario tenga un rol específico (o varios roles permitidos)
     * @param array|string $roles
     */
    protected function requireRole($roles) {
        $this->requireAuth();
        $allowed = is_array($roles) ? $roles : [$roles];
        $userRole = $_SESSION['rol'] ?? '';

        if (!in_array($userRole, $allowed)) {
            setFlash('danger', 'Acceso denegado: tu rol (' . ucfirst($userRole) . ') no tiene permisos para esta acción.');
            if ($userRole === 'vendedor') {
                $this->redirect('orden');
            } else {
                $this->redirect('panel');
            }
        }
    }

    /**
     * Obtener el usuario autenticado
     * @param string|null $field
     * @return mixed
     */
    protected function currentUser($field = null) {
        if (!isset($_SESSION['usuario_id'])) return null;
        if ($field) return $_SESSION[$field] ?? null;
        return [
            'id' => $_SESSION['usuario_id'],
            'usuario' => $_SESSION['usuario'] ?? '',
            'nombre' => $_SESSION['nombre_completo'] ?? '',
            'rol' => $_SESSION['rol'] ?? ''
        ];
    }

    /**
     * Verificar si el usuario autenticado tiene un rol
     * @param array|string $roles
     * @return bool
     */
    protected function hasRole($roles) {
        if (!isset($_SESSION['usuario_id'])) return false;
        $allowed = is_array($roles) ? $roles : [$roles];
        return in_array($_SESSION['rol'] ?? '', $allowed);
    }
}
