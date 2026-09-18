<?php
/**
 * @var string $rol
 * @var array $stats
 * @var array $recientes
 * @var int $totalClientes
 * @var int $totalEquipos
 * @var int $totalProductos
 * @var int $productosActivos
 * @var int $stockBajo
 * @var int $agotados
 * @var int $totalPiezasAuditadas
 * @var int $totalUsuarios
 * @var array $metricasVentas
 * @var array $pedidosRecientes
 */
$flash = getFlash();
if ($flash): ?>
  <div class="alert alert-<?= e($flash['type']) ?>">
    <span><?= e($flash['message']) ?></span>
  </div>
<?php endif; ?>

<div class="page-header">
  <div>
    <div style="display:flex;align-items:center;gap:0.6rem;flex-wrap:wrap;">
      <h1 style="margin:0;display:flex;align-items:center;gap:0.5rem;">
        <svg style="width:28px;height:28px;fill:var(--color-blue);" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/></svg>
        <span>
          <?php if ($rol === 'vendedor'): ?>
            Panel de Control Comercial & Ventas
          <?php elseif ($rol === 'tecnico'): ?>
            Centro de Operaciones de Taller
          <?php else: ?>
            Panel de Control & Administración General
          <?php endif; ?>
        </span>
      </h1>
      <span class="badge badge-<?= strtolower(e($rol)) ?>" style="font-size:0.75rem;padding:0.25rem 0.65rem;">
        Perfil: <?= strtoupper(e($rol)) ?>
      </span>
    </div>
    <p style="margin-top:0.35rem;">Bienvenido(a), <strong><?= e(auth('nombre_completo')) ?></strong> &mdash; MAKPC Enterprises S.A.C.</p>
  </div>

  <div class="page-header-actions">
    <?php if ($rol === 'vendedor'): ?>
      <a href="<?= url('producto/crear') ?>" class="btn btn-yellow" style="display:inline-flex;align-items:center;gap:0.4rem;">
        <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
        <span>Subir Producto</span>
      </a>
      <a href="<?= url('orden/crear') ?>" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:0.4rem;">
        <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
        <span>Recepción de Equipo</span>
      </a>
    <?php elseif ($rol === 'tecnico'): ?>
      <a href="<?= url('orden') ?>" class="btn btn-yellow" style="display:inline-flex;align-items:center;gap:0.4rem;">
        <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z"/></svg>
        <span>Órdenes en Taller</span>
      </a>
      <a href="<?= url('componente') ?>" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:0.4rem;">
        <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
        <span>Trazabilidad Repuestos</span>
      </a>
    <?php else: ?>
      <a href="<?= url('orden/crear') ?>" class="btn btn-yellow" style="display:inline-flex;align-items:center;gap:0.4rem;">
        <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
        <span>Nueva Orden</span>
      </a>
      <a href="<?= url('producto/crear') ?>" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:0.4rem;">
        <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 24 24"><path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm7 17H5V8h14v12z"/></svg>
        <span>Nuevo Producto</span>
      </a>
      <a href="<?= url('usuario/crear') ?>" class="btn btn-outline" style="display:inline-flex;align-items:center;gap:0.4rem;">
        <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 24 24"><path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
        <span>Nuevo Usuario</span>
      </a>
    <?php endif; ?>
  </div>
</div>

<!-- ========================================================
     KPIS ADAPTATIVOS POR ROL
     ======================================================== -->
<div class="kpi-grid">
  <?php if ($rol === 'vendedor'): ?>
    <div class="kpi-card">
      <div class="kpi-icon blue">
        <svg viewBox="0 0 24 24"><path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm7 17H5V8h14v12z"/></svg>
      </div>
      <div class="kpi-details">
        <h3>Productos en Catálogo</h3>
        <div class="kpi-value"><?= (int)$totalProductos ?></div>
      </div>
    </div>

    <div class="kpi-card">
      <div class="kpi-icon green">
        <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
      </div>
      <div class="kpi-details">
        <h3>Activos para Venta</h3>
        <div class="kpi-value"><?= (int)$productosActivos ?></div>
      </div>
    </div>

    <div class="kpi-card">
      <div class="kpi-icon yellow">
        <svg viewBox="0 0 24 24"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
      </div>
      <div class="kpi-details">
        <h3>Alerta Stock Bajo (&le; 5)</h3>
        <div class="kpi-value" style="color:#d97706;"><?= (int)$stockBajo ?></div>
      </div>
    </div>

    <div class="kpi-card">
      <div class="kpi-icon celeste">
        <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
      </div>
      <div class="kpi-details">
        <h3>Clientes Registrados</h3>
        <div class="kpi-value"><?= (int)$totalClientes ?></div>
      </div>
    </div>

  <?php elseif ($rol === 'tecnico'): ?>
    <div class="kpi-card">
      <div class="kpi-icon yellow">
        <svg viewBox="0 0 24 24"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm4.2 14.2L11 13V7h1.5v5.2l4.5 2.7-.8 1.3z"/></svg>
      </div>
      <div class="kpi-details">
        <h3>Pendientes Diagnóstico</h3>
        <div class="kpi-value"><?= (int)($stats['pendientes'] ?? 0) ?></div>
      </div>
    </div>

    <div class="kpi-card">
      <div class="kpi-icon blue">
        <svg viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z"/></svg>
      </div>
      <div class="kpi-details">
        <h3>En Reparación en Banco</h3>
        <div class="kpi-value"><?= (int)($stats['en_reparacion'] ?? 0) ?></div>
      </div>
    </div>

    <div class="kpi-card">
      <div class="kpi-icon green">
        <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
      </div>
      <div class="kpi-details">
        <h3>Terminados / Listos</h3>
        <div class="kpi-value"><?= (int)($stats['terminados'] ?? 0) ?></div>
      </div>
    </div>

    <div class="kpi-card">
      <div class="kpi-icon celeste">
        <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
      </div>
      <div class="kpi-details">
        <h3>Piezas Auditadas / Series</h3>
        <div class="kpi-value"><?= (int)$totalPiezasAuditadas ?></div>
      </div>
    </div>

  <?php else: ?>
    <!-- ADMIN: MÉTRICAS GLOBALES -->
    <div class="kpi-card">
      <div class="kpi-icon yellow">
        <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
      </div>
      <div class="kpi-details">
        <h3>Órdenes de Servicio</h3>
        <div class="kpi-value"><?= (int)($stats['total'] ?? 0) ?></div>
      </div>
    </div>

    <div class="kpi-card">
      <div class="kpi-icon blue">
        <svg viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
      </div>
      <div class="kpi-details">
        <h3>Ingresos Taller Estimados</h3>
        <div class="kpi-value" style="font-size:1.35rem;color:var(--color-success);"><?= formatPrecio($stats['ingresos_total'] ?? 0) ?></div>
      </div>
    </div>

    <div class="kpi-card">
      <div class="kpi-icon green">
        <svg viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
      </div>
      <div class="kpi-details">
        <h3>Ventas Online Culqi</h3>
        <div class="kpi-value" style="font-size:1.35rem;color:var(--color-success);">S/ <?= number_format((float)($metricasVentas['total_ventas_soles'] ?? 0), 2) ?></div>
      </div>
    </div>

    <div class="kpi-card">
      <div class="kpi-icon celeste">
        <svg viewBox="0 0 24 24"><path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm7 17H5V8h14v12z"/></svg>
      </div>
      <div class="kpi-details">
        <h3>Productos en E-commerce</h3>
        <div class="kpi-value"><?= (int)$totalProductos ?></div>
      </div>
    </div>
  <?php endif; ?>
</div>

<!-- ========================================================
     CUERPO PRINCIPAL DEL DASHBOARD
     ======================================================== -->
<div class="dashboard-main-grid">
  <!-- TABLA DE ÓRDENES RECIENTES -->
  <div class="card">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
      <h2 style="margin:0;font-size:1.15rem;display:flex;align-items:center;gap:0.5rem;">
        <svg style="width:20px;height:20px;fill:var(--color-blue);" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
        <span>Órdenes de Servicio en Taller</span>
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
              <td colspan="6" style="text-align:center;padding:2.5rem;color:var(--color-shadow);">
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

  <!-- TARJETA LATERAL SEGÚN ROL -->
  <div>
    <?php if ($rol === 'vendedor'): ?>
      <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header">
          <h3 style="margin:0;font-size:1.05rem;">Módulo Comercial</h3>
        </div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:1rem;">
          <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:0.75rem;border-bottom:1px solid #edf0f5;">
            <span>Total en Catálogo:</span>
            <strong><?= (int)$totalProductos ?> productos</strong>
          </div>
          <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:0.75rem;border-bottom:1px solid #edf0f5;">
            <span>Stock Bajo (&le; 5):</span>
            <strong style="color:#d97706;"><?= (int)$stockBajo ?> productos</strong>
          </div>
          <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:0.75rem;border-bottom:1px solid #edf0f5;">
            <span>Agotados (0 stock):</span>
            <strong style="color:#dc2626;"><?= (int)$agotados ?> productos</strong>
          </div>
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <span>Clientes Registrados:</span>
            <strong><?= (int)$totalClientes ?></strong>
          </div>
        </div>
      </div>

      <div class="card" style="background:linear-gradient(135deg, var(--color-blue), var(--color-blue-dark));color:#ffffff;">
        <div class="card-body" style="text-align:center;padding:1.75rem;">
          <h3 style="color:var(--color-yellow);margin-bottom:0.5rem;">Gestión de Catálogo</h3>
          <p style="font-size:0.85rem;color:rgba(255,255,255,0.8);margin-bottom:1.25rem;">
            Suba nuevos productos, actualice precios, fotos y existencias disponibles para la tienda online.
          </p>
          <a href="<?= url('producto') ?>" class="btn btn-yellow" style="width:100%;">
            Administrar Catálogo de Productos
          </a>
        </div>
      </div>

    <?php elseif ($rol === 'tecnico'): ?>
      <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header">
          <h3 style="margin:0;font-size:1.05rem;">Estado de Operaciones Taller</h3>
        </div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:1rem;">
          <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:0.75rem;border-bottom:1px solid #edf0f5;">
            <span>Pendientes de Revisión:</span>
            <strong style="color:#d97706;"><?= (int)($stats['pendientes'] ?? 0) ?></strong>
          </div>
          <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:0.75rem;border-bottom:1px solid #edf0f5;">
            <span>En Reparación Activa:</span>
            <strong style="color:var(--color-celeste);"><?= (int)($stats['en_reparacion'] ?? 0) ?></strong>
          </div>
          <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:0.75rem;border-bottom:1px solid #edf0f5;">
            <span>Terminados Listos:</span>
            <strong style="color:var(--color-success);"><?= (int)($stats['terminados'] ?? 0) ?></strong>
          </div>
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <span>Total Entregados:</span>
            <strong><?= (int)($stats['entregados'] ?? 0) ?></strong>
          </div>
        </div>
      </div>

      <div class="card" style="background:linear-gradient(135deg, var(--color-blue), var(--color-blue-dark));color:#ffffff;">
        <div class="card-body" style="text-align:center;padding:1.75rem;">
          <h3 style="color:var(--color-yellow);margin-bottom:0.5rem;">Trazabilidad de Repuestos</h3>
          <p style="font-size:0.85rem;color:rgba(255,255,255,0.8);margin-bottom:1.25rem;">
            Registre números de serie retirados e instalados para garantizar la transparencia con el cliente.
          </p>
          <a href="<?= url('componente') ?>" class="btn btn-yellow" style="width:100%;">
            Ver Auditoría de Componentes
          </a>
        </div>
      </div>

    <?php else: ?>
      <!-- ADMIN: RESUMEN INTEGRAL -->
      <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header">
          <h3 style="margin:0;font-size:1.05rem;">Resumen Integral del Sistema</h3>
        </div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:1rem;">
          <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:0.75rem;border-bottom:1px solid #edf0f5;">
            <span>Clientes Registrados:</span>
            <strong><?= (int)$totalClientes ?></strong>
          </div>
          <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:0.75rem;border-bottom:1px solid #edf0f5;">
            <span>Equipos en Registro:</span>
            <strong><?= (int)$totalEquipos ?></strong>
          </div>
          <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:0.75rem;border-bottom:1px solid #edf0f5;">
            <span>Productos en Tienda:</span>
            <strong><?= (int)$totalProductos ?> (<?= (int)$stockBajo ?> stock bajo)</strong>
          </div>
          <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:0.75rem;border-bottom:1px solid #edf0f5;">
            <span>Usuarios y Roles:</span>
            <strong><?= (int)$totalUsuarios ?> colaboradores</strong>
          </div>
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <span>Ingresos Totales:</span>
            <strong style="color:var(--color-success);"><?= formatPrecio($stats['ingresos_total'] ?? 0) ?></strong>
          </div>
        </div>
      </div>

      <div class="card" style="background:linear-gradient(135deg, var(--color-blue), var(--color-blue-dark));color:#ffffff;">
        <div class="card-body" style="text-align:center;padding:1.75rem;">
          <h3 style="color:var(--color-yellow);margin-bottom:0.5rem;">Catálogo & Inventario</h3>
          <p style="font-size:0.85rem;color:rgba(255,255,255,0.8);margin-bottom:1.25rem;">
            Supervise los artículos en venta, alertas de stock bajo y gestione el inventario en tiempo real.
          </p>
          <a href="<?= url('producto') ?>" class="btn btn-yellow" style="width:100%;">
            Gestionar Catálogo Comercial
          </a>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php if (hasRole(['admin', 'vendedor'])): ?>
  <!-- ========================================================
       TABLA DE VENTAS E-COMMERCE & COMPROBANTES SUNAT RECIENTES
       ======================================================== -->
  <div class="card" style="margin-top:2rem;">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
      <h2 style="margin:0;font-size:1.15rem;display:flex;align-items:center;gap:0.5rem;">
        <svg style="width:20px;height:20px;fill:var(--color-blue);" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
        <span>Últimas Ventas E-commerce &amp; Facturación SUNAT (Culqi Online &bull; Tumbes)</span>
      </h2>
      <a href="<?= url('pedido') ?>" class="btn btn-sm btn-outline">Ver Todas las Ventas</a>
    </div>
    <div class="table-responsive">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Código Pedido</th>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Destino (Tumbes)</th>
            <th>Comprobante SUNAT</th>
            <th>Medio de Pago</th>
            <th>Estado Pago</th>
            <th>Despacho</th>
            <th style="text-align:right;">Total (S/)</th>
            <th style="text-align:center;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($pedidosRecientes)): ?>
            <tr>
              <td colspan="10" style="text-align:center;padding:2rem;color:var(--color-shadow);">
                No hay ventas e-commerce registradas aún.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($pedidosRecientes as $ped): ?>
              <tr>
                <td>
                  <a href="<?= url("pedido/ver/{$ped['codigo_pedido']}") ?>" style="font-weight:700;color:var(--color-blue);text-decoration:none;">
                    <?= e($ped['codigo_pedido']) ?>
                  </a>
                </td>
                <td style="font-size:0.8rem;color:var(--color-gray-600);">
                  <?= date('d/m/Y H:i', strtotime($ped['creado_en'])) ?>
                </td>
                <td>
                  <strong><?= e($ped['cliente_nombre']) ?></strong>
                  <div style="font-size:0.75rem;color:var(--color-gray-500);"><?= e($ped['tipo_documento']) ?>: <?= e($ped['numero_documento']) ?></div>
                </td>
                <td style="font-size:0.8rem;">
                  <?= e($ped['direccion_distrito'] ?: 'Tumbes') ?>
                </td>
                <td>
                  <?php if (!empty($ped['comprobante_numero'])): ?>
                    <a href="<?= url("tienda/comprobante/{$ped['codigo_pedido']}") ?>" target="_blank" 
                       class="badge <?= str_starts_with($ped['comprobante_numero'], 'F') ? 'badge-primary' : 'badge-celeste' ?>" 
                       style="text-decoration:none;font-weight:800;" title="Ver Comprobante Electrónico SUNAT">
                      <?= e($ped['comprobante_numero']) ?>
                    </a>
                  <?php else: ?>
                    <span style="font-size:0.75rem;color:var(--color-gray-500);">-</span>
                  <?php endif; ?>
                </td>
                <td style="font-size:0.8rem;">
                  <?= e($ped['culqi_brand'] ?: $ped['metodo_pago']) ?>
                </td>
                <td>
                  <span class="badge <?= $ped['estado_pago'] === 'Pagado' ? 'badge-success' : 'badge-warning' ?>" style="font-size:0.75rem;">
                    <?= e($ped['estado_pago']) ?>
                  </span>
                </td>
                <td>
                  <?php
                  $cls = 'badge-warning';
                  if ($ped['estado_despacho'] === 'Entregado') $cls = 'badge-success';
                  elseif ($ped['estado_despacho'] === 'Enviado') $cls = 'badge-primary';
                  ?>
                  <span class="badge <?= $cls ?>" style="font-size:0.75rem;">
                    <?= e($ped['estado_despacho']) ?>
                  </span>
                </td>
                <td style="text-align:right;font-weight:800;color:var(--color-navy);">
                  S/ <?= number_format((float)$ped['total'], 2) ?>
                </td>
                <td style="text-align:center;">
                  <div style="display:inline-flex;gap:0.35rem;">
                    <a href="<?= url("pedido/ver/{$ped['codigo_pedido']}") ?>" class="btn btn-sm btn-outline" style="padding:0.25rem 0.5rem;font-size:0.75rem;">
                      Ver
                    </a>
                    <?php if (!empty($ped['comprobante_numero'])): ?>
                      <a href="<?= url("tienda/comprobante/{$ped['codigo_pedido']}") ?>" target="_blank" class="btn btn-sm btn-primary" style="padding:0.25rem 0.5rem;font-size:0.75rem;">
                        SUNAT
                      </a>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>
