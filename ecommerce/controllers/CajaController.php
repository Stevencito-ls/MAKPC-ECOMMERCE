<?php
/**
 * CajaController - Módulo de Finanzas y Caja
 */
class CajaController extends Controller {

    /** @var Caja */
    private Caja $cajaModel;
    /** @var CajaMovimiento */
    private CajaMovimiento $movimientoModel;

    public function __construct() {
        $this->requireRole(['admin', 'vendedor']); // Permite a vendedores operar la caja
        $this->cajaModel = new Caja();
        $this->movimientoModel = new CajaMovimiento();
    }

    public function index() {
        $cajaAbierta = $this->cajaModel->getCajaAbierta();
        $movimientos = [];
        $montoActual = 0;

        if ($cajaAbierta) {
            $movimientos = $this->movimientoModel->getPorCaja($cajaAbierta['id']);
            $montoActual = $this->cajaModel->calcularMontoActual($cajaAbierta['id']);
        }

        // Obtener historial de cajas
        $historialCajas = $this->cajaModel->query("SELECT * FROM caja_diaria ORDER BY id DESC LIMIT 30");

        $this->view('caja/index', [
            'title' => 'Gestión de Caja y Finanzas',
            'caja' => $cajaAbierta,
            'movimientos' => $movimientos,
            'montoActual' => $montoActual,
            'historial' => $historialCajas
        ]);
    }

    public function abrir() {
        if ($this->isPost()) {
            if ($this->cajaModel->getCajaAbierta()) {
                setFlash('danger', 'Ya existe una caja abierta.');
                $this->redirect('caja');
            }

            $montoApertura = (float)$this->input('monto_apertura', 0);
            
            $this->cajaModel->create([
                'fecha' => date('Y-m-d'),
                'estado' => 'ABIERTA',
                'usuario_apertura' => auth('id'),
                'monto_apertura' => $montoApertura
            ]);

            setFlash('success', 'Caja abierta correctamente con S/ ' . number_format($montoApertura, 2));
        }
        $this->redirect('caja');
    }

    public function cerrar() {
        if ($this->isPost()) {
            $cajaAbierta = $this->cajaModel->getCajaAbierta();
            if (!$cajaAbierta) {
                setFlash('danger', 'No hay ninguna caja abierta.');
                $this->redirect('caja');
            }

            $montoCalculado = $this->cajaModel->calcularMontoActual($cajaAbierta['id']);
            $montoReal = (float)$this->input('monto_cierre_real', $montoCalculado);
            $diferencia = $montoReal - $montoCalculado;

            $this->cajaModel->update($cajaAbierta['id'], [
                'estado' => 'CERRADA',
                'usuario_cierre' => auth('id'),
                'monto_cierre_calculado' => $montoCalculado,
                'monto_cierre_real' => $montoReal,
                'diferencia' => $diferencia,
                'fecha_cierre' => date('Y-m-d H:i:s')
            ]);

            setFlash('success', 'Caja cerrada correctamente.');
        }
        $this->redirect('caja');
    }

    public function movimiento() {
        if ($this->isPost()) {
            $cajaAbierta = $this->cajaModel->getCajaAbierta();
            if (!$cajaAbierta) {
                setFlash('danger', 'Debes abrir una caja primero.');
                $this->redirect('caja');
            }

            $tipo = $this->input('tipo'); // INGRESO, EGRESO
            $monto = (float)$this->input('monto');
            $concepto = $this->input('concepto');
            $metodo_pago = $this->input('metodo_pago', 'EFECTIVO');

            if ($monto <= 0) {
                setFlash('danger', 'El monto debe ser mayor a 0.');
                $this->redirect('caja');
            }

            $this->movimientoModel->create([
                'id_caja' => $cajaAbierta['id'],
                'tipo' => $tipo,
                'metodo_pago' => $metodo_pago,
                'monto' => $monto,
                'concepto' => $concepto,
                'usuario_id' => auth('id')
            ]);

            setFlash('success', 'Movimiento registrado.');
        }
        $this->redirect('caja');
    }
}
