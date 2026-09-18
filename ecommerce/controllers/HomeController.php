<?php
/**
 * Controlador de la Página Principal (Front Office / Tienda / Servicios)
 */
class HomeController extends Controller {

    public function index() {
        require_once __DIR__ . '/TiendaController.php';
        $tienda = new TiendaController();
        $tienda->index();
    }

    public function panel() {
        $this->redirect('panel');
    }

    public function notFound() {
        $this->view('home/404', [
            'title' => 'Página no encontrada'
        ]);
    }
}
