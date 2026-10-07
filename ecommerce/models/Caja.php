<?php
class Caja extends Model {
    protected $table = 'caja_diaria';
    protected $primaryKey = 'id';

    public function getCajaAbierta() {
        return $this->queryOne("SELECT * FROM {$this->table} WHERE estado = 'ABIERTA' ORDER BY id DESC LIMIT 1");
    }

    public function calcularMontoActual(string|int $idCaja) {
        $caja = $this->find($idCaja);
        if (!$caja) return 0;
        
        $sql = "SELECT SUM(CASE WHEN tipo = 'INGRESO' THEN monto ELSE -monto END) as balance FROM caja_movimientos WHERE id_caja = ?";
        $res = $this->queryOne($sql, [$idCaja]);
        $balance = $res['balance'] ?? 0;
        return $caja['monto_apertura'] + $balance;
    }
}
