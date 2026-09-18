<?php
/**
 * @var array $equipos
 */
$flash = getFlash();
if ($flash): ?>
  <div class="alert alert-<?= e($flash['type']) ?>">
    <span><?= e($flash['message']) ?></span>
  </div>
<?php endif; ?>

<div class="page-header">
  <div>
    <h1>💻 Inventario de Equipos</h1>
    <p>Dispositivos vinculados a clientes para mantenimiento y reparación</p>
  </div>
  <div class="page-header-actions">
    <a href="<?= url('equipo/crear') ?>" class="btn btn-yellow">
      ➕ Registrar Equipo
    </a>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <div style="flex:1;max-width:380px;">
      <input type="text" class="form-control" placeholder="Filtrar por marca, serie, cliente..." data-table-search="tablaEquipos">
    </div>
    <span style="font-size:0.85rem;color:var(--color-shadow);">
      Total: <strong><?= count($equipos) ?></strong> equipos
    </span>
  </div>

  <div class="table-responsive">
    <table class="custom-table" id="tablaEquipos">
      <thead>
        <tr>
          <th>ID</th>
          <th>Tipo</th>
          <th>Marca / Modelo</th>
          <th>N° de Serie</th>
          <th>Propietario (Cliente)</th>
          <th>Teléfono</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($equipos)): ?>
          <tr>
            <td colspan="7" style="text-align:center;padding:2rem;color:var(--color-shadow);">
              No hay equipos registrados en el sistema.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($equipos as $eq): ?>
            <tr>
              <td>#<?= $eq['id_equipo'] ?></td>
              <td><span class="badge badge-revision"><?= e($eq['tipo_equipo']) ?></span></td>
              <td><strong><?= e($eq['marca']) ?> <?= e($eq['modelo']) ?></strong></td>
              <td><code><?= e($eq['numero_serie'] ?: 'S/N') ?></code></td>
              <td>
                <a href="<?= url('cliente/ver/' . $eq['id_cliente']) ?>" style="font-weight:600;color:var(--color-blue);">
                  <?= e($eq['cliente_nombre']) ?>
                </a>
              </td>
              <td>
                <a href="https://wa.me/51<?= preg_replace('/[^0-9]/', '', $eq['cliente_tel']) ?>" target="_blank" style="color:#25D366;font-weight:600;">
                  📱 <?= e($eq['cliente_tel']) ?>
                </a>
              </td>
              <td style="white-space:nowrap;">
                <a href="<?= url('orden/crear?equipo_id=' . $eq['id_equipo']) ?>" class="btn btn-sm btn-yellow" title="Crear Orden">
                  ⚡ Orden
                </a>
                <a href="<?= url('equipo/editar/' . $eq['id_equipo']) ?>" class="btn btn-sm btn-outline" title="Editar">
                  ✏️
                </a>
                <a href="<?= url('equipo/eliminar/' . $eq['id_equipo']) ?>" class="btn btn-sm btn-danger" data-confirm="¿Desea eliminar este equipo?" title="Eliminar">
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
