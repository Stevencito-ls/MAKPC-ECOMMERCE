<?php
/**
 * MAKPC - Ficha de Producto 100% E-Commerce (Estilo Coolbox.pe)
 * @var array $producto
 * @var array $relacionados
 */
$precio = (float)$producto['precio'];
$precioAnt = !empty($producto['precio_anterior']) ? (float)$producto['precio_anterior'] : 0;
$ahorro = ($precioAnt > $precio) ? ($precioAnt - $precio) : 0;
$cuotas12 = $precio / 12;
$imgFile = $producto['imagen'] ?: 'prod_1.jpg';

$whatsappMsg = "Hola MAKPC, estoy interesado en comprar el producto: " . $producto['nombre'] . " (Precio: S/ " . number_format($precio, 2) . ") disponible en su tienda web.";
$installMsg = "Hola MAKPC, deseo cotizar la compra e instalación en taller del producto: " . $producto['nombre'];
?>

<div style="max-width:1360px;margin:1.5rem auto 3.5rem;padding:0 1.5rem;">
  
  <!-- BREADCRUMB E-COMMERCE CON SVG -->
  <nav aria-label="Breadcrumb" style="display:flex;align-items:center;gap:0.5rem;font-size:0.85rem;color:var(--cb-text-muted);margin-bottom:1.75rem;flex-wrap:wrap;">
    <a href="<?= url() ?>" style="color:var(--cb-navy);text-decoration:none;font-weight:600;">Inicio</a>
    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
    <a href="<?= url('tienda') ?>" style="color:var(--cb-navy);text-decoration:none;font-weight:600;">Catálogo</a>
    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
    <?php if (!empty($producto['categoria_nombre'])): ?>
      <a href="<?= url('tienda?cat=' . ($producto['categoria_slug'] ?? '')) ?>" style="color:var(--cb-navy);text-decoration:none;font-weight:600;"><?= e($producto['categoria_nombre']) ?></a>
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
    <?php endif; ?>
    <span style="color:var(--cb-cyan);font-weight:700;"><?= e($producto['nombre']) ?></span>
  </nav>

  <!-- FICHA DE PRODUCTO PRINCIPAL (GRID 2 COLUMNAS) -->
  <div style="background:#FFFFFF;border-radius:var(--cb-radius-lg);border:1px solid var(--cb-border);box-shadow:var(--cb-shadow-sm);overflow:hidden;padding:2.5rem;margin-bottom:2.5rem;">
    <div style="display:grid;grid-template-columns:1fr 1.1fr;gap:3rem;align-items:start;">
      
      <!-- COLUMNA IZQUIERDA: IMAGEN REAL DEL PRODUCTO Y GARANTÍAS -->
      <div>
        <div style="background:#F8FAFC;border:1px solid #EDF2F7;border-radius:var(--cb-radius);padding:2.5rem;text-align:center;min-height:380px;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;">
          <?php if (!empty($producto['etiqueta'])): ?>
            <span class="cb-card-badge discount" style="top:15px;left:15px;font-size:0.8rem;padding:0.35rem 0.75rem;">
              <?= e($producto['etiqueta']) ?>
            </span>
          <?php endif; ?>

          <img 
            src="<?= asset('img/productos/' . $imgFile) ?>" 
            alt="<?= e($producto['nombre']) ?>" 
            id="mainProductImage"
            style="max-width:100%;max-height:340px;object-fit:contain;transition:transform 0.3s ease;"
          >
        </div>

        <!-- Badges de Confianza bajo la Imagen -->
        <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:0.75rem;margin-top:1.25rem;text-align:center;">
          <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:var(--cb-radius-sm);padding:0.75rem 0.5rem;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--cb-cyan)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 0.25rem;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
            <div style="font-size:0.75rem;font-weight:700;color:var(--cb-navy);">Garantía Oficial</div>
            <div style="font-size:0.68rem;color:var(--cb-text-muted);">12 a 36 meses</div>
          </div>

          <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:var(--cb-radius-sm);padding:0.75rem 0.5rem;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--cb-cyan)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 0.25rem;"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
            <div style="font-size:0.75rem;font-weight:700;color:var(--cb-navy);">Envíos a Todo el Perú</div>
            <div style="font-size:0.68rem;color:var(--cb-text-muted);">Tumbes 24h / Nacional</div>
          </div>

          <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:var(--cb-radius-sm);padding:0.75rem 0.5rem;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--cb-cyan)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 0.25rem;"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
            <div style="font-size:0.75rem;font-weight:700;color:var(--cb-navy);">Taller y Laboratorio</div>
            <div style="font-size:0.68rem;color:var(--cb-text-muted);">Soporte & Instalación</div>
          </div>
        </div>
      </div>

      <!-- COLUMNA DERECHA: INFORMACIÓN COMERCIAL & BUY BOX -->
      <div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.5rem;">
          <span style="font-size:0.78rem;text-transform:uppercase;color:var(--cb-cyan);font-weight:800;letter-spacing:1px;">
            <?= e($producto['marca'] ?: 'MAKPC Original') ?> &bull; SKU: MAK-<?= str_pad((string)$producto['id_producto'], 5, '0', STR_PAD_LEFT) ?>
          </span>
          <span class="cb-free-shipping-tag">Envío Express Tumbes</span>
        </div>

        <h1 style="font-family:var(--cb-font-display);font-size:1.85rem;font-weight:900;color:var(--cb-navy);line-height:1.25;margin:0 0 0.85rem;">
          <?= e($producto['nombre']) ?>
        </h1>

        <!-- Rating con estrellas SVG -->
        <div style="display:flex;align-items:center;gap:0.6rem;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid #F1F5F9;">
          <div style="display:flex;gap:2px;color:#F59E0B;">
            <?php for ($i = 0; $i < 5; $i++): ?>
              <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
            <?php endfor; ?>
          </div>
          <span style="font-size:0.85rem;font-weight:700;color:var(--cb-navy);">4.9</span>
          <span style="font-size:0.82rem;color:var(--cb-text-muted);">(<?= (int)($producto['num_resenas'] ?: 24) ?> calificaciones de clientes)</span>
        </div>

        <!-- CAJA DE PRECIOS & FINANCIAMIENTO -->
        <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:var(--cb-radius);padding:1.25rem 1.5rem;margin-bottom:1.5rem;">
          <div style="display:flex;align-items:baseline;gap:0.75rem;margin-bottom:0.35rem;">
            <div style="font-family:var(--cb-font-display);font-size:2.2rem;font-weight:900;color:var(--cb-navy);">
              <span style="font-size:1.2rem;color:var(--cb-cyan);font-weight:700;">S/</span> <?= number_format($precio, 2) ?>
            </div>
            <?php if ($precioAnt > $precio): ?>
              <span style="font-size:1.1rem;color:#94A3B8;text-decoration:line-through;">
                S/ <?= number_format($precioAnt, 2) ?>
              </span>
              <span style="background:#DC2626;color:#fff;font-size:0.75rem;font-weight:800;padding:0.2rem 0.5rem;border-radius:4px;">
                Ahorras S/ <?= number_format($ahorro, 2) ?>
              </span>
            <?php endif; ?>
          </div>

          <div style="font-size:0.85rem;color:#475569;margin-top:0.25rem;">
            O paga en hasta 12 cuotas sin intereses de <strong>S/ <?= number_format($cuotas12, 2) ?></strong> con BBVA, BCP e Interbank
          </div>

          <div style="margin-top:0.75rem;padding-top:0.75rem;border-top:1px solid #E2E8F0;display:flex;align-items:center;gap:0.5rem;font-size:0.82rem;font-weight:700;color:var(--cb-success);">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span>Stock disponible: <?= (int)$producto['stock'] ?> unidades para despacho inmediato</span>
          </div>
        </div>

        <!-- BOTONES DE COMPRA Y SERVICIO TÉCNICO -->
        <div style="display:flex;flex-direction:column;gap:0.75rem;margin-bottom:1.75rem;">
          
          <div style="display:grid;grid-template-columns:1fr auto;gap:0.75rem;">
            <button 
              type="button" 
              class="cb-btn-hero-primary btn-add-cart-action" 
              style="width:100%;justify-content:center;padding:0.9rem 1.5rem;font-size:1rem;"
              data-id="<?= e($producto['id_producto']) ?>"
              data-name="<?= e($producto['nombre']) ?>"
              data-price="<?= e($producto['precio']) ?>"
              data-img="<?= asset('img/productos/' . $imgFile) ?>"
            >
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
              <span>Añadir al Carrito de Compras</span>
            </button>

            <button 
              type="button" 
              class="cb-btn-wishlist" 
              style="position:static;width:48px;height:48px;border:1px solid var(--cb-border);" 
              title="Guardar en lista de deseos"
              onclick="toggleWishlist(<?= e($producto['id_producto']) ?>, this)"
            >
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
            </button>
          </div>

          <a 
            href="https://wa.me/51975513327?text=<?= urlencode($whatsappMsg) ?>" 
            target="_blank" 
            class="cb-btn-hero-outline"
            style="width:100%;justify-content:center;background:#25D366;border-color:#25D366;color:#FFFFFF;padding:0.85rem 1.5rem;font-size:0.95rem;"
          >
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
            <span>Comprar Rápido por WhatsApp</span>
          </a>

          <a 
            href="https://wa.me/51975513327?text=<?= urlencode($installMsg) ?>" 
            target="_blank" 
            class="cb-btn-hero-outline"
            style="width:100%;justify-content:center;border-color:var(--cb-navy);color:var(--cb-navy);padding:0.75rem 1.5rem;font-size:0.88rem;"
          >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
            <span>Comprar con Instalación & Prueba de Estrés en Taller</span>
          </a>

        </div>

        <!-- Asesoría de Compatibilidad en Taller -->
        <div style="background:rgba(5,169,233,0.08);border:1px solid rgba(5,169,233,0.25);border-radius:var(--cb-radius-sm);padding:0.9rem 1.1rem;display:flex;align-items:flex-start;gap:0.6rem;font-size:0.82rem;color:var(--cb-navy);">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--cb-cyan)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
          <div>
            <strong>¿Tienes dudas de compatibilidad con tu equipo?</strong> Nuestros técnicos en taller verifican tu placa madre o fuente de poder sin costo adicional antes del despacho.
          </div>
        </div>

      </div>

    </div>

    <!-- PESTAÑAS DE ESPECIFICACIONES Y DETALLES -->
    <div style="margin-top:3rem;padding-top:2rem;border-top:1px solid #E2E8F0;">
      <h3 style="font-family:var(--cb-font-display);font-size:1.25rem;font-weight:800;color:var(--cb-navy);margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--cb-cyan)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
        Descripción y Especificaciones Técnicas:
      </h3>
      <div style="color:#475569;line-height:1.7;font-size:0.95rem;background:#F8FAFC;padding:1.5rem;border-radius:var(--cb-radius);border:1px solid #E2E8F0;">
        <?= nl2br(e($producto['descripcion'] ?: 'Hardware de alto rendimiento con certificación y respaldo técnico de MAKPC Enterprises S.A.C. Todos nuestros componentes pasan por control de calidad y cuentan con número de serie registrado para validación de garantía.')) ?>
      </div>
    </div>

  </div>

  <!-- PRODUCTOS RELACIONADOS Y RECOMENDADOS -->
  <?php if (!empty($relacionados)): ?>
    <div style="margin-top:2.5rem;">
      <div class="cb-section-header">
        <div>
          <h2 class="cb-section-title">Productos Relacionados & Recomendados</h2>
          <span style="font-size:0.85rem;color:var(--cb-text-muted);display:block;margin-top:2px;">
            Componentes y equipos compatibles para potenciar tu configuración
          </span>
        </div>
      </div>

      <div class="cb-deals-grid">
        <?php foreach ($relacionados as $rel): 
          $relImg = $rel['imagen'] ?: 'prod_1.jpg';
        ?>
          <article class="cb-product-card">
            <a href="<?= url('tienda/producto/' . $rel['slug']) ?>" class="cb-card-media-wrapper" title="<?= e($rel['nombre']) ?>">
              <img src="<?= asset('img/productos/' . $relImg) ?>" alt="<?= e($rel['nombre']) ?>" class="cb-card-real-img" loading="lazy">
            </a>

            <div class="cb-card-body">
              <span class="cb-card-brand"><?= e($rel['marca'] ?: 'MAKPC') ?></span>
              <h3 class="cb-card-title">
                <a href="<?= url('tienda/producto/' . $rel['slug']) ?>"><?= e($rel['nombre']) ?></a>
              </h3>

              <div class="cb-card-price-row">
                <div class="cb-card-price-current">
                  <span class="currency">S/</span> <?= number_format((float)$rel['precio'], 2) ?>
                </div>
              </div>

              <div class="cb-card-actions">
                <button 
                  type="button" 
                  class="cb-btn-add-cart btn-add-cart-action"
                  data-id="<?= e($rel['id_producto']) ?>"
                  data-name="<?= e($rel['nombre']) ?>"
                  data-price="<?= e($rel['precio']) ?>"
                  data-img="<?= asset('img/productos/' . $relImg) ?>"
                >
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                  <span>Añadir</span>
                </button>
                <a 
                  href="https://wa.me/51975513327?text=Hola%20MAKPC,%20estoy%20interesado%20en%20el%20producto:%20<?= urlencode($rel['nombre']) ?>" 
                  class="cb-btn-quick-wa" 
                  target="_blank" 
                  title="Consultar por WhatsApp"
                >
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                </a>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>

</div>
