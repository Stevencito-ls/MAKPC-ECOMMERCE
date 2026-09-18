<?php
/**
 * @var array $equipos
 * @var mixed $preEquipoId
 * @var array $clientes
 */
$flash = getFlash();
if ($flash): ?>
  <div class="alert alert-<?= e($flash['type']) ?>">
    <span><?= e($flash['message']) ?></span>
  </div>
<?php endif; ?>

<div class="page-header">
  <div>
    <h1>Generar Nueva Orden de Servicio</h1>
    <p>Registrar recepción técnica de equipo y emisión de comprobante de ingreso</p>
  </div>
  <div class="page-header-actions">
    <a href="<?= url('orden') ?>" class="btn btn-outline">
      &larr; Volver al Listado
    </a>
  </div>
</div>

<div class="card" style="max-width:900px;margin:0 auto;">
  <div class="card-header">
    <h3>Datos de Recepción Técnica</h3>
  </div>
  <div class="card-body">
    <form action="<?= url('orden/crear') ?>" method="POST">
      <?= csrf_field() ?>
      <div class="form-grid">
        <div class="form-group" style="grid-column:1 / -1;">
          <label for="id_equipo">Equipo a Ingresar *</label>
          <select name="id_equipo" id="id_equipo" class="form-control" required>
            <option value="">-- Seleccionar Equipo del Cliente --</option>
            <?php foreach ($equipos as $eq): ?>
              <option value="<?= $eq['id_equipo'] ?>" <?= ($preEquipoId == $eq['id_equipo']) ? 'selected' : '' ?>>
                <?= e($eq['marca']) ?> <?= e($eq['modelo']) ?> [<?= e($eq['tipo_equipo']) ?>] — Cliente: <?= e($eq['cliente_nombre']) ?> (Tel: <?= e($eq['cliente_tel']) ?>)
              </option>
            <?php endforeach; ?>
          </select>
          <small style="color:var(--color-shadow);margin-top:4px;">
            ¿El equipo no está en la lista? <a href="<?= url('equipo/crear') ?>" target="_blank" style="color:var(--color-celeste);font-weight:600;">Registrarlo primero aquí</a>
          </small>
        </div>

        <div class="form-group">
          <label for="tecnico_responsable">Atendido / Recepcionado por *</label>
          <input type="text" id="tecnico_responsable" name="tecnico_responsable" class="form-control" required value="<?= e(auth('nombre_completo') ?: 'Steve - Soporte MAKPC') ?> (<?= strtoupper(e(auth('rol') ?: 'TECNICO')) ?>)">
        </div>

        <div class="form-group" style="display:flex;flex-direction:row;align-items:center;gap:0.75rem;padding-top:1.5rem;">
          <input type="checkbox" id="es_inmediato" name="es_inmediato" value="1" style="width:20px;height:20px;">
          <label for="es_inmediato" style="font-weight:700;color:#DC2626;cursor:pointer;">
            Servicio Urgente / Inmediato
          </label>
        </div>

        <div class="form-group" style="grid-column:1 / -1;">
          <label for="falla_reportada">Falla Reportada por el Cliente *</label>
          <textarea id="falla_reportada" name="falla_reportada" class="form-control" required placeholder="Ej: La laptop enciende pero la pantalla se queda negra con parpadeo del botón de encendido..."></textarea>
        </div>

        <div class="form-group" style="grid-column:1 / -1;">
          <label for="estado_recepcion_fisico">Estado Físico en Recepción *</label>
          <textarea id="estado_recepcion_fisico" name="estado_recepcion_fisico" class="form-control" required placeholder="Ej: Chasis con leves rayones en tapa superior, pantalla sin rajaduras, falta un tornillo en bisagra izquierda..."></textarea>
        </div>

        <div class="form-group">
          <label for="accesorios_entregados">Accesorios Recibidos</label>
          <input type="text" id="accesorios_entregados" name="accesorios_entregados" class="form-control" placeholder="Ej: Cargador original 65W, funda azul">
        </div>

        <div class="form-group">
          <label for="servicio_solicitado">Servicio Solicitado</label>
          <input type="text" id="servicio_solicitado" name="servicio_solicitado" class="form-control" placeholder="Ej: Diagnóstico general, formateo, cambio de pasta térmica">
        </div>

        <div class="form-group" style="grid-column:1 / -1;">
          <label for="diagnostico">Diagnóstico Inicial (Opcional)</label>
          <textarea id="diagnostico" name="diagnostico" class="form-control" placeholder="Observaciones preliminares del técnico..."></textarea>
        </div>

        <div class="form-group">
          <label for="costo_mano_obra">Costo Mano de Obra (S/)</label>
          <input type="number" step="0.01" id="costo_mano_obra" name="costo_mano_obra" class="form-control" value="0.00">
        </div>

        <div class="form-group">
          <label for="costo_repuestos">Costo Repuestos (S/)</label>
          <input type="number" step="0.01" id="costo_repuestos" name="costo_repuestos" class="form-control" value="0.00">
        </div>

        <div class="form-group">
          <label for="adelanto">Adelanto / Anticipo (S/)</label>
          <input type="number" step="0.01" id="adelanto" name="adelanto" class="form-control" value="0.00">
        </div>

        <div class="form-group">
          <label for="garantia_meses">Garantía Otorgada (Meses)</label>
          <input type="number" id="garantia_meses" name="garantia_meses" class="form-control" value="3">
        </div>

        <div class="form-group">
          <label for="estado">Estado Inicial</label>
          <select name="estado" id="estado" class="form-control">
            <option value="Pendiente">Pendiente</option>
            <option value="En Reparacion">En Reparación</option>
          </select>
        </div>

        <div class="form-group">
          <label for="observaciones_internas">Observaciones Internas</label>
          <input type="text" id="observaciones_internas" name="observaciones_internas" class="form-control" placeholder="Anotaciones solo visibles por el taller">
        </div>
      </div>

      <div style="margin-top:2rem;display:flex;justify-content:flex-end;gap:1rem;">
        <a href="<?= url('orden') ?>" class="btn btn-outline">Cancelar</a>
        <button type="submit" class="btn btn-yellow" style="display:inline-flex;align-items:center;gap:6px;">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
          Generar Orden & Imprimir
        </button>
      </div>
    </form>
  </div>
</div>
