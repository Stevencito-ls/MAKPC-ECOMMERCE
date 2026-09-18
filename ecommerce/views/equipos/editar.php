<?php
/**
 * @var array $equipo
 * @var array $clientes
 */
$flash = getFlash();
if ($flash): ?>
  <div class="alert alert-<?= e($flash['type']) ?>">
    <span><?= e($flash['message']) ?></span>
  </div>
<?php endif; ?>

<div class="page-header">
  <div>
    <h1>✏️ Editar Equipo</h1>
    <p>Actualizar especificaciones de <?= e($equipo['marca']) ?> <?= e($equipo['modelo']) ?></p>
  </div>
  <div class="page-header-actions">
    <a href="<?= url('equipo') ?>" class="btn btn-outline">
      🔙 Volver a Equipos
    </a>
  </div>
</div>

<div class="card" style="max-width:800px;margin:0 auto;">
  <div class="card-header">
    <h3>📝 Modificar Equipo</h3>
  </div>
  <div class="card-body">
    <form action="<?= url('equipo/editar/' . $equipo['id_equipo']) ?>" method="POST">
      <?= csrf_field() ?>
      <div class="form-grid">
        <div class="form-group" style="grid-column:1 / -1;">
          <label for="id_cliente">Cliente Propietario *</label>
          <select name="id_cliente" id="id_cliente" class="form-control" required>
            <?php foreach ($clientes as $c): ?>
              <option value="<?= $c['id_cliente'] ?>" <?= ($equipo['id_cliente'] == $c['id_cliente']) ? 'selected' : '' ?>>
                <?= e($c['nombres_apellidos']) ?> (Tel: <?= e($c['telefono']) ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="tipo_equipo">Tipo de Dispositivo *</label>
          <select name="tipo_equipo" id="tipo_equipo" class="form-control" required>
            <?php
            $tipos = ['Laptop', 'Computadora Torre', 'All-in-One', 'Impresora', 'Consola / Otro'];
            foreach ($tipos as $t): ?>
              <option value="<?= $t ?>" <?= ($equipo['tipo_equipo'] == $t) ? 'selected' : '' ?>><?= $t ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="marca">Marca *</label>
          <input type="text" id="marca" name="marca" class="form-control" required value="<?= e($equipo['marca']) ?>">
        </div>

        <div class="form-group">
          <label for="modelo">Modelo *</label>
          <input type="text" id="modelo" name="modelo" class="form-control" required value="<?= e($equipo['modelo']) ?>">
        </div>

        <div class="form-group">
          <label for="numero_serie">Número de Serie (S/N)</label>
          <input type="text" id="numero_serie" name="numero_serie" class="form-control" value="<?= e($equipo['numero_serie']) ?>">
        </div>

        <div class="form-group">
          <label for="codigo_patrimonial">Código Patrimonial</label>
          <input type="text" id="codigo_patrimonial" name="codigo_patrimonial" class="form-control" value="<?= e($equipo['codigo_patrimonial']) ?>">
        </div>

        <div class="form-group">
          <label for="color_detalles">Color y Detalles Físicos</label>
          <input type="text" id="color_detalles" name="color_detalles" class="form-control" value="<?= e($equipo['color_detalles']) ?>">
        </div>
      </div>

      <div style="margin-top:1.5rem;display:flex;justify-content:flex-end;gap:1rem;">
        <a href="<?= url('equipo') ?>" class="btn btn-outline">Cancelar</a>
        <button type="submit" class="btn btn-yellow">💾 Guardar Cambios</button>
      </div>
    </form>
  </div>
</div>
