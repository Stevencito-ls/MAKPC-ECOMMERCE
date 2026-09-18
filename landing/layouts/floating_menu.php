<?php
/**
 * Layout: Menú Flotante de Redes Sociales y WhatsApp (floating_menu.php)
 * MAKPC Enterprises S.A.C.
 * Escritorio: lateral izquierda/derecha | Móvil: botón FAB esquina inferior derecha
 */
?>
<!-- MENÚ LATERAL DE REDES SOCIALES FLOTANTE -->
<div class="lateral-menu-container">
    <button class="lateral-main-btn" id="lateralMenuBtn" aria-label="Abrir canales de atención" type="button" title="Atención y Redes Sociales">
        <span class="lateral-menu-icon"><i class="fa-solid fa-headset" style="font-size:24px;color:#fcc827;"></i></span>
    </button>
    <div class="lateral-social-buttons" id="lateralSocialButtons">
        <a href="https://wa.me/51975513327?text=<?= urlencode('¡Hola MAKPC! Deseo realizar una consulta técnica o comercial.') ?>" 
           class="lateral-btn lateral-whatsapp" 
           target="_blank" 
           rel="noopener noreferrer"
           aria-label="WhatsApp" 
           title="WhatsApp Oficial (+51 975 513 327)">
            <i class="fab fa-whatsapp"></i>
        </a>
        <a href="https://www.facebook.com/mak.pc.enterprises.sac" 
           class="lateral-btn lateral-facebook" 
           target="_blank" 
           rel="noopener noreferrer"
           aria-label="Facebook" 
           title="Facebook Oficial MAKPC">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://www.tiktok.com/@makpc_tumbes" 
           class="lateral-btn lateral-tiktok" 
           target="_blank" 
           rel="noopener noreferrer"
           aria-label="TikTok" 
           title="TikTok Oficial @makpc_tumbes">
            <i class="fab fa-tiktok"></i>
        </a>
    </div>
</div>
