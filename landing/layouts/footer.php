<?php
/**
 * Layout: Pie de Página Institucional (footer.php)
 * MAKPC Enterprises S.A.C.
 * 3 columnas corporativas, medios de pago, derechos reservados y scripts JS
 */
?>
<footer class="footer-container" id="Contactanos">
    <div class="content-wrapper">
        <!-- Columna Izquierda: Identidad y Contacto -->
        <section class="column-left">
            <img src="imagenes/logo.png" alt="Logo de MAK-PC ENTERPRISES SAC" class="logo" />
            <div class="company-name">MAK-PC ENTERPRISES SAC</div>
            <p class="description">
                Con un legado de más de 25 años en Tumbes, en MAK-PC Enterprise SAC nos enorgullece
                ser su socio tecnológico de confianza. Ofrecemos soluciones de hardware de vanguardia
                y un soporte técnico especializado inigualable, diseñados para impulsar su crecimiento
                y productividad digital.
            </p>
            <div class="divider-container">
                <hr class="divider" />
            </div>
            <div class="contact-info">
                <div class="contact-item">
                    <i class="fa-solid fa-envelope" style="margin-right:8px;color:var(--cyan-color);"></i>
                    <span>makpcenterprises75@gmail.com</span>
                </div>
                <div class="contact-item">
                    <i class="fa-solid fa-phone" style="margin-right:8px;color:var(--cyan-color);"></i>
                    <a href="https://wa.me/51975513327" target="_blank" style="color:inherit;text-decoration:none;">+51 975 513 327</a>
                </div>
                <div class="contact-item">
                    <i class="fa-solid fa-location-dot" style="margin-right:8px;color:var(--cyan-color);"></i>
                    <span>Jr. Simón Bolívar N° 461 Int. 001 - Tumbes, Perú</span>
                </div>
            </div>
        </section>

        <div class="vertical-divider"></div>
        
        <!-- Columna Central: Menú Rápido y Enlaces al Sistema -->
        <section class="column-middle">
            <ul class="menu">
                <li><a href="#Tienda">Tienda</a></li>
                <li><a href="#Conocenos-mas">Conócenos más</a></li>
                <li><a href="#servicios">Servicios</a></li>
                <li><a href="#Convenios">Convenios</a></li>
                <li><a href="#Nuestros-Datos">Nuestros Datos</a></li>
                <li><a href="<?= $ecommerceUrl ?>">Ecommerce MAKPC</a></li>
                <li><a href="<?= $builderUrl ?>">Crea tu PC Gamer</a></li>
                <li><a href="<?= $soporteUrl ?>">Rastreo de Órdenes</a></li>
                <li><a href="<?= $loginUrl ?>">Acceso Panel Taller</a></li>
            </ul>
        </section>

        <div class="vertical-divider"></div>
        
        <!-- Columna Derecha: Redes Sociales y Medios de Pago Oficiales -->
        <section class="column-right">
            <div class="social-section">
                <div class="social-title">Redes sociales</div>
                <div class="social-icons">
                    <a href="https://www.tiktok.com/@makpc_tumbes" target="_blank" rel="noopener noreferrer" class="social-icon tiktok" aria-label="TikTok" title="TikTok Oficial">
                        <i class="fab fa-tiktok"></i>
                    </a>
                    <a href="https://www.facebook.com/mak.pc.enterprises.sac" target="_blank" rel="noopener noreferrer" class="social-icon facebook" aria-label="Facebook" title="Facebook Oficial">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://wa.me/51975513327" target="_blank" rel="noopener noreferrer" class="social-icon whatsapp" aria-label="WhatsApp" title="WhatsApp Oficial">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>

            <div class="payment-section">
                <div class="payment-text">Esta página permite pagos online con:</div>
                <div class="payment-methods">
                    <img src="imagenes/Visa-logo.svg.svg" alt="Visa" class="payment-icon" />
                    <img src="imagenes/mastercard.svg.svg" alt="Mastercard" class="payment-icon" />
                    <img src="imagenes/Yape-png.png" alt="Yape" class="payment-icon" />
                </div>
            </div>
        </section>
    </div>

    <!-- Copyright -->
    <div class="copyright">
        &copy; <?= date('Y') ?> MAK-PC ENTERPRISES SAC. RUC 20409456520. Todos los derechos reservados.
    </div>
</footer>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Script de Menú Flotante de Redes Sociales -->
<script src="js/BotonFlotante.js?v=<?= time() ?>"></script>

<!-- Script de Navegación Móvil Responsive & Cierre Automático -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nav = document.getElementById('mainNav');
    const toggleBtn = document.getElementById('mobileNavToggle');
    const navLinks = document.querySelectorAll('#navMenu a');

    if (toggleBtn && nav) {
        toggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            nav.classList.toggle('is-open');
            toggleBtn.classList.toggle('is-active');
        });

        // Cerrar menú automáticamente al pulsar sobre cualquier enlace de navegación
        navLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                nav.classList.remove('is-open');
                toggleBtn.classList.remove('is-active');
            });
        });

        // Cerrar menú al hacer clic fuera del contenedor
        document.addEventListener('click', function(e) {
            if (!nav.contains(e.target)) {
                nav.classList.remove('is-open');
                toggleBtn.classList.remove('is-active');
            }
        });
    }
});
</script>
</body>
</html>
