<?php
declare(strict_types=1);

namespace Services;

use Config\Database;
use PDO;

class DashboardService {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Obtiene métricas en tiempo real del mostrador y laboratorio
     */
    public function getMetricas(): array {
        // Conteo de órdenes por estado
        $stmtEstados = $this->db->query("
            SELECT estado, COUNT(*) AS total 
            FROM ordenes_servicio 
            GROUP BY estado
        ");
        $estadosRaw = $stmtEstados->fetchAll();
        $estados = [
            'RECEPCIONADO'     => 0,
            'EN_DIAGNOSTICO'   => 0,
            'ESPERA_APROBACION'=> 0,
            'ESPERA_REPUESTOS' => 0,
            'EN_REPARACION'    => 0,
            'REPARADO'         => 0,
            'NO_REPARABLE'     => 0,
            'ENTREGADO'        => 0,
            'CANCELADO'        => 0
        ];
        foreach ($estadosRaw as $row) {
            if (isset($estados[$row['estado']])) {
                $estados[$row['estado']] = (int)$row['total'];
            }
        }

        // Totales financieros de recibos activos
        $stmtFinanzas = $this->db->query("
            SELECT 
                COUNT(*) AS total_recibos_emitidos,
                COALESCE(SUM(monto_total), 0) AS total_facturado,
                COALESCE(SUM(monto_a_cuenta), 0) AS total_cobrado,
                COALESCE(SUM(monto_saldo), 0) AS total_por_cobrar
            FROM recibos 
            WHERE anulado = FALSE
        ");
        $finanzas = $stmtFinanzas->fetch();

        // Equipos listos para entrega inmediata
        $stmtListos = $this->db->query("
            SELECT COUNT(*) FROM ordenes_servicio WHERE estado = 'REPARADO'
        ");
        $equiposListos = (int)$stmtListos->fetchColumn();

        // Total clientes registrados
        $stmtClientes = $this->db->query("SELECT COUNT(*) FROM clientes");
        $totalClientes = (int)$stmtClientes->fetchColumn();

        // Últimas 5 órdenes ingresadas
        $stmtUltimas = $this->db->query("
            SELECT 
                o.id, o.codigo_orden, o.tipo_equipo, o.marca, o.modelo, o.estado, o.fecha_ingreso,
                c.nombres_razon_social AS cliente_nombre, c.telefono AS cliente_telefono
            FROM ordenes_servicio o
            INNER JOIN clientes c ON o.cliente_id = c.id
            ORDER BY o.id DESC LIMIT 5
        ");
        $ultimasOrdenes = $stmtUltimas->fetchAll();

        return [
            'ordenes_por_estado' => $estados,
            'equipos_listos_entrega' => $equiposListos,
            'total_clientes'     => $totalClientes,
            'finanzas'           => [
                'total_recibos'    => (int)$finanzas['total_recibos_emitidos'],
                'total_facturado'  => (float)$finanzas['total_facturado'],
                'total_cobrado'    => (float)$finanzas['total_cobrado'],
                'total_por_cobrar' => (float)$finanzas['total_por_cobrar']
            ],
            'ultimas_ordenes'    => $ultimasOrdenes
        ];
    }
}
