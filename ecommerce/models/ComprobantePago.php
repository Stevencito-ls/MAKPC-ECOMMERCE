<?php
/**
 * Modelo ComprobantePago - Emisión de Boletas y Facturas Electrónicas SUNAT
 */
class ComprobantePago extends Model {
    protected $table = 'comprobantes_pago';
    protected $primaryKey = 'id_comprobante';

    /**
     * Generar la siguiente serie y correlativo según tipo (Boleta B001 / Factura F001)
     */
    public function generarSiguienteNumero(string $tipo) {
        $serie = ($tipo === 'Factura') ? 'F001' : 'B001';
        
        $sql = "SELECT MAX(correlativo) as ultimo FROM {$this->table} WHERE serie = :serie";
        $row = $this->queryOne($sql, [':serie' => $serie]);
        
        $correlativo = ($row && !empty($row['ultimo'])) ? (int)$row['ultimo'] + 1 : 1;
        $numeroCompleto = $serie . '-' . str_pad((string)$correlativo, 8, '0', STR_PAD_LEFT);

        return [
            'serie' => $serie,
            'correlativo' => $correlativo,
            'numero_completo' => $numeroCompleto
        ];
    }

    /**
     * Buscar comprobante por número oficial (ej. B001-00000001)
     */
    public function buscarPorNumero(string $numeroCompleto) {
        $sql = "SELECT c.*, p.codigo_pedido, p.items_json, p.metodo_pago, p.culqi_charge_id, p.culqi_authorization_code, p.culqi_brand
                FROM {$this->table} c
                INNER JOIN pedidos_tienda p ON c.id_pedido = p.id_pedido
                WHERE c.numero_completo = :num LIMIT 1";
        return $this->queryOne($sql, [':num' => $numeroCompleto]);
    }

    /**
     * Buscar comprobante por ID de pedido
     */
    public function buscarPorIdPedido(int $idPedido) {
        $sql = "SELECT * FROM {$this->table} WHERE id_pedido = :id_pedido LIMIT 1";
        return $this->queryOne($sql, [':id_pedido' => $idPedido]);
    }
}
