<?php
/**
 * UsuarioController - Gestión de Usuarios, Roles y Auditoría del Sistema
 * Solo accesible por rol 'admin'
 */
class UsuarioController extends Controller {

    /** @var Usuario */
    private $usuarioModel;

    public function __construct() {
        $this->requireRole('admin');
        $this->usuarioModel = new Usuario();
    }

    /**
     * Listado de usuarios del sistema y auditoría
     */
    public function index() {
        $usuarios = $this->usuarioModel->all('id_usuario ASC');

        $this->view('usuarios/index', [
            'title' => 'Gestión de Usuarios & Roles | MAKPC',
            'usuarios' => $usuarios
        ]);
    }

    /**
     * Registrar un nuevo colaborador
     */
    public function crear() {
        if ($this->isPost()) {
            if (!verify_csrf($this->input('csrf_token'))) {
                setFlash('danger', 'Token de seguridad expirado. Intente nuevamente.');
                $this->redirect('usuario/crear');
            }

            $usuario = strtolower(trim($this->input('usuario', '')));
            $nombre = trim($this->input('nombre_completo', ''));
            $password = trim($this->input('password', ''));
            $rol = $this->input('rol', 'tecnico');

            if (empty($usuario) || empty($nombre) || empty($password)) {
                setFlash('danger', 'Todos los campos son obligatorios.');
                $this->redirect('usuario/crear');
            }

            // Verificar si usuario ya existe
            $existe = $this->usuarioModel->findByUsuario($usuario);
            if ($existe) {
                setFlash('danger', "El nombre de usuario '{$usuario}' ya está registrado.");
                $this->redirect('usuario/crear');
            }

            $hash = password_hash($password, PASSWORD_DEFAULT);
            $this->usuarioModel->create([
                'usuario' => $usuario,
                'nombre_completo' => $nombre,
                'email' => strtolower($usuario) . '@makpc.com.pe',
                'password_hash' => $hash,
                'rol' => in_array($rol, ['admin', 'tecnico', 'vendedor']) ? $rol : 'tecnico',
                'activo' => 1
            ]);

            setFlash('success', "Usuario '{$nombre}' registrado exitosamente con rol " . strtoupper($rol) . ".");
            $this->redirect('usuario');
        }

        $this->view('usuarios/crear', [
            'title' => 'Registrar Colaborador | MAKPC'
        ]);
    }

    /**
     * Alternar estado activo / inactivo
     * @param int|string $id
     */
    public function toggle($id) {
        $id = (int)$id;
        $user = $this->usuarioModel->find($id);

        if (!$user) {
            setFlash('danger', 'Usuario no encontrado.');
            $this->redirect('usuario');
        }

        // No permitir desactivarse a sí mismo
        if ($id === (int)auth('id')) {
            setFlash('danger', 'No puedes desactivar tu propia cuenta activa.');
            $this->redirect('usuario');
        }

        $nuevoEstado = $user['activo'] ? 0 : 1;
        $this->usuarioModel->update($id, ['activo' => $nuevoEstado]);

        $msg = $nuevoEstado ? "Usuario '{$user['nombre_completo']}' activado." : "Usuario '{$user['nombre_completo']}' desactivado.";
        setFlash('info', $msg);
        $this->redirect('usuario');
    }

    /**
     * Restablecer contraseña de colaborador
     */
    public function resetPassword(string $id) {
        $id = (int)$id;
        $user = $this->usuarioModel->find($id);

        if (!$user) {
            setFlash('danger', 'Usuario no encontrado.');
            $this->redirect('usuario');
        }

        if ($this->isPost()) {
            if (!verify_csrf($this->input('csrf_token'))) {
                setFlash('danger', 'Token de seguridad inválido.');
                $this->redirect('usuario');
            }

            $nueva = trim($this->input('password', ''));
            if (strlen($nueva) < 6) {
                setFlash('danger', 'La contraseña debe tener al menos 6 caracteres.');
                $this->redirect('usuario');
            }

            $this->usuarioModel->updatePassword($id, $nueva);
            setFlash('success', "Contraseña de '{$user['usuario']}' actualizada correctamente.");
        }

        $this->redirect('usuario');
    }
}
