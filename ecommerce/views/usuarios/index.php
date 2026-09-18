<?php
/**
 * @var array $usuarios
 * @var string $title
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
        <svg style="width:28px;height:28px;fill:var(--color-blue);" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg>
        <span>Usuarios & Auditoría de Roles</span>
      </h1>
      <span class="badge badge-admin" style="font-size:0.75rem;padding:0.25rem 0.65rem;">
        Solo Administrador
      </span>
    </div>
    <p style="margin-top:0.35rem;">Administración de cuentas del personal, asignación estricta de privilegios y control de acceso al sistema.</p>
  </div>
  <div class="page-header-actions">
    <a href="<?= url('usuario/crear') ?>" class="btn btn-yellow" style="display:inline-flex;align-items:center;gap:0.4rem;">
      <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
      <span>Registrar Colaborador</span>
    </a>
  </div>
</div>

<div class="card">
  <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
    <h3 style="margin:0;font-size:1.1rem;">Cuentas de Acceso Activas (<?= count($usuarios) ?> colaboradores)</h3>
  </div>
  <div class="table-responsive">
    <table class="custom-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Usuario</th>
          <th>Nombre Completo</th>
          <th>Rol / Privilegio</th>
          <th>Estado</th>
          <th>Fecha Registro</th>
          <th style="text-align:right;">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($usuarios as $u): 
          $roleClass = match($u['rol']) {
            'admin' => 'badge-admin',
            'tecnico' => 'badge-tecnico',
            'vendedor' => 'badge-vendedor',
            default => 'badge-revision'
          };
          $esPropio = ((int)$u['id_usuario'] === (int)auth('id'));
        ?>
          <tr>
            <td><strong>#<?= (int)$u['id_usuario'] ?></strong></td>
            <td>
              <code>@<?= e($u['usuario']) ?></code>
              <?php if ($esPropio): ?>
                <span style="font-size:0.7rem;background:var(--color-lavender);color:var(--color-blue);padding:0.1rem 0.35rem;border-radius:4px;font-weight:700;">Tú</span>
              <?php endif; ?>
            </td>
            <td><strong><?= e($u['nombre_completo']) ?></strong></td>
            <td>
              <span class="badge <?= $roleClass ?>">
                <?= strtoupper(e($u['rol'])) ?>
              </span>
            </td>
            <td>
              <?php if ($u['activo']): ?>
                <span class="badge badge-listo">Activo</span>
              <?php else: ?>
                <span class="badge badge-cancelado">Inactivo</span>
              <?php endif; ?>
            </td>
            <td><?= date('d/m/Y H:i', strtotime($u['creado_en'])) ?></td>
            <td style="text-align:right;">
              <div style="display:inline-flex;gap:0.4rem;align-items:center;">
                <button 
                  type="button" 
                  class="btn btn-sm btn-outline" 
                  onclick="abrirModalReset(<?= (int)$u['id_usuario'] ?>, '<?= e(addslashes($u['usuario'])) ?>', '<?= e(addslashes($u['nombre_completo'])) ?>')"
                  title="Restablecer Contraseña"
                  style="display:inline-flex;align-items:center;gap:4px;"
                >
                  <svg style="width:13px;height:13px;fill:currentColor;" viewBox="0 0 24 24"><path d="M12.65 10C11.83 7.67 9.61 6 7 6c-3.31 0-6 2.69-6 6s2.69 6 6 6c2.61 0 4.83-1.67 5.65-4H17v4h4v-4h2v-4H12.65zM7 14c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/></svg>
                  <span>Clave</span>
                </button>
                <?php if (!$esPropio): ?>
                  <a 
                    href="<?= url('usuario/toggle/' . $u['id_usuario']) ?>" 
                    class="btn btn-sm <?= $u['activo'] ? 'btn-outline' : 'btn-primary' ?>" 
                    onclick="return confirm('¿Deseas cambiar el estado de este colaborador?')"
                    title="<?= $u['activo'] ? 'Desactivar cuenta' : 'Activar cuenta' ?>"
                  >
                    <?= $u['activo'] ? 'Desactivar' : 'Activar' ?>
                  </a>
                <?php else: ?>
                  <span style="font-size:0.75rem;color:var(--color-shadow);font-weight:600;">Sesión Actual</span>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- MATRIZ DE AUDITORÍA Y PERMISOS ESTRICTOS -->
<div class="card" style="margin-top:2rem;">
  <div class="card-header">
    <h3 style="margin:0;font-size:1.05rem;">Matriz de Roles y Privilegios del Sistema MAKPC</h3>
  </div>
  <div class="card-body">
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:1.5rem;">
      <div style="background:#f8fafc;border:1px solid #edf0f5;border-radius:var(--radius-md);padding:1.35rem;">
        <h4 style="color:var(--color-blue);display:flex;align-items:center;gap:0.5rem;margin-bottom:0.6rem;">
          <svg style="width:18px;height:18px;fill:var(--color-yellow);" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg>
          <span>Administrador (<code>admin</code>)</span>
        </h4>
        <p style="font-size:0.83rem;color:#4b5563;line-height:1.6;margin:0;">
          Acceso total y sin restricciones. Supervisa ingresos financieros, gestiona usuarios y credenciales, audita trazabilidad de repuestos, administra el catálogo y configura el sistema.
        </p>
      </div>

      <div style="background:#f8fafc;border:1px solid #edf0f5;border-radius:var(--radius-md);padding:1.35rem;">
        <h4 style="color:var(--color-celeste);display:flex;align-items:center;gap:0.5rem;margin-bottom:0.6rem;">
          <svg style="width:18px;height:18px;fill:var(--color-celeste);" viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z"/></svg>
          <span>Especialista Técnico (<code>tecnico</code>)</span>
        </h4>
        <p style="font-size:0.83rem;color:#4b5563;line-height:1.6;margin:0;">
          Centro de Operaciones de Taller. Diagnostica fallas de hardware/software, registra soluciones, cambia estados de órdenes y documenta series de componentes instalados y retirados.
        </p>
      </div>

      <div style="background:#f8fafc;border:1px solid #edf0f5;border-radius:var(--radius-md);padding:1.35rem;">
        <h4 style="color:#059669;display:flex;align-items:center;gap:0.5rem;margin-bottom:0.6rem;">
          <svg style="width:18px;height:18px;fill:#059669;" viewBox="0 0 24 24"><path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm7 17H5V8h14v12z"/></svg>
          <span>Asesor Comercial (<code>vendedor</code>)</span>
        </h4>
        <p style="font-size:0.83rem;color:#4b5563;line-height:1.6;margin:0;">
          Módulo de Ventas e Inventario. Sube nuevos productos, actualiza precios y stock, registra clientes y genera recepciones de equipos en mostrador. Sin acceso a usuarios ni finanzas.
        </p>
      </div>
    </div>
  </div>
</div>

<!-- MODAL RESTABLECER CONTRASEÑA -->
<div id="modalResetPassword" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.6);z-index:9999;align-items:center;justify-content:center;padding:1rem;backdrop-filter:blur(2px);">
  <div class="card" style="width:100%;max-width:440px;box-shadow:0 20px 25px -5px rgba(0,0,0,0.3);margin:auto;">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
      <h3 style="margin:0;font-size:1.1rem;display:flex;align-items:center;gap:6px;">
        🔑 <span>Restablecer Contraseña</span>
      </h3>
      <button type="button" onclick="cerrarModalReset()" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:#64748b;line-height:1;">&times;</button>
    </div>
    <div class="card-body">
      <p id="resetUserText" style="font-size:0.9rem;color:#475569;margin-bottom:1.25rem;"></p>
      <form id="formResetPassword" method="POST" action="">
        <?= csrf_field() ?>
        <div class="form-group" style="margin-bottom:1.25rem;">
          <label for="new_password" style="display:block;margin-bottom:0.4rem;font-weight:700;">Nueva Contraseña *</label>
          <input type="password" id="new_password" name="password" class="form-control" required minlength="6" placeholder="Mínimo 6 caracteres">
        </div>
        <div style="display:flex;justify-content:flex-end;gap:0.75rem;">
          <button type="button" class="btn btn-outline" onclick="cerrarModalReset()">Cancelar</button>
          <button type="submit" class="btn btn-primary">Actualizar Contraseña</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function abrirModalReset(id, usuario, nombre) {
  const modal = document.getElementById('modalResetPassword');
  const form = document.getElementById('formResetPassword');
  const userText = document.getElementById('resetUserText');
  const inputPass = document.getElementById('new_password');
  
  form.action = '<?= url("usuario/resetPassword/") ?>' + id;
  userText.innerHTML = 'Asignando nueva clave de acceso para <strong>@' + usuario + '</strong> (' + nombre + '):';
  inputPass.value = '';
  modal.style.display = 'flex';
  setTimeout(() => inputPass.focus(), 100);
}

function cerrarModalReset() {
  document.getElementById('modalResetPassword').style.display = 'none';
}

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') cerrarModalReset();
});
</script>
