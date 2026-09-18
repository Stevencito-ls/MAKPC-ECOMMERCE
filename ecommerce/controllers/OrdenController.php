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
        $clienteModel = new Cliente();
        $equipoModel = new Equipo();

        $clientes = $clienteModel->all('nombres_apellidos ASC');
        $equipos = $equipoModel->conCliente();

        if ($this->isPost()) {
            if (!verify_csrf($this->input('csrf_token'))) {
                setFlash('danger', 'Token de seguridad inválido o sesión expirada.');
                $this->redirect('orden/crear');
            }

            $idEquipo = $this->input('id_equipo');
            $falla = trim($this->input('falla_reportada'));
            $estadoFisico = trim($this->input('estado_recepcion_fisico'));

            if (empty($idEquipo) || empty($falla) || empty($estadoFisico)) {
                setFlash('danger', 'Equipo, Falla reportada y Estado físico de recepción son obligatorios.');
                $this->redirect('orden/crear');
            }

            $ordenModel = new OrdenServicio();
            $codigo = generarCodigoOrden($ordenModel->getPdo());

            $manoObra = (float)$this->input('costo_mano_obra', 0);
            $repuestos = (float)$this->input('costo_repuestos', 0);
            $total = $manoObra + $repuestos;
            $adelanto = (float)$this->input('adelanto', 0);

            $idOrden = $ordenModel->create([
                'codigo_orden' => $codigo,
                'id_equipo' => $idEquipo,
                'es_inmediato' => $this->input('es_inmediato') ? 1 : 0,
                'tecnico_responsable' => trim($this->input('tecnico_responsable')) ?: 'Técnico de Turno',
                'accesorios_entregados' => trim($this->input('accesorios_entregados')) ?: null,
                'estado_recepcion_fisico' => $estadoFisico,
                'falla_reportada' => $falla,
                'servicio_solicitado' => trim($this->input('servicio_solicitado')) ?: null,
                'diagnostico' => trim($this->input('diagnostico')) ?: null,
                'solucion_aplicada' => trim($this->input('solucion_aplicada')) ?: null,
                'costo_mano_obra' => $manoObra,
                'costo_repuestos' => $repuestos,
                'costo_total' => $total,
                'adelanto' => $adelanto,
                'garantia_meses' => (int)$this->input('garantia_meses', 0),
                'estado' => $this->input('estado', 'Pendiente'),
                'observaciones_internas' => trim($this->input('observaciones_internas')) ?: null
            ]);

            setFlash('success', "Orden de servicio #{$codigo} generada exitosamente.");
            $this->redirect('orden/ver/' . $idOrden);
        }

        $this->view('ordenes/crear', [
            'title' => 'Nueva Orden de Servicio',
            'clientes' => $clientes,
            'equipos' => $equipos,
            'preEquipoId' => $this->input('equipo_id')
        ]);
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
        $ordenModel = new OrdenServicio();
        $orden = $ordenModel->verCompleto($id);

        if (!$orden) {
            setFlash('danger', 'Orden no encontrada.');
            $this->redirect('orden');
        }

        if ($this->isPost()) {
            if (!verify_csrf($this->input('csrf_token'))) {
                setFlash('danger', 'Token de seguridad inválido o sesión expirada.');
                $this->redirect('orden/editar/' . $id);
            }

            $manoObra = (float)$this->input('costo_mano_obra', $orden['costo_mano_obra']);
            $repuestos = (float)$this->input('costo_repuestos', $orden['costo_repuestos']);
            $total = $manoObra + $repuestos;
            $adelanto = (float)$this->input('adelanto', $orden['adelanto']);
            $estado = $this->input('estado', $orden['estado']);

            $fechaEntrega = $orden['fecha_entrega'];
            if ($estado === 'Entregado' && empty($fechaEntrega)) {
                $fechaEntrega = date('Y-m-d H:i:s');
            }

            $ordenModel->update($id, [
                'es_inmediato' => $this->input('es_inmediato') ? 1 : 0,
                'tecnico_responsable' => trim($this->input('tecnico_responsable')),
                'accesorios_entregados' => trim($this->input('accesorios_entregados')),
                'estado_recepcion_fisico' => trim($this->input('estado_recepcion_fisico')),
                'falla_reportada' => trim($this->input('falla_reportada')),
                'servicio_solicitado' => trim($this->input('servicio_solicitado')),
                'diagnostico' => trim($this->input('diagnostico')),
                'solucion_aplicada' => trim($this->input('solucion_aplicada')),
                'costo_mano_obra' => $manoObra,
                'costo_repuestos' => $repuestos,
                'costo_total' => $total,
                'adelanto' => $adelanto,
                'garantia_meses' => (int)$this->input('garantia_meses', 0),
                'estado' => $estado,
                'fecha_entrega' => $fechaEntrega,
                'observaciones_internas' => trim($this->input('observaciones_internas'))
            ]);

            setFlash('success', 'Orden de servicio actualizada correctamente.');
            $this->redirect('orden/ver/' . $id);
        }

        $this->view('ordenes/editar', [
            'title' => 'Editar Orden ' . $orden['codigo_orden'],
            'orden' => $orden
        ]);
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
