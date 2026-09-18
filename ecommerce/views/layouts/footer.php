</div> <!-- /app-container -->

<footer class="app-footer">
  <div class="footer-grid">
    <div class="footer-brand">
      <h3>MAKPC Enterprises S.A.C.</h3>
      <p>Especialistas en soporte técnico informático, mantenimiento preventivo, reparación de computadoras, laptops y venta de repuestos y accesorios originales.</p>
    </div>
    <div class="footer-col">
      <h4>Navegación</h4>
      <ul class="footer-links">
        <li><a href="<?= url() ?>">Dashboard</a></li>
        <li><a href="<?= url('orden') ?>">Órdenes de Servicio</a></li>
        <li><a href="<?= url('tienda') ?>">Catálogo Online</a></li>
        <li><a href="<?= url('componente') ?>">Auditoría de Series</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Servicios</h4>
      <ul class="footer-links">
        <li><a href="<?= url('tienda/soporte') ?>">Diagnóstico de PC</a></li>
        <li><a href="<?= url('tienda/soporte') ?>">Mantenimiento Pro</a></li>
        <li><a href="<?= url('tienda/soporte') ?>">Upgrades SSD / RAM</a></li>
        <li><a href="<?= url('tienda/soporte') ?>">Garantía y Trazabilidad</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Contacto</h4>
      <p style="font-size:0.85rem;color:rgba(255,255,255,0.7);line-height:1.8;">
        <span style="display:flex;align-items:center;gap:6px;margin-bottom:4px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
          Cal. Simón Bolívar Nro. 461 Int. 001, Cercado de Tumbes, Perú
        </span>
        <span style="display:flex;align-items:center;gap:6px;margin-bottom:4px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
          +51 975 513 327
        </span>
        <span style="display:flex;align-items:center;gap:6px;margin-bottom:4px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
          contacto@makpc.pe
        </span>
        <span style="display:flex;align-items:center;gap:6px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
          Lun - Sáb: 8:30 AM - 7:30 PM
        </span>
      </p>
    </div>
  </div>

  <div class="footer-bottom">
    &copy; <?= date('Y') ?> MAK-PC ENTERPRISES S.A.C. - RUC: 20409456520 - Todos los derechos reservados. Sistema de Gestión & E-commerce.
  </div>
</footer>

<!-- Botón Flotante WhatsApp -->
<a href="https://wa.me/51975513327?text=Hola%20MAKPC,%20deseo%20hacer%20una%20consulta" class="floating-whatsapp" target="_blank" title="Chatear con soporte">
  <svg width="26" height="26" viewBox="0 0 24 24" fill="#ffffff"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
</a>

<script src="<?= asset('js/app.js') ?>?v=<?= file_exists(__DIR__ . '/../../assets/js/app.js') ? filemtime(__DIR__ . '/../../assets/js/app.js') : '1.0' ?>"></script>
</body>
</html>
