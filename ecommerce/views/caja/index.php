<?php
/**
 * @var array $caja
 * @var array $movimientos
 * @var float $montoActual
 * @var array $historial
 * @var string $title
 */
?>
<div class="page-header">
  <div>
    <h1><i class="ph-bold ph-wallet"></i> Gestión de Finanzas y Arqueo de Caja</h1>
    <p>Apertura, ingresos, egresos y cierre de caja diario.</p>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;">
  <!-- Panel Principal -->
  <div>
    <div class="card">
      <div class="card-header">
        <h3>Estado Actual de la Caja</h3>
      </div>
      <div class="card-body">
        <?php if (!$caja): ?>
          <div style="background:#f1f5f9;padding:2rem;text-align:center;border-radius:8px;">
            <i class="ph-bold ph-lock-key" style="font-size:3rem;color:#94a3b8;margin-bottom:1rem;display:block;"></i>
            <h3 style="margin-bottom:0.5rem;color:#475569;">Caja Cerrada</h3>
            <p style="color:#64748b;margin-bottom:1.5rem;">Debe abrir la caja para registrar transacciones.</p>
            
            <form action="<?= url('caja/abrir') ?>" method="POST" style="max-width:300px;margin:0 auto;text-align:left;">
              <div class="form-group">
                <label>Monto de Apertura (Efectivo Base) S/</label>
                <input type="number" step="0.10" name="monto_apertura" class="form-control" value="0.00" required>
              </div>
              <button type="submit" class="btn btn-primary" style="width:100%;">Abrir Caja</button>
            </form>
          </div>
        <?php else: ?>
          <div style="display:flex;justify-content:space-between;align-items:center;background:#f0fdf4;padding:1.5rem;border-radius:8px;border:1px solid #bbf7d0;margin-bottom:1.5rem;">
            <div>
              <span class="badge badge-listo" style="margin-bottom:0.5rem;display:inline-block;">CAJA ABIERTA</span>
              <p style="margin:0;font-size:0.9rem;color:#166534;">
                Abierta por <strong>ID <?= $caja['usuario_apertura'] ?></strong> el <?= date('d/m/Y H:i', strtotime($caja['fecha_apertura'])) ?>
              </p>
            </div>
            <div style="text-align:right;">
              <div style="font-size:0.85rem;color:#166534;font-weight:700;">SALDO ACTUAL ESTIMADO</div>
              <div style="font-size:2rem;font-weight:800;color:#15803d;">S/ <?= number_format($montoActual, 2) ?></div>
            </div>
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;">
            <button class="btn btn-outline" style="border-color:#10b981;color:#10b981;" onclick="abrirModalMovimiento('INGRESO')">
              <i class="ph-bold ph-arrow-down-left"></i> Registrar Ingreso
            </button>
            <button class="btn btn-outline" style="border-color:#ef4444;color:#ef4444;" onclick="abrirModalMovimiento('EGRESO')">
              <i class="ph-bold ph-arrow-up-right"></i> Registrar Egreso (Gasto)
            </button>
          </div>

          <form action="<?= url('caja/cerrar') ?>" method="POST" onsubmit="return confirm('¿Está seguro de cerrar la caja actual?');" style="background:#f8fafc;padding:1rem;border-radius:8px;border:1px solid #e2e8f0;">
            <h4 style="margin-top:0;margin-bottom:1rem;font-size:0.95rem;color:#334155;">Cerrar Caja</h4>
            <div style="display:flex;gap:1rem;align-items:flex-end;">
              <div class="form-group" style="margin:0;flex:1;">
                <label style="font-size:0.8rem;">Efectivo/Saldo Real Contado S/</label>
                <input type="number" step="0.10" name="monto_cierre_real" class="form-control" value="<?= number_format($montoActual, 2, '.', '') ?>" required>
              </div>
              <button type="submit" class="btn btn-danger" style="white-space:nowrap;">
                <i class="ph-bold ph-lock-key"></i> Cerrar Caja
              </button>
            </div>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Movimientos -->
  <div>
    <div class="card" style="height:100%;">
      <div class="card-header">
        <h3>Movimientos de la Caja Actual</h3>
      </div>
      <div class="table-responsive">
        <table class="custom-table">
          <thead>
            <tr>
              <th>Hora</th>
              <th>Tipo</th>
              <th>Concepto</th>
              <th>Método</th>
              <th style="text-align:right;">Monto S/</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($movimientos)): ?>
              <tr><td colspan="5" style="text-align:center;">No hay movimientos en la caja actual.</td></tr>
            <?php else: ?>
              <?php foreach ($movimientos as $mov): ?>
                <tr>
                  <td><?= date('H:i', strtotime($mov['creado_en'])) ?></td>
                  <td>
                    <?php if ($mov['tipo'] == 'INGRESO'): ?>
                      <span style="color:#10b981;font-weight:700;">INGRESO</span>
                    <?php else: ?>
                      <span style="color:#ef4444;font-weight:700;">EGRESO</span>
                    <?php endif; ?>
                  </td>
                  <td><?= e($mov['concepto']) ?></td>
                  <td><span class="badge" style="background:#e2e8f0;color:#475569;"><?= e($mov['metodo_pago']) ?></span></td>
                  <td style="text-align:right;font-weight:700;color:<?= $mov['tipo'] == 'INGRESO' ? '#10b981' : '#ef4444' ?>">
                    <?= $mov['tipo'] == 'INGRESO' ? '+' : '-' ?><?= number_format($mov['monto'], 2) ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal Movimiento -->
<div id="modalMovimiento" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.6);z-index:999;align-items:center;justify-content:center;padding:1rem;">
  <div class="card" style="width:100%;max-width:400px;margin:auto;">
    <div class="card-header" style="display:flex;justify-content:space-between;">
      <h3 id="modalMovTitle" style="margin:0;">Registrar Movimiento</h3>
      <button type="button" onclick="cerrarModalMovimiento()" style="background:none;border:none;font-size:1.5rem;cursor:pointer;">&times;</button>
    </div>
    <div class="card-body">
      <form action="<?= url('caja/movimiento') ?>" method="POST">
        <input type="hidden" name="tipo" id="inputMovTipo">
        
        <div class="form-group">
          <label>Concepto / Motivo</label>
          <input type="text" name="concepto" class="form-control" placeholder="Ej. Compra de suministros, Venta directa..." required>
        </div>
        
        <div class="form-group">
          <label>Monto S/</label>
          <input type="number" step="0.10" name="monto" class="form-control" required min="0.1">
        </div>

        <div class="form-group">
          <label>Método de Pago</label>
          <select name="metodo_pago" class="form-control" required>
            <option value="EFECTIVO">Efectivo</option>
            <option value="YAPE">Yape</option>
            <option value="PLIN">Plin</option>
            <option value="TRANSFERENCIA">Transferencia</option>
            <option value="TARJETA">Tarjeta</option>
          </select>
        </div>
        
        <button type="submit" class="btn btn-primary" style="width:100%;">Guardar Movimiento</button>
      </form>
    </div>
  </div>
</div>

<script>
function abrirModalMovimiento(tipo) {
  document.getElementById('inputMovTipo').value = tipo;
  document.getElementById('modalMovTitle').innerText = 'Registrar ' + tipo;
  document.getElementById('modalMovimiento').style.display = 'flex';
}
function cerrarModalMovimiento() {
  document.getElementById('modalMovimiento').style.display = 'none';
}
</script>
