<?php
class Equipo extends Model {
    protected $table = 'equipos';
    protected $primaryKey = 'id_equipo';

    /**
     * @param int|string|null $id
     * @return array|false
     */
    public function conCliente($id = null) {
        $sql = "SELECT e.*, c.nombres_apellidos as cliente_nombre, c.telefono as cliente_telefono, c.telefono as cliente_tel
                FROM equipos e 
                INNER JOIN clientes c ON e.id_cliente = c.id";
        if ($id) {
            $sql .= " WHERE e.id_equipo = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        }
        $sql .= " ORDER BY e.creado_en DESC";
        return $this->query($sql);
    }

    /**
     * @param int|string $idCliente
     * @return array
     */
    public function porCliente($idCliente) {
        return $this->where('id_cliente', $idCliente);
    }
}
