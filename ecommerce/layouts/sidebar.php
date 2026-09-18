<?php
$currentUrl = $_GET['url'] ?? '';
$active = function($prefix) use ($currentUrl) {
    if ($prefix === 'panel' && ($currentUrl === 'panel' || $currentUrl === 'admin' || $currentUrl === 'dashboard')) return 'active';
    if ($prefix !== '' && $prefix !== 'panel' && strpos($currentUrl, $prefix) === 0) return 'active';
    return '';
};
$rol = auth('rol');
?>
<aside class="app-sidebar" id="appSidebar">
  <div class="sidebar-mobile-header">
    <div class="header-brand-title">
      MAK<span>PC</span> <span style="font-size:0.75rem;color:var(--color-yellow);">ENTERPRISES</span>
    </div>
    <button class="sidebar-close-btn" id="sidebarCloseBtn" aria-label="Cerrar menú">&times;</button>
  </div>

  <!-- SECCIÓN PRINCIPAL SEGÚN ROL -->
  <div class="sidebar-section">
    <div class="sidebar-title">
      <?php if ($rol === 'vendedor'): ?>
        Módulo Comercial
      <?php elseif ($rol === 'tecnico'): ?>
        Módulo de Taller
      <?php else: ?>
        Gestión General
      <?php endif; ?>
    </div>
    <ul class="sidebar-menu">
      <li>
        <a href="<?= url('panel') ?>" class="sidebar-link <?= $active('panel') ?>">
          <span class="icon">
            <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/></svg>
          </span>
          <span>Dashboard</span>
        </a>
      </li>

      <?php if ($rol === 'vendedor'): ?>
        <li>
          <a href="<?= url('producto') ?>" class="sidebar-link <?= $active('producto') ?>">
            <span class="icon">
              <svg viewBox="0 0 24 24"><path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm7 17H5V8h14v12z"/></svg>
            </span>
            <span>Catálogo Productos</span>
          </a>
        </li>
        <li>
          <a href="<?= url('pedido') ?>" class="sidebar-link <?= $active('pedido') ?>">
            <span class="icon">
              <svg viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
            </span>
            <span>Ventas &amp; Facturación SUNAT</span>
          </a>
        </li>
      <?php endif; ?>

      <li>
        <a href="<?= url('orden') ?>" class="sidebar-link <?= $active('orden') ?>">
          <span class="icon">
            <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
          </span>
          <span><?= $rol === 'vendedor' ? 'Recepción de Equipos' : 'Órdenes de Servicio' ?></span>
        </a>
      </li>

      <li>
        <a href="<?= url('cliente') ?>" class="sidebar-link <?= $active('cliente') ?>">
          <span class="icon">
            <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
          </span>
          <span>Clientes</span>
        </a>
      </li>

      <li>
        <a href="<?= url('equipo') ?>" class="sidebar-link <?= $active('equipo') ?>">
          <span class="icon">
            <svg viewBox="0 0 24 24"><path d="M20 18c1.1 0 1.99-.9 1.99-2L22 5c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2H0c0 1.1.9 2 2 2h20c1.1 0 2-.9 2-2h-4zM4 5h16v11H4V5z"/></svg>
          </span>
          <span>Equipos</span>
        </a>
      </li>

      <?php if (hasRole(['admin', 'tecnico'])): ?>
        <li>
          <a href="<?= url('componente') ?>" class="sidebar-link <?= $active('componente') ?>">
            <span class="icon">
              <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
            </span>
            <span>Trazabilidad / Series</span>
          </a>
        </li>
      <?php endif; ?>
    </ul>
  </div>

  <!-- SECCIÓN PRODUCTOS & E-COMMERCE (ADMIN) -->
  <?php if (hasRole('admin')): ?>
    <div class="sidebar-section">
      <div class="sidebar-title">Catálogo Comercial</div>
      <ul class="sidebar-menu">
        <li>
          <a href="<?= url('producto') ?>" class="sidebar-link <?= $active('producto') ?>">
            <span class="icon">
              <svg viewBox="0 0 24 24"><path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm7 17H5V8h14v12z"/></svg>
            </span>
            <span>Gestión de Productos</span>
          </a>
        </li>
        <li>
          <a href="<?= url('pedido') ?>" class="sidebar-link <?= $active('pedido') ?>">
            <span class="icon">
              <svg viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
            </span>
            <span>Ventas &amp; Facturación SUNAT</span>
          </a>
        </li>
      </ul>
    </div>

    <div class="sidebar-section">
      <div class="sidebar-title">Auditoría & Sistema</div>
      <ul class="sidebar-menu">
        <li>
          <a href="<?= url('usuario') ?>" class="sidebar-link <?= $active('usuario') ?>">
            <span class="icon">
              <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg>
            </span>
            <span>Usuarios & Roles</span>
          </a>
        </li>
      </ul>
    </div>
  <?php endif; ?>

  <!-- TIENDA PÚBLICA -->
  <div class="sidebar-section">
    <div class="sidebar-title">Tienda & Atención</div>
    <ul class="sidebar-menu">
      <li>
        <a href="<?= url() ?>" class="sidebar-link" target="_blank">
          <span class="icon">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
          </span>
          <span>Página Principal / Tienda</span>
        </a>
      </li>
      <li>
        <a href="<?= url('tienda/soporte') ?>" class="sidebar-link <?= $active('tienda/soporte') ?>">
          <span class="icon">
            <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-7 12h-2v-2h2v2zm0-4h-2V6h2v4z"/></svg>
          </span>
          <span>Rastreo de Órdenes</span>
        </a>
      </li>
    </ul>
  </div>

  <div class="sidebar-footer">
    <?php if (isLoggedIn()): ?>
      <div style="background:rgba(255,255,255,0.06);border-radius:var(--radius-md);padding:0.75rem;margin-bottom:0.85rem;border:1px solid rgba(255,255,255,0.1);">
        <div style="font-size:0.7rem;color:rgba(255,255,255,0.6);text-transform:uppercase;font-weight:700;">Sesión Activa</div>
        <div style="font-size:0.86rem;font-weight:700;color:#fff;margin:0.2rem 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= e(auth('nombre_completo')) ?></div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:0.4rem;">
          <span style="background:var(--color-yellow);color:var(--color-blue);font-weight:800;font-size:0.65rem;padding:0.15rem 0.5rem;border-radius:10px;text-transform:uppercase;">
            <?= e(auth('rol')) ?>
          </span>
          <a href="<?= url('logout') ?>" style="font-size:0.75rem;color:#fca5a5;font-weight:600;display:inline-flex;align-items:center;gap:0.25rem;" title="Cerrar sesión">
            <svg style="width:12px;height:12px;fill:currentColor;" viewBox="0 0 24 24"><path d="M10.09 15.59L11.5 17l5-5-5-5-1.41 1.41L12.67 11H3v2h9.67l-2.58 2.59zM19 3H5c-1.11 0-2 .9-2 2v4h2V5h14v14H5v-4H3v4c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
            <span>Salir</span>
          </a>
        </div>
      </div>
    <?php endif; ?>

    <div class="support-box">
      <h4>MAKPC Soporte</h4>
      <p>Línea directa de taller y soporte</p>
      <a href="https://wa.me/51975513327?text=Hola%20MAKPC,%20necesito%20soporte%20tecnico" target="_blank" class="btn-whatsapp">
        <span>WhatsApp Directo</span>
      </a>
    </div>
  </div>
</aside>
