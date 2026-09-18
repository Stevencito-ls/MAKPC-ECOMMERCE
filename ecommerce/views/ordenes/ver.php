<?php
/**
 * @var array $orden
 * @var array $componentes
 */
$flash = getFlash();
if ($flash): ?>
  <div class="alert alert-<?= e($flash['type']) ?>">
    <span><?= e($flash['message']) ?></span>
  </div>
<?php endif; 

$saldo = (float)$orden['costo_total'] - (float)$orden['adelanto'];
$stBadge = match($orden['estado']) {
  'Pendiente' => 'badge-pendiente',
  'En Reparacion' => 'badge-reparacion',
  'Terminado' => 'badge-listo',
  'Entregado' => 'badge-entregado',
  default => 'badge-cancelado'
};

$msgWhatsApp = "Hola " . $orden['cliente_nombre'] . ", le saludamos de MAKPC. Le informamos sobre su orden de servicio " . $orden['codigo_orden'] . " para su equipo " . $orden['equipo_marca'] . " " . $orden['equipo_modelo'] . ": Actualmente se encuentra en estado: *" . $orden['estado'] . "*. Cualquier duda estamos a su servicio.";
?>

<div class="page-header">
  <div>
    <h1>
      Orden #<?= e($orden['codigo_orden']) ?>
      <span class="badge <?= $stBadge ?>" style="font-size:0.85rem;margin-left:8px;"><?= e($orden['estado']) ?></span>
      <?php if ($orden['es_inmediato']): ?>
        <span class="badge-urgent-pill" style="font-size:0.75rem;vertical-align:middle;margin-left:6px;">URGENTE</span>
      <?php endif; ?>
    </h1>
    <p>Ingresado el <?= date('d/m/Y \a \l\a\s H:i', strtotime($orden['fecha_recepcion'])) ?> — Técnico: <strong><?= e($orden['tecnico_responsable']) ?></strong></p>
  </div>
  <div class="page-header-actions">
    <a href="https://wa.me/51<?= preg_replace('/[^0-9]/', '', $orden['telefono']) ?>?text=<?= urlencode($msgWhatsApp) ?>" target="_blank" class="btn btn-yellow" style="background:#25D366;color:#fff;">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" style="margin-right:6px;"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
      Notificar WhatsApp
    </a>
    <a href="<?= url('orden/imprimir/' . $orden['id_orden']) ?>" target="_blank" class="btn btn-celeste">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
      Imprimir Hoja de Servicio
    </a>
    <a href="<?= url('orden/editar/' . $orden['id_orden']) ?>" class="btn btn-outline">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
      Editar Orden
    </a>
  </div>
</div>

<!-- ACCIÓN RÁPIDA DE CAMBIO DE ESTADO -->
<div class="card" style="margin-bottom:1.5rem;background:#F9FAFB;">
  <div class="card-body" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;padding:1rem 1.5rem;">
    <div style="font-weight:700;color:var(--color-blue);font-size:0.95rem;">
      Cambio Rápido de Estado Operativo:
    </div>
    <form action="<?= url('orden/cambiarEstado/' . $orden['id_orden']) ?>" method="POST" style="display:flex;align-items:center;gap:0.75rem;">
      <?= csrf_field() ?>
      <select name="estado" class="form-control" style="width:auto;min-width:180px;">
        <option value="Pendiente" <?= ($orden['estado'] === 'Pendiente') ? 'selected' : '' ?>>Pendiente</option>
        <option value="En Reparacion" <?= ($orden['estado'] === 'En Reparacion') ? 'selected' : '' ?>>En Reparación</option>
        <option value="Terminado" <?= ($orden['estado'] === 'Terminado') ? 'selected' : '' ?>>Terminado (Listo para entrega)</option>
        <option value="Entregado" <?= ($orden['estado'] === 'Entregado') ? 'selected' : '' ?>>Entregado al Cliente</option>
        <option value="Cancelado" <?= ($orden['estado'] === 'Cancelado') ? 'selected' : '' ?>>Cancelado</option>
      </select>
      <button type="submit" class="btn btn-primary btn-sm">Actualizar Estado</button>
    </form>
  </div>
</div>

<div style="display:grid;grid-template-columns:1.5fr 1fr;gap:1.5rem;align-items:start;margin-bottom:2rem;">
  <!-- DETALLES TÉCNICOS -->
  <div>
    <div class="card" style="margin-bottom:1.5rem;">
      <div class="card-header">
        <h3>Diagnóstico & Servicio Realizado</h3>
      </div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:1.25rem;">
        <div>
          <strong style="color:var(--color-blue);font-size:0.85rem;text-transform:uppercase;">Falla Reportada por el Cliente:</strong>
          <p style="margin-top:0.25rem;color:#374151;font-size:0.95rem;background:var(--color-lavender);padding:0.75rem;border-radius:var(--radius-sm);">
            <?= nl2br(e($orden['falla_reportada'])) ?>
          </p>
        </div>

        <div>
          <strong style="color:var(--color-blue);font-size:0.85rem;text-transform:uppercase;">Estado Físico en Recepción:</strong>
          <p style="margin-top:0.25rem;color:#374151;font-size:0.9rem;">
            <?= nl2br(e($orden['estado_recepcion_fisico'])) ?>
          </p>
        </div>

        <?php if (!empty($orden['accesorios_entregados'])): ?>
          <div>
            <strong style="color:var(--color-blue);font-size:0.85rem;text-transform:uppercase;">Accesorios Dejados:</strong>
            <p style="margin-top:0.25rem;color:#374151;font-size:0.9rem;">
              <?= e($orden['accesorios_entregados']) ?>
            </p>
          </div>
        <?php endif; ?>

        <div>
          <strong style="color:var(--color-blue);font-size:0.85rem;text-transform:uppercase;">Diagnóstico Técnico:</strong>
          <p style="margin-top:0.25rem;color:#374151;font-size:0.95rem;">
            <?= !empty($orden['diagnostico']) ? nl2br(e($orden['diagnostico'])) : '<em style="color:var(--color-shadow);">Aún sin diagnóstico ingresado.</em>' ?>
          </p>
        </div>

        <div>
          <strong style="color:var(--color-blue);font-size:0.85rem;text-transform:uppercase;">Solución / Procedimiento Aplicado:</strong>
          <p style="margin-top:0.25rem;color:#374151;font-size:0.95rem;">
            <?= !empty($orden['solucion_aplicada']) ? nl2br(e($orden['solucion_aplicada'])) : '<em style="color:var(--color-shadow);">En proceso de trabajo...</em>' ?>
          </p>
        </div>

        <div style="display:flex;gap:2rem;border-top:1px solid #edf0f5;padding-top:1rem;">
          <div>
            <span style="font-size:0.8rem;color:var(--color-shadow);text-transform:uppercase;font-weight:700;">Garantía de Servicio:</span>
            <div style="font-weight:700;color:var(--color-blue);"><?= (int)$orden['garantia_meses'] ?> Meses</div>
          </div>
          <?php if (!empty($orden['fecha_entrega'])): ?>
            <div>
              <span style="font-size:0.8rem;color:var(--color-shadow);text-transform:uppercase;font-weight:700;">Fecha de Entrega:</span>
              <div style="font-weight:700;color:var(--color-success);"><?= date('d/m/Y H:i', strtotime($orden['fecha_entrega'])) ?></div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- CLIENTE Y EQUIPO -->
  <div>
    <!-- Tarjeta Cliente -->
    <div class="card" style="margin-bottom:1.5rem;">
      <div class="card-header">
        <h3>Propietario / Cliente</h3>
        <a href="<?= url('cliente/ver/' . $orden['id_cliente']) ?>" class="btn btn-sm btn-outline">Ver Perfil</a>
      </div>
      <div class="card-body" style="font-size:0.9rem;display:flex;flex-direction:column;gap:0.6rem;">
        <div><strong><?= e($orden['cliente_nombre']) ?></strong></div>
        <div>
          <span>Teléfono:</span>
          <a href="https://wa.me/51<?= preg_replace('/[^0-9]/', '', $orden['telefono']) ?>" target="_blank" style="color:#25D366;font-weight:700;">
            <?= e($orden['telefono']) ?>
          </a>
        </div>
        <div><span>DNI / RUC:</span> <?= e($orden['dni'] ?: 'No registrado') ?></div>
        <div><span>Dirección:</span> <?= e($orden['direccion'] ?: 'No registrada') ?></div>
      </div>
    </div>

    <!-- Tarjeta Equipo -->
    <div class="card" style="margin-bottom:1.5rem;">
      <div class="card-header">
        <h3>Dispositivo</h3>
      </div>
      <div class="card-body" style="font-size:0.9rem;display:flex;flex-direction:column;gap:0.6rem;">
        <div>
          <span class="badge badge-revision"><?= e($orden['tipo_equipo']) ?></span>
          <strong style="margin-left:4px;"><?= e($orden['equipo_marca']) ?> <?= e($orden['equipo_modelo']) ?></strong>
        </div>
        <div><span>N° Serie:</span> <code><?= e($orden['numero_serie'] ?: 'S/N') ?></code></div>
        <div><span>Cód. Patrimonial:</span> <?= e($orden['codigo_patrimonial'] ?: '-') ?></div>
        <div><span>Detalles:</span> <?= e($orden['color_detalles'] ?: '-') ?></div>
      </div>
    </div>

    <!-- Resumen Financiero -->
    <div class="card" style="border:2px solid var(--color-blue);">
      <div class="card-header" style="background:var(--color-blue);color:#fff;">
        <h3 style="color:#fff;">Liquidación de Costos</h3>
      </div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:0.75rem;">
        <div style="display:flex;justify-content:space-between;">
          <span>Mano de Obra:</span>
          <strong><?= formatPrecio($orden['costo_mano_obra']) ?></strong>
        </div>
        <div style="display:flex;justify-content:space-between;">
          <span>Repuestos / Piezas:</span>
          <strong><?= formatPrecio($orden['costo_repuestos']) ?></strong>
        </div>
        <div style="display:flex;justify-content:space-between;border-top:1px solid #edf0f5;padding-top:0.5rem;font-size:1.05rem;">
          <strong>Total Estimado:</strong>
          <strong style="color:var(--color-blue);"><?= formatPrecio($orden['costo_total']) ?></strong>
        </div>
        <div style="display:flex;justify-content:space-between;color:var(--color-success);">
          <span>Adelanto Abonado:</span>
          <strong>- <?= formatPrecio($orden['adelanto']) ?></strong>
        </div>
        <div style="display:flex;justify-content:space-between;border-top:2px solid var(--color-lavender);padding-top:0.6rem;font-size:1.15rem;">
          <strong style="color:var(--color-danger);">Saldo por Cobrar:</strong>
          <strong style="color:var(--color-danger);"><?= formatPrecio($saldo) ?></strong>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- SECCIÓN TRAZABILIDAD DE COMPONENTES -->
<div class="card">
  <div class="card-header">
    <h2 style="display:flex;align-items:center;gap:8px;">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
      Trazabilidad de Repuestos y Componentes Instalados
    </h2>
  </div>
  <div class="card-body">
    <div class="table-responsive" style="margin-bottom:2rem;">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Componente</th>
            <th>Serie Retirada (Original)</th>
            <th>Pieza / Repuesto Instalado</th>
            <th>Serie Instalada (Nueva)</th>
            <th>Precio</th>
            <th>Observación</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($componentes)): ?>
            <tr>
              <td colspan="7" style="text-align:center;padding:1.5rem;color:var(--color-shadow);">
                No se han registrado cambios de piezas o repuestos para esta orden.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($componentes as $cp): ?>
              <tr>
                <td><strong><?= e($cp['tipo_componente']) ?></strong></td>
                <td><code><?= e($cp['serie_retirada'] ?: 'N/A') ?></code></td>
                <td><?= e($cp['pieza_instalada'] ?: 'Revisión / Mismo') ?></td>
                <td><code><?= e($cp['serie_instalada'] ?: 'N/A') ?></code></td>
                <td><?= formatPrecio($cp['precio']) ?></td>
                <td><?= e($cp['observacion'] ?: '-') ?></td>
                <td>
                  <a href="<?= url('componente/eliminar/' . $cp['id_detalle']) ?>" class="btn btn-sm btn-danger" data-confirm="¿Desea quitar este componente de la orden?" title="Eliminar componente">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- FORMULARIO PARA AÑADIR PIEZA / COMPONENTE -->
    <?php if (hasRole(['admin', 'tecnico'])): ?>
      <div style="background:var(--color-white);border:1px solid #edf0f5;border-radius:var(--radius-md);padding:1.25rem;">
        <h4 style="font-size:0.95rem;color:var(--color-blue);margin-bottom:1rem;display:flex;align-items:center;gap:6px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          Registrar Reemplazo de Componente / Trazabilidad:
        </h4>
        <form action="<?= url('componente/agregar') ?>" method="POST">
          <?= csrf_field() ?>
          <input type="hidden" name="id_orden" value="<?= $orden['id_orden'] ?>">
          <div class="form-grid">
            <div class="form-group">
              <label for="tipo_componente">Tipo de Componente *</label>
              <input type="text" id="tipo_componente" name="tipo_componente" class="form-control" required placeholder="Ej: Disco SSD, Memoria RAM, Pantalla, Batería">
            </div>

            <div class="form-group">
              <label for="serie_retirada">N° Serie Retirado</label>
              <input type="text" id="serie_retirada" name="serie_retirada" class="form-control" placeholder="Serie de la pieza antigua/dañada">
            </div>

            <div class="form-group">
              <label for="pieza_instalada">Repuesto Instalado (Modelo/Marca)</label>
              <input type="text" id="pieza_instalada" name="pieza_instalada" class="form-control" placeholder="Ej: Kingston NV2 1TB NVMe PCIe 4.0">
            </div>

            <div class="form-group">
              <label for="serie_instalada">N° Serie Instalado (Nuevo)</label>
              <input type="text" id="serie_instalada" name="serie_instalada" class="form-control" placeholder="Serie para auditoría y garantía">
            </div>

            <div class="form-group">
              <label for="precio">Precio Repuesto (S/)</label>
              <input type="number" step="0.01" id="precio" name="precio" class="form-control" value="0.00">
            </div>

            <div class="form-group">
              <label for="observacion">Observación</label>
              <input type="text" id="observacion" name="observacion" class="form-control" placeholder="Garantía de tienda 12 meses">
            </div>
          </div>

          <div style="margin-top:1rem;text-align:right;">
            <button type="submit" class="btn btn-yellow" style="display:inline-flex;align-items:center;gap:6px;">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
              Agregar a Trazabilidad
            </button>
          </div>
        </form>
      </div>
    <?php else: ?>
      <div style="background:#f8fafc;border:1px dashed #d1d5db;border-radius:var(--radius-md);padding:1rem;font-size:0.85rem;color:var(--color-shadow);text-align:center;">
        El registro y auditoría de números de serie y piezas internas es gestionado exclusivamente por el área técnica de taller.
      </div>
    <?php endif; ?>
  </div>
</div>
