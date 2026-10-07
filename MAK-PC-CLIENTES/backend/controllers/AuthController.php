<?php
namespace Controllers;

use Config\Database;
use Config\Response;

class AuthController {
    public function login() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['email']) || empty($data['password'])) {
            Response::badRequest('Correo y contraseña son requeridos');
        }

        $email = $data['email'];
        $password = $data['password'];

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT id, nombre_completo, email, password_hash, rol, activo FROM usuarios WHERE email = ? OR usuario = ? LIMIT 1");
        $stmt->execute([$email, $email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            Response::unauthorized('Credenciales incorrectas');
        }

        if ($user['activo'] != 1) {
            Response::unauthorized('Cuenta desactivada');
        }

        // Remover hash de la respuesta
        unset($user['password_hash']);

        Response::ok([
            'usuario' => $user,
            'token' => base64_encode(json_encode($user)) // Dummy token for frontend
        ], 'Login exitoso');
    }
}
