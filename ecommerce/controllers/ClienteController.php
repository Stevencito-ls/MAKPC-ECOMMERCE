<?php
class ClienteController extends Controller {

    public function __construct() {
        $this->requireAuth();
    }

    public function index() {
        $clienteModel = new Cliente();
        $busqueda = $this->input('q');

        if ($busqueda) {
            $clientes = $clienteModel->buscar($busqueda);
        } else {
            $clientes = $clienteModel->all('nombres_apellidos ASC');
        }

        $this->view('clientes/index', [
            'title' => 'Gestión de Clientes',
            'clientes' => $clientes,
            'busqueda' => $busqueda
        ]);
    }

    public function crear() {
        if ($this->isPost()) {
            if (!verify_csrf($this->input('csrf_token'))) {
                setFlash('danger', 'Token de seguridad inválido o sesión expirada. Por favor intente nuevamente.');
                $this->redirect('cliente/crear');
            }

            $nombres = trim($this->input('nombres_apellidos'));
            $telefono = trim($this->input('telefono'));
            $dni = trim($this->input('dni')) ?: null;
            $correo = trim($this->input('correo')) ?: null;
            $direccion = trim($this->input('direccion')) ?: null;
            $notas = trim($this->input('notas_cliente')) ?: null;

            if (empty($nombres) || empty($telefono)) {
                setFlash('danger', 'El nombre y el teléfono son campos obligatorios.');
                $this->redirect('cliente/crear');
            }

            $clienteModel = new Cliente();
            $id = $clienteModel->create([
                'nombres_razon_social' => $nombres,
                'telefono' => $telefono,
                'telefono_secundario' => trim($this->input('telefono_secundario')) ?: null,
                'numero_documento' => $dni,
                'email' => $correo,
                'direccion' => $direccion,
                'notas_cliente' => $notas
            ]);

            setFlash('success', 'Cliente registrado correctamente.');
            $this->redirect('cliente/ver/' . $id);
        }

        $this->view('clientes/crear', [
            'title' => 'Nuevo Cliente'
        ]);
    }

    /**
     * @param int|string $id
     */
    public function ver($id) {
        $clienteModel = new Cliente();
        $equipoModel = new Equipo();
        $ordenModel = new OrdenServicio();

        $cliente = $clienteModel->find($id);
        if (!$cliente) {
            setFlash('danger', 'Cliente no encontrado.');
            $this->redirect('cliente');
        }

        $equipos = $equipoModel->where('id_cliente', $id);
        $ordenes = $ordenModel->query(
            "SELECT o.*, CONCAT(e.marca, ' ', e.modelo) as equipo
             FROM ordenes_servicio o
             INNER JOIN equipos e ON o.id_equipo = e.id_equipo
             WHERE e.id_cliente = ?
             ORDER BY o.fecha_recepcion DESC",
            [$id]
        );

        $this->view('clientes/ver', [
            'title' => $cliente['nombres_apellidos'],
            'cliente' => $cliente,
            'equipos' => $equipos,
            'ordenes' => $ordenes
        ]);
    }

    /**
     * @param int|string $id
     */
    public function editar($id) {
        $clienteModel = new Cliente();
        $cliente = $clienteModel->find($id);

        if (!$cliente) {
            setFlash('danger', 'Cliente no encontrado.');
            $this->redirect('cliente');
        }

        if ($this->isPost()) {
            if (!verify_csrf($this->input('csrf_token'))) {
                setFlash('danger', 'Token de seguridad inválido o sesión expirada.');
                $this->redirect('cliente/editar/' . $id);
            }

            $nombres = trim($this->input('nombres_apellidos'));
            $telefono = trim($this->input('telefono'));

            if (empty($nombres) || empty($telefono)) {
                setFlash('danger', 'El nombre y el teléfono son campos obligatorios.');
                $this->redirect('cliente/editar/' . $id);
            }

            $clienteModel->update($id, [
                'nombres_razon_social' => $nombres,
                'telefono' => $telefono,
                'telefono_secundario' => trim($this->input('telefono_secundario')) ?: null,
                'numero_documento' => trim($this->input('dni')) ?: null,
                'email' => trim($this->input('correo')) ?: null,
                'direccion' => trim($this->input('direccion')) ?: null,
                'notas_cliente' => trim($this->input('notas_cliente')) ?: null
            ]);

            setFlash('success', 'Cliente actualizado exitosamente.');
            $this->redirect('cliente/ver/' . $id);
        }

        $this->view('clientes/editar', [
            'title' => 'Editar Cliente',
            'cliente' => $cliente
        ]);
    }

    /**
     * @param int|string $id
     */
    public function eliminar($id) {
        $this->requireRole('admin');
        $clienteModel = new Cliente();
        
        try {
            // Verificar si tiene órdenes de servicio a través de sus equipos
            $ordenModel = new OrdenServicio();
            $ordenesExistentes = $ordenModel->query(
                "SELECT COUNT(*) as total FROM ordenes_servicio o 
                 INNER JOIN equipos e ON o.id_equipo = e.id_equipo 
                 WHERE e.id_cliente = ?", 
                [$id]
            );
            $totalOrdenes = (int)($ordenesExistentes[0]['total'] ?? 0);
            
            if ($totalOrdenes > 0) {
                setFlash('warning', "No es posible eliminar el cliente porque tiene {$totalOrdenes} orden(es) de servicio asociadas en el taller.");
                $this->redirect('cliente/ver/' . $id);
            }

            $clienteModel->delete($id);
            setFlash('success', 'Cliente eliminado del sistema.');
        } catch (PDOException $e) {
            setFlash('danger', 'No se pudo eliminar el cliente debido a restricciones de integridad de datos.');
        }

        $this->redirect('cliente');
    }
}
