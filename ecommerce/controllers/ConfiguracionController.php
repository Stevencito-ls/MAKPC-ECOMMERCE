<?php
class ConfiguracionController extends Controller {

    public function __construct() {
        $this->requireRole(['admin']);
    }

    public function ofertas() {
        $configModel = new Configuracion();
        $config = $configModel->getConfig();

        if ($this->isPost()) {
            if (!verify_csrf($this->input('csrf_token'))) {
                setFlash('danger', 'Token de seguridad expirado.');
                $this->redirect('configuracion/ofertas');
            }

            $ofertasActivas = $this->input('ofertas_activas') ? 1 : 0;
            $ofertasFin = $this->input('ofertas_fin');

            if (empty($ofertasFin)) {
                $ofertasFin = null;
            }

            $data = [
                'ofertas_activas' => $ofertasActivas,
                'ofertas_fin' => $ofertasFin
            ];

            if ($config) {
                $configModel->update($config['id'], $data);
            } else {
                $configModel->create(array_merge([
                    'ruc' => '20409456520',
                    'razon_social' => 'MAK-PC ENTERPRISES S.A.C.',
                    'nombre_comercial' => 'MAK-PC Soporte Tecnológico',
                    'telefono' => '960 702 605',
                    'direccion' => 'Tumbes, Perú'
                ], $data));
            }

            setFlash('success', 'Configuración de ofertas actualizada.');
            $this->redirect('configuracion/ofertas');
        }

        $this->view('configuracion/ofertas', [
            'title' => 'Gestión de Ofertas | MAKPC',
            'config' => $config
        ]);
    }
}
