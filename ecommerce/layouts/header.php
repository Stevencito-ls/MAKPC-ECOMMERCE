<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($title) ? e($title) . ' - ' : '' ?>Ecommerce MAKPC | Tienda Oficial & Servicio Técnico Especializado</title>
  
  <!-- Google Fonts: Inter & Outfit (Corporate High-Tech Style) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800;900&display=swap" rel="stylesheet">
  
  <!-- CSS Base & Coolbox Enterprise Stylesheet -->
  <link rel="stylesheet" href="<?= asset('css/app.css') ?>?v=<?= file_exists(__DIR__ . '/../assets/css/app.css') ? filemtime(__DIR__ . '/../assets/css/app.css') : '1.0' ?>">
  <link rel="stylesheet" href="<?= asset('css/coolbox.css') ?>?v=<?= file_exists(__DIR__ . '/../assets/css/coolbox.css') ? filemtime(__DIR__ . '/../assets/css/coolbox.css') : '1.0' ?>">
  <link rel="icon" type="image/png" href="<?= asset('img/logo.png') ?>">
</head>
<body>

<!-- 1. TOP ANNOUNCEMENT BAR (Estilo Coolbox.pe) -->
<div class="cb-topbar">
  <div class="cb-topbar-inner">
    <div class="cb-topbar-left">
      <span class="cb-topbar-item highlight">
        <span class="cb-topbar-badge">OFICIAL</span>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
        <span>Ecommerce MAKPC Oficial &bull; Envíos a todo el Perú & Retiro gratuito en tienda</span>
      </span>
      <span class="cb-topbar-item" style="opacity:0.85;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
        <span>Garantía Oficial 12 a 36 meses & Soporte Especializado</span>
      </span>
    </div>
    <div class="cb-topbar-right">
      <a href="../landing/" class="cb-topbar-link" style="color:var(--cb-gold);font-weight:700;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
        <span>🏢 Conoce MAK-PC (Empresa)</span>
      </a>
      <a href="https://wa.me/51975513327?text=Hola%20Ecommerce%20MAKPC,%20deseo%20asesoria%20tecnica" target="_blank" class="cb-topbar-link">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
        <span>Asesoría WhatsApp: +51 975 513 327</span>
      </a>
      <a href="/MAKPC-ECOMMERCE/MAK-PC-CLIENTES/" class="cb-topbar-link">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <span>Rastrear Orden</span>
      </a>
    </div>
  </div>
</div>

<!-- 2. CABECERA PRINCIPAL EMPRESARIAL (MAIN HEADER) -->
<header class="cb-main-header">
  <div class="cb-header-inner">
    
    <!-- Logo Oficial MAKPC con colores corporativos -->
    <a href="<?= url() ?>" class="cb-brand" title="Ecommerce MAKPC - Tienda Oficial">
      <img src="<?= asset('img/logo.png') ?>" alt="Logo Ecommerce MAKPC">
      <div class="cb-brand-text">
        <div class="cb-brand-name">
          Ecommerce <span class="accent">MAKPC</span>
        </div>
        <span class="cb-brand-sub">TIENDA ONLINE OFICIAL</span>
      </div>
    </a>

    <!-- Barra de Búsqueda Omnipresente (Coolbox Style) -->
    <div class="cb-search-wrapper">
      <form action="<?= url() ?>" method="GET" class="cb-search-form">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--cb-text-muted)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:0.35rem;flex-shrink:0;">
          <circle cx="11" cy="11" r="8"></circle>
          <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <input 
          type="text" 
          name="q" 
          id="headerSearchInput" 
          class="cb-search-input" 
          placeholder="Buscar procesadores, laptops gamer, monitores, tarjetas de video..." 
          value="<?= e($_GET['q'] ?? '') ?>"
          autocomplete="off"
        >
        <button type="submit" class="cb-search-btn">
          Buscar
        </button>
      </form>
    </div>

    <!-- Acciones de Cabecera (Derecha) -->
    <div class="cb-actions">
      
      <!-- BOTÓN ESTRELLA: CREA TU PC -->
      <a href="<?= url('tienda/crear-pc') ?>" class="cb-btn-builder" title="Arma tu PC con asesoría anti cuello de botella">
        <span class="pulse-dot"></span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"></path><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-4 11a22.35 22.35 0 0 1-4 2z"></path><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"></path><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"></path></svg>
        <span>Crea tu PC</span>
      </a>

      <!-- Rastrear Orden / Soporte -->
      <a href="/MAKPC-ECOMMERCE/MAK-PC-CLIENTES/" class="cb-action-btn" title="Consultar avance de reparación">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
        <span style="display:inline-block;line-height:1.2;">
          <small style="display:block;font-size:0.68rem;opacity:0.75;text-transform:uppercase;">Servicio</small>
          Taller
        </span>
      </a>

      <!-- Mi Cuenta / Iniciar Sesión -->
      <?php if (isLoggedIn()): ?>
        <a href="<?= url('panel') ?>" class="cb-action-btn" title="Ir al Panel de Control de Taller">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
          <span style="display:inline-block;line-height:1.2;">
            <small style="display:block;font-size:0.68rem;color:var(--cb-gold);text-transform:uppercase;"><?= e(auth('rol')) ?></small>
            Panel
          </span>
        </a>
      <?php else: ?>
        <a href="<?= url('login') ?>" class="cb-action-btn" title="Acceso al personal / clientes">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          <span style="display:inline-block;line-height:1.2;">
            <small style="display:block;font-size:0.68rem;opacity:0.75;">Bienvenido</small>
            Ingresar
          </span>
        </a>
      <?php endif; ?>

      <!-- Carrito de Compras Deslizable -->
      <button type="button" class="cb-cart-btn" id="headerCartBtn" title="Ver carrito de compras">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="9" cy="21" r="1"></circle>
          <circle cx="20" cy="21" r="1"></circle>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
        </svg>
        <span>Carrito</span>
        <span class="cb-cart-count" id="headerCartCounter">0</span>
      </button>

      <!-- Toggle Menú Móvil -->
      <button class="public-mobile-toggle" id="publicMenuToggle" aria-label="Abrir menú de navegación móvil" style="margin-left:0.25rem;">
        <span></span>
        <span></span>
        <span></span>
      </button>
    </div>

  </div>
</header>

<!-- 3. CINTA DE NAVEGACIÓN E-COMMERCE CON MEGA-MENÚ (Sin duplicar categorías) -->
<nav class="cb-subnav" aria-label="Navegación de tienda">
  <div class="cb-subnav-inner">
    
    <!-- Mega Menú Dropdown Trigger -->
    <div class="cb-megamenu-container">
      <button type="button" class="cb-btn-departments" id="btnDepartmentsToggle" aria-expanded="false">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="3" y1="12" x2="21" y2="12"></line>
          <line x1="3" y1="6" x2="21" y2="6"></line>
          <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
        <span>Todas las Categorías</span>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-left:auto;">
          <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
      </button>

      <!-- Mega Menú Desplegable Flotante -->
      <div class="cb-megamenu-dropdown" id="megaMenuDropdown">
        <div class="cb-megamenu-grid">
          
          <?php 
          if (!class_exists('Categoria')) {
              require_once __DIR__ . '/../models/Categoria.php';
          }
          $categorias_nav = (new Categoria())->activas();
          foreach ($categorias_nav as $cat): 
          ?>
          <div class="cb-megamenu-col">
            <h4 class="cb-megamenu-heading">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--cb-cyan)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
              <?= e($cat['nombre']) ?>
            </h4>
            <ul class="cb-megamenu-links">
              <li><a href="<?= url('tienda?cat=' . $cat['slug']) ?>">Ver todos los productos en <?= e($cat['nombre']) ?></a></li>
            </ul>
          </div>
          <?php endforeach; ?>

          <!-- Columna Promocional MegaMenu -->
          <div class="cb-megamenu-promo">
            <span class="cb-promo-tag">DESTACADO 2026</span>
            <h5>Armador de PC Inteligente</h5>
            <p>Selecciona tus partes sin miedo al cuello de botella con cálculo de vatios en vivo.</p>
            <a href="<?= url('tienda/crear-pc') ?>" class="cb-promo-btn">
              <span>Probar Estudio &rarr;</span>
            </a>
          </div>

        </div>
      </div>
    </div>

    <!-- Enlaces Comerciales Estratégicos (Sin duplicar categorías) -->
    <ul class="cb-cat-links">
      <li>
        <a href="<?= url('#ofertas-flash') ?>">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--cb-gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
          <span>Ofertas Flash 24H</span>
        </a>
      </li>
      <li>
        <a href="<?= url('tienda?orden=populares') ?>">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
          <span>Más Vendidos</span>
        </a>
      </li>
      <li>
        <a href="<?= url('#marcas-oficiales') ?>">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
          <span>Marcas Oficiales</span>
        </a>
      </li>
      <li>
        <a href="<?= url('landing') ?>" target="_blank" title="Portal Corporativo e Historia MAK-PC S.A.C.">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--cb-gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
          <span style="color:var(--cb-gold);font-weight:700;">Conócenos</span>
        </a>
      </li>
    </ul>

  </div>
</nav>

<!-- 4. MENÚ LATERAL MÓVIL (MOBILE DRAWER) -->
<div class="public-mobile-drawer" id="publicMobileDrawer">
  <div class="mobile-drawer-header">
    <div class="mobile-drawer-brand">
      <img src="<?= asset('img/logo.png') ?>" alt="Logo Ecommerce MAKPC">
      <span>Ecommerce <span style="color:var(--cb-gold)">MAKPC</span></span>
    </div>
    <button class="mobile-drawer-close" id="publicDrawerClose" aria-label="Cerrar menú">&times;</button>
  </div>
  
  <div class="mobile-drawer-search">
    <form action="<?= url() ?>" method="GET">
      <input type="text" name="q" placeholder="Buscar productos, marcas..." value="<?= e($_GET['q'] ?? '') ?>">
      <button type="submit" aria-label="Buscar">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
      </button>
    </form>
  </div>

  <ul class="mobile-drawer-links">
    <li>
      <a href="<?= url('tienda/crear-pc') ?>" style="color:var(--cb-gold);font-weight:800;background:rgba(252,200,39,0.1);">
        Crea tu PC (Armador Inteligente) &rarr;
      </a>
    </li>
    <li><a href="<?= url() ?>">Inicio</a></li>
    
    <?php 
    if (!isset($categorias_nav) && class_exists('Categoria')) {
        $categorias_nav = (new Categoria())->activas();
    }
    if (isset($categorias_nav)):
        foreach ($categorias_nav as $cat): 
    ?>
    <li><a href="<?= url('tienda?cat=' . $cat['slug']) ?>"><?= e($cat['nombre']) ?></a></li>
    <?php 
        endforeach; 
    endif;
    ?>
    
    <li><a href="/MAKPC-ECOMMERCE/MAK-PC-CLIENTES/">Servicio Técnico y Taller</a></li>
    <li><a href="<?= url('landing') ?>" target="_blank" style="color:var(--cb-cyan);font-weight:700;">Conócenos (Portal Institucional) &rarr;</a></li>
  </ul>

  <div class="mobile-drawer-actions">
    <?php if (isLoggedIn()): ?>
      <div style="font-size:0.85rem;color:var(--cb-gold);padding:0.25rem 0.5rem;font-weight:700;">
        Conectado: <?= e(auth('nombre_completo')) ?> (<?= strtoupper(e(auth('rol'))) ?>)
      </div>
      <a href="<?= url('panel') ?>" class="btn-drawer-outline">
        Ir al Panel de Control (<?= ucfirst(e(auth('rol'))) ?>)
      </a>
      <a href="<?= url('logout') ?>" class="btn-drawer-outline btn-drawer-danger">
        Cerrar Sesión
      </a>
    <?php else: ?>
      <a href="<?= url('login') ?>" class="btn-drawer-primary">
        Iniciar Sesión / Personal
      </a>
      <a href="/MAKPC-ECOMMERCE/MAK-PC-CLIENTES/" class="btn-drawer-outline">
        Rastrear Orden de Taller
      </a>
    <?php endif; ?>
  </div>
</div>
<div class="mobile-drawer-overlay" id="publicDrawerOverlay"></div>

<main class="public-main-container">
