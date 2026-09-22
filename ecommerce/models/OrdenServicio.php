<?php
class OrdenServicio extends Model {
    protected $table = 'ordenes_servicio';
    protected $primaryKey = 'id';

    public function listarCompleto($filtroEstado = null, $busqueda = null) {
        $sql = "SELECT o.*, e.marca, e.modelo, e.tipo_equipo, 
                       c.nombres_apellidos as cliente_nombre, c.telefono as cliente_telefono
                FROM ordenes_servicio o
                INNER JOIN equipos e ON o.id_equipo = e.id_equipo
                INNER JOIN clientes c ON e.id_cliente = c.id";
        $params = [];
        $conditions = [];

        if ($filtroEstado) {
            $conditions[] = "o.estado = ?";
            $params[] = $filtroEstado;
        }
        if ($busqueda) {
            $conditions[] = "(o.codigo_orden LIKE ? OR c.nombres_apellidos LIKE ? OR c.telefono LIKE ? OR e.numero_serie LIKE ?)";
            $params = array_merge($params, ["%$busqueda%", "%$busqueda%", "%$busqueda%", "%$busqueda%"]);
        }
        if ($conditions) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        $sql .= " ORDER BY o.fecha_recepcion DESC";
        return $this->query($sql, $params);
    }

    /**
     * @param int|string $id
     * @return array|false
     */
    public function verCompleto($id) {
        $sql = "SELECT o.*, e.*, c.*,
                       e.marca as equipo_marca, e.modelo as equipo_modelo,
                       c.nombres_apellidos as cliente_nombre
                FROM ordenes_servicio o
                INNER JOIN equipos e ON o.id_equipo = e.id_equipo
                INNER JOIN clientes c ON e.id_cliente = c.id
                WHERE o.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function estadisticas() {
        return $this->query(
            "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN estado = 'Pendiente' THEN 1 ELSE 0 END) as pendientes,
                SUM(CASE WHEN estado = 'En Reparacion' THEN 1 ELSE 0 END) as en_reparacion,
                SUM(CASE WHEN estado = 'Terminado' THEN 1 ELSE 0 END) as terminados,
                SUM(CASE WHEN estado = 'Entregado' THEN 1 ELSE 0 END) as entregados,
                SUM(costo_total) as ingresos_total,
                SUM(adelanto) as adelantos_total
             FROM ordenes_servicio"
        )[0];
    }

    public function recientes($limit = 5) {
        $limit = (int)$limit;
        return $this->query(
            "SELECT o.*, c.nombres_apellidos as cliente_nombre, CONCAT(e.marca, ' ', e.modelo) as equipo
             FROM ordenes_servicio o
             INNER JOIN equipos e ON o.id_equipo = e.id_equipo
             INNER JOIN clientes c ON e.id_cliente = c.id
             ORDER BY o.fecha_recepcion DESC LIMIT $limit"
        );
    }
}
