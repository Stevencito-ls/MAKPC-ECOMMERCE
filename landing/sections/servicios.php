<?php
/**
 * Sección: Servicios y Soporte Técnico (servicios.php)
 * MAKPC Enterprises S.A.C.
 * Tarjetas chevron: Soporte técnico experto, Repuestos y Mantenimiento preventivo
 */
?>
<!-- SECCIÓN 3: SERVICIOS -->
<section class="chevron-section">
    <h2 id="servicios">Servicios</h2>
    <p class="texto_p_Servicios">
        En MAK-PC, nos especializamos en brindar soluciones tecnológicas
        integrales que incluyen:
    </p>
    <div class="chevron-grid">
        <div class="chevron-card">
            <img src="imagenes/Soportexd.jpg" alt="Soporte técnico experto en computadoras y laptops" />
            <h3>Soporte técnico experto</h3>
            <p>
                Resolvemos cualquier problema técnico y mantenemos tus equipos siempre al
                máximo rendimiento con diagnóstico especializado.
            </p>
        </div>
        <div class="chevron-card">
            <img src="imagenes/ventas.jpg" alt="Venta de repuestos y componentes premium" />
            <h3>Repuestos originales y componentes premium</h3>
            <p>Para todas las marcas líderes de hardware: calidad certificada y garantía oficial aseguradas.</p>
        </div>
        <div class="chevron-card">
            <img src="imagenes/Mantenimiento preventivo.jpg" alt="Mantenimiento preventivo y correctivo" />
            <h3>Mantenimiento preventivo y correctivo</h3>
            <p>
                Extiende la vida útil de tus dispositivos informáticos y previene fallas
                inesperadas con limpieza ultrasónica y pasta térmica premium.
            </p>
        </div>
    </div>

    <!-- Banner Rápido de Rastreo de Órdenes de Servicio Técnico -->
    <div style="margin-top:2rem;background:#ffffff;border:1.5px solid #E2E8F0;border-radius:12px;padding:1.5rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;box-shadow:0 4px 14px rgba(0,0,0,0.04);">
        <div style="display:flex;align-items:center;gap:1rem;">
            <div style="width:48px;height:48px;background:rgba(5,169,233,0.12);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#05A9E9;font-size:1.4rem;">
                <i class="fa-solid fa-screwdriver-wrench"></i>
            </div>
            <div>
                <h4 style="font-size:1.05rem;font-weight:800;color:#161D45;margin:0 0 0.2rem;">¿Tienes un equipo ingresado a nuestro taller técnico?</h4>
                <p style="font-size:0.85rem;color:#64748B;margin:0;">Consulta el avance de diagnóstico, repuestos instalados y cotización en tiempo real.</p>
            </div>
        </div>
        <a href="<?= $soporteUrl ?>" class="btn-visita-online" style="margin:0;padding:0.65rem 1.3rem;font-size:0.9rem;" title="Rastrear Orden de Taller">
            <i class="fa-solid fa-magnifying-glass" style="margin-right:6px;"></i> Rastrear mi Orden en Vivo &rarr;
        </a>
    </div>
</section>
