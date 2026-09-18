<?php
/**
 * @var array $registros
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
    <h1>🔍 Trazabilidad y Auditoría de Componentes</h1>
    <p>Control estricto de números de serie originales vs repuestos instalados</p>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <div style="flex:1;max-width:480px;">
      <form action="<?= url('componente') ?>" method="GET" style="display:flex;gap:0.5rem;">
        <input type="text" name="q" class="form-control" placeholder="Buscar por serie retirada, serie instalada, o tipo..." value="<?= e($busqueda ?? '') ?>">
        <button type="submit" class="btn btn-primary">Buscar Serie</button>
        <?php if (!empty($busqueda)): ?>
          <a href="<?= url('componente') ?>" class="btn btn-outline">Limpiar</a>
        <?php endif; ?>
      </form>
    </div>
    <span style="font-size:0.85rem;color:var(--color-shadow);">
      Total registros auditados: <strong><?= count($registros) ?></strong>
    </span>
  </div>

  <div class="table-responsive">
    <table class="custom-table">
      <thead>
        <tr>
          <th>Orden</th>
          <th>Cliente</th>
          <th>Equipo</th>
          <th>Componente</th>
          <th>Serie Retirada</th>
          <th>Pieza Instalada</th>
          <th>Serie Instalada</th>
          <th>Precio</th>
          <th>Fecha</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($registros)): ?>
          <tr>
            <td colspan="9" style="text-align:center;padding:2.5rem;color:var(--color-shadow);">
              No se encontraron registros de trazabilidad con la serie indicada.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($registros as $reg): ?>
            <tr>
              <td>
                <a href="<?= url('orden/ver/' . $reg['id_orden']) ?>" style="font-weight:700;color:var(--color-blue);">
                  <?= e($reg['codigo_orden']) ?>
                </a>
              </td>
              <td>
                <a href="<?= url('cliente/ver/' . $reg['id_cliente']) ?>">
                  <?= e($reg['cliente_nombre']) ?>
                </a>
                <div style="font-size:0.75rem;color:var(--color-shadow);">
                  Tel: <?= e($reg['cliente_telefono']) ?>
                </div>
              </td>
              <td>
                <?= e($reg['equipo_modelo'] ?? ($reg['tipo_equipo'] ?? 'Equipo')) ?>
                <?php if (!empty($reg['equipo_serie'])): ?>
                  <div style="font-size:0.75rem;color:var(--color-shadow);">S/N: <?= e($reg['equipo_serie']) ?></div>
                <?php endif; ?>
              </td>
              <td><strong><?= e($reg['tipo_componente']) ?></strong></td>
              <td>
                <?php if (!empty($reg['serie_retirada'])): ?>
                  <span style="background:#FEE2E2;color:#991B1B;padding:0.2rem 0.5rem;border-radius:4px;font-family:monospace;font-size:0.85rem;">
                    <?= e($reg['serie_retirada']) ?>
                  </span>
                <?php else: ?>
                  <span style="color:var(--color-shadow);font-size:0.8rem;">No registrada</span>
                <?php endif; ?>
              </td>
              <td><?= e($reg['pieza_instalada'] ?: 'Mismo / Reparado') ?></td>
              <td>
                <?php if (!empty($reg['serie_instalada'])): ?>
                  <span style="background:#D1FAE5;color:#065F46;padding:0.2rem 0.5rem;border-radius:4px;font-family:monospace;font-size:0.85rem;">
                    <?= e($reg['serie_instalada']) ?>
                  </span>
                <?php else: ?>
                  <span style="color:var(--color-shadow);font-size:0.8rem;">N/A</span>
                <?php endif; ?>
              </td>
              <td><strong><?= formatPrecio($reg['precio'] ?? 0) ?></strong></td>
              <td style="font-size:0.8rem;color:var(--color-shadow);">
                <?= !empty($reg['fecha_recepcion']) ? date('d/m/Y', strtotime($reg['fecha_recepcion'])) : '-' ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
