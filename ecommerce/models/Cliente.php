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
            "SELECT c.*, COUNT(o.id) as total_equipos
             FROM clientes c LEFT JOIN ordenes_servicio o ON c.id = o.cliente_id
             WHERE c.id = ? GROUP BY c.id", [$id]
        );
    }

    /**
     * @param string $term
     * @return array
     */
    public function buscar($term) {
        return $this->search(['nombres_razon_social', 'numero_documento', 'telefono', 'email'], $term);
    }

    public function conEstadisticas() {
        return $this->query(
            "SELECT c.*, 
                    COUNT(DISTINCT o.id) as total_equipos,
                    COUNT(DISTINCT o.id) as total_ordenes
             FROM clientes c 
             LEFT JOIN ordenes_servicio o ON c.id = o.cliente_id
             GROUP BY c.id
             ORDER BY c.creado_en DESC"
        );
    }
}
