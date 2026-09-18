<?php
/**
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
    <h1>Registrar Nuevo Colaborador</h1>
    <p>Creación de credenciales de acceso y asignación de rol en MAKPC Enterprises S.A.C.</p>
  </div>
  <div class="page-header-actions">
    <a href="<?= url('usuario') ?>" class="btn btn-outline" style="display:inline-flex;align-items:center;gap:0.4rem;">
      <svg style="width:14px;height:14px;fill:currentColor;" viewBox="0 0 24 24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
      <span>Volver al Listado</span>
    </a>
  </div>
</div>

<div class="card" style="max-width:700px;margin:0 auto;">
  <div class="card-header">
    <h3>Formulario de Registro de Usuario</h3>
  </div>
  <div class="card-body">
    <form action="<?= url('usuario/crear') ?>" method="POST">
      <?= csrf_field() ?>

      <div class="form-grid">
        <div class="form-group">
          <label for="usuario">Nombre de Usuario (Login) *</label>
          <input type="text" id="usuario" name="usuario" class="form-control" required placeholder="ej: jcarlos" autocomplete="off" autofocus>
          <small style="color:var(--color-shadow);font-size:0.75rem;">Sin espacios ni caracteres especiales.</small>
        </div>

        <div class="form-group">
          <label for="nombre_completo">Nombres y Apellidos Completos *</label>
          <input type="text" id="nombre_completo" name="nombre_completo" class="form-control" required placeholder="ej: Juan Carlos Ramos">
        </div>

        <div class="form-group">
          <label for="password">Contraseña Inicial *</label>
          <input type="password" id="password" name="password" class="form-control" required placeholder="••••••••" autocomplete="new-password">
        </div>

        <div class="form-group">
          <label for="rol">Rol de Acceso / Privilegios *</label>
          <select name="rol" id="rol" class="form-control" required>
            <option value="tecnico">Técnico (Taller, Diagnóstico y Reparaciones)</option>
            <option value="vendedor">Vendedor (Catálogo, Precios, Stock y Clientes)</option>
            <option value="admin">Administrador (Acceso Total y Configuración)</option>
          </select>
        </div>
      </div>

      <div style="margin-top:2rem;display:flex;gap:1rem;justify-content:flex-end;border-top:1px solid #edf0f5;padding-top:1.25rem;">
        <a href="<?= url('usuario') ?>" class="btn btn-outline">Cancelar</a>
        <button type="submit" class="btn btn-yellow" style="display:inline-flex;align-items:center;gap:0.4rem;">
          <svg style="width:16px;height:16px;fill:currentColor;" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
          <span>Guardar Colaborador</span>
        </button>
      </div>
    </form>
  </div>
</div>
