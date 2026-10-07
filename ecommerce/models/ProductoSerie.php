<?php
class ProductoSerie extends Model {
    protected $table = 'productos_series';
    protected $primaryKey = 'id';

    public function getDisponibles($idProducto) {
        return $this->query("SELECT * FROM {$this->table} WHERE id_producto = ? AND estado = 'DISPONIBLE' ORDER BY creado_en ASC", [$idProducto]);
    }

    public function markAsUsed($idSerie, $estado, $referencia) {
        return $this->update($idSerie, [
            'estado' => $estado,
            'referencia_salida' => $referencia
        ]);
    }
}
