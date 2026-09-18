<?php
/**
 * Vista de Detalle de Orden E-commerce y Comprobante SUNAT
 * @var array $pedido
 * @var array $items
 */
$flash = getFlash();
if ($flash): ?>
  <div class="alert alert-<?= e($flash['type']) ?>">
    <span><?= e($flash['message']) ?></span>
  </div>
<?php endif; ?>

<div class="page-header">
  <div>
    <div style="display:flex;align-items:center;gap:0.6rem;flex-wrap:wrap;margin-bottom:0.25rem;">
      <a href="<?= url('pedido') ?>" style="color:var(--color-blue);text-decoration:none;font-size:0.85rem;font-weight:600;">
        &larr; Volver a Ventas E-commerce
      </a>
    </div>
    <h1 style="margin:0;display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap;">
      <span>Orden: <?= e($pedido['codigo_pedido']) ?></span>
      <?php if (!empty($pedido['comprobante_numero'])): ?>
        <span class="badge <?= str_starts_with($pedido['comprobante_numero'], 'F') ? 'badge-primary' : 'badge-celeste' ?>" style="font-size:0.85rem;padding:0.35rem 0.75rem;">
          SUNAT: <?= e($pedido['comprobante_numero']) ?>
        </span>
      <?php endif; ?>
    </h1>
    <p style="margin-top:0.35rem;font-size:0.85rem;color:var(--color-gray-600);">
      Registrado el <?= date('d/m/Y \a \l\a\s H:i', strtotime($pedido['creado_en'])) ?> &bull; Sede Central Tumbes
    </p>
  </div>

  <div class="page-header-actions">
    <?php if (!empty($pedido['comprobante_numero'])): ?>
      <a href="<?= url("tienda/comprobante/{$pedido['codigo_pedido']}") ?>" target="_blank" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:0.4rem;">
        <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 24 24"><path d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"/></svg>
        <span>Imprimir Comprobante SUNAT</span>
      </a>
    <?php endif; ?>
    <a href="<?= url('pedido') ?>" class="btn btn-outline">Volver</a>
  </div>
</div>

<div style="display:grid;grid-template-columns:1.7fr 1.1fr;gap:1.75rem;align-items:start;">
  
  <!-- COLUMNA IZQUIERDA: DETALLE DE PRODUCTOS & TOTALES -->
  <div>
    
    <!-- TARJETA DE PRODUCTOS -->
    <div class="card" style="margin-bottom:1.5rem;">
      <div class="card-header">
        <h2 style="margin:0;font-size:1.1rem;display:flex;align-items:center;gap:0.5rem;">
          <svg style="width:18px;height:18px;fill:var(--color-blue);" viewBox="0 0 24 24"><path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm7 17H5V8h14v12z"/></svg>
          <span>Productos Adquiridos (<?= count($items) ?>)</span>
        </h2>
      </div>

      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Ítem</th>
              <th>Descripción del Producto</th>
              <th style="text-align:center;">Cant.</th>
              <th style="text-align:right;">P. Unitario</th>
              <th style="text-align:right;">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $i = 1;
            foreach ($items as $it): 
              $p = (float)($it['price'] ?? 0);
              $q = (int)($it['qty'] ?? 1);
            ?>
              <tr>
                <td style="color:var(--color-gray-500);width:30px;"><?= $i++ ?></td>
                <td>
                  <strong><?= e($it['name'] ?? 'Producto') ?></strong>
                  <?php if (!empty($it['sku'])): ?>
                    <div style="font-size:0.75rem;color:var(--color-gray-500);">SKU: <?= e($it['sku']) ?></div>
                  <?php endif; ?>
                </td>
                <td style="text-align:center;font-weight:700;"><?= $q ?></td>
                <td style="text-align:right;">S/ <?= number_format($p, 2) ?></td>
                <td style="text-align:right;font-weight:700;color:var(--color-navy);">
                  S/ <?= number_format($p * $q, 2) ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- DESGLOSE ECONÓMICO -->
      <div style="padding:1.25rem 1.5rem;background:#F8FAFC;border-top:1px solid var(--color-gray-200);display:flex;justify-content:flex-end;">
        <div style="width:280px;display:flex;flex-direction:column;gap:0.4rem;font-size:0.88rem;">
          <div style="display:flex;justify-content:space-between;color:var(--color-gray-600);">
            <span>Subtotal:</span>
            <strong>S/ <?= number_format((float)$pedido['subtotal'], 2) ?></strong>
          </div>
          <div style="display:flex;justify-content:space-between;color:var(--color-gray-600);">
            <span>Costo de Envío:</span>
            <strong><?= (float)$pedido['costo_envio'] > 0 ? 'S/ ' . number_format((float)$pedido['costo_envio'], 2) : 'GRATIS' ?></strong>
          </div>
          <div style="display:flex;justify-content:space-between;color:var(--color-gray-500);font-size:0.8rem;">
            <span>Op. Gravadas:</span>
            <span>S/ <?= number_format((float)$pedido['op_gravadas'], 2) ?></span>
          </div>
          <div style="display:flex;justify-content:space-between;color:var(--color-gray-500);font-size:0.8rem;">
            <span>IGV (18.00%):</span>
            <span>S/ <?= number_format((float)$pedido['igv'], 2) ?></span>
          </div>
          <div style="display:flex;justify-content:space-between;font-size:1.15rem;font-weight:900;color:var(--color-navy);border-top:2px solid var(--color-navy);padding-top:0.5rem;margin-top:0.25rem;">
            <span>TOTAL:</span>
            <span>S/ <?= number_format((float)$pedido['total'], 2) ?></span>
          </div>
        </div>
      </div>
    </div>

    <!-- DATOS DE DESPACHO Y ENTREGA -->
    <div class="card">
      <div class="card-header">
        <h2 style="margin:0;font-size:1.1rem;display:flex;align-items:center;gap:0.5rem;">
          <svg style="width:18px;height:18px;fill:var(--color-blue);" viewBox="0 0 24 24"><path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13.5-9l1.96 2.5H17V9.5h2.5zm-1.5 9c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/></svg>
          <span>Dirección de Despacho &bull; Tumbes</span>
        </h2>
      </div>
      <div style="padding:1.25rem;font-size:0.88rem;line-height:1.7;">
        <div><strong>Modalidad de Entrega:</strong> <?= e($pedido['metodo_envio']) ?></div>
        <div><strong>Región / Departamento:</strong> <?= e($pedido['direccion_departamento']) ?></div>
        <div><strong>Distrito / Localidad:</strong> <?= e($pedido['direccion_distrito']) ?></div>
        <div><strong>Dirección Exacta:</strong> <?= e($pedido['direccion_calle']) ?></div>
        <?php if (!empty($pedido['direccion_referencia'])): ?>
          <div><strong>Referencia:</strong> <?= e($pedido['direccion_referencia']) ?></div>
        <?php endif; ?>
      </div>
    </div>

  </div>

  <!-- COLUMNA DERECHA: CLIENTE, VOUCHER CULQI, SUNAT & GESTIÓN DE ESTADO -->
  <div>
    
    <!-- TARJETA CLIENTE -->
    <div class="card" style="margin-bottom:1.5rem;">
      <div class="card-header">
        <h3 style="margin:0;font-size:1rem;display:flex;align-items:center;gap:0.5rem;">
          <svg style="width:16px;height:16px;fill:var(--color-blue);" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
          <span>Datos del Cliente / Facturación</span>
        </h3>
      </div>
      <div style="padding:1.25rem;font-size:0.85rem;line-height:1.7;">
        <div><strong>Cliente:</strong> <?= e($pedido['cliente_nombre']) ?></div>
        <div><strong><?= e($pedido['tipo_documento']) ?>:</strong> <code><?= e($pedido['numero_documento']) ?></code></div>
        <div><strong>Teléfono / WhatsApp:</strong> <?= e($pedido['cliente_telefono']) ?></div>
        <div><strong>Correo Electrónico:</strong> <?= e($pedido['cliente_correo']) ?></div>
        
        <div style="margin-top:0.75rem;padding-top:0.75rem;border-top:1px solid var(--color-gray-200);">
          <a href="https://wa.me/51<?= preg_replace('/[^0-9]/', '', $pedido['cliente_telefono']) ?>?text=<?= urlencode("Estimado(a) {$pedido['cliente_nombre']}, le saludamos del taller central MAKPC Enterprises respecto a su pedido {$pedido['codigo_pedido']}.") ?>" 
             target="_blank" class="btn btn-success" style="width:100%;justify-content:center;background:#25D366;border-color:#25D366;font-weight:700;">
            <span>Contactar por WhatsApp</span> &rarr;
          </a>
        </div>
      </div>
    </div>

    <!-- TARJETA VOUCHER CULQI -->
    <div class="card" style="margin-bottom:1.5rem;border-left:4px solid #05A9E9;">
      <div class="card-header">
        <h3 style="margin:0;font-size:1rem;display:flex;align-items:center;gap:0.5rem;color:var(--color-navy);">
          <svg style="width:16px;height:16px;fill:#05A9E9;" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
          <span>Voucher Culqi Online (Modo Sandbox)</span>
        </h3>
      </div>
      <div style="padding:1.25rem;font-size:0.82rem;line-height:1.7;color:var(--color-gray-700);">
        <div><strong>Estado del Pago:</strong> <span class="badge badge-success"><?= e($pedido['estado_pago']) ?></span></div>
        <div><strong>ID Transacción:</strong> <code><?= e($pedido['culqi_charge_id'] ?: 'chr_test_simulated') ?></code></div>
        <div><strong>Cód. Autorización:</strong> <strong><?= e($pedido['culqi_authorization_code'] ?: 'AUTH-TEST') ?></strong></div>
        <div><strong>Medio Utilizado:</strong> <?= e($pedido['culqi_brand'] ?: $pedido['metodo_pago']) ?></div>
        <div><strong>Entorno:</strong> SANDBOX (Pruebas Aprobadas)</div>
      </div>
    </div>

    <!-- TARJETA COMPROBANTE SUNAT -->
    <?php if (!empty($pedido['comprobante_numero'])): ?>
      <div class="card" style="margin-bottom:1.5rem;border-left:4px solid var(--color-navy);">
        <div class="card-header">
          <h3 style="margin:0;font-size:1rem;display:flex;align-items:center;gap:0.5rem;">
            <svg style="width:16px;height:16px;fill:var(--color-navy);" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
            <span>Comprobante Electrónico SUNAT</span>
          </h3>
        </div>
        <div style="padding:1.25rem;font-size:0.82rem;line-height:1.7;">
          <div><strong>Tipo de Documento:</strong> <?= e($pedido['comprobante_tipo'] ?: 'Boleta') ?></div>
          <div><strong>Número Oficial:</strong> <strong style="color:var(--color-blue);font-size:0.95rem;"><?= e($pedido['comprobante_numero']) ?></strong></div>
          <div><strong>Hash Digital SHA-256:</strong> <code><?= e(substr($pedido['codigo_hash'], 0, 18)) ?>...</code></div>
          <div><strong>Estado SUNAT:</strong> <span style="color:var(--color-success);font-weight:700;">Aceptado / Emitido</span></div>
          <div style="margin-top:0.75rem;">
            <a href="<?= url("tienda/comprobante/{$pedido['codigo_pedido']}") ?>" target="_blank" class="btn btn-outline" style="width:100%;justify-content:center;font-size:0.85rem;">
              <span>Ver / Imprimir Comprobante Oficial</span> &rarr;
            </a>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- TARJETA ACTUALIZAR ESTADO DE DESPACHO -->
    <div class="card">
      <div class="card-header">
        <h3 style="margin:0;font-size:1rem;display:flex;align-items:center;gap:0.5rem;">
          <svg style="width:16px;height:16px;fill:var(--color-yellow);" viewBox="0 0 24 24"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
          <span>Actualizar Estado de Despacho</span>
        </h3>
      </div>
      <div style="padding:1.25rem;">
        <form method="POST" action="<?= url('pedido/actualizarDespacho') ?>">
          <?= csrf_field() ?>
          <input type="hidden" name="id_pedido" value="<?= (int)$pedido['id_pedido'] ?>">
          <input type="hidden" name="codigo_pedido" value="<?= e($pedido['codigo_pedido']) ?>">

          <div style="margin-bottom:1rem;">
            <label style="display:block;font-size:0.8rem;font-weight:700;margin-bottom:0.35rem;color:var(--color-navy);">
              Estado Actual de Entrega:
            </label>
            <select name="estado_despacho" class="form-control" style="font-weight:600;">
              <option value="Pendiente" <?= $pedido['estado_despacho'] === 'Pendiente' ? 'selected' : '' ?>>Pendiente de preparación</option>
              <option value="En preparación" <?= $pedido['estado_despacho'] === 'En preparación' ? 'selected' : '' ?>>En preparación en taller</option>
              <option value="Enviado" <?= $pedido['estado_despacho'] === 'Enviado' ? 'selected' : '' ?>>Enviado / En ruta a destino</option>
              <option value="Entregado" <?= $pedido['estado_despacho'] === 'Entregado' ? 'selected' : '' ?>>Entregado al cliente</option>
            </select>
          </div>

          <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
            <span>Guardar Nuevo Estado</span>
          </button>
        </form>
      </div>
    </div>

  </div>

</div>
