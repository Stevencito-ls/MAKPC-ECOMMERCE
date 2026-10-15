<?php
$currentUrl = $_GET['url'] ?? '';
$active = function($prefix) use ($currentUrl) {
    if ($prefix === 'panel' && ($currentUrl === 'panel' || $currentUrl === 'admin' || $currentUrl === 'dashboard')) return 'active';
    if ($prefix !== '' && $prefix !== 'panel' && strpos($currentUrl, $prefix) === 0) return 'active';
    return '';
};
$rol = auth('rol');

// Detección inteligente de la sección activa para desplegar su acordeón por defecto
$isGeneralActive = ($active('panel') || $active('orden') || $active('cliente') || $active('equipo') || $active('componente') || ($rol === 'vendedor' && ($active('producto') || $active('pedido'))));
$isCatalogoActive = (!$isGeneralActive && ($active('producto') || $active('pedido') || $active('inventario') || $active('caja')));
$isAuditoriaActive = ($active('usuario'));
$isTiendaActive = ($active('tienda/soporte'));

if (!$isGeneralActive && !$isCatalogoActive && !$isAuditoriaActive && !$isTiendaActive) {
    $isGeneralActive = true;
}
?>
<aside class="app-sidebar" id="appSidebar">
  <!-- Cabecera Móvil del Sidebar -->
  <div class="sidebar-mobile-header">
    <div class="header-brand-title">
      MAK<span>PC</span> <span style="font-size:0.75rem;color:var(--color-yellow);">ENTERPRISES</span>
    </div>
    <button class="sidebar-close-btn" id="sidebarCloseBtn" aria-label="Cerrar menú">&times;</button>
  </div>

  <div class="sidebar-scrollable-content">

    <!-- 1. ACORDEÓN: GESTIÓN PRINCIPAL SEGÚN ROL -->
    <div class="sidebar-section <?= $isGeneralActive ? 'is-open' : '' ?>" data-accordion="general">
      <button type="button" class="sidebar-accordion-header" aria-expanded="<?= $isGeneralActive ? 'true' : 'false' ?>">
        <span class="sidebar-title-text">
          <?php if ($rol === 'vendedor'): ?>
            Módulo Comercial
          <?php elseif ($rol === 'tecnico'): ?>
            Módulo de Taller
          <?php else: ?>
            Gestión General
          <?php endif; ?>
        </span>
        <svg class="sidebar-accordion-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
      </button>

      <div class="sidebar-accordion-content">
        <div class="sidebar-accordion-inner">
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

            <li>
              <a href="<?= TALLER_URL ?>" class="sidebar-link" title="Abrir Mostrador y Tablero Taller POS">
                <span class="icon">
                  <svg viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z"/></svg>
                </span>
                <span>Mostrador Taller POS &rarr;</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- 2. ACORDEÓN: CATÁLOGO COMERCIAL (ADMIN) -->
    <?php if (hasRole('admin')): ?>
      <div class="sidebar-section <?= $isCatalogoActive ? 'is-open' : '' ?>" data-accordion="catalogo">
        <button type="button" class="sidebar-accordion-header" aria-expanded="<?= $isCatalogoActive ? 'true' : 'false' ?>">
          <span class="sidebar-title-text">Catálogo Comercial</span>
          <svg class="sidebar-accordion-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="6 9 12 15 18 9"></polyline>
          </svg>
        </button>

        <div class="sidebar-accordion-content">
          <div class="sidebar-accordion-inner">
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
              <li>
                <a href="<?= url('inventario') ?>" class="sidebar-link <?= $active('inventario') ?>">
                  <span class="icon">
                    <svg viewBox="0 0 24 24"><path d="M20 13V4.83C20 3.82 19.18 3 18.17 3H5.83C4.82 3 4 3.82 4 4.83V13c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2zm-2-8v8h-3.17c-.41 1.16-1.52 2-2.83 2s-2.42-.84-2.83-2H6V5h12z"/></svg>
                  </span>
                  <span>Inventario &amp; Kardex</span>
                </a>
              </li>
              <li>
                <a href="<?= url('caja') ?>" class="sidebar-link <?= $active('caja') ?>">
                  <span class="icon">
                    <svg viewBox="0 0 24 24"><path d="M19 14V6c0-1.1-.9-2-2-2H3c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zm-9-1c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm13-6v11c0 1.1-.9 2-2 2H4v-2h17V7h2z"/></svg>
                  </span>
                  <span>Caja Diaria &amp; Cuadre</span>
                </a>
              </li>
              <li>
                <a href="<?= TALLER_URL ?>" class="sidebar-link" target="_blank" rel="noopener noreferrer">
                  <span class="icon">
                    <svg viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z"/></svg>
                  </span>
                  <span>Mostrador Taller POS &rarr;</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- 3. ACORDEÓN: AUDITORÍA & SISTEMA (ADMIN) -->
      <div class="sidebar-section <?= $isAuditoriaActive ? 'is-open' : '' ?>" data-accordion="auditoria">
        <button type="button" class="sidebar-accordion-header" aria-expanded="<?= $isAuditoriaActive ? 'true' : 'false' ?>">
          <span class="sidebar-title-text">Auditoría &amp; Sistema</span>
          <svg class="sidebar-accordion-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="6 9 12 15 18 9"></polyline>
          </svg>
        </button>

        <div class="sidebar-accordion-content">
          <div class="sidebar-accordion-inner">
            <ul class="sidebar-menu">
              <li>
                <a href="<?= url('usuario') ?>" class="sidebar-link <?= $active('usuario') ?>">
                  <span class="icon">
                    <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg>
                  </span>
                  <span>Usuarios &amp; Roles</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- 4. ACORDEÓN: TIENDA PÚBLICA & ATENCIÓN -->
    <div class="sidebar-section <?= $isTiendaActive ? 'is-open' : '' ?>" data-accordion="tienda">
      <button type="button" class="sidebar-accordion-header" aria-expanded="<?= $isTiendaActive ? 'true' : 'false' ?>">
        <span class="sidebar-title-text">Tienda &amp; Atención</span>
        <svg class="sidebar-accordion-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
      </button>

      <div class="sidebar-accordion-content">
        <div class="sidebar-accordion-inner">
          <ul class="sidebar-menu">
            <li>
              <a href="<?= url() ?>" class="sidebar-link">
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
      </div>
    </div>

  </div> <!-- /sidebar-scrollable-content -->

  <!-- Footer del Sidebar -->
  <div class="sidebar-footer">
    <?php if (isLoggedIn()): ?>
      <div class="sidebar-session-card">
        <div class="sidebar-session-header">
          <span class="sidebar-session-tag">Sesión Activa</span>
          <span class="sidebar-session-badge">
            <?= e(auth('rol')) ?>
          </span>
        </div>
        <div class="sidebar-session-user" title="<?= e(auth('nombre_completo')) ?>">
          <?= e(auth('nombre_completo')) ?>
        </div>
        <div class="sidebar-session-actions">
          <a href="<?= url('logout') ?>" class="sidebar-btn-logout" title="Cerrar sesión">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
              <polyline points="16 17 21 12 16 7"></polyline>
              <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            <span>Cerrar Sesión</span>
          </a>
        </div>
      </div>
    <?php endif; ?>

    <div class="support-box">
      <h4>MAKPC Soporte</h4>
      <p>Línea directa de taller y soporte</p>
      <a href="https://wa.me/51975513327?text=Hola%20MAKPC,%20necesito%20soporte%20tecnico" target="_blank" rel="noopener noreferrer" class="btn-whatsapp">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
          <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
        </svg>
        <span>WhatsApp Directo</span>
      </a>
    </div>
  </div>
</aside>
