<?php
class Categoria extends Model {
    protected $table = 'categorias_productos';
    protected $primaryKey = 'id_categoria';

    public function activas() {
        return $this->query("SELECT cp.*, COUNT(p.id_producto) as total_productos
                            FROM categorias_productos cp
                            LEFT JOIN productos p ON cp.id_categoria = p.id_categoria AND p.activo = 1
                            WHERE cp.activo = 1
                            GROUP BY cp.id_categoria
                            ORDER BY cp.orden ASC");
    }
}
