<?php
/**
 * InventarioController - Kardex y Control de Series
 */
class InventarioController extends Controller {

    private $productoModel;
    private $kardexModel;
    private $serieModel;

    public function __construct() {
        $this->requireRole(['admin', 'vendedor']);
        $this->productoModel = new Producto();
        $this->kardexModel = new Kardex();
        $this->serieModel = new ProductoSerie();
    }

    public function index() {
        $productos = $this->productoModel->all();
        $alertas = array_filter($productos, fn($p) => $p['stock'] <= 2);

        $this->view('inventario/index', [
            'title' => 'Kardex de Inventario',
            'productos' => $productos,
            'alertas' => $alertas
        ]);
    }

    public function agregarStock($idProducto) {
        if ($this->isPost()) {
            $cantidad = (int)$this->input('cantidad', 0);
            $seriesRaw = trim($this->input('series', ''));
            
            if ($cantidad <= 0) {
                setFlash('danger', 'Cantidad no válida.');
                $this->redirect('inventario');
            }

            $producto = $this->productoModel->find($idProducto);
            if (!$producto) {
                setFlash('danger', 'Producto no encontrado.');
                $this->redirect('inventario');
            }

            // Actualizar stock principal
            $nuevoStock = $producto['stock'] + $cantidad;
            $this->productoModel->update($idProducto, ['stock' => $nuevoStock]);

            // Registrar movimiento en Kardex
            $this->kardexModel->registrarMovimiento($idProducto, 'ENTRADA', $cantidad, 'Ingreso Manual Almacén');

            // Registrar series si se especificaron
            if (!empty($seriesRaw)) {
                $seriesList = array_filter(array_map('trim', explode("\n", $seriesRaw)));
                foreach ($seriesList as $serie) {
                    try {
                        $this->serieModel->create([
                            'id_producto' => $idProducto,
                            'numero_serie' => $serie,
                            'estado' => 'DISPONIBLE'
                        ]);
                    } catch (Exception $e) {
                        // Ignorar series duplicadas
                    }
                }
            }

            setFlash('success', "Stock actualizado (+{$cantidad}) y movimiento registrado en Kardex.");
        }
        $this->redirect('inventario');
    }
}
