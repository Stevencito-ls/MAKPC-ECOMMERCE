<?php
$flash = getFlash();
if ($flash): ?>
  <div class="alert alert-<?= e($flash['type']) ?>">
    <span><?= e($flash['message']) ?></span>
  </div>
<?php endif; ?>

<div class="page-header">
  <div>
    <h1 style="display:flex;align-items:center;gap:0.5rem;">
      <svg style="width:24px;height:24px;fill:currentColor;" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
      <span>Registrar Nuevo Cliente</span>
    </h1>
    <p>Ingrese los datos personales y de contacto para altas y órdenes</p>
  </div>
  <div class="page-header-actions">
    <a href="<?= url('cliente') ?>" class="btn btn-outline" style="display:inline-flex;align-items:center;gap:0.4rem;">
      <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 24 24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
      <span>Volver al Listado</span>
    </a>
  </div>
</div>

<div class="card" style="max-width:800px;margin:0 auto;">
  <div class="card-header">
    <h3 style="display:flex;align-items:center;gap:0.5rem;margin:0;">
      <svg style="width:18px;height:18px;fill:currentColor;" viewBox="0 0 24 24"><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
      <span>Formulario de Cliente</span>
    </h3>
  </div>
  <div class="card-body">
    <form action="<?= url('cliente/crear') ?>" method="POST">
      <?= csrf_field() ?>
      <div class="form-grid">
        <div class="form-group" style="grid-column:1 / -1;">
          <label for="nombres_apellidos">Nombres y Apellidos *</label>
          <input type="text" id="nombres_apellidos" name="nombres_apellidos" class="form-control" required placeholder="Ej: Carlos Mendoza Alarcón">
        </div>

        <div class="form-group">
          <label for="telefono">Teléfono Celular (WhatsApp) *</label>
          <input type="text" id="telefono" name="telefono" class="form-control" required placeholder="Ej: 987654321">
        </div>

        <div class="form-group">
          <label for="telefono_secundario">Teléfono Secundario / Fijo</label>
          <input type="text" id="telefono_secundario" name="telefono_secundario" class="form-control" placeholder="Ej: 072-521234">
        </div>

        <div class="form-group">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.25rem;">
            <label for="dni" style="margin:0;">DNI / RUC</label>
            <span id="dniDocBadge" style="font-size:0.75rem;font-weight:700;display:none;"></span>
          </div>
          <div style="display:flex;gap:6px;">
            <input type="text" id="dni" name="dni" class="form-control" placeholder="Ej: 70000000 o RUC" maxlength="11">
            <button type="button" id="btnConsultarDoc" onclick="consultarDocCliente()" class="btn btn-primary btn-sm" style="white-space:nowrap;padding:0.35rem 0.75rem;display:inline-flex;align-items:center;gap:0.3rem;" title="Consultar en RENIEC o SUNAT">
              <svg style="width:13px;height:13px;fill:currentColor;" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
              <span>Validar</span>
            </button>
          </div>
        </div>

        <div class="form-group">
          <label for="correo">Correo Electrónico</label>
          <input type="email" id="correo" name="correo" class="form-control" placeholder="Ej: cliente@correo.com">
        </div>

        <div class="form-group" style="grid-column:1 / -1;">
          <label for="direccion">Dirección</label>
          <input type="text" id="direccion" name="direccion" class="form-control" placeholder="Ej: Cal. Bolívar 461, Cercado de Tumbes">
        </div>

        <div class="form-group" style="grid-column:1 / -1;">
          <label for="notas_cliente">Notas adicionales</label>
          <textarea id="notas_cliente" name="notas_cliente" class="form-control" placeholder="Información relevante o preferencias del cliente..."></textarea>
        </div>
      </div>

      <div style="margin-top:1.5rem;display:flex;justify-content:flex-end;gap:1rem;">
        <a href="<?= url('cliente') ?>" class="btn btn-outline">Cancelar</a>
        <button type="submit" class="btn btn-yellow" style="display:inline-flex;align-items:center;gap:0.4rem;">
          <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 24 24"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>
          <span>Guardar Cliente</span>
        </button>
      </div>
    </form>
  </div>
</div>

<script>
async function consultarDocCliente() {
  const inp = document.getElementById('dni');
  const badge = document.getElementById('dniDocBadge');
  const btn = document.getElementById('btnConsultarDoc');
  const val = (inp.value || '').trim();

  let tipo = 'dni';
  if (val.length === 11) {
    tipo = 'ruc';
  } else if (val.length === 8) {
    tipo = 'dni';
  } else {
    alert('Ingrese un DNI (8 dígitos) o RUC (11 dígitos) válido.');
    inp.focus();
    return;
  }

  btn.disabled = true;
  btn.textContent = '...';
  badge.style.display = 'inline-block';
  badge.style.color = '#F59E0B';
  badge.textContent = 'Consultando...';

  try {
    const resp = await fetch('<?= url("consulta-documento") ?>?tipo=' + tipo + '&numero=' + encodeURIComponent(val));
    const data = await resp.json();
    if (data.success) {
      badge.style.color = '#10B981';
      badge.textContent = '✓ ' + (data.origen || 'Verificado');
      if (tipo === 'dni') {
        document.getElementById('nombres_apellidos').value = data.nombre_completo;
        document.getElementById('nombres_apellidos').style.background = '#ECFDF5';
        setTimeout(() => document.getElementById('nombres_apellidos').style.background = '', 1500);
      } else {
        document.getElementById('nombres_apellidos').value = data.razon_social;
        document.getElementById('direccion').value = data.direccion || '';
        document.getElementById('nombres_apellidos').style.background = '#ECFDF5';
        setTimeout(() => document.getElementById('nombres_apellidos').style.background = '', 1500);
      }
    } else {
      badge.style.color = '#EF4444';
      badge.textContent = data.message || 'No encontrado';
    }
  } catch (e) {
    badge.style.color = '#EF4444';
    badge.textContent = 'Error conexión';
  } finally {
    btn.disabled = false;
    btn.textContent = '🔍 Validar';
  }
}

document.getElementById('dni')?.addEventListener('input', function() {
  const l = this.value.trim().length;
  if (l === 8 || l === 11) {
    consultarDocCliente();
  }
});
</script>
