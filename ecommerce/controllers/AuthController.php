<?php
/**
 * AuthController - Controlador de Autenticación con Máxima Seguridad
 * Protección CSRF, Rate Limiting contra Fuerza Bruta y Gestión Segura de Sesiones
 */
class AuthController extends Controller {

    /** @var Usuario */
    private $usuarioModel;

    // Configuración de Rate Limiting
    private const MAX_INTENTOS_FALLIDOS = 5;
    private const TIEMPO_BLOQUEO_SEGUNDOS = 300; // 5 minutos de bloqueo tras exceder intentos

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    /**
     * Mostrar vista y procesar autenticación de usuario
     */
    public function login() {
        // Si ya está autenticado, redirigir al panel principal
        if (isLoggedIn()) {
            $this->redirect('panel');
        }

        $error = null;
        $bloqueadoHasta = 0;

        // Comprobar estado de Rate Limiting (Bloqueo temporal por intentos)
        if (isset($_SESSION['login_throttle']['lock_until'])) {
            $tiempoRestante = $_SESSION['login_throttle']['lock_until'] - time();
            if ($tiempoRestante > 0) {
                $minutos = ceil($tiempoRestante / 60);
                $error = "Acceso bloqueado temporalmente por seguridad tras múltiples intentos fallidos. Por favor espere {$minutos} minuto(s) antes de reintentar.";
                $bloqueadoHasta = $_SESSION['login_throttle']['lock_until'];
            } else {
                // El bloqueo expiró, reiniciar contador
                unset($_SESSION['login_throttle']);
            }
        }

        // Procesar formulario POST de Login
        if ($this->isPost()) {
            // Si sigue bloqueado, denegar inmediatamente
            if ($bloqueadoHasta > time()) {
                $tiempoRestante = $bloqueadoHasta - time();
                $minutos = ceil($tiempoRestante / 60);
                $error = "Acceso bloqueado temporalmente. Por favor espere {$minutos} minuto(s).";
            } else {
                // 1. Verificación de Token CSRF
                $csrfToken = $this->input('csrf_token', '');
                if (!verify_csrf($csrfToken)) {
                    $error = 'Token de seguridad inválido o sesión expirada. Por favor intente nuevamente.';
                } else {
                    $usuario = trim($this->input('usuario', ''));
                    $password = trim($this->input('password', ''));

                    if (empty($usuario) || empty($password)) {
                        $error = 'Por favor complete su usuario y contraseña.';
                    } else {
                        // 2. Búsqueda de usuario y verificación criptográfica
                        $user = $this->usuarioModel->findByUsuario($usuario);

                        if ($user && !empty($user['activo']) && password_verify($password, $user['password_hash'])) {
                            // Éxito: Limpiar intentos fallidos
                            unset($_SESSION['login_throttle']);

                            // Regenerar ID de sesión para prevenir fijación de sesión
                            session_regenerate_id(true);

                            // Regenerar token CSRF para la nueva sesión autenticada
                            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                            $_SESSION['usuario_id'] = (int)$user['id_usuario'];
                            $_SESSION['usuario'] = $user['usuario'];
                            $_SESSION['nombre_completo'] = $user['nombre_completo'];
                            $_SESSION['rol'] = $user['rol'];
                            $_SESSION['login_time'] = time();

                            setFlash('success', '¡Bienvenido(a) al sistema, ' . htmlspecialchars($user['nombre_completo']) . '!');

                            // Redirección inteligente
                            $intended = $_SESSION['intended_url'] ?? '';
                            unset($_SESSION['intended_url']);

                            if (!empty($intended) && strpos($intended, 'login') === false) {
                                header('Location: ' . $intended);
                                exit;
                            }

                            $this->redirect('panel');
                        } else {
                            // Fallo de autenticación: Registrar intento fallido (Rate Limiting)
                            if (!isset($_SESSION['login_throttle'])) {
                                $_SESSION['login_throttle'] = [
                                    'intentos' => 0,
                                    'primer_intento' => time(),
                                    'lock_until' => 0
                                ];
                            }

                            $_SESSION['login_throttle']['intentos']++;
                            $intentosRestantes = self::MAX_INTENTOS_FALLIDOS - $_SESSION['login_throttle']['intentos'];

                            if ($_SESSION['login_throttle']['intentos'] >= self::MAX_INTENTOS_FALLIDOS) {
                                $_SESSION['login_throttle']['lock_until'] = time() + self::TIEMPO_BLOQUEO_SEGUNDOS;
                                $error = "Ha superado el límite de 5 intentos fallidos. Su acceso ha sido bloqueado por 5 minutos por protocolo de seguridad.";
                            } else {
                                $error = "Credenciales incorrectas o cuenta inactiva. Le quedan {$intentosRestantes} intento(s) antes del bloqueo de seguridad.";
                            }
                        }
                    }
                }
            }
        }

        $this->view('auth/login', [
            'title' => 'Acceso Seguro al Sistema | MAKPC Enterprises',
            'error' => $error,
            'usuario_previo' => $this->input('usuario', ''),
            'bloqueado' => ($bloqueadoHasta > time())
        ]);
    }

    /**
     * Cerrar sesión de usuario de forma segura
     */
    public function logout() {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params["path"],
                    $params["domain"],
                    $params["secure"],
                    $params["httponly"]
                );
            }
            session_destroy();
        }

        session_start();
        session_regenerate_id(true);
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        setFlash('info', 'Has cerrado sesión de forma segura.');
        $this->redirect('login');
    }
}
