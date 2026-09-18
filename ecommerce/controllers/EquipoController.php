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
        $clienteModel = new Cliente();
        $clientes = $clienteModel->all('nombres_apellidos ASC');
        $preClienteId = $this->input('cliente_id');

        if ($this->isPost()) {
            if (!verify_csrf($this->input('csrf_token'))) {
                setFlash('danger', 'Token de seguridad inválido o sesión expirada.');
                $this->redirect('equipo/crear');
            }

            $clienteId = $this->input('id_cliente');
            $tipo = $this->input('tipo_equipo');
            $marca = trim($this->input('marca'));
            $modelo = trim($this->input('modelo'));
            $serie = trim($this->input('numero_serie')) ?: null;
            $codigoPat = trim($this->input('codigo_patrimonial')) ?: null;
            $color = trim($this->input('color_detalles')) ?: null;

            if (empty($clienteId) || empty($marca) || empty($modelo)) {
                setFlash('danger', 'Cliente, marca y modelo son campos obligatorios.');
                $this->redirect('equipo/crear');
            }

            $equipoModel = new Equipo();
            $id = $equipoModel->create([
                'id_cliente' => $clienteId,
                'tipo_equipo' => $tipo,
                'marca' => $marca,
                'modelo' => $modelo,
                'numero_serie' => $serie,
                'codigo_patrimonial' => $codigoPat,
                'color_detalles' => $color
            ]);

            setFlash('success', 'Equipo registrado exitosamente.');
            $this->redirect('cliente/ver/' . $clienteId);
        }

        $this->view('equipos/crear', [
            'title' => 'Registrar Equipo',
            'clientes' => $clientes,
            'preClienteId' => $preClienteId
        ]);
    }

    /**
     * @param int|string $id
     */
    public function editar($id) {
        $equipoModel = new Equipo();
        $equipo = $equipoModel->find($id);

        if (!$equipo) {
            setFlash('danger', 'Equipo no encontrado.');
            $this->redirect('equipo');
        }

        $clienteModel = new Cliente();
        $clientes = $clienteModel->all('nombres_apellidos ASC');

        if ($this->isPost()) {
            if (!verify_csrf($this->input('csrf_token'))) {
                setFlash('danger', 'Token de seguridad inválido o sesión expirada.');
                $this->redirect('equipo/editar/' . $id);
            }

            $equipoModel->update($id, [
                'id_cliente' => $this->input('id_cliente'),
                'tipo_equipo' => $this->input('tipo_equipo'),
                'marca' => trim($this->input('marca')),
                'modelo' => trim($this->input('modelo')),
                'numero_serie' => trim($this->input('numero_serie')) ?: null,
                'codigo_patrimonial' => trim($this->input('codigo_patrimonial')) ?: null,
                'color_detalles' => trim($this->input('color_detalles')) ?: null
            ]);

            setFlash('success', 'Equipo actualizado correctamente.');
            $this->redirect('cliente/ver/' . $equipo['id_cliente']);
        }

        $this->view('equipos/editar', [
            'title' => 'Editar Equipo',
            'equipo' => $equipo,
            'clientes' => $clientes
        ]);
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
