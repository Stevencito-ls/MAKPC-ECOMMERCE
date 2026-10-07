<?php
/**
 * PedidoController - Gestión Administrativa de Ventas E-commerce & Comprobantes SUNAT
 * Exclusivo para roles: 'admin' y 'vendedor'
 */
class PedidoController extends Controller {

    /** @var PedidoTienda */
    private $pedidoModel;

    public function __construct() {
        $this->requireRole(['admin', 'vendedor']);
        $this->pedidoModel = new PedidoTienda();
    }

    /**
     * Listado general de pedidos y ventas e-commerce
     */
    public function index() {
        $busqueda = trim($this->input('q', ''));
        $filtroPago = trim($this->input('pago', ''));

        $pedidos = $this->pedidoModel->listarAdmin($busqueda, $filtroPago);
        $metricas = $this->pedidoModel->obtenerMetricasVentas();

        $this->view('pedidos/index', [
            'title' => 'Ventas E-commerce & Comprobantes SUNAT | MAKPC',
            'pedidos' => $pedidos,
            'metricas' => $metricas,
            'busqueda' => $busqueda,
            'filtroPago' => $filtroPago
        ]);
    }

    /**
     * Ver detalle completo de una orden
     */
    public function ver($codigo = null) {
        if (empty($codigo)) {
            $codigo = $this->input('codigo');
        }

        if (empty($codigo)) {
            setFlash('danger', 'Debe especificar el código de la orden.');
            $this->redirect('pedido');
        }

        $pedido = $this->pedidoModel->obtenerConComprobante($codigo);

        if (!$pedido) {
            setFlash('danger', 'Pedido no encontrado.');
            $this->redirect('pedido');
        }

        $items = json_decode($pedido['items_json'] ?? '[]', true);

        $this->view('pedidos/ver', [
            'title' => "Orden {$pedido['codigo_pedido']} | MAKPC",
            'pedido' => $pedido,
            'items' => $items
        ]);
    }

    /**
     * Actualizar estado de despacho de la orden
     */
    public function actualizarDespacho() {
        if (!$this->isPost()) {
            $this->redirect('pedido');
        }

        if (!verify_csrf($this->input('csrf_token'))) {
            setFlash('danger', 'Token de seguridad inválido. Intente nuevamente.');
            $this->redirect('pedido');
        }

        $idPedido = (int)$this->input('id_pedido');
        $codigoPedido = trim($this->input('codigo_pedido'));
        $nuevoEstado = trim($this->input('estado_despacho'));

        if ($this->pedidoModel->actualizarDespacho($idPedido, $nuevoEstado)) {
            setFlash('success', "Estado de despacho de la orden {$codigoPedido} actualizado a \"{$nuevoEstado}\".");
        } else {
            setFlash('danger', 'No fue posible actualizar el estado de despacho.');
        }

        $this->redirect(!empty($codigoPedido) ? "pedido/ver/{$codigoPedido}" : 'pedido');
    }

    /**
     * Acceso directo al comprobante legal SUNAT y voucher
     */
    public function comprobante($codigo = null) {
        if (empty($codigo)) {
            $this->redirect('pedido');
        }
        $this->redirect("tienda/comprobante/{$codigo}");
    }
    /**
     * Subir Boleta PDF manualmente
     */
    public function subirBoleta() {
        if (!$this->isPost()) {
            $this->redirect('pedido');
        }

        if (!verify_csrf($this->input('csrf_token'))) {
            setFlash('danger', 'Token de seguridad inválido. Intente nuevamente.');
            $this->redirect('pedido');
        }

        $idPedido = (int)$this->input('id_pedido');
        $codigoPedido = trim($this->input('codigo_pedido'));

        if (isset($_FILES['boleta_pdf']) && $_FILES['boleta_pdf']['error'] === UPLOAD_ERR_OK) {
            $fileTmp = $_FILES['boleta_pdf']['tmp_name'];
            $fileName = $_FILES['boleta_pdf']['name'];
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if ($ext === 'pdf') {
                $uploadDir = __DIR__ . '/../uploads/boletas/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $newFileName = 'boleta_' . $codigoPedido . '_' . time() . '.pdf';
                $destPath = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmp, $destPath)) {
                    $this->pedidoModel->update($idPedido, ['ruta_comprobante_pdf' => 'uploads/boletas/' . $newFileName]);
                    setFlash('success', 'Boleta PDF subida y adjuntada correctamente a la orden ' . $codigoPedido . '.');
                } else {
                    setFlash('danger', 'Error al guardar el archivo PDF.');
                }
            } else {
                setFlash('danger', 'Formato no permitido. Solo se aceptan archivos PDF.');
            }
        } else {
            setFlash('danger', 'No se ha seleccionado ningún archivo PDF válido.');
        }

        $this->redirect(!empty($codigoPedido) ? "pedido/ver/{$codigoPedido}" : 'pedido');
    }
}
