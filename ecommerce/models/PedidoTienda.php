<?php
/**
 * Modelo PedidoTienda - Gestión de órdenes de compra e-commerce y facturación SUNAT
 */
class PedidoTienda extends Model {
    protected $table = 'pedidos_tienda';
    protected $primaryKey = 'id_pedido';

    /**
     * Buscar pedido por su código público (ej. PED-20260911-A89F)
     */
    public function buscarPorCodigo(string $codigo) {
        $sql = "SELECT * FROM {$this->table} WHERE codigo_pedido = :codigo LIMIT 1";
        return $this->queryOne($sql, [':codigo' => $codigo]);
    }

    /**
     * Obtener pedido con su comprobante electrónico SUNAT asociado
     */
    public function obtenerConComprobante(string $codigo) {
        $sql = "SELECT p.*, c.id_comprobante, c.tipo as comprobante_tipo, c.serie, c.correlativo, 
                       c.numero_completo as comprobante_numero, c.codigo_hash, c.estado_sunat,
                       c.fecha_emision as comprobante_fecha
                FROM {$this->table} p
                LEFT JOIN comprobantes_pago c ON p.id_pedido = c.id_pedido
                WHERE p.codigo_pedido = :codigo LIMIT 1";
        return $this->queryOne($sql, [':codigo' => $codigo]);
    }

    /**
     * Listado administrativo de pedidos para el Panel con filtros
     */
    public function listarAdmin($busqueda = '', $filtroPago = '', $limite = 50) {
        $conditions = ["1=1"];
        $params = [];

        if (!empty($busqueda)) {
            $conditions[] = "(p.codigo_pedido LIKE :q1 OR p.cliente_nombre LIKE :q2 OR p.numero_documento LIKE :q3 OR p.cliente_telefono LIKE :q4 OR c.numero_completo LIKE :q5)";
            $searchTerm = "%{$busqueda}%";
            $params[':q1'] = $searchTerm;
            $params[':q2'] = $searchTerm;
            $params[':q3'] = $searchTerm;
            $params[':q4'] = $searchTerm;
            $params[':q5'] = $searchTerm;
        }

        if (!empty($filtroPago)) {
            $conditions[] = "p.estado_pago = :estado_pago";
            $params[':estado_pago'] = $filtroPago;
        }

        $whereClause = implode(' AND ', $conditions);

        $sql = "SELECT p.*, c.id_comprobante, c.tipo as comprobante_tipo, c.numero_completo as comprobante_numero,
                       c.codigo_hash, c.estado_sunat, c.fecha_emision as comprobante_fecha
                FROM {$this->table} p
                LEFT JOIN comprobantes_pago c ON p.id_pedido = c.id_pedido
                WHERE {$whereClause}
                ORDER BY p.creado_en DESC
                LIMIT " . (int)$limite;

        return $this->query($sql, $params);
    }

    /**
     * Obtener métricas consolidadas de ventas online para Dashboard
     */
    public function obtenerMetricasVentas() {
        $sql = "SELECT 
                    COUNT(*) as total_pedidos,
                    COALESCE(SUM(CASE WHEN estado_pago = 'Pagado' THEN total ELSE 0 END), 0) as total_ventas_soles,
                    COALESCE(SUM(CASE WHEN estado_pago = 'Pagado' THEN 1 ELSE 0 END), 0) as pedidos_pagados,
                    COALESCE(SUM(CASE WHEN estado_pago = 'Pendiente' THEN 1 ELSE 0 END), 0) as pedidos_pendientes,
                    COALESCE(SUM(CASE WHEN estado_despacho IN ('En preparación', 'Pendiente') THEN 1 ELSE 0 END), 0) as por_despachar
                FROM {$this->table}";

        return $this->queryOne($sql) ?: [
            'total_pedidos' => 0,
            'total_ventas_soles' => 0.00,
            'pedidos_pagados' => 0,
            'pedidos_pendientes' => 0,
            'por_despachar' => 0
        ];
    }

    /**
     * Últimos pedidos para el Dashboard general
     */
    public function ultimosPedidos($limite = 5) {
        $sql = "SELECT p.*, c.numero_completo as comprobante_numero, c.tipo as comprobante_tipo
                FROM {$this->table} p
                LEFT JOIN comprobantes_pago c ON p.id_pedido = c.id_pedido
                ORDER BY p.creado_en DESC
                LIMIT " . (int)$limite;

        return $this->query($sql);
    }

    /**
     * Actualizar estado de despacho
     */
    public function actualizarDespacho(int $idPedido, string $nuevoEstado) {
        $validos = ['Pendiente', 'En preparación', 'Enviado', 'Entregado'];
        if (!in_array($nuevoEstado, $validos)) return false;

        $sql = "UPDATE {$this->table} SET estado_despacho = :estado WHERE id_pedido = :id";
        return $this->execute($sql, [':estado' => $nuevoEstado, ':id' => (int)$idPedido]);
    }
}
