<?php
/**
 * Vista de Listado de Pedidos E-commerce & Comprobantes SUNAT
 * @var array $pedidos
 * @var array $metricas
 * @var string $busqueda
 * @var string $filtroPago
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
        <svg style="width:28px;height:28px;fill:var(--color-blue);" viewBox="0 0 24 24"><path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm7 17H5V8h14v12z"/></svg>
        <span>Ventas E-commerce &amp; Facturación SUNAT</span>
      </h1>
      <span class="badge badge-admin" style="font-size:0.75rem;padding:0.25rem 0.65rem;">
        Módulo Comercial &bull; Tumbes
      </span>
    </div>
    <p style="margin-top:0.35rem;">Gestión de órdenes online, pasarela Culqi (Yape/Tarjetas) y emisión de Boletas y Facturas Electrónicas.</p>
  </div>

  <div class="page-header-actions">
    <a href="<?= url('tienda') ?>" target="_blank" class="btn btn-outline" style="display:inline-flex;align-items:center;gap:0.4rem;">
      <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 24 24"><path d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"/></svg>
      <span>Ver Tienda Web</span>
    </a>
  </div>
</div>

<!-- ========================================================
     KPIS DE VENTAS Y FACTURACIÓN
     ======================================================== -->
<div class="kpi-grid" style="margin-bottom:1.75rem;">
  <div class="kpi-card">
    <div class="kpi-icon green">
      <svg viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
    </div>
    <div class="kpi-details">
      <h3>Total Facturado (Pagado)</h3>
      <div class="kpi-value" style="font-size:1.4rem;color:var(--color-success);">
        S/ <?= number_format((float)($metricas['total_ventas_soles'] ?? 0), 2) ?>
      </div>
    </div>
  </div>

  <div class="kpi-card">
    <div class="kpi-icon blue">
      <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
    </div>
    <div class="kpi-details">
      <h3>Pedidos Pagados</h3>
      <div class="kpi-value"><?= (int)($metricas['pedidos_pagados'] ?? 0) ?></div>
    </div>
  </div>

  <div class="kpi-card">
    <div class="kpi-icon yellow">
      <svg viewBox="0 0 24 24"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm4.2 14.2L11 13V7h1.5v5.2l4.5 2.7-.8 1.3z"/></svg>
    </div>
    <div class="kpi-details">
      <h3>Por Despachar / Almacén</h3>
      <div class="kpi-value" style="color:#d97706;"><?= (int)($metricas['por_despachar'] ?? 0) ?></div>
    </div>
  </div>

  <div class="kpi-card">
    <div class="kpi-icon celeste">
      <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
    </div>
    <div class="kpi-details">
      <h3>Total de Órdenes</h3>
      <div class="kpi-value"><?= (int)($metricas['total_pedidos'] ?? 0) ?></div>
    </div>
  </div>
</div>

<!-- ========================================================
     BARRA DE BÚSQUEDA Y FILTROS
     ======================================================== -->
<div class="card" style="margin-bottom:1.5rem;padding:1.25rem;">
  <form method="GET" action="<?= url('pedido') ?>" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:center;">
    
    <div style="flex:1;min-width:260px;">
      <input type="text" name="q" class="form-control" 
             placeholder="Buscar por código (PED-...), cliente, DNI, RUC o N° comprobante..." 
             value="<?= e($busqueda) ?>">
    </div>

    <div style="min-width:180px;">
      <select name="pago" class="form-control">
        <option value="">Todos los Estados de Pago</option>
        <option value="Pagado" <?= $filtroPago === 'Pagado' ? 'selected' : '' ?>>Pagados (Culqi / Verificado)</option>
        <option value="Pendiente" <?= $filtroPago === 'Pendiente' ? 'selected' : '' ?>>Pendientes de Pago</option>
        <option value="Rechazado" <?= $filtroPago === 'Rechazado' ? 'selected' : '' ?>>Rechazados</option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:0.4rem;">
      <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
      <span>Filtrar</span>
    </button>

    <?php if (!empty($busqueda) || !empty($filtroPago)): ?>
      <a href="<?= url('pedido') ?>" class="btn btn-outline">Limpiar</a>
    <?php endif; ?>

  </form>
</div>

<!-- ========================================================
     TABLA DE PEDIDOS Y COMPROBANTES SUNAT
     ======================================================== -->
<div class="card">
  <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
    <h2 style="margin:0;font-size:1.1rem;display:flex;align-items:center;gap:0.5rem;">
      <svg style="width:18px;height:18px;fill:var(--color-blue);" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
      <span>Registro de Ventas E-commerce &bull; MAKPC Enterprises (<?= count($pedidos) ?>)</span>
    </h2>
  </div>

  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>Código Pedido</th>
          <th>Fecha &amp; Hora</th>
          <th>Cliente / Documento</th>
          <th>Localidad (Tumbes)</th>
          <th>Comprobante SUNAT</th>
          <th>Medio de Pago</th>
          <th>Estado Pago</th>
          <th>Despacho</th>
          <th style="text-align:right;">Total (S/)</th>
          <th style="text-align:center;">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($pedidos)): ?>
          <?php foreach ($pedidos as $p): ?>
            <tr>
              <td>
                <a href="<?= url("pedido/ver/{$p['codigo_pedido']}") ?>" style="font-weight:700;color:var(--color-blue);text-decoration:none;">
                  <?= e($p['codigo_pedido']) ?>
                </a>
              </td>
              <td style="font-size:0.8rem;color:var(--color-gray-600);">
                <?= date('d/m/Y H:i', strtotime($p['creado_en'])) ?>
              </td>
              <td>
                <div style="font-weight:600;color:var(--color-navy);"><?= e($p['cliente_nombre']) ?></div>
                <div style="font-size:0.75rem;color:var(--color-gray-500);">
                  <?= e($p['tipo_documento']) ?>: <?= e($p['numero_documento']) ?> &bull; <?= e($p['cliente_telefono']) ?>
                </div>
              </td>
              <td style="font-size:0.8rem;">
                <strong><?= e($p['direccion_distrito'] ?: 'Tumbes') ?></strong>, <?= e($p['direccion_departamento'] ?: 'Tumbes') ?>
              </td>
              <td>
                <?php if (!empty($p['comprobante_numero'])): ?>
                  <a href="<?= url("tienda/comprobante/{$p['codigo_pedido']}") ?>" target="_blank" 
                     class="badge <?= str_starts_with($p['comprobante_numero'], 'F') ? 'badge-primary' : 'badge-celeste' ?>" 
                     style="text-decoration:none;display:inline-flex;align-items:center;gap:4px;font-weight:800;"
                     title="Ver comprobante electrónico oficial SUNAT">
                    <svg style="width:12px;height:12px;fill:currentColor;" viewBox="0 0 24 24"><path d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"/></svg>
                    <span><?= e($p['comprobante_numero']) ?></span>
                  </a>
                <?php else: ?>
                  <span style="font-size:0.75rem;color:var(--color-gray-500);font-style:italic;">Sin Comprobante</span>
                <?php endif; ?>
              </td>
              <td style="font-size:0.8rem;">
                <span style="font-weight:600;color:var(--color-navy);"><?= e($p['culqi_brand'] ?: $p['metodo_pago']) ?></span>
                <?php if (!empty($p['culqi_authorization_code'])): ?>
                  <div style="font-size:0.7rem;color:var(--color-gray-500);">Auth: <?= e($p['culqi_authorization_code']) ?></div>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($p['estado_pago'] === 'Pagado'): ?>
                  <span class="badge badge-success" style="font-size:0.75rem;">Pagado</span>
                <?php elseif ($p['estado_pago'] === 'Rechazado'): ?>
                  <span class="badge badge-danger" style="font-size:0.75rem;">Rechazado</span>
                <?php else: ?>
                  <span class="badge badge-warning" style="font-size:0.75rem;">Pendiente</span>
                <?php endif; ?>
              </td>
              <td>
                <?php
                $despachoClass = 'badge-warning';
                if ($p['estado_despacho'] === 'Entregado') $despachoClass = 'badge-success';
                elseif ($p['estado_despacho'] === 'Enviado') $despachoClass = 'badge-primary';
                ?>
                <span class="badge <?= $despachoClass ?>" style="font-size:0.75rem;">
                  <?= e($p['estado_despacho']) ?>
                </span>
              </td>
              <td style="text-align:right;font-weight:800;color:var(--color-navy);">
                S/ <?= number_format((float)$p['total'], 2) ?>
              </td>
              <td style="text-align:center;">
                <div style="display:inline-flex;gap:0.35rem;">
                  <a href="<?= url("pedido/ver/{$p['codigo_pedido']}") ?>" class="btn btn-outline" style="padding:0.3rem 0.6rem;font-size:0.8rem;" title="Ver Detalle de Orden">
                    Ver
                  </a>
                  <?php if (!empty($p['comprobante_numero'])): ?>
                    <a href="<?= url("tienda/comprobante/{$p['codigo_pedido']}") ?>" target="_blank" class="btn btn-primary" style="padding:0.3rem 0.6rem;font-size:0.8rem;" title="Imprimir Comprobante SUNAT">
                      SUNAT
                    </a>
                  <?php endif; ?>
                  <?php if (!empty($p['cliente_telefono'])): ?>
                    <a href="https://wa.me/51<?= preg_replace('/[^0-9]/', '', $p['cliente_telefono']) ?>?text=<?= urlencode("Hola {$p['cliente_nombre']}, le escribimos de MAKPC Enterprises sobre su orden {$p['codigo_pedido']}.") ?>" 
                       target="_blank" class="btn btn-success" style="padding:0.3rem 0.5rem;font-size:0.8rem;background:#25D366;border-color:#25D366;" title="Contactar por WhatsApp">
                      WA
                    </a>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="10" style="text-align:center;padding:2.5rem;color:var(--color-gray-500);">
              No se encontraron órdenes registradas con los filtros aplicados.
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
