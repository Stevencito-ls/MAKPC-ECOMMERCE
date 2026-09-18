<?php
/**
 * Layout: Barra de Navegación Responsiva (navbar.php)
 * MAKPC Enterprises S.A.C.
 * Soporta navegación desktop con isotipo y menú móvil hamburguesa
 */
?>
<div class="nav-top">
    <div class="nav-container">
        <!-- Logotipo e Identidad para Pantallas de Escritorio -->
        <a href="#top" class="desktop-brand" title="MAK-PC Enterprises S.A.C.">
            <img src="imagenes/logo.png" alt="MAK-PC Logo">
            <span>MAK-PC</span>
        </a>

        <!-- Contenedor Principal de Navegación -->
        <nav id="mainNav">
            <!-- Barra Superior Móvil con Hamburguesa -->
            <div class="mobile-nav-header">
                <a href="#top" class="mobile-brand" title="Inicio">
                    <img src="imagenes/logo.png" alt="MAK-PC Logo">
                    <span>MAK-PC</span>
                </a>
                <button class="mobile-nav-toggle" id="mobileNavToggle" aria-label="Abrir menú de navegación" type="button">
                    <span class="bar bar-1"></span>
                    <span class="bar bar-2"></span>
                    <span class="bar bar-3"></span>
                </button>
            </div>

            <!-- Lista de Enlaces de Navegación -->
            <ul id="navMenu">
                <li><a href="#Tienda">Tienda</a></li>
                <li><a href="#Conocenos-mas">Conócenos más</a></li>
                <li><a href="#servicios">Servicios</a></li>
                <li><a href="#Convenios">Convenios</a></li>
                <li><a href="#Nuestros-Datos">Nuestros Datos</a></li>
                <li><a href="#Contactanos">Contáctanos</a></li>
                <li>
                    <a href="<?= $ecommerceUrl ?>" class="nav-tienda-btn" title="Ir a Ecommerce MAKPC">
                        <i class="fa-solid fa-cart-shopping"></i> Ecommerce MAKPC
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
