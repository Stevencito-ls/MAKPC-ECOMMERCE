<?php
/**
 * @var array $ordenes
 * @var string|null $filtroEstado
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
    <h1>Órdenes de Servicio</h1>
    <p>Recepción, diagnóstico, ejecución de trabajos y trazabilidad de garantías</p>
  </div>
  <div class="page-header-actions">
    <a href="<?= url('orden/crear') ?>" class="btn btn-yellow" style="display:inline-flex;align-items:center;gap:6px;">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
      Nueva Orden de Servicio
    </a>
  </div>
</div>

<!-- FILTROS POR ESTADO -->
<div style="display:flex;gap:0.5rem;margin-bottom:1.5rem;flex-wrap:wrap;">
  <a href="<?= url('orden') ?>" class="btn btn-sm <?= empty($filtroEstado) ? 'btn-primary' : 'btn-outline' ?>">
    Todas
  </a>
  <a href="<?= url('orden?estado=Pendiente') ?>" class="btn btn-sm <?= ($filtroEstado === 'Pendiente') ? 'btn-primary' : 'btn-outline' ?>">
    Pendientes
  </a>
  <a href="<?= url('orden?estado=En Reparacion') ?>" class="btn btn-sm <?= ($filtroEstado === 'En Reparacion') ? 'btn-primary' : 'btn-outline' ?>">
    En Reparación
  </a>
  <a href="<?= url('orden?estado=Terminado') ?>" class="btn btn-sm <?= ($filtroEstado === 'Terminado') ? 'btn-primary' : 'btn-outline' ?>">
    Terminados (Listos)
  </a>
  <a href="<?= url('orden?estado=Entregado') ?>" class="btn btn-sm <?= ($filtroEstado === 'Entregado') ? 'btn-primary' : 'btn-outline' ?>">
    Entregados
  </a>
</div>

<div class="card">
  <div class="card-header">
    <div style="flex:1;max-width:400px;">
      <form action="<?= url('orden') ?>" method="GET" style="display:flex;gap:0.5rem;">
        <input type="text" name="q" class="form-control" placeholder="Buscar por código, cliente o serie..." value="<?= e($busqueda ?? '') ?>">
        <?php if (!empty($filtroEstado)): ?>
          <input type="hidden" name="estado" value="<?= e($filtroEstado) ?>">
        <?php endif; ?>
        <button type="submit" class="btn btn-primary">Buscar</button>
      </form>
    </div>
    <span style="font-size:0.85rem;color:var(--color-shadow);">
      Total: <strong><?= count($ordenes) ?></strong> órdenes
    </span>
  </div>

  <div class="table-responsive">
    <table class="custom-table">
      <thead>
        <tr>
          <th>Código</th>
          <th>Fecha Recepción</th>
          <th>Cliente</th>
          <th>Equipo</th>
          <th>Tipo</th>
          <th>Estado</th>
          <th>Total</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($ordenes)): ?>
          <tr>
            <td colspan="8" style="text-align:center;padding:2rem;color:var(--color-shadow);">
              No hay órdenes de servicio con los criterios seleccionados.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($ordenes as $ord): 
            $badge = match($ord['estado']) {
              'Pendiente' => 'badge-pendiente',
              'En Reparacion' => 'badge-reparacion',
              'Terminado' => 'badge-listo',
              'Entregado' => 'badge-entregado',
              default => 'badge-cancelado'
            };
          ?>
            <tr>
              <td>
                <div class="code-badge-wrapper">
                  <a href="<?= url('orden/ver/' . $ord['id_orden']) ?>" class="order-code-link">
                    <?= e($ord['codigo_orden']) ?>
                  </a>
                  <?php if ($ord['es_inmediato']): ?>
                    <span class="badge-urgent-pill">URGENTE</span>
                  <?php endif; ?>
                </div>
              </td>
              <td><?= date('d/m/Y H:i', strtotime($ord['fecha_recepcion'])) ?></td>
              <td>
                <strong><?= e($ord['cliente_nombre']) ?></strong>
                <div style="font-size:0.8rem;color:var(--color-shadow);">
                  Tel: <?= e($ord['cliente_telefono']) ?>
                </div>
              </td>
              <td>
                <?= e($ord['marca']) ?> <?= e($ord['modelo']) ?>
                <div style="font-size:0.75rem;color:var(--color-celeste);"><?= e($ord['tipo_equipo']) ?></div>
              </td>
              <td><?= e($ord['servicio_solicitado'] ?: 'Reparación / Diagnóstico') ?></td>
              <td><span class="badge <?= $badge ?>"><?= e($ord['estado']) ?></span></td>
              <td><strong><?= formatPrecio($ord['costo_total']) ?></strong></td>
              <td>
                <div class="table-actions-cell">
                  <a href="<?= url('orden/ver/' . $ord['id_orden']) ?>" class="btn btn-sm btn-primary" title="Ver Detalle">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                  </a>
                  <a href="<?= url('orden/imprimir/' . $ord['id_orden']) ?>" target="_blank" class="btn btn-sm btn-yellow" title="Imprimir Ticket">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                  </a>
                  <a href="<?= url('orden/editar/' . $ord['id_orden']) ?>" class="btn btn-sm btn-outline" title="Editar">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                  </a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
