<?php
/**
 * @var array $stats
 * @var array $recientes
 * @var int $totalClientes
 * @var int $totalEquipos
 * @var int $totalProductos
 */
$flash = getFlash();
if ($flash): ?>
  <div class="alert alert-<?= e($flash['type']) ?>">
    <span><?= e($flash['message']) ?></span>
  </div>
<?php endif; ?>

<div class="page-header">
  <div>
    <h1>Panel de Control</h1>
    <p>Bienvenido a MAKPC Enterprises - Sistema de Gestión de Taller & E-commerce</p>
  </div>
  <div class="page-header-actions">
    <a href="<?= url('orden/crear') ?>" class="btn btn-yellow">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px;"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
      Nueva Orden
    </a>
    <a href="<?= url('cliente/crear') ?>" class="btn btn-primary">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
      Registrar Cliente
    </a>
    <a href="<?= url('tienda') ?>" class="btn btn-outline" target="_blank">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px;"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
      Ver Tienda
    </a>
  </div>
</div>

<!-- KPIs -->
<div class="kpi-grid">
  <div class="kpi-card">
    <div class="kpi-icon yellow">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
    </div>
    <div class="kpi-details">
      <h3>Órdenes Totales</h3>
      <div class="kpi-value"><?= (int)($stats['total'] ?? 0) ?></div>
    </div>
  </div>

  <div class="kpi-card">
    <div class="kpi-icon celeste">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
    </div>
    <div class="kpi-details">
      <h3>Pendientes / Revisión</h3>
      <div class="kpi-value"><?= (int)($stats['pendientes'] ?? 0) ?></div>
    </div>
  </div>

  <div class="kpi-card">
    <div class="kpi-icon blue">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
    </div>
    <div class="kpi-details">
      <h3>En Reparación</h3>
      <div class="kpi-value"><?= (int)($stats['en_reparacion'] ?? 0) ?></div>
    </div>
  </div>

  <div class="kpi-card">
    <div class="kpi-icon green">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
    </div>
    <div class="kpi-details">
      <h3>Terminados / Entregados</h3>
      <div class="kpi-value"><?= (int)($stats['terminados'] ?? 0) + (int)($stats['entregados'] ?? 0) ?></div>
    </div>
  </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;align-items:start;">
  <!-- ÓRDENES RECIENTES -->
  <div class="card">
    <div class="card-header">
      <h2 style="display:flex;align-items:center;gap:8px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
        Órdenes de Servicio Recientes
      </h2>
      <a href="<?= url('orden') ?>" class="btn btn-sm btn-outline">Ver Todas</a>
    </div>
    <div class="table-responsive">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Código</th>
            <th>Cliente</th>
            <th>Equipo</th>
            <th>Falla Reportada</th>
            <th>Estado</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($recientes)): ?>
            <tr>
              <td colspan="6" style="text-align:center;padding:2rem;color:var(--color-shadow);">
                No hay órdenes de servicio registradas aún.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($recientes as $ord): 
              $estadoClass = match($ord['estado']) {
                'Pendiente' => 'badge-pendiente',
                'En Reparacion' => 'badge-reparacion',
                'Terminado' => 'badge-listo',
                'Entregado' => 'badge-entregado',
                default => 'badge-revision'
              };
            ?>
              <tr>
                <td><strong><?= e($ord['codigo_orden']) ?></strong></td>
                <td><?= e($ord['cliente_nombre']) ?></td>
                <td><?= e($ord['equipo']) ?></td>
                <td style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                  <?= e($ord['falla_reportada']) ?>
                </td>
                <td>
                  <span class="badge <?= $estadoClass ?>"><?= e($ord['estado']) ?></span>
                </td>
                <td>
                  <a href="<?= url('orden/ver/' . $ord['id_orden']) ?>" class="btn btn-sm btn-primary">
                    Ver
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- RESUMEN LATERAL -->
  <div>
    <div class="card">
      <div class="card-header">
        <h3 style="display:flex;align-items:center;gap:8px;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
          Datos del Sistema
        </h3>
      </div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:1rem;">
        <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:0.75rem;border-bottom:1px solid #edf0f5;">
          <span style="display:flex;align-items:center;gap:6px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            Clientes Registrados:
          </span>
          <strong><?= (int)$totalClientes ?></strong>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:0.75rem;border-bottom:1px solid #edf0f5;">
          <span style="display:flex;align-items:center;gap:6px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
            Equipos en Base de Datos:
          </span>
          <strong><?= (int)$totalEquipos ?></strong>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:0.75rem;border-bottom:1px solid #edf0f5;">
          <span style="display:flex;align-items:center;gap:6px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
            Productos en Catálogo:
          </span>
          <strong><?= (int)$totalProductos ?></strong>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;">
          <span style="display:flex;align-items:center;gap:6px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            Ingresos Estimados:
          </span>
          <strong style="color:var(--color-success);"><?= formatPrecio($stats['ingresos_total'] ?? 0) ?></strong>
        </div>
      </div>
    </div>

    <div class="card" style="background:linear-gradient(135deg, var(--color-blue), var(--color-blue-dark));color:#ffffff;">
      <div class="card-body" style="text-align:center;padding:1.75rem;">
        <h3 style="color:var(--color-yellow);margin-bottom:0.5rem;display:flex;align-items:center;justify-content:center;gap:8px;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          Trazabilidad de Repuestos
        </h3>
        <p style="font-size:0.85rem;color:rgba(255,255,255,0.8);margin-bottom:1.25rem;">
          Audite piezas cambiadas, números de serie retirados e instalados con garantía certificada.
        </p>
        <a href="<?= url('componente') ?>" class="btn btn-yellow" style="width:100%;">
          Auditar Componentes
        </a>
      </div>
    </div>
  </div>
</div>
