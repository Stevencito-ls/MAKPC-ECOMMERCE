<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h4 mb-0 fw-bold"><i class="fa-solid fa-tags me-2 text-primary"></i>Configuración de Ofertas</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= url('panel') ?>">Panel</a></li>
                <li class="breadcrumb-item active" aria-current="page">Ofertas</li>
            </ol>
        </nav>
    </div>
</div>

<?php 
$flash = getFlash();
if ($flash): 
?>
  <div class="alert alert-<?= e($flash['type']) ?>">
    <span><?= e($flash['message']) ?></span>
  </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="<?= url('configuracion/ofertas') ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                    <div class="mb-4">
                        <label class="form-label fw-bold">Estado de Ofertas Relámpago</label>
                        <div class="form-check form-switch fs-5">
                            <input class="form-check-input" type="checkbox" role="switch" id="ofertas_activas" name="ofertas_activas" value="1" <?= !empty($config['ofertas_activas']) ? 'checked' : '' ?>>
                            <label class="form-check-label ms-2" for="ofertas_activas">
                                <?= !empty($config['ofertas_activas']) ? '<span class="text-success">Activas</span>' : '<span class="text-muted">Desactivadas</span>' ?>
                            </label>
                        </div>
                        <small class="text-muted d-block mt-1">Activa o desactiva la ventana flotante de ofertas en la tienda.</small>
                    </div>

                    <div class="mb-4">
                        <label for="ofertas_fin" class="form-label fw-bold">Fecha y Hora de Fin</label>
                        <input type="datetime-local" class="form-control" id="ofertas_fin" name="ofertas_fin" value="<?= !empty($config['ofertas_fin']) ? date('Y-m-d\TH:i', strtotime($config['ofertas_fin'])) : '' ?>">
                        <small class="text-muted d-block mt-1">El contador regresivo en la tienda terminará en esta fecha.</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold">
                        <i class="fa-solid fa-save me-2"></i>Guardar Configuración
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="alert alert-info">
            <h5 class="alert-heading"><i class="fa-solid fa-circle-info me-2"></i>¿Cómo funciona?</h5>
            <p>1. Para que un producto aparezca en la sección de ofertas relámpago, debe tener un <strong>precio anterior</strong> mayor a su <strong>precio actual</strong>.</p>
            <p>2. Puedes modificar los precios de los productos desde la sección <a href="<?= url('producto') ?>">Productos</a>.</p>
            <p class="mb-0">3. Si la fecha de fin ya pasó o las ofertas están desactivadas, el modal no se mostrará a los clientes.</p>
        </div>
    </div>
</div>

<script>
document.getElementById('ofertas_activas').addEventListener('change', function() {
    const label = this.nextElementSibling;
    if(this.checked) {
        label.innerHTML = '<span class="text-success">Activas</span>';
    } else {
        label.innerHTML = '<span class="text-muted">Desactivadas</span>';
    }
});
</script>
