<?php
class CajaMovimiento extends Model {
    protected $table = 'caja_movimientos';
    protected $primaryKey = 'id';

    public function getPorCaja($idCaja) {
        return $this->query("SELECT * FROM {$this->table} WHERE id_caja = ? ORDER BY creado_en DESC", [$idCaja]);
    }
}
