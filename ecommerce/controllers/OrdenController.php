<?php
class OrdenController extends Controller {

    public function __construct() {
        $this->requireAuth();
    }

    public function index() {
        $ordenModel = new OrdenServicio();
        $filtroEstado = $this->input('estado');
        $busqueda = $this->input('q');

        $ordenes = $ordenModel->listarCompleto($filtroEstado, $busqueda);

        $this->view('ordenes/index', [
            'title' => 'Órdenes de Servicio',
            'ordenes' => $ordenes,
            'filtroEstado' => $filtroEstado,
            'busqueda' => $busqueda
        ]);
    }

    public function crear() {
        setFlash('warning', 'La creación de nuevas órdenes de servicio se ha migrado al nuevo sistema de taller (MAK-PC-CLIENTES). Por favor utilice el nuevo panel para esta operación.');
        $this->redirect('orden');
    }

    /**
     * @param int|string $id
     */
    public function ver($id) {
        $ordenModel = new OrdenServicio();
        $componenteModel = new DetalleComponente();

        $orden = $ordenModel->verCompleto($id);
        if (!$orden) {
            setFlash('danger', 'Orden no encontrada.');
            $this->redirect('orden');
        }

        $componentes = $componenteModel->porOrden($id);

        $this->view('ordenes/ver', [
            'title' => 'Orden ' . $orden['codigo_orden'],
            'orden' => $orden,
            'componentes' => $componentes
        ]);
    }

    /**
     * @param int|string $id
     */
    public function editar($id) {
        setFlash('warning', 'La edición de órdenes de servicio se gestiona desde el nuevo sistema de taller (MAK-PC-CLIENTES).');
        $this->redirect('orden/ver/' . $id);
    }

    /**
     * @param int|string $id
     */
    public function cambiarEstado($id) {
        $this->requireRole(['admin', 'tecnico']);
        if ($this->isPost()) {
            if (!verify_csrf($this->input('csrf_token'))) {
                setFlash('danger', 'Token de seguridad inválido o sesión expirada.');
                $this->redirect('orden/ver/' . $id);
            }

            $nuevoEstado = $this->input('estado');
            $ordenModel = new OrdenServicio();
            
            $data = ['estado' => $nuevoEstado];
            if ($nuevoEstado === 'Entregado') {
                $data['fecha_entrega'] = date('Y-m-d H:i:s');
            }

            $ordenModel->update($id, $data);
            setFlash('success', "Estado actualizado a '{$nuevoEstado}'.");
        }
        $this->redirect('orden/ver/' . $id);
    }

    /**
     * @param int|string $id
     */
    public function imprimir($id) {
        $ordenModel = new OrdenServicio();
        $componenteModel = new DetalleComponente();

        $orden = $ordenModel->verCompleto($id);
        if (!$orden) {
            setFlash('danger', 'Orden no encontrada.');
            $this->redirect('orden');
        }

        $componentes = $componenteModel->porOrden($id);

        // Vista de impresión independiente (sin headers ni sidebar)
        require __DIR__ . '/../views/ordenes/imprimir.php';
        exit;
    }
}
