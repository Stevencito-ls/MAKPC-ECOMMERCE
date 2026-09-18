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

        <!-- 4. Marcas Populares -->
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

                <a href="<?= url('tienda/producto/' . $p['slug']) ?>" class="kw-card-img-wrap" title="<?= e($p['nombre']) ?>">
                  <img src="<?= asset('img/productos/' . $imgFile) ?>" alt="<?= e($p['nombre']) ?>" class="kw-card-img" loading="lazy">
                </a>

                <div class="kw-card-body">
                  <span class="kw-card-brand"><?= e($p['marca'] ?: ($p['categoria_nombre'] ?? 'MAKPC')) ?></span>
                  <h3 class="kw-card-title">
                    <a href="<?= url('tienda/producto/' . $p['slug']) ?>" title="<?= e($p['nombre']) ?>">
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

<!-- ==============================================================================
     2. VENTANA FLOTANTE DE OFERTAS RELÁMPAGO 24H (MODAL CERRABLE & BOTÓN FLOTANTE)
     ============================================================================== -->
<div class="deals-modal-overlay" id="dealsModalOverlay" onclick="handleDealsBackdropClick(event)">
  <div class="deals-modal-card" id="dealsModalCard">
    
    <!-- Header del Modal -->
    <div class="deals-modal-header">
      <div class="deals-modal-title-box">
        <h3 class="deals-modal-title">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="#F59E0B" stroke="#D97706" stroke-width="1"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
          OFERTAS RELÁMPAGO 24 HORAS
        </h3>
        <span class="deals-modal-badge">Precios Cyber Online</span>
      </div>

      <!-- Temporizador Regresivo -->
      <div class="deals-modal-timer">
        <span>TERMINA EN:</span>
        <span class="deals-timer-box" id="flashTimerHours">08</span> :
        <span class="deals-timer-box" id="flashTimerMinutes">42</span> :
        <span class="deals-timer-box" id="flashTimerSeconds">19</span>
      </div>

      <!-- Botón de Cerrar Modal -->
      <button type="button" class="deals-modal-close" onclick="cerrarOfertasModal()" title="Cerrar ventana de ofertas" aria-label="Cerrar">&times;</button>
    </div>

    <!-- Cuerpo del Modal con 4 Productos en Oferta -->
    <div class="deals-modal-body">
      <div class="deals-modal-grid">
        <?php 
        $flashProducts = array_slice($productos, 0, 4);
        foreach ($flashProducts as $fp): 
          $imgFile = $fp['imagen'] ?: 'prod_1.jpg';
          $precioNum = (float)$fp['precio'];
        ?>
          <article class="cb-product-card" style="border-color:#FCD34D;">
            <span class="cb-card-badge discount" style="background:#DC2626;">-25% OFERTA</span>
            
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
                  <span>Vendidos: 15 / 20</span>
                  <span>¡Últimas 5 unidades!</span>
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
                <a 
                  href="https://wa.me/51975513327?text=Hola%20MAKPC,%20quiero%20la%20oferta%20flash:%20<?= urlencode($fp['nombre']) ?>%20(S/%20<?= number_format($precioNum, 2) ?>)" 
                  class="cb-btn-quick-wa" 
                  target="_blank" 
                  title="Comprar directo por WhatsApp"
                >
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                </a>
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
<button type="button" class="btn-flotante-ofertas" id="btnTriggerOfertas" onclick="abrirOfertasModal()" title="Ver Ofertas Relámpago 24H">
  <span class="flotante-pulse"></span>
  <svg width="18" height="18" viewBox="0 0 24 24" fill="#F59E0B" stroke="#D97706" stroke-width="1"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
  <span>Ofertas Flash 24H (-25%)</span>
</button>

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
        <a href="<?= url('tienda/crear-pc') ?>" class="cb-btn-hero-primary" style="font-size:0.92rem;padding:0.75rem 1.4rem;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"></path><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-4 11a22.35 22.35 0 0 1-4 2z"></path><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"></path><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"></path></svg>
          <span>Abrir Estudio Crea tu PC</span> &rarr;
        </a>
        <a href="<?= url('tienda/crear-pc#presets') ?>" class="cb-btn-hero-outline" style="font-size:0.92rem;padding:0.75rem 1.4rem;">
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
    let hours = 8, minutes = 42, seconds = 19;
    const hEl = document.getElementById('flashTimerHours');
    const mEl = document.getElementById('flashTimerMinutes');
    const sEl = document.getElementById('flashTimerSeconds');
    
    if (hEl && mEl && sEl) {
      setInterval(() => {
        seconds--;
        if (seconds < 0) {
          seconds = 59;
          minutes--;
          if (minutes < 0) {
            minutes = 59;
            hours--;
            if (hours < 0) hours = 12;
          }
        }
        hEl.textContent = String(hours).padStart(2, '0');
        mEl.textContent = String(minutes).padStart(2, '0');
        sEl.textContent = String(seconds).padStart(2, '0');
      }, 1000);
    }

    // Auto-apertura sutil solo en primera visita a tienda si no se está filtrando
    <?php if (!$isSearchOrFilter): ?>
      if (!sessionStorage.getItem('makpc_ofertas_dismissed')) {
        setTimeout(abrirOfertasModal, 2000);
      }
    <?php endif; ?>
  })();

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
