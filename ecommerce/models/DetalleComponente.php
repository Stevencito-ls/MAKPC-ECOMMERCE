<?php
class DetalleComponente extends Model {
    protected $table = 'detalle_componentes';
    protected $primaryKey = 'id_detalle';

    /**
     * @param int|string $idOrden
     * @return array
     */
    public function porOrden($idOrden) {
        return $this->where('id_orden', $idOrden);
    }

    /**
     * @param string $serie
     * @return array
     */
    public function buscarPorSerie($serie) {
        return $this->query(
            "SELECT d.*, o.codigo_orden, o.estado as estado_orden,
                    c.id as id_cliente, c.nombres_razon_social as cliente_nombre, CONCAT(o.marca, ' ', o.modelo) as equipo
             FROM detalle_componentes d
             INNER JOIN ordenes_servicio o ON d.id_orden = o.id
             INNER JOIN clientes c ON o.cliente_id = c.id
             WHERE d.serie_retirada LIKE ? OR d.serie_instalada LIKE ?
             ORDER BY d.creado_en DESC",
            ["%$serie%", "%$serie%"]
        );
    }

    public function auditoria($busqueda = null) {
        $sql = "SELECT d.*, o.codigo_orden, o.estado as estado_orden, o.fecha_recepcion,
                       c.id as id_cliente, c.nombres_razon_social as cliente_nombre, CONCAT(o.marca, ' ', o.modelo) as equipo
                FROM detalle_componentes d
                INNER JOIN ordenes_servicio o ON d.id_orden = o.id
                INNER JOIN clientes c ON o.cliente_id = c.id";
        $params = [];
        if ($busqueda) {
            $sql .= " WHERE o.codigo_orden LIKE ? OR c.nombres_razon_social LIKE ? OR d.serie_retirada LIKE ? OR d.serie_instalada LIKE ? OR d.tipo_componente LIKE ?";
            $params = ["%$busqueda%", "%$busqueda%", "%$busqueda%", "%$busqueda%", "%$busqueda%"];
        }
        $sql .= " ORDER BY o.fecha_recepcion DESC";
        return $this->query($sql, $params);
    }

    public function vistaAuditoria() {
        return $this->auditoria();
    }
}
