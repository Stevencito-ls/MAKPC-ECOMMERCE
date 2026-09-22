<?php
class EquipoController extends Controller {

    public function __construct() {
        $this->requireAuth();
    }

    public function index() {
        $equipoModel = new Equipo();
        $equipos = $equipoModel->conCliente();

        $this->view('equipos/index', [
            'title' => 'Gestión de Equipos',
            'equipos' => $equipos
        ]);
    }

    public function crear() {
        setFlash('warning', 'La creación de equipos se gestiona de manera unificada junto a las órdenes en el nuevo sistema de taller (MAK-PC-CLIENTES).');
        $this->redirect('equipo');
    }

    /**
     * @param int|string $id
     */
    public function editar($id) {
        setFlash('warning', 'La edición de equipos se gestiona desde el nuevo sistema de taller (MAK-PC-CLIENTES).');
        $this->redirect('equipo');
    }

    /**
     * @param int|string $id
     */
    public function eliminar($id) {
        $this->requireRole('admin');
        $equipoModel = new Equipo();
        $equipo = $equipoModel->find($id);
        $clienteId = $equipo ? $equipo['id_cliente'] : null;

        if (!$equipo) {
            setFlash('danger', 'Equipo no encontrado.');
            $this->redirect('equipo');
        }

        try {
            // Verificar si el equipo tiene órdenes de servicio asociadas
            $ordenModel = new OrdenServicio();
            $ordenesExistentes = $ordenModel->query(
                "SELECT COUNT(*) as total FROM ordenes_servicio WHERE id_equipo = ?",
                [$id]
            );
            $totalOrdenes = (int)($ordenesExistentes[0]['total'] ?? 0);

            if ($totalOrdenes > 0) {
                setFlash('warning', "No es posible eliminar el equipo porque tiene {$totalOrdenes} orden(es) de servicio asociadas en el taller.");
                if ($clienteId) {
                    $this->redirect('cliente/ver/' . $clienteId);
                } else {
                    $this->redirect('equipo');
                }
            }

            $equipoModel->delete($id);
            setFlash('success', 'Equipo eliminado correctamente.');
        } catch (PDOException $e) {
            setFlash('danger', 'No se pudo eliminar el equipo debido a restricciones de integridad de datos.');
        }
        
        if ($clienteId) {
            $this->redirect('cliente/ver/' . $clienteId);
        } else {
            $this->redirect('equipo');
        }
    }
}
