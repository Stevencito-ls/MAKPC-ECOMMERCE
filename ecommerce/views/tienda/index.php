<?php
/**
 * MAKPC - Portada E-Commerce Optimizada (Inspirada en Karus Web & Coolbox.pe)
 * Orden de Jerarquía UX:
 * 1. Explorar Catálogo (Píldoras, Filtros y Grilla de Productos al Frente)
 * 2. Ventana Flotante de Ofertas Relámpago 24H (Modal interactivo cerrable + Botón Flotante)
 * 3. Spotlight "Crea tu PC" (Armador inteligente sin cuello de botella)
 * 4. Marcas Oficiales, Testimonios y Franja de Garantías
 * 
 * @var array $productos
 * @var array $categorias
 * @var array $marcas
 * @var array $filtros
 */

$flash = getFlash();
if ($flash): ?>
  <div style="max-width:1380px;margin:1rem auto;padding:0 1.5rem;">
    <div class="alert alert-<?= e($flash['type']) ?>">
      <span><?= e($flash['message']) ?></span>
    </div>
  </div>
<?php endif;

$activeCat = $filtros['categoria'] ?? '';
$isSearchOrFilter = !empty($activeCat) || !empty($filtros['busqueda']) || !empty($filtros['marca']) || !empty($filtros['precio_min']) || !empty($filtros['disponibilidad']);
?>

<!-- ==============================================================================
     1. SECCIÓN PRINCIPAL: EXPLORAR CATÁLOGO (AL FRENTE DE LA TIENDA)
     ============================================================================== -->
<section class="kw-catalog-section" id="explorar-catalogo">
  
  <!-- Breadcrumbs -->
  <div class="kw-breadcrumbs">
    <a href="<?= url() ?>">Inicio</a>
    <span>/</span>
    <span><?= !empty($activeCat) ? ucfirst(e($activeCat)) : 'Catálogo Completo' ?></span>
  </div>

  <!-- Título Principal del Catálogo -->
  <h1 class="kw-catalog-title">Explorar Catálogo</h1>

  <!-- Píldoras de Categorías Horizontales con Iconos SVG Limpios -->
  <div class="kw-pills-scroll">
    <a href="<?= url('tienda') ?>" class="kw-pill <?= empty($activeCat) ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
      <span>Todos</span>
    </a>

    <?php 
    $catIcons = [
      'laptops' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="12" rx="2"></rect><line x1="2" y1="20" x2="22" y2="20"></line></svg>',
      'pcs-escritorio' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>',
      'componentes' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2"></rect><rect x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="14" x2="23" y2="14"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="14" x2="4" y2="14"></line></svg>',
      'monitores' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>',
      'teclados' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"></rect><line x1="6" y1="8" x2="6" y2="8"></line><line x1="10" y1="8" x2="10" y2="8"></line><line x1="14" y1="8" x2="14" y2="8"></line><line x1="18" y1="8" x2="18" y2="8"></line><line x1="6" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="18" y2="12"></line><line x1="7" y1="16" x2="17" y2="16"></line></svg>',
      'mouse' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="3" width="12" height="18" rx="6"></rect><line x1="12" y1="7" x2="12" y2="11"></line></svg>',
      'audio' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18v-6a9 9 0 0 1 18 0v6"></path><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path></svg>',
      'impresoras' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>',
      'redes' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.55a11 11 0 0 1 14.08 0"></path><path d="M1.42 9a16 16 0 0 1 21.16 0"></path><path d="M8.53 16.11a6 6 0 0 1 6.95 0"></path><line x1="12" y1="20" x2="12.01" y2="20"></line></svg>',
      'accesorios' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line></svg>'
    ];
    
    foreach ($categorias as $cat): 
      $icon = $catIcons[$cat['slug']] ?? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>';
    ?>
      <a href="<?= url('tienda?cat=' . $cat['slug']) ?>" class="kw-pill <?= ($activeCat === $cat['slug']) ? 'active' : '' ?>">
        <?= $icon ?>
        <span><?= e($cat['nombre']) ?></span>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- Formulario de Búsqueda, Filtros y Grilla -->
  <form action="<?= url('tienda') ?>" method="GET" id="catalogForm">
    <div class="kw-layout-grid">
      
      <!-- SIDEBAR DE FILTROS LATERAL -->
      <aside class="kw-sidebar">
        
        <!-- 1. Búsqueda de Productos -->
        <div class="kw-sidebar-group">
          <div class="kw-search-input-wrap">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input 
              type="text" 
              name="q" 
              class="kw-search-input" 
              value="<?= e($filtros['busqueda']) ?>" 
              placeholder="¿Qué producto buscas? (ej. RTX...)"
            >
          </div>
        </div>

        <?php if (!empty($activeCat)): ?>
          <input type="hidden" name="cat" value="<?= e($activeCat) ?>">
        <?php endif; ?>

        <!-- 2. Rango de Precio -->
        <div class="kw-sidebar-group">
          <div class="kw-sidebar-heading">
            <span>Precio</span>
            <small style="color:var(--cb-text-muted);font-size:0.75rem;">Soles (S/)</small>
          </div>
          <div class="kw-price-inputs">
            <div class="kw-price-field">
              <span>S/</span>
              <input type="number" name="min" value="<?= e($filtros['precio_min']) ?>" placeholder="min">
            </div>
            <span style="color:#94A3B8;">&mdash;</span>
            <div class="kw-price-field">
              <span>S/</span>
              <input type="number" name="max" value="<?= e($filtros['precio_max']) ?>" placeholder="max">
            </div>
            <button type="submit" class="kw-price-btn" title="Filtrar por precio">&rarr;</button>
          </div>
        </div>

        <!-- 3. Disponibilidad y Stock -->
        <div class="kw-sidebar-group">
          <div class="kw-sidebar-heading">Disponibilidad</div>
          <div class="kw-check-list">
            <label class="kw-check-label">
              <span>
                <input type="radio" name="stock" value="" <?= empty($filtros['disponibilidad']) ? 'checked' : '' ?> onchange="this.form.submit()">
                Todos los productos
              </span>
            </label>
            <label class="kw-check-label">
              <span>
                <input type="radio" name="stock" value="stock" <?= ($filtros['disponibilidad'] === 'stock') ? 'checked' : '' ?> onchange="this.form.submit()">
                En Stock (Entrega Inmediata)
              </span>
            </label>
            <label class="kw-check-label">
              <span>
                <input type="radio" name="stock" value="oferta" <?= ($filtros['disponibilidad'] === 'oferta') ? 'checked' : '' ?> onchange="this.form.submit()">
                En Oferta / Cyber Deals
              </span>
            </label>
          </div>
        </div>

        <!-- 4. Período de Garantía -->
        <div class="kw-sidebar-group">
          <div class="kw-sidebar-heading">Garantía del Producto</div>
          <div class="kw-check-list">
            <label class="kw-check-label">
              <span>
                <input type="radio" name="garantia" value="" <?= empty($filtros['garantia']) ? 'checked' : '' ?> onchange="this.form.submit()">
                Cualquier periodo
              </span>
            </label>
            <label class="kw-check-label">
              <span>
                <input type="radio" name="garantia" value="365" <?= (isset($filtros['garantia']) && $filtros['garantia'] === '365') ? 'checked' : '' ?> onchange="this.form.submit()">
                Garantía Extendida (1 Año a +)
              </span>
            </label>
            <label class="kw-check-label">
              <span>
                <input type="radio" name="garantia" value="180" <?= (isset($filtros['garantia']) && $filtros['garantia'] === '180') ? 'checked' : '' ?> onchange="this.form.submit()">
                Garantía Media (6 meses)
              </span>
            </label>
            <label class="kw-check-label">
              <span>
                <input type="radio" name="garantia" value="90" <?= (isset($filtros['garantia']) && $filtros['garantia'] === '90') ? 'checked' : '' ?> onchange="this.form.submit()">
                Garantía Estándar (90 días)
              </span>
            </label>
          </div>
        </div>

        <!-- 5. Marcas Populares -->
        <div class="kw-sidebar-group">
          <div class="kw-sidebar-heading">Marcas Populares</div>
          <div class="kw-check-list">
            <label class="kw-check-label">
              <span>
                <input type="radio" name="marca" value="" <?= empty($filtros['marca']) ? 'checked' : '' ?> onchange="this.form.submit()">
                Todas las marcas
              </span>
            </label>
            <?php foreach ($marcas as $m): ?>
              <label class="kw-check-label">
                <span>
                  <input type="radio" name="marca" value="<?= e($m['marca']) ?>" <?= ($filtros['marca'] === $m['marca']) ? 'checked' : '' ?> onchange="this.form.submit()">
                  <?= e($m['marca']) ?>
                </span>
                <span class="count">(<?= (int)$m['total'] ?>)</span>
              </label>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- 5. Reset de Filtros -->
        <?php if ($isSearchOrFilter): ?>
          <div class="kw-sidebar-group">
            <a href="<?= url('tienda') ?>" class="kw-btn-reset-filters">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
              Limpiar filtros aplicados
            </a>
          </div>
        <?php endif; ?>

      </aside>

      <!-- COLUMNA PRINCIPAL DE PRODUCTOS -->
      <main class="kw-products-main">
        
        <!-- Barra de Resultados y Ordenamiento -->
        <div class="kw-results-bar">
          <div class="kw-results-count">
            Mostrando <strong><?= count($productos) ?></strong> <?= count($productos) === 1 ? 'producto' : 'resultados' ?>
            <?php if (!empty($filtros['busqueda'])): ?>
              <span style="font-size:0.85rem;color:var(--cb-text-muted);font-weight:400;margin-left:0.4rem;">
                para: <strong>"<?= e($filtros['busqueda']) ?>"</strong>
              </span>
            <?php endif; ?>
          </div>

          <div class="kw-sort-control">
            <label for="sortSelect">Ordenar por:</label>
            <select id="sortSelect" name="orden" class="kw-sort-select" onchange="this.form.submit()">
              <option value="relevancia" <?= ($filtros['orden'] === 'relevancia') ? 'selected' : '' ?>>Recomendados</option>
              <option value="precio_asc" <?= ($filtros['orden'] === 'precio_asc') ? 'selected' : '' ?>>Precio: menor a mayor</option>
              <option value="precio_desc" <?= ($filtros['orden'] === 'precio_desc') ? 'selected' : '' ?>>Precio: mayor a menor</option>
              <option value="vendidos" <?= ($filtros['orden'] === 'vendidos') ? 'selected' : '' ?>>Más Vendidos</option>
              <option value="nuevos" <?= ($filtros['orden'] === 'nuevos') ? 'selected' : '' ?>>Novedades</option>
            </select>
          </div>
        </div>

        <!-- Grilla Karus Web de Tarjetas de Producto -->
        <?php if (!empty($productos)): ?>
          <div class="kw-products-grid">
            <?php foreach ($productos as $p): 
              $badge = $p['etiqueta'] ?? '';
              $badgeText = '';
              $badgeClass = '';

              if (strtolower($badge) === 'hot' || (int)($p['veces_vendido'] ?? 0) >= 15) {
                $badgeText = '★ MÁS VENDIDO';
                $badgeClass = 'kw-badge-hot';
              } elseif (!empty($p['precio_anterior']) && (float)$p['precio_anterior'] > (float)$p['precio']) {
                $badgeText = (!empty($badge) && $badge !== 'Oferta') ? '🔥 ' . e($badge) : '🔥 OFERTA CYBER';
                $badgeClass = 'kw-badge-offer';
              } elseif (strtolower($badge) === 'nuevo') {
                $badgeText = '✨ NUEVO INGRESO';
                $badgeClass = 'kw-badge-new';
              } elseif ((int)$p['stock'] > 0) {
                $badgeText = '⚡ ENTREGA INMEDIATA';
                $badgeClass = 'kw-badge-stock';
              }

              $imgFile = $p['imagen'] ?: 'prod_1.jpg';
              $precioNum = (float)$p['precio'];
              $stockNum = (int)$p['stock'];
            ?>
              <article class="kw-card">
                <?php if (!empty($badgeText)): ?>
                  <span class="kw-card-badge <?= $badgeClass ?>"><?= $badgeText ?></span>
                <?php endif; ?>

                <?php
                  $productData = json_encode([
                      'id' => $p['id_producto'],
                      'nombre' => $p['nombre'],
                      'precio' => $p['precio'],
                      'precio_anterior' => $p['precio_anterior'],
                      'marca' => $p['marca'] ?: ($p['categoria_nombre'] ?? 'MAKPC'),
                      'descripcion' => $p['descripcion'] ?? 'Sin descripción detallada.',
                      'stock' => $stockNum,
                      'imagen' => asset('img/productos/' . $imgFile),
                      'url' => url('tienda/producto/' . $p['slug'])
                  ], JSON_HEX_APOS | JSON_HEX_QUOT);
                ?>
                <a href="<?= url('tienda/producto/' . $p['slug']) ?>" 
                   onclick="event.preventDefault(); openQuickView(this);"
                   data-product="<?= htmlspecialchars($productData, ENT_QUOTES, 'UTF-8') ?>"
                   class="kw-card-img-wrap" title="<?= e($p['nombre']) ?>">
                  <img src="<?= asset('img/productos/' . $imgFile) ?>" alt="<?= e($p['nombre']) ?>" class="kw-card-img" loading="lazy">
                </a>

                <div class="kw-card-body">
                  <span class="kw-card-brand"><?= e($p['marca'] ?: ($p['categoria_nombre'] ?? 'MAKPC')) ?></span>
                  <h3 class="kw-card-title">
                    <a href="<?= url('tienda/producto/' . $p['slug']) ?>"
                       onclick="event.preventDefault(); openQuickView(this);"
                       data-product="<?= htmlspecialchars($productData, ENT_QUOTES, 'UTF-8') ?>"
                       title="<?= e($p['nombre']) ?>">
                      <?= e($p['nombre']) ?>
                    </a>
                  </h3>

                  <div class="kw-card-stock <?= ($stockNum <= 5 && $stockNum > 0) ? 'low' : '' ?>">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="8"></circle></svg>
                    <span><?= $stockNum > 0 ? "En Stock: {$stockNum} unid." : 'Agotado temporalmente' ?></span>
                  </div>

                  <div class="kw-card-price-row">
                    <span class="kw-price-current">S/. <?= number_format($precioNum, 2) ?></span>
                    <?php if (!empty($p['precio_anterior']) && (float)$p['precio_anterior'] > $precioNum): ?>
                      <span class="kw-price-old">S/. <?= number_format((float)$p['precio_anterior'], 2) ?></span>
                    <?php endif; ?>
                  </div>

                  <button 
                    type="button" 
                    class="kw-btn-add btn-add-cart-action"
                    data-id="<?= e($p['id_producto']) ?>"
                    data-name="<?= e($p['nombre']) ?>"
                    data-price="<?= e($p['precio']) ?>"
                    data-img="<?= asset('img/productos/' . $imgFile) ?>"
                  >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    <span>Añadir al carrito</span>
                  </button>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div style="background:#FFFFFF;border-radius:var(--cb-radius);padding:4rem 2rem;text-align:center;border:1px solid #E2E8F0;">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 1rem;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <h3 style="font-family:var(--cb-font-display);font-size:1.3rem;font-weight:800;color:var(--cb-navy);">
              No encontramos productos con los filtros seleccionados
            </h3>
            <p style="color:var(--cb-text-muted);font-size:0.9rem;max-width:500px;margin:0.5rem auto 1.5rem;">
              Intente ajustando el rango de precios, la marca o explore nuestras categorías destacadas.
            </p>
            <a href="<?= url('tienda') ?>" class="cb-btn-hero-primary" style="display:inline-flex;font-size:0.9rem;padding:0.6rem 1.4rem;">
              Ver catálogo completo &rarr;
            </a>
          </div>
        <?php endif; ?>

      </main>
    </div>
  </form>

</section>

<?php 
$flashProducts = [];
if (!empty($ofertasActivas) && !empty($productos)) {
    $flashProducts = array_filter($productos, function($p) {
        return !empty($p['precio_anterior']) && (float)$p['precio_anterior'] > (float)$p['precio'];
    });
    $flashProducts = array_slice($flashProducts, 0, 4);
}
?>

<?php if (!empty($ofertasActivas) && !empty($flashProducts)): ?>
<!-- ==============================================================================
     2. VENTANA FLOTANTE DE OFERTAS RELÁMPAGO (MODAL CERRABLE & BOTÓN FLOTANTE)
     ============================================================================== -->
<div class="deals-modal-overlay" id="dealsModalOverlay" onclick="handleDealsBackdropClick(event)">
  <div class="deals-modal-card" id="dealsModalCard">
    
    <!-- Header del Modal -->
    <div class="deals-modal-header">
      <div class="deals-modal-title-box">
        <h3 class="deals-modal-title">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="#F59E0B" stroke="#D97706" stroke-width="1"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
          OFERTAS RELÁMPAGO
        </h3>
        <span class="deals-modal-badge">Precios Cyber Online</span>
      </div>

      <!-- Temporizador Regresivo -->
      <div class="deals-modal-timer">
        <span>TERMINA EN:</span>
        <span class="deals-timer-box" id="flashTimerHours">00</span> :
        <span class="deals-timer-box" id="flashTimerMinutes">00</span> :
        <span class="deals-timer-box" id="flashTimerSeconds">00</span>
      </div>

      <!-- Botón de Cerrar Modal -->
      <button type="button" class="deals-modal-close" onclick="cerrarOfertasModal()" title="Cerrar ventana de ofertas" aria-label="Cerrar">&times;</button>
    </div>

    <!-- Cuerpo del Modal con Productos en Oferta -->
    <div class="deals-modal-body">
      <div class="deals-modal-grid">
        <?php 
        foreach ($flashProducts as $fp): 
          $imgFile = $fp['imagen'] ?: 'prod_1.jpg';
          $precioNum = (float)$fp['precio'];
          // Calcular % descuento
          $descPorc = 0;
          if (!empty($fp['precio_anterior']) && (float)$fp['precio_anterior'] > 0) {
              $descPorc = round(((float)$fp['precio_anterior'] - $precioNum) / (float)$fp['precio_anterior'] * 100);
          }
        ?>
          <article class="cb-product-card" style="border-color:#FCD34D;">
            <?php if($descPorc > 0): ?>
            <span class="cb-card-badge discount" style="background:#DC2626;">-<?= $descPorc ?>% OFERTA</span>
            <?php endif; ?>
            
            <a href="<?= url('tienda/producto/' . $fp['slug']) ?>" class="cb-card-media-wrapper" title="<?= e($fp['nombre']) ?>">
              <img src="<?= asset('img/productos/' . $imgFile) ?>" alt="<?= e($fp['nombre']) ?>" class="cb-card-real-img" loading="lazy">
            </a>

            <div class="cb-card-body">
              <span class="cb-card-brand"><?= e($fp['marca'] ?: 'MAKPC') ?></span>
              <h3 class="cb-card-title">
                <a href="<?= url('tienda/producto/' . $fp['slug']) ?>"><?= e($fp['nombre']) ?></a>
              </h3>

              <div class="cb-stock-meter-wrap">
                <div class="cb-stock-meter-track">
                  <div class="cb-stock-meter-bar" style="width:75%;"></div>
                </div>
                <div class="cb-stock-meter-text">
                  <span>En Stock: <?= e($fp['stock']) ?></span>
                </div>
              </div>

              <div class="cb-card-price-row">
                <?php if (!empty($fp['precio_anterior'])): ?>
                  <span class="cb-card-price-old">S/ <?= number_format((float)$fp['precio_anterior'], 2) ?></span>
                <?php endif; ?>
                <div class="cb-card-price-current">
                  <span class="currency">S/</span> <?= number_format($precioNum, 2) ?>
                </div>
              </div>

              <div class="cb-card-actions" style="margin-top:0.75rem;">
                <button 
                  type="button" 
                  class="cb-btn-add-cart btn-add-cart-action"
                  data-id="<?= e($fp['id_producto']) ?>"
                  data-name="<?= e($fp['nombre']) ?>"
                  data-price="<?= e($fp['precio']) ?>"
                  data-img="<?= asset('img/productos/' . $imgFile) ?>"
                >
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                  <span>Añadir</span>
                </button>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Footer del Modal -->
    <div class="deals-modal-footer">
      <span style="font-size:0.82rem;color:var(--cb-text-muted);">
        * Ofertas por tiempo limitado sujetas a disponibilidad de existencias.
      </span>
      <button type="button" onclick="cerrarOfertasModal()" class="cb-btn-hero-primary" style="padding:0.5rem 1.25rem;font-size:0.88rem;">
        Seguir Explorando el Catálogo &rarr;
      </button>
    </div>

  </div>
</div>

<!-- Botón Flotante Permanente para Reabrir Ofertas -->
<button type="button" class="btn-flotante-ofertas" id="btnTriggerOfertas" onclick="abrirOfertasModal()" title="Ver Ofertas Relámpago">
  <span class="flotante-pulse"></span>
  <svg width="18" height="18" viewBox="0 0 24 24" fill="#F59E0B" stroke="#D97706" stroke-width="1"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
  <span>Ofertas Flash</span>
</button>
<?php endif; ?>

<!-- ==============================================================================
     3. SPOTLIGHT BANNER "CREA TU PC" (DEBAJO DEL CATÁLOGO)
     ============================================================================== -->
<section class="cb-pc-builder-spotlight" style="max-width:1380px;margin:0 auto 3rem;padding:0 1.5rem;">
  <div class="cb-builder-banner">
    <div>
      <div class="cb-builder-chips">
        <span class="cb-chip gold">TECNOLOGÍA EXCLUSIVA MAKPC</span>
        <span class="cb-chip cyan">0% CUELLO DE BOTELLA</span>
        <span class="cb-chip">COTIZACIÓN EN VIVO</span>
      </div>
      <h2 style="font-family:var(--cb-font-display);font-size:2rem;font-weight:900;line-height:1.2;margin-bottom:0.75rem;">
        ¿Deseas armar una computadora a tu medida?
      </h2>
      <p style="font-size:0.95rem;color:#CBD5E1;line-height:1.5;margin-bottom:1.5rem;">
        Nuestro configurador inteligente analiza compatibilidad de socket, consumo eléctrico y balance CPU/GPU en tiempo real para oficina, estudio, streaming o gaming competitivo.
      </p>
      <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
        <a href="<?= url('crear-pc') ?>" class="cb-btn-hero-primary" style="font-size:0.92rem;padding:0.75rem 1.4rem;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"></path><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-4 11a22.35 22.35 0 0 1-4 2z"></path><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"></path><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"></path></svg>
          <span>Abrir Estudio Crea tu PC</span> &rarr;
        </a>
        <a href="<?= url('crear-pc#presets') ?>" class="cb-btn-hero-outline" style="font-size:0.92rem;padding:0.75rem 1.4rem;">
          <span>Ver 4 Presets Listos</span>
        </a>
      </div>
    </div>

    <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);border-radius:var(--cb-radius);padding:1.5rem;">
      <div style="font-size:0.78rem;font-weight:800;letter-spacing:1px;color:var(--cb-gold);text-transform:uppercase;margin-bottom:0.75rem;">
        VENTAJAS DEL ARMADOR INTELIGENTE
      </div>
      <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:0.75rem;font-size:0.85rem;color:#E2E8F0;">
        <li style="display:flex;align-items:center;gap:0.5rem;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--cb-gold)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
          <span><strong>Detección de Socket:</strong> Bloquea combinaciones incompatibles (AM4, AM5, LGA1700).</span>
        </li>
        <li style="display:flex;align-items:center;gap:0.5rem;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--cb-gold)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
          <span><strong>Monitor Anti Cuello de Botella:</strong> Equilibrio CPU vs. GPU en tiempo real.</span>
        </li>
        <li style="display:flex;align-items:center;gap:0.5rem;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--cb-gold)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
          <span><strong>Cálculo de Watts Reales:</strong> Recomienda la fuente de poder con 25% de margen.</span>
        </li>
      </ul>
    </div>
  </div>
</section>

<!-- ==============================================================================
     4. MARCAS OFICIALES ALIADAS
     ============================================================================== -->
<section style="max-width:1380px;margin:0 auto 3rem;padding:0 1.5rem;" id="marcas-oficiales">
  <div class="cb-brands-section">
    <div style="text-align:center;margin-bottom:1.5rem;">
      <span style="font-size:0.75rem;font-weight:800;color:var(--cb-cyan);letter-spacing:1.5px;text-transform:uppercase;">DISTRIBUIDOR AUTORIZADO</span>
      <h3 style="font-family:var(--cb-font-display);font-size:1.4rem;font-weight:900;color:var(--cb-navy);margin:0.25rem 0 0;">
        Hardware Original con Garantía de Fábrica
      </h3>
    </div>

    <div class="cb-brands-grid">
      <a href="<?= url('tienda?marca=Intel') ?>" class="cb-brand-pill">INTEL</a>
      <a href="<?= url('tienda?marca=AMD') ?>" class="cb-brand-pill">AMD RYZEN</a>
      <a href="<?= url('tienda?marca=ASUS') ?>" class="cb-brand-pill">ASUS ROG</a>
      <a href="<?= url('tienda?marca=MSI') ?>" class="cb-brand-pill">MSI</a>
      <a href="<?= url('tienda?marca=Gigabyte') ?>" class="cb-brand-pill">GIGABYTE</a>
      <a href="<?= url('tienda?marca=Corsair') ?>" class="cb-brand-pill">CORSAIR</a>
      <a href="<?= url('tienda?marca=Kingston') ?>" class="cb-brand-pill">KINGSTON</a>
      <a href="<?= url('tienda?marca=Logitech') ?>" class="cb-brand-pill">LOGITECH</a>
      <a href="<?= url('tienda?marca=Samsung') ?>" class="cb-brand-pill">SAMSUNG</a>
      <a href="<?= url('tienda?marca=LG') ?>" class="cb-brand-pill">LG GAMING</a>
      <a href="<?= url('tienda?marca=HyperX') ?>" class="cb-brand-pill">HYPERX</a>
    </div>
  </div>
</section>

<!-- ==============================================================================
     5. TESTIMONIOS DE CLIENTES VERIFICADOS
     ============================================================================== -->
<section style="max-width:1380px;margin:0 auto 3rem;padding:0 1.5rem;">
  <div class="cb-reviews-section">
    <div class="cb-section-header">
      <div>
        <h2 class="cb-section-title">Opiniones de Clientes Verificados</h2>
        <span style="font-size:0.85rem;color:var(--cb-text-muted);display:block;margin-top:2px;">
          Más de 1,200 PCs ensambladas y enviadas a todo el Perú
        </span>
      </div>
    </div>

    <div class="cb-reviews-grid">
      <div class="cb-review-card">
        <div class="cb-review-stars">★★★★★</div>
        <p class="cb-review-text">
          "Armé mi PC con la asesoría anti cuello de botella. Llegó a Trujillo en 48 horas súper bien embalada y probada con benchmarks. Rinde perfecto en 1440p."
        </p>
        <div class="cb-review-author">
          <div>
            <strong>Carlos Mendoza P.</strong> &mdash; Trujillo
          </div>
          <span class="cb-review-verified">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            Compra verificada
          </span>
        </div>
      </div>

      <div class="cb-review-card">
        <div class="cb-review-stars">★★★★★</div>
        <p class="cb-review-text">
          "Excelente atención técnica. Compré la laptop gamer Lenovo y me ayudaron ampliando el SSD y la RAM en su taller físico antes del envío. Garantía total."
        </p>
        <div class="cb-review-author">
          <div>
            <strong>Valeria Rojas S.</strong> &mdash; Tumbes (Zorritos)
          </div>
          <span class="cb-review-verified">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            Compra verificada
          </span>
        </div>
      </div>

      <div class="cb-review-card">
        <div class="cb-review-stars">★★★★★</div>
        <p class="cb-review-text">
          "Tienen los mejores precios en componentes originales. Compré el monitor LG UltraGear y memoria RAM Corsair; llegaron con boleta electrónica y garantía."
        </p>
        <div class="cb-review-author">
          <div>
            <strong>Jorge Paredes T.</strong> &mdash; Arequipa
          </div>
          <span class="cb-review-verified">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            Compra verificada
          </span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ==============================================================================
     6. NEWSLETTER / CLUB MAKPC
     ============================================================================== -->
<section style="max-width:1380px;margin:0 auto 3rem;padding:0 1.5rem;">
  <div class="cb-newsletter-box">
    <div class="cb-newsletter-content">
      <span style="font-size:0.75rem;font-weight:800;color:var(--cb-gold);letter-spacing:1px;text-transform:uppercase;">ÚNETE AL CLUB MAKPC</span>
      <h3>Recibe S/ 30 de Descuento en tu Primera Compra</h3>
      <p>Suscríbete para recibir cupones exclusivos, alertas de ofertas relámpago y stock de hardware.</p>
    </div>

    <form class="cb-newsletter-form" onsubmit="event.preventDefault(); alert('¡Gracias por unirte al Club MAKPC! Te hemos enviado un cupón de S/ 30 a tu correo.');">
      <input type="email" class="cb-newsletter-input" placeholder="Ingresa tu correo electrónico..." required>
      <button type="submit" class="cb-newsletter-btn">Suscribirme</button>
    </form>
  </div>
</section>

<!-- ==============================================================================
     7. FRANJA DE BENEFICIOS Y GARANTÍAS
     ============================================================================== -->
<section class="cb-trust-strip">
  <div class="cb-trust-inner">
    <div class="cb-trust-card">
      <div class="cb-trust-icon">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--cb-gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
      </div>
      <div class="cb-trust-info">
        <h4>Garantía Oficial Local</h4>
        <p>Hasta 3 años de garantía en componentes y servicio técnico propio en Tumbes.</p>
      </div>
    </div>

    <div class="cb-trust-card theme-cyan">
      <div class="cb-trust-icon">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
      </div>
      <div class="cb-trust-info">
        <h4>Envíos a Todo el Perú</h4>
        <p>Despacho en 24h para Tumbes y cobertura nacional vía Olva Courier y Shalom.</p>
      </div>
    </div>

    <div class="cb-trust-card">
      <div class="cb-trust-icon">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--cb-gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
      </div>
      <div class="cb-trust-info">
        <h4>Cero Cuello de Botella</h4>
        <p>Asesoría técnica y balance de hardware testeado por ingenieros.</p>
      </div>
    </div>

    <div class="cb-trust-card theme-cyan">
      <div class="cb-trust-icon">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
      </div>
      <div class="cb-trust-info">
        <h4>Pagos 100% Seguros</h4>
        <p>Aceptamos tarjetas de crédito, hasta 12 cuotas, Yape, Plin y transferencia.</p>
      </div>
    </div>
  </div>
</section>

<!-- ==============================================================================
     QUICK VIEW MODAL (GLASSMORPHISM)
     ============================================================================== -->
<div class="quick-view-overlay" id="quickViewOverlay" onclick="handleQuickViewBackdropClick(event)">
  <div class="quick-view-card glass-modal" id="quickViewCard">
    <button type="button" class="quick-view-close" onclick="cerrarQuickView()" title="Cerrar">&times;</button>
    <div class="quick-view-content">
      <div class="qv-image-col">
        <img src="" id="qvImage" alt="Producto">
      </div>
      <div class="qv-details-col">
        <span class="qv-brand" id="qvBrand">Marca</span>
        <h2 class="qv-title" id="qvTitle">Nombre del Producto</h2>
        <div class="qv-price-row">
          <span class="qv-price-current" id="qvPriceCurrent">S/. 0.00</span>
          <span class="qv-price-old" id="qvPriceOld" style="display:none;">S/. 0.00</span>
        </div>
        <div class="qv-stock-badge" id="qvStock">En Stock</div>
        <div class="qv-description" id="qvDescription">Descripción aquí...</div>
        
        <div class="qv-actions">
          <button type="button" class="cb-btn-add-cart btn-add-cart-action" id="qvBtnAdd" style="flex:1;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:8px;"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
            Añadir al Carrito
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  /* Estilos Glassmorphism para Quick View Modal */
  .quick-view-overlay {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(4px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
  }
  .quick-view-overlay.active {
    opacity: 1;
    pointer-events: auto;
  }
  .glass-modal {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
  }
  .quick-view-card {
    position: relative;
    width: 90%;
    max-width: 800px;
    border-radius: 16px;
    padding: 2rem;
    transform: scale(0.95) translateY(20px);
    transition: transform 0.3s ease;
  }
  .quick-view-overlay.active .quick-view-card {
    transform: scale(1) translateY(0);
  }
  .quick-view-close {
    position: absolute;
    top: 15px; right: 15px;
    background: rgba(255,255,255,0.2);
    border: none;
    color: #fff;
    width: 32px; height: 32px;
    border-radius: 50%;
    font-size: 1.5rem;
    line-height: 1;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
  }
  .quick-view-close:hover {
    background: rgba(255,255,255,0.4);
  }
  .quick-view-content {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
  }
  .qv-image-col {
    flex: 1;
    min-width: 250px;
    background: #fff;
    border-radius: 12px;
    padding: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .qv-image-col img {
    max-width: 100%;
    max-height: 300px;
    object-fit: contain;
  }
  .qv-details-col {
    flex: 1.5;
    min-width: 300px;
    color: #fff;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }
  .qv-brand {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--cb-gold, #FCD34D);
    margin-bottom: 0.5rem;
    font-weight: 700;
  }
  .qv-title {
    font-size: 1.5rem;
    font-weight: 800;
    margin: 0 0 1rem;
    line-height: 1.2;
    color: #fff;
  }
  .qv-price-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
  }
  .qv-price-current {
    font-size: 1.8rem;
    font-weight: 900;
    color: #10B981; /* Verde esmeralda */
  }
  .qv-price-old {
    font-size: 1.1rem;
    text-decoration: line-through;
    color: #94A3B8;
  }
  .qv-stock-badge {
    display: inline-block;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
    margin-bottom: 1rem;
    background: rgba(16, 185, 129, 0.2);
    color: #34D399;
    border: 1px solid rgba(16, 185, 129, 0.4);
    align-self: flex-start;
  }
  .qv-stock-badge.low {
    background: rgba(239, 68, 68, 0.2);
    color: #F87171;
    border-color: rgba(239, 68, 68, 0.4);
  }
  .qv-description {
    font-size: 0.95rem;
    line-height: 1.5;
    color: #E2E8F0;
    margin-bottom: 1.5rem;
    max-height: 200px;
    overflow-y: auto;
    padding-right: 10px;
  }
  
  .qv-description::-webkit-scrollbar {
    width: 6px;
  }
  .qv-description::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.2);
    border-radius: 4px;
  }
  .qv-description ul, .qv-description ol {
    margin-left: 1.5rem;
    margin-bottom: 1rem;
  }
  .qv-description li {
    margin-bottom: 0.3rem;
  }
  .qv-actions {
    display: flex;
    gap: 1rem;
    margin-top: auto;
  }
  @media (max-width: 768px) {
    .quick-view-content {
      flex-direction: column;
    }
  }
</style>

<!-- ==============================================================================
     8. SCRIPTS DE CONTROL DEL MODAL Y TEMPORIZADOR
     ============================================================================== -->
<script>
  // Control de la Ventana Flotante de Ofertas Relámpago
  function abrirOfertasModal() {
    const modal = document.getElementById('dealsModalOverlay');
    if (modal) {
      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }
  }

  function cerrarOfertasModal() {
    const modal = document.getElementById('dealsModalOverlay');
    if (modal) {
      modal.classList.remove('active');
      document.body.style.overflow = '';
      sessionStorage.setItem('makpc_ofertas_dismissed', '1');
    }
  }

  function handleDealsBackdropClick(e) {
    if (e.target.id === 'dealsModalOverlay') {
      cerrarOfertasModal();
    }
  }

  // Cerrar con tecla Escape
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      cerrarOfertasModal();
    }
  });

  // Temporizador Flash Deals
  (function() {
    const countDownDate = <?php if(!empty($ofertasFin)): ?>new Date("<?= date('Y-m-d\TH:i:s', strtotime($ofertasFin)) ?>").getTime()<?php else: ?>0<?php endif; ?>;

    const hEl = document.getElementById('flashTimerHours');
    const mEl = document.getElementById('flashTimerMinutes');
    const sEl = document.getElementById('flashTimerSeconds');
    
    if (hEl && mEl && sEl && countDownDate > 0) {
      setInterval(() => {
        const now = new Date().getTime();
        const distance = countDownDate - now;

        if (distance < 0) {
          hEl.textContent = '00';
          mEl.textContent = '00';
          sEl.textContent = '00';
          return;
        }

        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        hEl.textContent = String(hours).padStart(2, '0');
        mEl.textContent = String(minutes).padStart(2, '0');
        sEl.textContent = String(seconds).padStart(2, '0');
      }, 1000);
    }

    // Auto-apertura sutil solo en primera visita a tienda si no se está filtrando
    <?php if (!$isSearchOrFilter && !empty($ofertasActivas) && !empty($flashProducts)): ?>
      if (!sessionStorage.getItem('makpc_ofertas_dismissed')) {
        setTimeout(abrirOfertasModal, 2000);
      }
    <?php endif; ?>
  })();

  // Control de Quick View
  function openQuickView(element) {
    const rawData = element.getAttribute('data-product');
    if (!rawData) return;
    const p = JSON.parse(rawData);

    document.getElementById('qvBrand').textContent = p.marca;
    document.getElementById('qvTitle').textContent = p.nombre;
    document.getElementById('qvDescription').innerHTML = p.descripcion;
    document.getElementById('qvImage').src = p.imagen;
    document.getElementById('qvImage').alt = p.nombre;

    // Precios
    const pCur = parseFloat(p.precio);
    const pOld = parseFloat(p.precio_anterior);
    document.getElementById('qvPriceCurrent').textContent = 'S/. ' + pCur.toFixed(2);
    if (!isNaN(pOld) && pOld > pCur) {
      document.getElementById('qvPriceOld').textContent = 'S/. ' + pOld.toFixed(2);
      document.getElementById('qvPriceOld').style.display = 'inline-block';
    } else {
      document.getElementById('qvPriceOld').style.display = 'none';
    }

    // Stock
    const stock = parseInt(p.stock, 10);
    const stockEl = document.getElementById('qvStock');
    if (stock > 5) {
      stockEl.textContent = 'En Stock: ' + stock + ' unidades';
      stockEl.className = 'qv-stock-badge';
    } else if (stock > 0) {
      stockEl.textContent = '¡Últimas ' + stock + ' unidades!';
      stockEl.className = 'qv-stock-badge low';
    } else {
      stockEl.textContent = 'Agotado Temporalmente';
      stockEl.className = 'qv-stock-badge low';
    }

    // Actualizar botón "Añadir"
    const btnAdd = document.getElementById('qvBtnAdd');
    btnAdd.setAttribute('data-id', p.id);
    btnAdd.setAttribute('data-name', p.nombre);
    btnAdd.setAttribute('data-price', p.precio);
    btnAdd.setAttribute('data-img', p.imagen);
    
    if (stock <= 0) {
      btnAdd.disabled = true;
      btnAdd.style.opacity = '0.5';
      btnAdd.style.cursor = 'not-allowed';
      btnAdd.innerHTML = 'Agotado';
    } else {
      btnAdd.disabled = false;
      btnAdd.style.opacity = '1';
      btnAdd.style.cursor = 'pointer';
      btnAdd.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:8px;"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> Añadir al Carrito`;
    }

    // Mostrar Modal
    const modal = document.getElementById('quickViewOverlay');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function cerrarQuickView() {
    const modal = document.getElementById('quickViewOverlay');
    if (modal) {
      modal.classList.remove('active');
      document.body.style.overflow = '';
    }
  }

  function handleQuickViewBackdropClick(e) {
    if (e.target.id === 'quickViewOverlay') {
      cerrarQuickView();
    }
  }

  // Cerrar con tecla Escape (ampliado)
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      cerrarOfertasModal();
      cerrarQuickView();
    }
  });

  // Toggle Wishlist
  function toggleWishlist(id, btn) {
    const isFavorited = btn.classList.toggle('active');
    btn.style.color = isFavorited ? '#EF4444' : '#94A3B8';
    btn.querySelector('svg').style.fill = isFavorited ? '#EF4444' : 'none';
    if (typeof showToast === 'function') {
      showToast(isFavorited ? 'Producto guardado en tus favoritos' : 'Producto removido de favoritos');
    }
  }
</script>

