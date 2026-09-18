<?php
/**
 * PanelController - Dashboard Adaptativo según Rol (Admin, Técnico, Vendedor)
 */
class PanelController extends Controller {

    public function index() {
        $this->requireRole(['admin', 'tecnico', 'vendedor']);

        $rol = auth('rol');

        $ordenModel = new OrdenServicio();
        $clienteModel = new Cliente();
        $equipoModel = new Equipo();
        $productoModel = new Producto();
        $pedidoModel = new PedidoTienda();

        // Estadísticas generales de órdenes y taller
        $stats = $ordenModel->estadisticas();
        $ordenesRecientes = $ordenModel->recientes(6);
        $totalClientes = $clienteModel->count();
        $totalEquipos = $equipoModel->count();

        // Métricas de inventario comercial
        $totalProductos = $productoModel->count();
        $productosActivos = $productoModel->count('activo = 1');
        $stockBajo = $productoModel->contarPorStock('bajo');
        $agotados = $productoModel->contarPorStock('agotado');

        // Métricas de ventas online & facturación SUNAT
        $metricasVentas = $pedidoModel->obtenerMetricasVentas();
        $pedidosRecientes = $pedidoModel->ultimosPedidos(5);

        // Métricas de trazabilidad y usuarios
        $detalleModel = new DetalleComponente();
        $totalPiezasAuditadas = $detalleModel->count();

        $usuarioModel = new Usuario();
        $totalUsuarios = $usuarioModel->count('activo = 1');

        $this->view('panel/index', [
            'title' => 'Panel de Control | MAKPC Enterprises',
            'rol' => $rol,
            'stats' => $stats,
            'recientes' => $ordenesRecientes,
            'totalClientes' => $totalClientes,
            'totalEquipos' => $totalEquipos,
            'totalProductos' => $totalProductos,
            'productosActivos' => $productosActivos,
            'stockBajo' => $stockBajo,
            'agotados' => $agotados,
            'metricasVentas' => $metricasVentas,
            'pedidosRecientes' => $pedidosRecientes,
            'totalPiezasAuditadas' => $totalPiezasAuditadas,
            'totalUsuarios' => $totalUsuarios
        ]);
    }
}
