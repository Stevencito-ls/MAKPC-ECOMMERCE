<?php
/**
 * Componente: Spotlight "Crea tu PC"
 * MAKPC Enterprises S.A.C.
 */
?>
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
