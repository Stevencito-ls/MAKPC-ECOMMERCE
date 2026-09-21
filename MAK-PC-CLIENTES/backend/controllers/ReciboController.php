<?php
declare(strict_types=1);

namespace Controllers;

use Services\ReciboService;
use Config\Response;
use Exception;

class ReciboController {
    private ReciboService $service;

    public function __construct() {
        $this->service = new ReciboService();
    }

    /**
     * GET /api/recibos/siguiente-correlativo
     */
    public function getSiguienteCorrelativo(): void {
        try {
            $data = $this->service->getSiguienteCorrelativo();
            Response::ok($data, "Siguiente correlativo obtenido.");
        } catch (Exception $e) {
            Response::serverError("Error al consultar correlativo: " . $e->getMessage());
        }
    }

    /**
     * GET /api/recibos/{id}
     */
    public function getById(int $id): void {
        try {
            $recibo = $this->service->getById($id);
            if (!$recibo) {
                Response::notFound("Recibo no encontrado.");
            }
            Response::ok($recibo, "Recibo recuperado exitosamente.");
        } catch (Exception $e) {
            Response::serverError("Error al obtener recibo: " . $e->getMessage());
        }
    }

    /**
     * GET /api/recibos/orden/{orden_id}
     */
    public function getByOrdenId(int $ordenId): void {
        try {
            $recibo = $this->service->getByOrdenId($ordenId);
            if (!$recibo) {
                Response::notFound("No existe recibo asociado a esta orden.");
            }
            Response::ok($recibo, "Recibo asociado recuperado.");
        } catch (Exception $e) {
            Response::serverError("Error al obtener recibo por orden: " . $e->getMessage());
        }
    }

    /**
     * POST /api/recibos (Emisión de comprobante A5)
     */
    public function create(): void {
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        try {
            $recibo = $this->service->emitirRecibo($body);
            Response::created($recibo, "Recibo Nº {$recibo['numero_recibo']} emitido exitosamente.");
        } catch (Exception $e) {
            Response::badRequest($e->getMessage());
        }
    }

    /**
     * PUT /api/recibos/{id}/liquidar-saldo (Liquidación de saldo al retirar equipo)
     */
    public function liquidarSaldo(int $id): void {
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $montoAbonado = (float)($body['monto_abonado'] ?? 0.00);
        $metodoPago = $body['metodo_pago'] ?? 'EFECTIVO';
        $marcarEntregada = isset($body['marcar_entregado']) ? (bool)$body['marcar_entregado'] : true;

        if ($montoAbonado <= 0) {
            Response::badRequest("Debe ingresar un monto abonado válido mayor a 0.00 soles.");
        }

        try {
            $reciboActualizado = $this->service->liquidarSaldo($id, $montoAbonado, $metodoPago, $marcarEntregada);
            Response::ok($reciboActualizado, "Saldo liquidado correctamente. Nuevo saldo: S/. {$reciboActualizado['monto_saldo']}");
        } catch (Exception $e) {
            Response::badRequest($e->getMessage());
        }
    }

    /**
     * PUT /api/recibos/{id}/anular
     */
    public function anular(int $id): void {
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $motivo = $body['motivo'] ?? '';
        try {
            $this->service->anular($id, $motivo);
            Response::ok(null, "Recibo anulado exitosamente.");
        } catch (Exception $e) {
            Response::badRequest($e->getMessage());
        }
    }
}
