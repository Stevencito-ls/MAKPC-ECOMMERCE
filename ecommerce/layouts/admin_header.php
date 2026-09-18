<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($title) ? e($title) . ' - ' : '' ?><?= APP_NAME ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= asset('css/app.css') ?>?v=<?= file_exists(__DIR__ . '/../../assets/css/app.css') ? filemtime(__DIR__ . '/../../assets/css/app.css') : '2.0' ?>">
  <link rel="icon" type="image/png" href="<?= asset('img/logo.png') ?>">
</head>
<body>

<!-- TOP HEADER -->
<header class="top-header">
  <div class="header-brand">
    <button class="menu-toggle-btn" id="menuToggle" aria-label="Abrir Menú">
      <svg style="width:20px;height:20px;fill:currentColor;" viewBox="0 0 24 24"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>
    </button>
    <a href="<?= url('panel') ?>" style="display:flex;align-items:center;gap:0.75rem;">
      <img src="<?= asset('img/logo.png') ?>" alt="Logo MAKPC">
      <div class="header-brand-title">
        MAK<span>PC</span>
      </div>
    </a>
  </div>

  <div class="header-search">
    <form action="<?= url('tienda') ?>" method="GET" style="display:flex;align-items:center;">
      <input type="text" name="q" placeholder="Buscar productos en catálogo o repuestos..." value="<?= e($_GET['q'] ?? '') ?>">
      <button type="submit" style="display:flex;align-items:center;gap:0.35rem;">
        <svg style="width:14px;height:14px;fill:currentColor;" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
        <span>Buscar</span>
      </button>
    </form>
  </div>

  <div class="header-actions">
    <a href="<?= url() ?>" class="btn-header-action" target="_blank" style="display:inline-flex;align-items:center;gap:0.4rem;">
      <svg style="width:15px;height:15px;fill:currentColor;" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
      <span>Ver Tienda</span>
    </a>

    <?php if (hasRole(['admin', 'vendedor'])): ?>
      <a href="<?= url('orden/crear') ?>" class="btn-header-cta" style="display:inline-flex;align-items:center;gap:0.4rem;">
        <svg style="width:14px;height:14px;fill:currentColor;" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
        <span><?= hasRole('vendedor') ? 'Recepción' : 'Nueva Orden' ?></span>
      </a>
    <?php endif; ?>

    <?php if (isLoggedIn()): ?>
      <div class="user-profile-pill" style="display:inline-flex;align-items:center;gap:0.45rem;">
        <svg style="width:16px;height:16px;fill:rgba(255,255,255,0.7);" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
        <span class="user-name"><?= e(auth('nombre_completo')) ?></span>
        <span class="badge-role-header badge-<?= strtolower(e(auth('rol'))) ?>">
          <?= strtoupper(e(auth('rol'))) ?>
        </span>
      </div>
      <a href="<?= url('logout') ?>" class="btn-header-action btn-header-logout" title="Cerrar sesión" style="display:inline-flex;align-items:center;gap:0.35rem;">
        <svg style="width:14px;height:14px;fill:currentColor;" viewBox="0 0 24 24"><path d="M10.09 15.59L11.5 17l5-5-5-5-1.41 1.41L12.67 11H3v2h9.67l-2.58 2.59zM19 3H5c-1.11 0-2 .9-2 2v4h2V5h14v14H5v-4H3v4c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
        <span>Salir</span>
      </a>
    <?php endif; ?>
  </div>
</header>

<div class="app-container">
