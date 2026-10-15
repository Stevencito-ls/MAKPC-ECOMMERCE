<?php
declare(strict_types=1);

namespace Controllers;

use Services\ClienteService;
use Config\Response;
use Exception;

class ClienteController {
    private ClienteService $service;

    public function __construct() {
        $this->service = new ClienteService();
    }

    /**
     * GET /api/clientes?q=... o GET /api/clientes
     */
    public function search(): void {
        $query = $_GET['q'] ?? '';
        try {
            $clientes = $this->service->search($query);
            Response::ok($clientes, "Búsqueda completada exitosamente.");
        } catch (Exception $e) {
            Response::serverError("Error al consultar clientes: " . $e->getMessage());
        }
    }

    /**
     * GET /api/clientes/{id}
     */
    public function getById(int $id): void {
        try {
            $cliente = $this->service->getById($id);
            if (!$cliente) {
                Response::notFound("Cliente no encontrado.");
            }
            Response::ok($cliente);
        } catch (Exception $e) {
            Response::serverError("Error al consultar cliente: " . $e->getMessage());
        }
    }

    /**
     * GET /api/clientes/documento/{doc}
     * Consulta inteligente: busca en base local primero, y si no existe,
     * consulta automáticamente en apisperu.com (RENIEC / SUNAT).
     */
    public function getByDocumento(string $documento): void {
        try {
            $lookup = new \Services\DocumentoLookupService();
            $tipo = $_GET['tipo'] ?? 'auto';
            $resultado = $lookup->buscar($documento, $tipo);

            if (!$resultado || empty($resultado['success'])) {
                Response::notFound("No se encontró información para el documento {$documento} en RENIEC/SUNAT.");
            }

            Response::ok($resultado, $resultado['origen'] ?? 'Consulta exitosa');
        } catch (Exception $e) {
            Response::serverError("Error al consultar documento: " . $e->getMessage());
        }
    }

    /**
     * GET /api/consulta-documento?numero=...&tipo=...
     */
    public function consultarDocumento(): void {
        $doc = $_GET['numero'] ?? ($_GET['doc'] ?? '');
        $tipo = $_GET['tipo'] ?? 'auto';
        $this->getByDocumento($doc);
    }

    /**
     * POST /api/clientes
     */
    public function create(): void {
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        try {
            $cliente = $this->service->findOrCreate($body);
            Response::created($cliente, "Cliente registrado o recuperado con éxito.");
        } catch (Exception $e) {
            Response::badRequest($e->getMessage());
        }
    }
}
