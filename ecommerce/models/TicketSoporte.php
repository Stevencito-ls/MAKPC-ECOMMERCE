<?php
class TicketSoporte extends Model {
    protected $table = 'tickets_soporte';
    protected $primaryKey = 'id_ticket';

    public function listarConCliente($filtroEstado = null) {
        $sql = "SELECT t.*, c.nombres_apellidos as cliente_nombre_reg
                FROM tickets_soporte t
                LEFT JOIN clientes c ON t.id_cliente = c.id";
        $params = [];
        if ($filtroEstado) {
            $sql .= " WHERE t.estado = :estado";
            $params[':estado'] = $filtroEstado;
        }
        $sql .= " ORDER BY t.creado_en DESC";
        return $this->query($sql, $params);
    }

    public function contarPorEstado() {
        $sql = "SELECT estado, COUNT(*) as total FROM tickets_soporte GROUP BY estado";
        $filas = $this->query($sql);
        $res = [];
        foreach ($filas as $f) {
            $res[$f['estado']] = (int)$f['total'];
        }
        return $res;
    }
}
