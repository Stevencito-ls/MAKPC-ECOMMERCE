<?php
/**
 * Sección: Hero Header Principal (hero.php)
 * MAKPC Enterprises S.A.C.
 * Logotipo corporativo delimitado y tipografía destacada
 */
?>
<header id="top">
    <!-- Barra de Navegación Modular -->
    <?php require_once __DIR__ . '/../layouts/navbar.php'; ?>

    <!-- Contenedor Hero Proporcionado -->
    <div class="header-container">
        <div class="description-section">
            <h1 class="Letra-bonita">
                ¡Tecnología de vanguardia en Tumbes!
            </h1>
            <p>
                Sabemos que en Tumbes no encuentras lo que necesitas.<br>
                <span class="highlight-text">¡Tú mejor opción en hardware y servicio técnico!</span>
            </p>
            <div style="margin-top:1rem;">
                <a href="<?= $ecommerceUrl ?>" class="btn-visita-online" style="margin-top:0;" title="Ver Catálogo de Productos en Ecommerce MAKPC">
                    <i class="fa-solid fa-arrow-right-to-bracket" style="margin-right:6px;"></i> Explorar Ecommerce MAKPC &rarr;
                </a>
            </div>
        </div>

        <div class="logo-section">
            <img src="imagenes/logo.png" alt="Logo Oficial MAK-PC Enterprises SAC" class="header-logo" />
        </div>
    </div>
</header>
