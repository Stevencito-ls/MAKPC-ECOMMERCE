<?php
class Kardex extends Model {
    protected $table = 'kardex_movimientos';
    protected $primaryKey = 'id';

    public function registrarMovimiento(string|int $idProducto, string $tipo, int $cantidad, string $origenDestino, string $notas = '') {
        return $this->create([
            'id_producto' => $idProducto,
            'tipo_movimiento' => $tipo,
            'cantidad' => $cantidad,
            'origen_destino' => $origenDestino,
            'usuario_id' => auth('id') ?? 1,
            'notas' => $notas
        ]);
    }
}
