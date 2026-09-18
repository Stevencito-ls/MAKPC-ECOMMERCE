<?php
/**
 * MAKPC - Vista Completa del Carrito de Compras 100% E-Commerce
 * @var array $relacionados
 */
?>

<div style="max-width:1360px;margin:1.5rem auto 4rem;padding:0 1.5rem;">
  
  <!-- BREADCRUMBS -->
  <nav aria-label="Breadcrumb" style="display:flex;align-items:center;gap:0.5rem;font-size:0.85rem;color:var(--cb-text-muted);margin-bottom:1.75rem;flex-wrap:wrap;">
    <a href="<?= url() ?>" style="color:var(--cb-navy);text-decoration:none;font-weight:600;">Inicio</a>
    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
    <a href="<?= url('tienda') ?>" style="color:var(--cb-navy);text-decoration:none;font-weight:600;">Tienda</a>
    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
    <span style="color:var(--cb-cyan);font-weight:700;">Mi Carrito de Compras</span>
  </nav>

  <!-- CABECERA DEL CARRITO -->
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;border-bottom:2px solid #F1F5F9;padding-bottom:1.25rem;">
    <div style="display:flex;align-items:center;gap:0.75rem;">
      <div style="background:var(--cb-navy);color:#FFFFFF;width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
      </div>
      <div>
        <h1 style="font-family:var(--cb-font-display);font-size:1.8rem;font-weight:900;color:var(--cb-navy);margin:0;line-height:1.2;">
          Bolsa de Compras
        </h1>
        <span style="font-size:0.85rem;color:var(--cb-text-muted);">
          Revisa tus artículos antes de confirmar tu pedido
        </span>
      </div>
    </div>

    <div style="display:flex;align-items:center;gap:0.75rem;">
      <a href="<?= url('tienda') ?>" style="display:inline-flex;align-items:center;gap:0.4rem;font-size:0.85rem;color:var(--cb-navy);font-weight:700;text-decoration:none;border:1px solid var(--cb-border);padding:0.5rem 1rem;border-radius:var(--cb-radius-sm);background:#FFFFFF;transition:all 0.2s ease;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        <span>Seguir Comprando</span>
      </a>
      <a href="<?= url('tienda/crear-pc') ?>" style="display:inline-flex;align-items:center;gap:0.4rem;font-size:0.85rem;color:var(--cb-navy);font-weight:800;text-decoration:none;border:1px solid var(--cb-gold);padding:0.5rem 1rem;border-radius:var(--cb-radius-sm);background:rgba(252,200,39,0.15);">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
        <span>Crea tu PC a Medida</span>
      </a>
    </div>
  </div>

  <!-- CONTENEDOR PRINCIPAL: ESTADO VACÍO O CON PRODUCTOS -->
  <div id="fullCartWrapper">
    
    <!-- ESTADO VACÍO -->
    <div id="fullCartEmpty" style="display:none;background:#FFFFFF;border-radius:var(--cb-radius-lg);border:1px solid var(--cb-border);padding:4rem 2rem;text-align:center;box-shadow:var(--cb-shadow-sm);">
      <div style="width:80px;height:80px;background:#F8FAFC;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;color:var(--cb-text-muted);">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
      </div>
      <h2 style="font-family:var(--cb-font-display);font-size:1.5rem;font-weight:900;color:var(--cb-navy);margin-bottom:0.5rem;">
        Tu bolsa de compras está vacía
      </h2>
      <p style="color:var(--cb-text-muted);font-size:0.95rem;max-width:450px;margin:0 auto 2rem;line-height:1.6;">
        No tienes ningún componente o equipo añadido por el momento. Descubre nuestros componentes más vendidos o configura tu PC ideal.
      </p>
      <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
        <a href="<?= url('tienda') ?>" class="cb-btn-hero-primary" style="padding:0.75rem 1.75rem;font-size:0.92rem;">
          <span>Explorar Catálogo de Productos</span> &rarr;
        </a>
        <a href="<?= url('tienda/crear-pc') ?>" class="cb-btn-hero-outline" style="padding:0.75rem 1.75rem;font-size:0.92rem;color:var(--cb-navy);border-color:var(--cb-navy);">
          <span>Configurar Mi Computadora</span>
        </a>
      </div>
    </div>

    <!-- ESTADO CON PRODUCTOS: GRID 2 COLUMNAS -->
    <div id="fullCartContent" style="display:grid;grid-template-columns:1.8fr 1fr;gap:2.5rem;align-items:start;">
      
      <!-- COLUMNA IZQUIERDA: LISTA DE PRODUCTOS, CUPÓN Y ESTIMACIÓN -->
      <div>
        
        <!-- Tarjeta de Artículos -->
        <div style="background:#FFFFFF;border-radius:var(--cb-radius);border:1px solid var(--cb-border);box-shadow:var(--cb-shadow-sm);overflow:hidden;margin-bottom:1.5rem;">
          <div style="background:#F8FAFC;padding:1rem 1.5rem;border-bottom:1px solid var(--cb-border);display:flex;align-items:center;justify-content:space-between;">
            <span style="font-weight:800;font-size:0.9rem;color:var(--cb-navy);">
              Productos Seleccionados (<span id="fullCartCount">0</span>)
            </span>
            <button type="button" id="btnFullCartClear" style="background:none;border:none;color:#DC2626;font-size:0.8rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:0.3rem;">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
              <span>Vaciar Carrito</span>
            </button>
          </div>

          <div id="fullCartItemsContainer" style="padding:0.5rem 1.5rem;">
            <!-- Filas inyectadas por Javascript con fotos reales -->
          </div>
        </div>

        <!-- SECCIÓN CUPÓN & ENVÍO ESTIMADO -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem;">
          
          <!-- Cupón de Descuento -->
          <div style="background:#FFFFFF;border-radius:var(--cb-radius);border:1px solid var(--cb-border);box-shadow:var(--cb-shadow-sm);padding:1.25rem;">
            <div style="display:flex;align-items:center;gap:0.45rem;margin-bottom:0.75rem;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--cb-cyan)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
              <strong style="font-size:0.88rem;color:var(--cb-navy);">Cupón de Descuento</strong>
            </div>
            <p style="font-size:0.78rem;color:var(--cb-text-muted);margin:0 0 0.75rem;">
              ¿Tienes un código promocional? Ingrésalo aquí (Ej: <strong>MAKPC30</strong>).
            </p>
            <div style="display:flex;gap:0.5rem;">
              <input type="text" id="couponCodeInput" class="form-control" placeholder="Código de descuento" style="text-transform:uppercase;font-size:0.85rem;font-weight:700;padding:0.5rem 0.75rem;">
              <button type="button" id="btnApplyCoupon" class="cb-btn-hero-primary" style="padding:0.5rem 1.1rem;font-size:0.82rem;white-space:nowrap;">
                Aplicar
              </button>
            </div>
            <div id="couponFeedback" style="font-size:0.8rem;font-weight:700;margin-top:0.5rem;display:none;"></div>
          </div>

          <!-- Estimación de Envío -->
          <div style="background:#FFFFFF;border-radius:var(--cb-radius);border:1px solid var(--cb-border);box-shadow:var(--cb-shadow-sm);padding:1.25rem;">
            <div style="display:flex;align-items:center;gap:0.45rem;margin-bottom:0.75rem;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--cb-gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
              <strong style="font-size:0.88rem;color:var(--cb-navy);">Calculador de Envío</strong>
            </div>
            <p style="font-size:0.78rem;color:var(--cb-text-muted);margin:0 0 0.75rem;">
              Selecciona tu destino para calcular el costo de entrega:
            </p>
            <select id="shippingZoneSelect" class="form-control" style="font-size:0.85rem;padding:0.5rem 0.75rem;">
              <option value="tumbes_express" data-cost="10">Tumbes Urbano &mdash; Envío Express Local (S/ 10.00)</option>
              <option value="corrales" data-cost="10">Corrales / San Jacinto &mdash; Entrega Directa (S/ 10.00)</option>
              <option value="provincias" data-cost="18">Zarumilla / Zorritos / Nacional &mdash; Olva / Shalom (S/ 18.00)</option>
              <option value="recojo" data-cost="0">Recojo Gratis en Sede Tumbes (Cal. Simón Bolívar Nro. 461 Int. 001) (S/ 0.00)</option>
            </select>
            <div id="freeShippingNotice" style="font-size:0.78rem;color:var(--cb-success);font-weight:700;margin-top:0.4rem;display:none;">
              ✓ ¡Felicidades! Tienes Envío Gratis por compras mayores a S/ 300.
            </div>
          </div>

        </div>

        <!-- TIRA DE CONFIANZA CORPORATIVA -->
        <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:var(--cb-radius);padding:1.25rem 1.5rem;display:grid;grid-template-columns:repeat(3, 1fr);gap:1rem;text-align:center;">
          <div>
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--cb-cyan)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 0.35rem;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
            <div style="font-size:0.8rem;font-weight:800;color:var(--cb-navy);">Garantía Real Escrita</div>
            <div style="font-size:0.72rem;color:var(--cb-text-muted);">12 a 36 meses por número de serie</div>
          </div>
          <div>
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--cb-gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 0.35rem;"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
            <div style="font-size:0.8rem;font-weight:800;color:var(--cb-navy);">Comprobante SUNAT</div>
            <div style="font-size:0.72rem;color:var(--cb-text-muted);">Emitimos Boleta o Factura con RUC</div>
          </div>
          <div>
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--cb-cyan)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 0.35rem;"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
            <div style="font-size:0.8rem;font-weight:800;color:var(--cb-navy);">Soporte & Ensamble</div>
            <div style="font-size:0.72rem;color:var(--cb-text-muted);">Testeo térmico en taller incluido</div>
          </div>
        </div>

      </div>

      <!-- COLUMNA DERECHA: RESUMEN FINANCIERO & BOTONES DE COMPRA -->
      <div style="position:sticky;top:90px;">
        <div style="background:#FFFFFF;border-radius:var(--cb-radius);border:1px solid var(--cb-border);box-shadow:var(--cb-shadow);padding:1.75rem;">
          
          <h2 style="font-family:var(--cb-font-display);font-size:1.25rem;font-weight:900;color:var(--cb-navy);margin:0 0 1.25rem;padding-bottom:0.75rem;border-bottom:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;">
            <span>Resumen de Compra</span>
            <span style="font-size:0.75rem;background:rgba(5,169,233,0.1);color:var(--cb-cyan);font-weight:800;padding:0.25rem 0.6rem;border-radius:4px;">SSL Seguro</span>
          </h2>

          <div style="display:flex;flex-direction:column;gap:0.75rem;font-size:0.88rem;color:#475569;margin-bottom:1.25rem;">
            <div style="display:flex;justify-content:space-between;">
              <span>Subtotal Productos:</span>
              <strong style="color:var(--cb-navy);" id="fsSubtotal">S/ 0.00</strong>
            </div>

            <div style="display:flex;justify-content:space-between;color:var(--cb-success);display:none;" id="fsDiscountRow">
              <span>Descuento Cupón:</span>
              <strong id="fsDiscount">- S/ 0.00</strong>
            </div>

            <div style="display:flex;justify-content:space-between;">
              <span>Costo de Envío:</span>
              <strong style="color:var(--cb-navy);" id="fsShipping">S/ 15.00</strong>
            </div>

            <div style="display:flex;justify-content:space-between;font-size:0.8rem;color:var(--cb-text-muted);border-top:1px dashed #E2E8F0;padding-top:0.5rem;">
              <span>IGV (18% incluido):</span>
              <span id="fsIgv">S/ 0.00</span>
            </div>
          </div>

          <!-- Total a Pagar -->
          <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:var(--cb-radius-sm);padding:1rem 1.25rem;margin-bottom:1.5rem;">
            <div style="display:flex;align-items:baseline;justify-content:space-between;">
              <span style="font-size:1rem;font-weight:800;color:var(--cb-navy);">TOTAL A PAGAR:</span>
              <div style="font-family:var(--cb-font-display);font-size:1.75rem;font-weight:900;color:var(--cb-navy);">
                <span style="font-size:1rem;color:var(--cb-cyan);">S/</span> <span id="fsTotal">0.00</span>
              </div>
            </div>
            <div style="font-size:0.75rem;color:#64748B;margin-top:0.35rem;">
              O hasta 12 cuotas de <strong id="fsInstallments">S/ 0.00</strong> con tarjetas de crédito BBVA, BCP e Interbank.
            </div>
          </div>

          <!-- BOTONES DE ACCIÓN PRINCIPALES -->
          <div style="display:flex;flex-direction:column;gap:0.75rem;">
            <a href="<?= url('checkout') ?>" class="cb-btn-hero-primary" style="width:100%;justify-content:center;padding:0.9rem 1.5rem;font-size:0.95rem;" id="btnProceedCheckout">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
              <span>Proceder al Pago Seguro (Checkout)</span> &rarr;
            </a>

            <a href="#" class="cb-btn-hero-outline" style="width:100%;justify-content:center;background:#25D366;border-color:#25D366;color:#FFFFFF;padding:0.85rem 1.5rem;font-size:0.92rem;" id="btnWhatsAppCartOrder" target="_blank">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
              <span>Pedir & Coordinar por WhatsApp</span>
            </a>
          </div>

          <!-- Medios de Pago Aceptados -->
          <div style="margin-top:1.5rem;padding-top:1rem;border-top:1px solid #F1F5F9;text-align:center;">
            <div style="font-size:0.72rem;font-weight:700;color:var(--cb-text-muted);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:0.5rem;">
              MÉTODOS DE PAGO DISPONIBLES
            </div>
            <div style="display:flex;justify-content:center;align-items:center;gap:0.4rem;flex-wrap:wrap;">
              <span style="background:#F1F5F9;padding:0.25rem 0.5rem;border-radius:4px;font-size:0.72rem;font-weight:800;color:var(--cb-navy);">VISA</span>
              <span style="background:#F1F5F9;padding:0.25rem 0.5rem;border-radius:4px;font-size:0.72rem;font-weight:800;color:var(--cb-navy);">Mastercard</span>
              <span style="background:rgba(113,29,141,0.1);color:#711D8D;padding:0.25rem 0.5rem;border-radius:4px;font-size:0.72rem;font-weight:800;">Yape</span>
              <span style="background:rgba(0,183,235,0.1);color:#008FB8;padding:0.25rem 0.5rem;border-radius:4px;font-size:0.72rem;font-weight:800;">Plin</span>
              <span style="background:#F1F5F9;padding:0.25rem 0.5rem;border-radius:4px;font-size:0.72rem;font-weight:800;color:#334155;">BCP / BBVA</span>
            </div>
          </div>

        </div>
      </div>

    </div>

  </div>

  <!-- PRODUCTOS RECOMENDADOS (CROSS-SELLING) -->
  <?php if (!empty($relacionados)): ?>
    <div style="margin-top:4rem;">
      <div class="cb-section-header">
        <div>
          <h3 class="cb-section-title">Productos Recomendados para tu Compra</h3>
          <span style="font-size:0.85rem;color:var(--cb-text-muted);display:block;margin-top:2px;">
            Accesorios y periféricos compatibles de alta rotación
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
              <h4 class="cb-card-title">
                <a href="<?= url('tienda/producto/' . $rel['slug']) ?>"><?= e($rel['nombre']) ?></a>
              </h4>

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
                  href="https://wa.me/51975513327?text=Hola%20MAKPC,%20deseo%20consultar%20por:%20<?= urlencode($rel['nombre']) ?>" 
                  class="cb-btn-quick-wa" 
                  target="_blank" 
                  title="Consultar por WhatsApp"
                >
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                </a>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>

</div>

<!-- SCRIPT ESPECÍFICO DE PÁGINA COMPLETA DE CARRITO -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const fullCartEmpty = document.getElementById('fullCartEmpty');
  const fullCartContent = document.getElementById('fullCartContent');
  const fullCartItemsContainer = document.getElementById('fullCartItemsContainer');
  const fullCartCount = document.getElementById('fullCartCount');
  const fsSubtotal = document.getElementById('fsSubtotal');
  const fsDiscountRow = document.getElementById('fsDiscountRow');
  const fsDiscount = document.getElementById('fsDiscount');
  const fsShipping = document.getElementById('fsShipping');
  const fsIgv = document.getElementById('fsIgv');
  const fsTotal = document.getElementById('fsTotal');
  const fsInstallments = document.getElementById('fsInstallments');
  const btnFullCartClear = document.getElementById('btnFullCartClear');
  const btnWhatsAppCartOrder = document.getElementById('btnWhatsAppCartOrder');
  const shippingZoneSelect = document.getElementById('shippingZoneSelect');
  const freeShippingNotice = document.getElementById('freeShippingNotice');
  const couponCodeInput = document.getElementById('couponCodeInput');
  const btnApplyCoupon = document.getElementById('btnApplyCoupon');
  const couponFeedback = document.getElementById('couponFeedback');

  let discountAmount = 0;
  let activeCoupon = null;

  function formatMoney(amount) {
    return 'S/ ' + Number(amount).toLocaleString('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function getCart() {
    try {
      return JSON.parse(localStorage.getItem('makpc_cart')) || [];
    } catch(e) {
      return [];
    }
  }

  function saveCart(cart) {
    localStorage.setItem('makpc_cart', JSON.stringify(cart));
    // Notificar a la app principal para sincronizar cabecera y drawer
    window.dispatchEvent(new Event('cartUpdated'));
    renderFullCart();
  }

  function renderFullCart() {
    const cart = getCart();
    const totalQty = cart.reduce((acc, i) => acc + (parseInt(i.qty) || 1), 0);
    
    if (fullCartCount) fullCartCount.textContent = totalQty;

    if (cart.length === 0) {
      if (fullCartEmpty) fullCartEmpty.style.display = 'block';
      if (fullCartContent) fullCartContent.style.display = 'none';
      return;
    }

    if (fullCartEmpty) fullCartEmpty.style.display = 'none';
    if (fullCartContent) fullCartContent.style.display = 'grid';

    let subtotal = 0;
    fullCartItemsContainer.innerHTML = '';

    cart.forEach((item, index) => {
      const price = parseFloat(item.price) || 0;
      const qty = parseInt(item.qty) || 1;
      const itemSub = price * qty;
      subtotal += itemSub;

      const isImgUrl = item.icon && (item.icon.includes('/') || item.icon.includes('.'));
      const thumb = isImgUrl 
        ? `<img src="${item.icon}" alt="${escapeHtml(item.name)}" style="width:100%;height:100%;object-fit:contain;" />`
        : `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--cb-cyan)" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>`;

      const itemRow = document.createElement('div');
      itemRow.style.display = 'grid';
      itemRow.style.gridTemplateColumns = '80px 1.5fr 110px 110px 40px';
      itemRow.style.gap = '1rem';
      itemRow.style.alignItems = 'center';
      itemRow.style.padding = '1.25rem 0';
      itemRow.style.borderBottom = '1px solid #F1F5F9';

      itemRow.innerHTML = `
        <div style="width:75px;height:75px;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:8px;padding:6px;display:flex;align-items:center;justify-content:center;overflow:hidden;">
          ${thumb}
        </div>
        <div>
          <div style="font-family:var(--cb-font-display);font-size:0.95rem;font-weight:700;color:var(--cb-navy);line-height:1.3;margin-bottom:0.25rem;">
            ${escapeHtml(item.name)}
          </div>
          ${item.details ? `<div style="font-size:0.75rem;color:#64748B;">${escapeHtml(item.details)}</div>` : ''}
          <div style="font-size:0.8rem;color:var(--cb-text-muted);margin-top:0.2rem;">
            Precio Unitario: <strong style="color:var(--cb-navy);">${formatMoney(price)}</strong>
          </div>
        </div>
        <div>
          <div style="display:inline-flex;align-items:center;border:1px solid var(--cb-border);border-radius:6px;overflow:hidden;background:#FFFFFF;">
            <button type="button" style="width:28px;height:28px;border:none;background:#F8FAFC;cursor:pointer;font-weight:700;color:var(--cb-navy);" data-full-minus="${index}">-</button>
            <span style="width:34px;text-align:center;font-size:0.85rem;font-weight:700;">${qty}</span>
            <button type="button" style="width:28px;height:28px;border:none;background:#F8FAFC;cursor:pointer;font-weight:700;color:var(--cb-navy);" data-full-plus="${index}">+</button>
          </div>
        </div>
        <div style="text-align:right;">
          <div style="font-family:var(--cb-font-display);font-size:1.05rem;font-weight:900;color:var(--cb-navy);">
            ${formatMoney(itemSub)}
          </div>
        </div>
        <div style="text-align:center;">
          <button type="button" style="background:none;border:none;color:#94A3B8;cursor:pointer;font-size:1.2rem;line-height:1;transition:color 0.2s;" data-full-del="${index}" title="Eliminar producto">&times;</button>
        </div>
      `;
      fullCartItemsContainer.appendChild(itemRow);
    });

    // Calcular Envío
    const selectedOption = shippingZoneSelect ? shippingZoneSelect.options[shippingZoneSelect.selectedIndex] : null;
    let shippingCost = selectedOption ? parseFloat(selectedOption.getAttribute('data-cost') || 0) : 10;

    if (subtotal >= 300 && selectedOption && selectedOption.value === 'tumbes_express') {
      shippingCost = 0;
      if (freeShippingNotice) freeShippingNotice.style.display = 'block';
    } else {
      if (freeShippingNotice) freeShippingNotice.style.display = 'none';
    }

    // Descuento
    let appliedDiscount = discountAmount;
    if (appliedDiscount > subtotal) appliedDiscount = subtotal;

    if (appliedDiscount > 0) {
      if (fsDiscountRow) fsDiscountRow.style.display = 'flex';
      if (fsDiscount) fsDiscount.textContent = '- ' + formatMoney(appliedDiscount);
    } else {
      if (fsDiscountRow) fsDiscountRow.style.display = 'none';
    }

    const total = Math.max(0, subtotal - appliedDiscount + shippingCost);
    const igv = total * 0.18 / 1.18;

    if (fsSubtotal) fsSubtotal.textContent = formatMoney(subtotal);
    if (fsShipping) fsShipping.textContent = (shippingCost === 0) ? 'GRATIS' : formatMoney(shippingCost);
    if (fsIgv) fsIgv.textContent = formatMoney(igv);
    if (fsTotal) fsTotal.textContent = Number(total).toLocaleString('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    if (fsInstallments) fsInstallments.textContent = formatMoney(total / 12);

    // Preparar mensaje de WhatsApp
    if (btnWhatsAppCartOrder) {
      let msg = "¡Hola MAKPC Enterprises!\nDeseo confirmar y realizar el pedido con los siguientes productos de mi carrito web:\n\n";
      cart.forEach(i => {
        msg += `• ${i.qty || 1}x ${i.name} — ${formatMoney((parseFloat(i.price) || 0) * (i.qty || 1))}\n`;
      });
      if (appliedDiscount > 0) {
        msg += `\nDescuento aplicado (${activeCoupon}): - ${formatMoney(appliedDiscount)}\n`;
      }
      msg += `Envío (${selectedOption ? selectedOption.text.split('—')[0].trim() : 'Tumbes'}): ${shippingCost === 0 ? 'GRATIS' : formatMoney(shippingCost)}\n`;
      msg += `*TOTAL A PAGAR:* ${formatMoney(total)}\n\n`;
      msg += `¿Qué número de cuenta o QR tienen disponible para realizar el abono y coordinar el despacho?`;

      btnWhatsAppCartOrder.href = `https://wa.me/51975513327?text=${encodeURIComponent(msg)}`;
    }
  }

  // Delegación de eventos en tabla
  if (fullCartItemsContainer) {
    fullCartItemsContainer.addEventListener('click', function(e) {
      const target = e.target;
      const cart = getCart();

      if (target.matches('[data-full-plus]')) {
        const idx = parseInt(target.getAttribute('data-full-plus'));
        if (cart[idx]) {
          cart[idx].qty = (parseInt(cart[idx].qty) || 1) + 1;
          saveCart(cart);
        }
      } else if (target.matches('[data-full-minus]')) {
        const idx = parseInt(target.getAttribute('data-full-minus'));
        if (cart[idx]) {
          if (cart[idx].qty > 1) {
            cart[idx].qty -= 1;
          } else {
            cart.splice(idx, 1);
          }
          saveCart(cart);
        }
      } else if (target.matches('[data-full-del]')) {
        const idx = parseInt(target.getAttribute('data-full-del'));
        if (cart[idx]) {
          cart.splice(idx, 1);
          saveCart(cart);
        }
      }
    });
  }

  // Vaciar carrito
  if (btnFullCartClear) {
    btnFullCartClear.addEventListener('click', function() {
      if (confirm('¿Estás seguro de que deseas vaciar todos los artículos de tu bolsa de compras?')) {
        saveCart([]);
      }
    });
  }

  // Cambio de zona de envío
  if (shippingZoneSelect) {
    shippingZoneSelect.addEventListener('change', renderFullCart);
  }

  // Aplicar cupón
  if (btnApplyCoupon && couponCodeInput) {
    btnApplyCoupon.addEventListener('click', function() {
      const code = couponCodeInput.value.trim().toUpperCase();
      if (!code) return;

      if (code === 'MAKPC30') {
        discountAmount = 30.00;
        activeCoupon = 'MAKPC30';
        couponFeedback.style.display = 'block';
        couponFeedback.style.color = 'var(--cb-success)';
        couponFeedback.textContent = '✓ Cupón MAKPC30 aplicado: S/ 30.00 de descuento.';
        renderFullCart();
      } else if (code === 'ENVIOFREE') {
        discountAmount = 15.00;
        activeCoupon = 'ENVIOFREE';
        couponFeedback.style.display = 'block';
        couponFeedback.style.color = 'var(--cb-success)';
        couponFeedback.textContent = '✓ Cupón ENVIOFREE aplicado: Envío gratuito.';
        renderFullCart();
      } else {
        couponFeedback.style.display = 'block';
        couponFeedback.style.color = '#DC2626';
        couponFeedback.textContent = '✕ El cupón ingresado no es válido o ya ha expirado.';
      }
    });
  }

  function escapeHtml(str) {
    if (!str) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  // Render inicial
  renderFullCart();

  // Escuchar actualizaciones externas de carrito
  window.addEventListener('cartUpdated', renderFullCart);
});
</script>
