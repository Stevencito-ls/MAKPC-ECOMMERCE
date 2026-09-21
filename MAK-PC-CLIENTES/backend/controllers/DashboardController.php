<?php
declare(strict_types=1);

namespace Controllers;

use Services\DashboardService;
use Config\Response;
use Exception;

class DashboardController {
    private DashboardService $service;

    public function __construct() {
        $this->service = new DashboardService();
    }

    /**
     * GET /api/dashboard/resumen
     */
    public function getResumen(): void {
        try {
            $metricas = $this->service->getMetricas();
            Response::ok($metricas, "Métricas del sistema obtenidas exitosamente.");
        } catch (Exception $e) {
            Response::serverError("Error al obtener métricas del dashboard: " . $e->getMessage());
        }
    }
}
