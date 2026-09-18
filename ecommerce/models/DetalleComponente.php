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
                    c.nombres_apellidos as cliente_nombre, CONCAT(e.marca, ' ', e.modelo) as equipo
             FROM detalle_componentes d
             INNER JOIN ordenes_servicio o ON d.id_orden = o.id_orden
             INNER JOIN equipos e ON o.id_equipo = e.id_equipo
             INNER JOIN clientes c ON e.id_cliente = c.id_cliente
             WHERE d.serie_retirada LIKE ? OR d.serie_instalada LIKE ?
             ORDER BY d.creado_en DESC",
            ["%$serie%", "%$serie%"]
        );
    }

    public function auditoria($busqueda = null) {
        $sql = "SELECT * FROM vw_auditoria_trazabilidad";
        $params = [];
        if ($busqueda) {
            $sql .= " WHERE codigo_orden LIKE ? OR cliente_nombre LIKE ? OR serie_retirada LIKE ? OR serie_instalada LIKE ? OR tipo_componente LIKE ?";
            $params = ["%$busqueda%", "%$busqueda%", "%$busqueda%", "%$busqueda%", "%$busqueda%"];
        }
        $sql .= " ORDER BY fecha_recepcion DESC";
        return $this->query($sql, $params);
    }

    public function vistaAuditoria() {
        return $this->auditoria();
    }
}
