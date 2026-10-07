<?php
/**
 * @var array $productos
 * @var array $alertas
 * @var string $title
 */
?>
<div class="page-header" style="display:flex;justify-content:space-between;align-items:center;">
  <div>
    <h1><i class="ph-bold ph-package"></i> Kardex e Inventario</h1>
    <p>Control de stock, alertas de reposición e impresión de etiquetas.</p>
  </div>
  <div>
    <!-- Botón temporal genérico -->
    <button class="btn btn-outline" onclick="alert('Funcionalidad de impresión de códigos QR para productos en desarrollo.');">
      <i class="ph-bold ph-printer"></i> Imprimir Etiquetas Masivas
    </button>
  </div>
</div>

<?php if (count($alertas) > 0): ?>
<div style="background:#fef2f2;border:1px solid #fecaca;padding:1rem;border-radius:8px;margin-bottom:1.5rem;display:flex;align-items:center;gap:1rem;">
  <i class="ph-bold ph-warning-circle" style="font-size:2rem;color:#ef4444;"></i>
  <div>
    <h4 style="margin:0;color:#b91c1c;">¡Alerta de Stock Bajo!</h4>
    <p style="margin:0;color:#ef4444;font-size:0.9rem;">Tienes <?= count($alertas) ?> producto(s) con inventario crítico (2 o menos unidades).</p>
  </div>
</div>
<?php endif; ?>

<div class="card">
  <div class="card-header">
    <h3>Inventario General</h3>
  </div>
  <div class="table-responsive">
    <table class="custom-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Producto</th>
          <th>Marca</th>
          <th style="text-align:right;">Stock Actual</th>
          <th style="text-align:right;">Acciones Kardex</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($productos as $p): ?>
          <tr>
            <td><strong>#<?= $p['id_producto'] ?></strong></td>
            <td>
              <div style="display:flex;align-items:center;gap:10px;">
                <img src="<?= e($p['imagen']) ?>" alt="img" style="width:40px;height:40px;object-fit:cover;border-radius:4px;background:#f1f5f9;">
                <span><?= e($p['nombre']) ?></span>
              </div>
            </td>
            <td><span class="badge badge-info"><?= e($p['marca']) ?></span></td>
            <td style="text-align:right;">
              <?php if ($p['stock'] <= 2): ?>
                <span class="badge badge-cancelado" style="font-size:1rem;padding:0.3rem 0.6rem;"><?= $p['stock'] ?> Und</span>
              <?php else: ?>
                <span class="badge badge-listo" style="font-size:1rem;padding:0.3rem 0.6rem;"><?= $p['stock'] ?> Und</span>
              <?php endif; ?>
            </td>
            <td style="text-align:right;">
              <button class="btn btn-sm btn-outline" style="border-color:#10b981;color:#10b981;" onclick="abrirModalStock(<?= $p['id_producto'] ?>, '<?= e(addslashes($p['nombre'])) ?>')">
                <i class="ph-bold ph-plus"></i> Ingreso / Series
              </button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Stock -->
<div id="modalStock" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.6);z-index:999;align-items:center;justify-content:center;padding:1rem;">
  <div class="card" style="width:100%;max-width:450px;margin:auto;">
    <div class="card-header" style="display:flex;justify-content:space-between;">
      <h3 style="margin:0;">Añadir Stock / Series</h3>
      <button type="button" onclick="cerrarModalStock()" style="background:none;border:none;font-size:1.5rem;cursor:pointer;">&times;</button>
    </div>
    <div class="card-body">
      <p id="modalStockText" style="color:#475569;margin-bottom:1rem;font-weight:600;"></p>
      
      <form id="formStock" method="POST" action="">
        <div class="form-group">
          <label>Cantidad a Ingresar</label>
          <input type="number" name="cantidad" class="form-control" required min="1" value="1" id="inputCantidad">
        </div>
        
        <div class="form-group" style="margin-top:1rem;">
          <label>Números de Serie (Opcional, 1 por línea)</label>
          <textarea name="series" class="form-control" rows="4" placeholder="Ej: SN-102930&#10;SN-102931&#10;Escanear con pistola aquí..." id="inputSeries"></textarea>
          <small style="color:#64748b;">La cantidad de series debe coincidir con la 'Cantidad' ingresada arriba.</small>
        </div>
        
        <button type="submit" class="btn btn-primary" style="width:100%;margin-top:1rem;">Registrar Entrada a Kardex</button>
      </form>
    </div>
  </div>
</div>

<script>
function abrirModalStock(id, nombre) {
  document.getElementById('modalStockText').innerText = nombre;
  document.getElementById('formStock').action = '<?= url("inventario/agregarStock/") ?>' + id;
  document.getElementById('inputCantidad').value = 1;
  document.getElementById('inputSeries').value = '';
  document.getElementById('modalStock').style.display = 'flex';
}
function cerrarModalStock() {
  document.getElementById('modalStock').style.display = 'none';
}
</script>
