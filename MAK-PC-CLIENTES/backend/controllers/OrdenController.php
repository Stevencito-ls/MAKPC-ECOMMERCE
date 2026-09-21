<?php
declare(strict_types=1);

namespace Controllers;

use Services\OrdenService;
use Config\Response;
use Exception;

class OrdenController {
    private OrdenService $service;

    public function __construct() {
        $this->service = new OrdenService();
    }

    /**
     * GET /api/ordenes (soporta ?estado=..., ?query=..., ?cliente_id=...)
     */
    public function index(): void {
        try {
            $filters = [
                'estado'     => $_GET['estado'] ?? null,
                'cliente_id' => $_GET['cliente_id'] ?? null,
                'tecnico_id' => $_GET['tecnico_id'] ?? null,
                'query'      => $_GET['q'] ?? null,
                'limit'      => $_GET['limit'] ?? 50
            ];
            $ordenes = $this->service->getAll($filters);
            Response::ok($ordenes, "Órdenes recuperadas exitosamente.");
        } catch (Exception $e) {
            Response::serverError("Error al obtener órdenes de servicio: " . $e->getMessage());
        }
    }

    /**
     * GET /api/ordenes/{id}
     */
    public function getById(int $id): void {
        try {
            $orden = $this->service->getById($id);
            if (!$orden) {
                Response::notFound("Orden de servicio no encontrada.");
            }
            Response::ok($orden);
        } catch (Exception $e) {
            Response::serverError("Error al obtener orden: " . $e->getMessage());
        }
    }

    /**
     * POST /api/ordenes (Recepción de nuevo equipo)
     */
    public function create(): void {
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        try {
            $orden = $this->service->create($body);
            Response::created($orden, "Equipo recepcionado exitosamente. Código: {$orden['codigo_orden']}");
        } catch (Exception $e) {
            Response::badRequest($e->getMessage());
        }
    }

    /**
     * PUT /api/ordenes/{id}/estado (Actualización de flujo técnico)
     */
    public function updateEstado(int $id): void {
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        if (empty($body['estado'])) {
            Response::badRequest("El campo 'estado' es obligatorio.");
        }

        try {
            $nuevoEstado = $body['estado'];
            $diagnostico = $body['diagnostico_tecnico'] ?? null;
            $solucion = $body['solucion_tecnica'] ?? null;
            $tecnicoId = !empty($body['tecnico_id']) ? (int)$body['tecnico_id'] : null;

            $orden = $this->service->updateEstado($id, $nuevoEstado, $diagnostico, $solucion, $tecnicoId);
            Response::ok($orden, "Estado de la orden actualizado a '{$nuevoEstado}'.");
        } catch (Exception $e) {
            Response::badRequest($e->getMessage());
        }
    }
}
