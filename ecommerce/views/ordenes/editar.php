<?php
/**
 * @var array $orden
 */
$flash = getFlash();
if ($flash): ?>
  <div class="alert alert-<?= e($flash['type']) ?>">
    <span><?= e($flash['message']) ?></span>
  </div>
<?php endif; ?>

<div class="page-header">
  <div>
    <h1>Modificar Orden #<?= e($orden['codigo_orden']) ?></h1>
    <p>Actualizar diagnóstico, solución técnica, costos y estado operativo</p>
  </div>
  <div class="page-header-actions">
    <a href="<?= url('orden/ver/' . $orden['id_orden']) ?>" class="btn btn-outline">
      &larr; Volver a la Orden
    </a>
  </div>
</div>

<div class="card" style="max-width:900px;margin:0 auto;">
  <div class="card-header">
    <h3>Edición de Orden de Servicio</h3>
  </div>
  <div class="card-body">
    <form action="<?= url('orden/editar/' . $orden['id_orden']) ?>" method="POST">
      <?= csrf_field() ?>
      <div class="form-grid">
        <div class="form-group">
          <label for="tecnico_responsable">Técnico Asignado *</label>
          <input type="text" id="tecnico_responsable" name="tecnico_responsable" class="form-control" required value="<?= e($orden['tecnico_responsable']) ?>">
        </div>

        <div class="form-group">
          <label for="estado">Estado del Servicio *</label>
          <select name="estado" id="estado" class="form-control" required>
            <?php
            $estados = ['Pendiente', 'En Reparacion', 'Terminado', 'Entregado', 'Cancelado'];
            foreach ($estados as $est): ?>
              <option value="<?= $est ?>" <?= ($orden['estado'] == $est) ? 'selected' : '' ?>><?= $est ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group" style="grid-column:1 / -1;">
          <label for="falla_reportada">Falla Reportada</label>
          <textarea id="falla_reportada" name="falla_reportada" class="form-control" required><?= e($orden['falla_reportada']) ?></textarea>
        </div>

        <div class="form-group" style="grid-column:1 / -1;">
          <label for="estado_recepcion_fisico">Estado Físico en Recepción</label>
          <textarea id="estado_recepcion_fisico" name="estado_recepcion_fisico" class="form-control" required><?= e($orden['estado_recepcion_fisico']) ?></textarea>
        </div>

        <div class="form-group" style="grid-column:1 / -1;">
          <label for="diagnostico">Diagnóstico Técnico Detallado</label>
          <textarea id="diagnostico" name="diagnostico" class="form-control" placeholder="Causa raíz de la falla, pruebas realizadas..."><?= e($orden['diagnostico']) ?></textarea>
        </div>

        <div class="form-group" style="grid-column:1 / -1;">
          <label for="solucion_aplicada">Solución / Reparación Aplicada</label>
          <textarea id="solucion_aplicada" name="solucion_aplicada" class="form-control" placeholder="Procedimientos realizados, limpieza, reprogramación..."><?= e($orden['solucion_aplicada']) ?></textarea>
        </div>

        <div class="form-group">
          <label for="costo_mano_obra">Mano de Obra (S/)</label>
          <input type="number" step="0.01" id="costo_mano_obra" name="costo_mano_obra" class="form-control" value="<?= e($orden['costo_mano_obra']) ?>">
        </div>

        <div class="form-group">
          <label for="costo_repuestos">Repuestos y Piezas (S/)</label>
          <input type="number" step="0.01" id="costo_repuestos" name="costo_repuestos" class="form-control" value="<?= e($orden['costo_repuestos']) ?>">
        </div>

        <div class="form-group">
          <label for="adelanto">Adelanto Recibido (S/)</label>
          <input type="number" step="0.01" id="adelanto" name="adelanto" class="form-control" value="<?= e($orden['adelanto']) ?>">
        </div>

        <div class="form-group">
          <label for="garantia_meses">Garantía (Meses)</label>
          <input type="number" id="garantia_meses" name="garantia_meses" class="form-control" value="<?= e($orden['garantia_meses']) ?>">
        </div>

        <div class="form-group" style="grid-column:1 / -1;">
          <label for="observaciones_internas">Observaciones Internas</label>
          <input type="text" id="observaciones_internas" name="observaciones_internas" class="form-control" value="<?= e($orden['observaciones_internas']) ?>">
        </div>
      </div>

      <div style="margin-top:2rem;display:flex;justify-content:flex-end;gap:1rem;">
        <a href="<?= url('orden/ver/' . $orden['id_orden']) ?>" class="btn btn-outline">Cancelar</a>
        <button type="submit" class="btn btn-yellow" style="display:inline-flex;align-items:center;gap:6px;">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
          Guardar Cambios
        </button>
      </div>
    </form>
  </div>
</div>
