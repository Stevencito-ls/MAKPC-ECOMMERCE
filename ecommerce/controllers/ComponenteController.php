<?php
class ComponenteController extends Controller {

    public function __construct() {
        $this->requireRole(['admin', 'tecnico']);
    }

    public function index() {
        /** @var DetalleComponente $detalleModel */
        $detalleModel = new DetalleComponente();
        $busqueda = $this->input('q');

        $trazabilidad = $detalleModel->auditoria($busqueda);

        $this->view('componentes/index', [
            'title' => 'Trazabilidad y Auditoría de Componentes',
            'registros' => $trazabilidad,
            'busqueda' => $busqueda
        ]);
    }

    public function agregar() {
        if ($this->isPost()) {
            $idOrden = $this->input('id_orden');
            if (!verify_csrf($this->input('csrf_token'))) {
                setFlash('danger', 'Token de seguridad inválido o sesión expirada.');
                $this->redirect('orden/ver/' . $idOrden);
            }

            $tipo = trim($this->input('tipo_componente'));
            $serieRetirada = trim($this->input('serie_retirada')) ?: null;
            $piezaInstalada = trim($this->input('pieza_instalada')) ?: null;
            $serieInstalada = trim($this->input('serie_instalada')) ?: null;
            $precio = (float)$this->input('precio', 0);
            $observacion = trim($this->input('observacion')) ?: null;

            if (empty($idOrden) || empty($tipo)) {
                setFlash('danger', 'El tipo de componente y la orden son requeridos.');
                $this->redirect('orden/ver/' . $idOrden);
            }

            $detalleModel = new DetalleComponente();
            $detalleModel->create([
                'id_orden' => $idOrden,
                'tipo_componente' => $tipo,
                'serie_retirada' => $serieRetirada,
                'pieza_instalada' => $piezaInstalada,
                'serie_instalada' => $serieInstalada,
                'precio' => $precio,
                'observacion' => $observacion
            ]);

            // Actualizar costo de repuestos en la orden si hay precio
            if ($precio > 0) {
                $ordenModel = new OrdenServicio();
                $orden = $ordenModel->find($idOrden);
                if ($orden) {
                    $nuevoRepuesto = (float)$orden['costo_repuestos'] + $precio;
                    $nuevoTotal = (float)$orden['costo_mano_obra'] + $nuevoRepuesto;
                    $ordenModel->update($idOrden, [
                        'costo_repuestos' => $nuevoRepuesto,
                        'costo_total' => $nuevoTotal
                    ]);
                }
            }

            setFlash('success', 'Componente registrado en la trazabilidad de la orden.');
            $this->redirect('orden/ver/' . $idOrden);
        }
    }

    /**
     * @param int|string $id
     */
    public function eliminar($id) {
        $detalleModel = new DetalleComponente();
        $comp = $detalleModel->find($id);
        $idOrden = $comp ? $comp['id_orden'] : null;

        if ($comp) {
            $detalleModel->delete($id);
            // Si tenía precio, restar de la orden
            if ((float)$comp['precio'] > 0 && $idOrden) {
                $ordenModel = new OrdenServicio();
                $orden = $ordenModel->find($idOrden);
                if ($orden) {
                    $nuevoRepuesto = max(0, (float)$orden['costo_repuestos'] - (float)$comp['precio']);
                    $nuevoTotal = (float)$orden['costo_mano_obra'] + $nuevoRepuesto;
                    $ordenModel->update($idOrden, [
                        'costo_repuestos' => $nuevoRepuesto,
                        'costo_total' => $nuevoTotal
                    ]);
                }
            }
            setFlash('success', 'Componente eliminado del registro.');
        }

        if ($idOrden) {
            $this->redirect('orden/ver/' . $idOrden);
        } else {
            $this->redirect('componente');
        }
    }
}
