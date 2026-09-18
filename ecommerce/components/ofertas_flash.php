<?php
/**
 * Componente Modular: Ventana Flotante de Ofertas Relámpago 24H
 * MAKPC Enterprises S.A.C.
 * @var array $productos
 */
$flashProducts = array_slice($productos ?? [], 0, 4);
?>
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
        * Ofertas por tiempo limitado sujetas a existencias en almacén.
      </span>
      <button type="button" onclick="cerrarOfertasModal()" class="cb-btn-hero-primary" style="padding:0.5rem 1.25rem;font-size:0.88rem;">
        Seguir Explorando el Catálogo &rarr;
      </button>
    </div>

  </div>
</div>
