<?php
/**
 * Modelo de Usuario del Sistema
 */
class Usuario extends Model {
    protected $table = 'usuarios';
    protected $primaryKey = 'id';

    /**
     * Buscar usuario por nombre de usuario
     * @param string $usuario
     * @return array|false
     */
    public function findByUsuario($usuario) {
        return $this->findWhere('usuario', $usuario);
    }

    /**
     * Buscar usuario por email o nombre de usuario
     * @param string $identificador
     * @return array|false
     */
    public function findByEmailOrUsuario($identificador) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email OR usuario = :usuario LIMIT 1";
        return $this->queryOne($sql, [':email' => $identificador, ':usuario' => $identificador]);
    }

    /**
     * Verificar si la contraseña coincide con el hash
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * Actualizar contraseña
     * @param int $id
     * @param string $nuevaPassword
     * @return bool
     */
    public function updatePassword($id, $nuevaPassword) {
        $hash = password_hash($nuevaPassword, PASSWORD_DEFAULT);
        return $this->update($id, ['password_hash' => $hash]);
    }

    /**
     * Obtener usuarios activos por rol
     * @param string $rol
     * @return array
     */
    public function getByRol($rol) {
        return $this->query("SELECT * FROM {$this->table} WHERE rol = ? AND activo = 1 ORDER BY nombre_completo ASC", [$rol]);
    }
}
