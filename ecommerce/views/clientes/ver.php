<?php
/**
 * @var array $cliente
 * @var array $equipos
 * @var array $ordenes
 */
$flash = getFlash();
if ($flash): ?>
  <div class="alert alert-<?= e($flash['type']) ?>">
    <span><?= e($flash['message']) ?></span>
  </div>
<?php endif; ?>

<div class="page-header">
  <div>
    <h1>👤 <?= e($cliente['nombres_apellidos']) ?></h1>
    <p>Perfil del cliente, inventario de equipos y trazabilidad de servicios</p>
  </div>
  <div class="page-header-actions">
    <a href="https://wa.me/51<?= preg_replace('/[^0-9]/', '', $cliente['telefono']) ?>?text=Hola%20<?= urlencode($cliente['nombres_apellidos']) ?>%20le%20saludamos%20de%20MAKPC" target="_blank" class="btn btn-yellow" style="background:#25D366;color:#fff;">
      💬 WhatsApp Directo
    </a>
    <a href="<?= url('equipo/crear?cliente_id=' . $cliente['id_cliente']) ?>" class="btn btn-celeste">
      💻 Añadir Equipo
    </a>
    <a href="<?= url('cliente/editar/' . $cliente['id_cliente']) ?>" class="btn btn-outline">
      ✏️ Editar Datos
    </a>
  </div>
</div>

<!-- INFO CLIENTE -->
<div class="card" style="margin-bottom:2rem;">
  <div class="card-header">
    <h3>📋 Información de Contacto</h3>
  </div>
  <div class="card-body">
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:1.5rem;">
      <div>
        <span style="font-size:0.8rem;color:var(--color-shadow);text-transform:uppercase;font-weight:700;">Teléfono Principal</span>
        <div style="font-size:1.1rem;font-weight:700;color:var(--color-blue);margin-top:0.2rem;">
          <?= e($cliente['telefono']) ?>
        </div>
      </div>

      <?php if (!empty($cliente['telefono_secundario'])): ?>
        <div>
          <span style="font-size:0.8rem;color:var(--color-shadow);text-transform:uppercase;font-weight:700;">Teléfono Secundario</span>
          <div style="font-size:1.1rem;font-weight:600;color:var(--color-blue);margin-top:0.2rem;">
            <?= e($cliente['telefono_secundario']) ?>
          </div>
        </div>
      <?php endif; ?>

      <div>
        <span style="font-size:0.8rem;color:var(--color-shadow);text-transform:uppercase;font-weight:700;">DNI / RUC</span>
        <div style="font-size:1.1rem;font-weight:600;color:var(--color-blue);margin-top:0.2rem;">
          <?= e($cliente['dni'] ?: 'No registrado') ?>
        </div>
      </div>

      <div>
        <span style="font-size:0.8rem;color:var(--color-shadow);text-transform:uppercase;font-weight:700;">Correo Electrónico</span>
        <div style="font-size:1rem;color:var(--color-blue);margin-top:0.2rem;">
          <?= e($cliente['correo'] ?: 'No registrado') ?>
        </div>
      </div>

      <div>
        <span style="font-size:0.8rem;color:var(--color-shadow);text-transform:uppercase;font-weight:700;">Dirección</span>
        <div style="font-size:1rem;color:var(--color-blue);margin-top:0.2rem;">
          <?= e($cliente['direccion'] ?: 'No registrada') ?>
        </div>
      </div>
    </div>

    <?php if (!empty($cliente['notas_cliente'])): ?>
      <div style="margin-top:1.25rem;padding:0.9rem;background:var(--color-lavender);border-radius:var(--radius-md);">
        <strong style="color:var(--color-blue);font-size:0.85rem;">📌 Notas del cliente:</strong>
        <p style="font-size:0.9rem;margin-top:0.25rem;"><?= nl2br(e($cliente['notas_cliente'])) ?></p>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- EQUIPOS DEL CLIENTE -->
<div class="card" style="margin-bottom:2rem;">
  <div class="card-header">
    <h3>💻 Equipos Asociados (<?= count($equipos) ?>)</h3>
    <a href="<?= url('equipo/crear?cliente_id=' . $cliente['id_cliente']) ?>" class="btn btn-sm btn-yellow">
      ➕ Agregar Equipo
    </a>
  </div>
  <div class="table-responsive">
    <table class="custom-table">
      <thead>
        <tr>
          <th>Tipo</th>
          <th>Marca / Modelo</th>
          <th>N° Serie</th>
          <th>Código Patrimonial</th>
          <th>Color / Detalles</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($equipos)): ?>
          <tr>
            <td colspan="6" style="text-align:center;padding:1.5rem;color:var(--color-shadow);">
              El cliente aún no tiene equipos registrados.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($equipos as $eq): ?>
            <tr>
              <td><span class="badge badge-revision"><?= e($eq['tipo_equipo']) ?></span></td>
              <td><strong><?= e($eq['marca']) ?> <?= e($eq['modelo']) ?></strong></td>
              <td><code><?= e($eq['numero_serie'] ?: 'S/N') ?></code></td>
              <td><?= e($eq['codigo_patrimonial'] ?: '-') ?></td>
              <td><?= e($eq['color_detalles'] ?: '-') ?></td>
              <td style="white-space:nowrap;">
                <a href="<?= url('orden/crear?equipo_id=' . $eq['id_equipo']) ?>" class="btn btn-sm btn-yellow" title="Generar Orden">
                  ⚡ Crear Orden
                </a>
                <a href="<?= url('equipo/editar/' . $eq['id_equipo']) ?>" class="btn btn-sm btn-outline" title="Editar">
                  ✏️
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- HISTORIAL DE ÓRDENES -->
<div class="card">
  <div class="card-header">
    <h3>📋 Historial de Órdenes de Servicio (<?= count($ordenes) ?>)</h3>
  </div>
  <div class="table-responsive">
    <table class="custom-table">
      <thead>
        <tr>
          <th>Código</th>
          <th>Fecha</th>
          <th>Equipo</th>
          <th>Falla Reportada</th>
          <th>Estado</th>
          <th>Costo Total</th>
          <th>Acción</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($ordenes)): ?>
          <tr>
            <td colspan="7" style="text-align:center;padding:1.5rem;color:var(--color-shadow);">
              No hay órdenes de servicio para este cliente todavía.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($ordenes as $o): 
            $stBadge = match($o['estado']) {
              'Pendiente' => 'badge-pendiente',
              'En Reparacion' => 'badge-reparacion',
              'Terminado' => 'badge-listo',
              'Entregado' => 'badge-entregado',
              default => 'badge-revision'
            };
          ?>
            <tr>
              <td><strong><?= e($o['codigo_orden']) ?></strong></td>
              <td><?= date('d/m/Y H:i', strtotime($o['fecha_recepcion'])) ?></td>
              <td><?= e($o['equipo']) ?></td>
              <td style="max-width:220px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                <?= e($o['falla_reportada']) ?>
              </td>
              <td><span class="badge <?= $stBadge ?>"><?= e($o['estado']) ?></span></td>
              <td><strong><?= formatPrecio($o['costo_total']) ?></strong></td>
              <td>
                <a href="<?= url('orden/ver/' . $o['id_orden']) ?>" class="btn btn-sm btn-primary">
                  Ver Orden
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
