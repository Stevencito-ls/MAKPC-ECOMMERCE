<?php
class Cliente extends Model {
    protected $table = 'clientes';
    protected $primaryKey = 'id';

    /**
     * @param int|string $id
     * @return array
     */
    public function conEquipos($id) {
        return $this->query(
            "SELECT c.*, COUNT(e.id_equipo) as total_equipos
             FROM clientes c LEFT JOIN equipos e ON c.id = e.id_cliente
             WHERE c.id = ? GROUP BY c.id", [$id]
        );
    }

    /**
     * @param string $term
     * @return array
     */
    public function buscar($term) {
        return $this->search(['nombres_apellidos', 'dni', 'telefono', 'correo'], $term);
    }

    public function conEstadisticas() {
        return $this->query(
            "SELECT c.*, 
                    COUNT(DISTINCT e.id_equipo) as total_equipos,
                    COUNT(DISTINCT o.id_orden) as total_ordenes
             FROM clientes c 
             LEFT JOIN equipos e ON c.id = e.id_cliente
             LEFT JOIN ordenes_servicio o ON e.id_equipo = o.id_equipo
             GROUP BY c.id
             ORDER BY c.creado_en DESC"
        );
    }
}
