<?php
/**
 * @var array $clientes
 * @var string|null $busqueda
 */
$flash = getFlash();
if ($flash): ?>
  <div class="alert alert-<?= e($flash['type']) ?>">
    <span><?= e($flash['message']) ?></span>
  </div>
<?php endif; ?>

<div class="page-header">
  <div>
    <h1>👥 Directorio de Clientes</h1>
    <p>Gestión de clientes, información de contacto primario y equipos asociados</p>
  </div>
  <div class="page-header-actions">
    <a href="<?= url('cliente/crear') ?>" class="btn btn-yellow">
      ➕ Nuevo Cliente
    </a>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <div style="flex:1;max-width:400px;">
      <form action="<?= url('cliente') ?>" method="GET" style="display:flex;gap:0.5rem;">
        <input type="text" name="q" class="form-control" placeholder="Buscar por nombre, teléfono o DNI..." value="<?= e($busqueda ?? '') ?>">
        <button type="submit" class="btn btn-primary">Buscar</button>
      </form>
    </div>
    <span style="font-size:0.85rem;color:var(--color-shadow);">
      Total: <strong><?= count($clientes) ?></strong> clientes
    </span>
  </div>

  <div class="table-responsive">
    <table class="custom-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombres y Apellidos</th>
          <th>Teléfono (WhatsApp)</th>
          <th>DNI / RUC</th>
          <th>Correo</th>
          <th>Equipos</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($clientes)): ?>
          <tr>
            <td colspan="7" style="text-align:center;padding:2rem;color:var(--color-shadow);">
              No se encontraron clientes registrados.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($clientes as $c): ?>
            <tr>
              <td>#<?= $c['id_cliente'] ?></td>
              <td>
                <a href="<?= url('cliente/ver/' . $c['id_cliente']) ?>" style="font-weight:700;color:var(--color-blue);">
                  <?= e($c['nombres_apellidos']) ?>
                </a>
              </td>
              <td>
                <a href="https://wa.me/51<?= preg_replace('/[^0-9]/', '', $c['telefono']) ?>" target="_blank" style="color:#25D366;font-weight:600;display:inline-flex;align-items:center;gap:0.25rem;">
                  📱 <?= e($c['telefono']) ?>
                </a>
              </td>
              <td><?= e($c['dni'] ?: '-') ?></td>
              <td><?= e($c['correo'] ?: '-') ?></td>
              <td>
                <span class="badge badge-revision">
                  <?= isset($c['total_equipos']) ? $c['total_equipos'] . ' eq.' : 'Ver' ?>
                </span>
              </td>
              <td style="white-space:nowrap;">
                <a href="<?= url('cliente/ver/' . $c['id_cliente']) ?>" class="btn btn-sm btn-primary" title="Ver Detalle">
                  👁️
                </a>
                <a href="<?= url('cliente/editar/' . $c['id_cliente']) ?>" class="btn btn-sm btn-outline" title="Editar">
                  ✏️
                </a>
                <a href="<?= url('cliente/eliminar/' . $c['id_cliente']) ?>" class="btn btn-sm btn-danger" data-confirm="¿Desea eliminar a este cliente? También se desvincularán sus registros." title="Eliminar">
                  🗑️
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
