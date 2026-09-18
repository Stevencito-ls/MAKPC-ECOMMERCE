<?php
/**
 * @var array $cliente
 */
$flash = getFlash();
if ($flash): ?>
  <div class="alert alert-<?= e($flash['type']) ?>">
    <span><?= e($flash['message']) ?></span>
  </div>
<?php endif; ?>

<div class="page-header">
  <div>
    <h1>✏️ Editar Cliente</h1>
    <p>Actualizar información de <?= e($cliente['nombres_apellidos']) ?></p>
  </div>
  <div class="page-header-actions">
    <a href="<?= url('cliente/ver/' . $cliente['id_cliente']) ?>" class="btn btn-outline">
      🔙 Volver al Perfil
    </a>
  </div>
</div>

<div class="card" style="max-width:800px;margin:0 auto;">
  <div class="card-header">
    <h3>📝 Modificar Datos</h3>
  </div>
  <div class="card-body">
    <form action="<?= url('cliente/editar/' . $cliente['id_cliente']) ?>" method="POST">
      <?= csrf_field() ?>
      <div class="form-grid">
        <div class="form-group" style="grid-column:1 / -1;">
          <label for="nombres_apellidos">Nombres y Apellidos *</label>
          <input type="text" id="nombres_apellidos" name="nombres_apellidos" class="form-control" required value="<?= e($cliente['nombres_apellidos']) ?>">
        </div>

        <div class="form-group">
          <label for="telefono">Teléfono Celular (WhatsApp) *</label>
          <input type="text" id="telefono" name="telefono" class="form-control" required value="<?= e($cliente['telefono']) ?>">
        </div>

        <div class="form-group">
          <label for="telefono_secundario">Teléfono Secundario / Fijo</label>
          <input type="text" id="telefono_secundario" name="telefono_secundario" class="form-control" value="<?= e($cliente['telefono_secundario']) ?>">
        </div>

        <div class="form-group">
          <label for="dni">DNI / RUC (Opcional)</label>
          <input type="text" id="dni" name="dni" class="form-control" value="<?= e($cliente['dni']) ?>">
        </div>

        <div class="form-group">
          <label for="correo">Correo Electrónico</label>
          <input type="email" id="correo" name="correo" class="form-control" value="<?= e($cliente['correo']) ?>">
        </div>

        <div class="form-group" style="grid-column:1 / -1;">
          <label for="direccion">Dirección</label>
          <input type="text" id="direccion" name="direccion" class="form-control" value="<?= e($cliente['direccion']) ?>">
        </div>

        <div class="form-group" style="grid-column:1 / -1;">
          <label for="notas_cliente">Notas adicionales</label>
          <textarea id="notas_cliente" name="notas_cliente" class="form-control"><?= e($cliente['notas_cliente']) ?></textarea>
        </div>
      </div>

      <div style="margin-top:1.5rem;display:flex;justify-content:flex-end;gap:1rem;">
        <a href="<?= url('cliente/ver/' . $cliente['id_cliente']) ?>" class="btn btn-outline">Cancelar</a>
        <button type="submit" class="btn btn-yellow">💾 Actualizar Cambios</button>
      </div>
    </form>
  </div>
</div>
