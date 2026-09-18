<?php
/**
 * MAKPC - Checkout Seguro 100% E-Commerce (Estilo Coolbox.pe)
 * Pasarela Culqi Online (Modo Sandbox / Prueba): Yape (con código de 6 dígitos) y Tarjetas
 * Emisión Automática de Boleta de Venta Electrónica (B001) / Factura Electrónica (F001) SUNAT
 * Dirección Fiscal & Operaciones: Cal. Simón Bolívar Nro. 461 Int. 001, Cercado de Tumbes, Perú
 */
$orderCode = 'PED-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
?>

<!-- CULQI CHECKOUT JS SDK v4 (SANDBOX & LIVE) -->
<script src="https://checkout.culqi.com/js/v4"></script>

<div style="max-width:1360px;margin:1.5rem auto 4rem;padding:0 1.5rem;">
  
  <!-- BREADCRUMBS -->
  <nav aria-label="Breadcrumb" style="display:flex;align-items:center;gap:0.5rem;font-size:0.85rem;color:var(--cb-text-muted);margin-bottom:1.75rem;flex-wrap:wrap;">
    <a href="<?= url() ?>" style="color:var(--cb-navy);text-decoration:none;font-weight:600;">Inicio</a>
    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
    <a href="<?= url('tienda') ?>" style="color:var(--cb-navy);text-decoration:none;font-weight:600;">Tienda</a>
    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
    <a href="<?= url('carrito') ?>" style="color:var(--cb-navy);text-decoration:none;font-weight:600;">Carrito</a>
    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
    <span style="color:var(--cb-cyan);font-weight:700;">Finalizar Compra</span>
  </nav>

  <!-- CABECERA DEL CHECKOUT -->
  <div style="background:linear-gradient(135deg, #161D45 0%, #0D122E 100%);border-radius:var(--cb-radius);padding:1.5rem 2rem;color:#FFFFFF;margin-bottom:2rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;border:1px solid rgba(255,255,255,0.1);">
    <div style="display:flex;align-items:center;gap:1rem;">
      <div style="background:var(--cb-gold);color:var(--cb-navy);width:42px;height:42px;border-radius:8px;display:flex;align-items:center;justify-content:center;">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
      </div>
      <div>
        <h1 style="font-family:var(--cb-font-display);font-size:1.4rem;font-weight:900;color:#FFFFFF;margin:0;">
          Checkout Seguro MAKPC &bull; Pasarela Online & Facturación SUNAT
        </h1>
        <span style="font-size:0.8rem;color:#94A3B8;">
          Transacciones protegidas con certificación SSL 256-Bit &bull; RUC: 20409456520 &bull; Tumbes, Perú
        </span>
      </div>
    </div>
    <div style="display:flex;align-items:center;gap:0.75rem;">
      <span style="font-size:0.75rem;background:rgba(252,200,39,0.15);color:var(--cb-gold);border:1px solid rgba(252,200,39,0.3);padding:0.35rem 0.75rem;border-radius:6px;font-weight:700;">
        SANDBOX ACTIVO
      </span>
      <div style="font-size:0.82rem;background:rgba(255,255,255,0.08);padding:0.4rem 0.8rem;border-radius:6px;color:#CBD5E1;">
        Código: <strong style="color:var(--cb-gold);" id="headerOrderCode"><?= $orderCode ?></strong>
      </div>
    </div>
  </div>

  <!-- AVISO DE CARRITO VACÍO (Si aplica) -->
  <div id="emptyCartNotice" style="display:none;background:#FFFFFF;border-radius:var(--cb-radius);border:1px solid var(--cb-border);box-shadow:var(--cb-shadow-sm);padding:3rem 2rem;text-align:center;max-width:600px;margin:2rem auto;">
    <div style="width:60px;height:60px;background:#F1F5F9;color:var(--cb-navy);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
    </div>
    <h3 style="font-family:var(--cb-font-display);color:var(--cb-navy);font-weight:800;margin-bottom:0.5rem;">Tu carrito está actualmente vacío</h3>
    <p style="color:#64748B;font-size:0.9rem;margin-bottom:1.5rem;">Puedes añadir un producto de prueba para verificar inmediatamente la pasarela Culqi (Yape y tarjetas) y la emisión de comprobantes SUNAT.</p>
    <div style="display:flex;gap:0.75rem;justify-content:center;flex-wrap:wrap;">
      <button type="button" onclick="cargarProductoPrueba()" class="cb-btn-hero-primary" style="padding:0.65rem 1.25rem;font-size:0.9rem;">
        <span>Cargar Producto Demo de Prueba</span> &rarr;
      </button>
      <a href="<?= url('tienda') ?>" class="cb-btn-hero-outline" style="padding:0.65rem 1.25rem;font-size:0.9rem;">
        <span>Explorar Catálogo</span>
      </a>
    </div>
  </div>

  <!-- CONTENEDOR CHECKOUT -->
  <div id="checkoutMainWrapper">
    
    <!-- GRID FORMULARIO + RESUMEN -->
    <div style="display:grid;grid-template-columns:1.7fr 1fr;gap:2.5rem;align-items:start;" id="checkoutGrid">
      
      <!-- COLUMNA IZQUIERDA: FORMULARIO PASO A PASO -->
      <form id="checkoutForm" onsubmit="handleFormSubmit(event)">
        
        <!-- PASO 1: DATOS DE CONTACTO & FACTURACIÓN -->
        <div style="background:#FFFFFF;border-radius:var(--cb-radius);border:1px solid var(--cb-border);box-shadow:var(--cb-shadow-sm);padding:1.75rem;margin-bottom:1.75rem;">
          <div style="display:flex;align-items:center;gap:0.6rem;margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid #F1F5F9;">
            <span style="width:26px;height:26px;background:var(--cb-navy);color:#fff;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:0.8rem;font-weight:800;">1</span>
            <h2 style="font-family:var(--cb-font-display);font-size:1.15rem;font-weight:800;color:var(--cb-navy);margin:0;">
              Datos de Facturación & Emisión SUNAT
            </h2>
          </div>

          <!-- Selector de Comprobante: Boleta o Factura -->
          <div style="margin-bottom:1.25rem;">
            <label style="display:block;font-size:0.82rem;font-weight:700;color:var(--cb-navy);margin-bottom:0.5rem;">Tipo de Comprobante Oficial Requerido *</label>
            <div style="display:flex;gap:1rem;">
              <label style="display:flex;align-items:center;gap:0.5rem;font-size:0.88rem;cursor:pointer;background:#F8FAFC;border:1px solid var(--cb-border);padding:0.65rem 1rem;border-radius:6px;flex:1;">
                <input type="radio" name="tipo_comprobante" value="boleta" checked onchange="toggleComprobanteFields()">
                <div>
                  <strong>Boleta de Venta Electrónica</strong>
                  <div style="font-size:0.72rem;color:var(--cb-text-muted);">Serie B001 &bull; Persona natural (DNI / CE)</div>
                </div>
              </label>
              <label style="display:flex;align-items:center;gap:0.5rem;font-size:0.88rem;cursor:pointer;background:#F8FAFC;border:1px solid var(--cb-border);padding:0.65rem 1rem;border-radius:6px;flex:1;">
                <input type="radio" name="tipo_comprobante" value="factura" onchange="toggleComprobanteFields()">
                <div>
                  <strong>Factura Electrónica</strong>
                  <div style="font-size:0.72rem;color:var(--cb-text-muted);">Serie F001 &bull; Empresas y personas con RUC</div>
                </div>
              </label>
            </div>
          </div>

          <!-- Campos Boleta con Consulta RENIEC en Vivo -->
          <div id="fieldsBoleta" style="display:grid;grid-template-columns:1fr 1.2fr;gap:1rem;margin-bottom:1rem;">
            <div>
              <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.35rem;">
                <label style="font-size:0.8rem;font-weight:700;color:var(--cb-navy);margin:0;">DNI (8 dígitos) *</label>
                <span id="dniStatusBadge" style="font-size:0.7rem;font-weight:700;display:none;"></span>
              </div>
              <div style="display:flex;gap:6px;">
                <input type="text" id="doc_numero" class="form-control" required placeholder="Ej: 70000000" maxlength="8" style="font-weight:700;letter-spacing:1px;" value="70000000">
                <button type="button" id="btnBuscarDni" onclick="buscarDocEnReniecSunat('dni')" class="btn" style="white-space:nowrap;padding:0.4rem 0.8rem;font-size:0.8rem;background:var(--cb-navy);color:#fff;border-radius:6px;border:none;display:inline-flex;align-items:center;gap:4px;cursor:pointer;" title="Consultar en RENIEC Oficial">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                  <span>RENIEC</span>
                </button>
              </div>
            </div>
            <div>
              <label style="display:block;font-size:0.8rem;font-weight:700;color:var(--cb-navy);margin-bottom:0.35rem;">Nombres y Apellidos Completos *</label>
              <input type="text" id="cliente_nombre" class="form-control" required placeholder="Nombres y Apellidos según RENIEC" value="CÓRDOVA VALDIVIA JHENNIFER IRENE">
            </div>
          </div>

          <!-- Campos Factura con Consulta SUNAT en Vivo (Ocultos por defecto) -->
          <div id="fieldsFactura" style="display:none;grid-template-columns:1fr 1.2fr;gap:1rem;margin-bottom:1rem;">
            <div>
              <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.35rem;">
                <label style="font-size:0.8rem;font-weight:700;color:var(--cb-navy);margin:0;">Número de RUC (11 dígitos) *</label>
                <span id="rucStatusBadge" style="font-size:0.7rem;font-weight:700;display:none;"></span>
              </div>
              <div style="display:flex;gap:6px;">
                <input type="text" id="ruc_numero" class="form-control" placeholder="Ej: 20409456520" maxlength="11" style="font-weight:700;letter-spacing:1px;" value="20409456520">
                <button type="button" id="btnBuscarRuc" onclick="buscarDocEnReniecSunat('ruc')" class="btn" style="white-space:nowrap;padding:0.4rem 0.8rem;font-size:0.8rem;background:var(--cb-navy);color:#fff;border-radius:6px;border:none;display:inline-flex;align-items:center;gap:4px;cursor:pointer;" title="Consultar en SUNAT Oficial">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                  <span>SUNAT</span>
                </button>
              </div>
            </div>
            <div>
              <label style="display:block;font-size:0.8rem;font-weight:700;color:var(--cb-navy);margin-bottom:0.35rem;">Razón Social de la Empresa *</label>
              <input type="text" id="razon_social" class="form-control" placeholder="Razón Social según SUNAT" value="MAK PC ENTERPRISES S.A.C.">
            </div>
            <div style="grid-column:1 / -1;">
              <label style="display:block;font-size:0.8rem;font-weight:700;color:var(--cb-navy);margin-bottom:0.35rem;">Dirección Fiscal Registrada *</label>
              <input type="text" id="direccion_fiscal" class="form-control" placeholder="Dirección Fiscal según Ficha RUC" value="CAL. SIMON BOLIVAR NRO 461 INT. 001, CERCADO DE TUMBES">
            </div>
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div>
              <label style="display:block;font-size:0.8rem;font-weight:700;color:var(--cb-navy);margin-bottom:0.35rem;">Teléfono / WhatsApp de Coordinación *</label>
              <input type="text" id="cliente_telefono" class="form-control" required placeholder="Ej: 972654321" maxlength="15" value="972654321">
            </div>
            <div>
              <label style="display:block;font-size:0.8rem;font-weight:700;color:var(--cb-navy);margin-bottom:0.35rem;">Correo Electrónico para Facturación *</label>
              <input type="email" id="cliente_correo" class="form-control" required placeholder="Ej: cliente@correo.com" value="cliente@correo.com">
            </div>
          </div>

        </div>

        <!-- PASO 2: MODALIDAD DE ENTREGA & DIRECCIÓN -->
        <div style="background:#FFFFFF;border-radius:var(--cb-radius);border:1px solid var(--cb-border);box-shadow:var(--cb-shadow-sm);padding:1.75rem;margin-bottom:1.75rem;">
          <div style="display:flex;align-items:center;gap:0.6rem;margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid #F1F5F9;">
            <span style="width:26px;height:26px;background:var(--cb-navy);color:#fff;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:0.8rem;font-weight:800;">2</span>
            <h2 style="font-family:var(--cb-font-display);font-size:1.15rem;font-weight:800;color:var(--cb-navy);margin:0;">
              Modalidad de Entrega (Tumbes & Nacional)
            </h2>
          </div>

          <!-- Selector de Envío -->
          <div style="display:flex;flex-direction:column;gap:0.75rem;margin-bottom:1.25rem;">
            <label style="display:flex;align-items:center;justify-content:space-between;background:#F8FAFC;border:1px solid var(--cb-border);padding:0.75rem 1rem;border-radius:8px;cursor:pointer;">
              <div style="display:flex;align-items:center;gap:0.6rem;">
                <input type="radio" name="shipping_method" value="tumbes_express" checked onchange="updateCheckoutTotals()">
                <div>
                  <strong style="font-size:0.88rem;color:var(--cb-navy);">Envío Express Local (Tumbes Urbano & Corrales)</strong>
                  <div style="font-size:0.75rem;color:var(--cb-text-muted);">Entrega en el mismo día &bull; Cercado, San José, Pampa Grande, Andrés Araujo, Corrales</div>
                </div>
              </div>
              <strong style="font-size:0.9rem;color:var(--cb-navy);" id="labelCostTumbes">S/ 10.00</strong>
            </label>

            <label style="display:flex;align-items:center;justify-content:space-between;background:#F8FAFC;border:1px solid var(--cb-border);padding:0.75rem 1rem;border-radius:8px;cursor:pointer;">
              <div style="display:flex;align-items:center;gap:0.6rem;">
                <input type="radio" name="shipping_method" value="provincias" onchange="updateCheckoutTotals()">
                <div>
                  <strong style="font-size:0.88rem;color:var(--cb-navy);">Envío a Provincias (Zarumilla, Zorritos, Aguas Verdes / Nacional)</strong>
                  <div style="font-size:0.75rem;color:var(--cb-text-muted);">Agencia Olva Courier o Shalom con número de seguimiento y seguro de carga</div>
                </div>
              </div>
              <strong style="font-size:0.9rem;color:var(--cb-navy);">S/ 18.00</strong>
            </label>

            <label style="display:flex;align-items:center;justify-content:space-between;background:#F8FAFC;border:1px solid var(--cb-border);padding:0.75rem 1rem;border-radius:8px;cursor:pointer;">
              <div style="display:flex;align-items:center;gap:0.6rem;">
                <input type="radio" name="shipping_method" value="recojo" onchange="updateCheckoutTotals()">
                <div>
                  <strong style="font-size:0.88rem;color:var(--cb-navy);">Recojo Presencial en Taller Central MAKPC (Gratis)</strong>
                  <div style="font-size:0.75rem;color:var(--cb-text-muted);">Cal. Simón Bolívar Nro. 461 Int. 001, Cercado de Tumbes</div>
                </div>
              </div>
              <strong style="font-size:0.9rem;color:var(--cb-success);">GRATIS</strong>
            </label>
          </div>

          <!-- Campos de Dirección -->
          <div id="deliveryAddressFields" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div>
              <label style="display:block;font-size:0.8rem;font-weight:700;color:var(--cb-navy);margin-bottom:0.35rem;">Departamento / Región *</label>
              <input type="text" id="direccion_departamento" class="form-control" value="Tumbes" required>
            </div>
            <div>
              <label style="display:block;font-size:0.8rem;font-weight:700;color:var(--cb-navy);margin-bottom:0.35rem;">Distrito / Localidad *</label>
              <input type="text" id="direccion_distrito" class="form-control" value="Tumbes" placeholder="Ej: Tumbes, Corrales, Zarumilla, Zorritos" required>
            </div>
            <div style="grid-column:1 / -1;">
              <label style="display:block;font-size:0.8rem;font-weight:700;color:var(--cb-navy);margin-bottom:0.35rem;">Dirección Exacta (Calle, Avenida, Número, Interior) *</label>
              <input type="text" id="direccion_calle" class="form-control" value="Cal. Simón Bolívar 461, Int. 001" placeholder="Ej: Calle Las Camelias 450, Dpto 302" required>
            </div>
            <div style="grid-column:1 / -1;">
              <label style="display:block;font-size:0.8rem;font-weight:700;color:var(--cb-navy);margin-bottom:0.35rem;">Referencia de Entrega</label>
              <input type="text" id="direccion_referencia" class="form-control" value="A media cuadra del Paseo Libertadores" placeholder="Ej: A media cuadra del parque central">
            </div>
          </div>

        </div>

        <!-- PASO 3: MÉTODO DE PAGO SEGURO -->
        <div style="background:#FFFFFF;border-radius:var(--cb-radius);border:1px solid var(--cb-border);box-shadow:var(--cb-shadow-sm);padding:1.75rem;margin-bottom:1.75rem;">
          <div style="display:flex;align-items:center;gap:0.6rem;margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid #F1F5F9;">
            <span style="width:26px;height:26px;background:var(--cb-navy);color:#fff;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:0.8rem;font-weight:800;">3</span>
            <h2 style="font-family:var(--cb-font-display);font-size:1.15rem;font-weight:800;color:var(--cb-navy);margin:0;">
              Método de Pago & Pasarela Online
            </h2>
          </div>

          <div style="display:flex;flex-direction:column;gap:1.25rem;">
            
            <!-- OPCIÓN 1: PASARELA CULQI (YAPE CON CÓDIGO & TARJETAS) - MODO SANDBOX -->
            <div style="border:2px solid #05A9E9;border-radius:10px;padding:1.25rem;background:#F0F9FF;">
              <label style="display:flex;align-items:center;gap:0.6rem;cursor:pointer;margin-bottom:0.5rem;">
                <input type="radio" name="payment_method" value="culqi" checked onchange="togglePaymentTab('culqi')">
                <div style="flex:1;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.5rem;">
                  <strong style="font-size:0.98rem;color:var(--cb-navy);">
                    Pasarela Culqi Online (Yape con código &amp; Tarjetas Crédito/Débito)
                  </strong>
                  <span style="background:#10B981;color:#fff;padding:0.2rem 0.6rem;border-radius:4px;font-size:0.72rem;font-weight:800;letter-spacing:0.5px;">
                    SANDBOX PRUEBAS ACTIVO
                  </span>
                </div>
              </label>

              <div id="paymentBoxCulqi" style="margin-top:0.75rem;background:#FFFFFF;border:1px solid #BAE6FD;border-radius:8px;padding:1.25rem;">
                
                <div style="display:grid;grid-template-columns:auto 1fr;gap:1rem;align-items:start;margin-bottom:1rem;">
                  <div style="display:flex;flex-direction:column;gap:0.4rem;align-items:center;">
                    <div style="background:#711D8D;color:#fff;padding:0.4rem 0.8rem;border-radius:6px;font-weight:900;font-size:0.85rem;letter-spacing:0.5px;">
                      YAPE
                    </div>
                    <div style="font-size:0.7rem;font-weight:800;color:#05A9E9;">
                      VISA / MC
                    </div>
                  </div>
                  <div style="font-size:0.84rem;color:#334155;line-height:1.6;">
                    <strong>Pago 100% Online con Culqi:</strong> Soporta <strong>Yape</strong> (ingresando número celular y el código de aprobación de 6 dígitos) y tarjetas de débito/crédito. Al confirmarse el pago se emitirá al instante tu <strong>Boleta o Factura Electrónica SUNAT</strong> con código QR y Hash SHA-256.
                  </div>
                </div>

                <!-- CAJA DE AYUDA DE MODO PRUEBA -->
                <div style="background:#F8FAFC;border:1px dashed #94A3B8;border-radius:6px;padding:0.75rem 1rem;font-size:0.78rem;color:#475569;margin-bottom:1.25rem;line-height:1.5;">
                  <strong style="color:var(--cb-navy);">Instrucciones para Modo Prueba (Sandbox):</strong><br>
                  &bull; <strong>Para pagar con Yape:</strong> Selecciona Yape en la ventana Culqi, escribe cualquier teléfono de 9 dígitos (ej. 987654321) y el código de aprobación <code>123456</code>.<br>
                  &bull; <strong>Para pagar con Tarjeta:</strong> Usa tarjeta de prueba Culqi (ej: <code>4111 1111 1111 1111</code>, MM/AA futuro, CVV <code>123</code>).<br>
                  &bull; <strong>Simulación en 1 Clic:</strong> Puedes pulsar el botón de prueba rápida abajo para testear la respuesta completa sin abrir el modal.
                </div>

                <!-- BOTONES DE ACCIÓN CULQI -->
                <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
                  <button type="button" onclick="iniciarPagoCulqiModal()" class="cb-btn-hero-primary" style="padding:0.8rem 1.4rem;font-size:0.95rem;background:#161D45;border-color:#161D45;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                    <span>Abrir Pasarela Culqi (Yape / Tarjetas)</span> &rarr;
                  </button>
                  <button type="button" onclick="simularPagoPruebaDirecto()" class="cb-btn-hero-outline" style="padding:0.8rem 1.2rem;font-size:0.88rem;color:#05A9E9;border-color:#05A9E9;background:#F0F9FF;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polyline></svg>
                    <span>⚡ Simular Pago de Prueba (1-Click Sandbox)</span>
                  </button>
                </div>

              </div>
            </div>

            <!-- OPCIÓN 2: TRANSFERENCIA BANCARIA BCP / BBVA -->
            <div style="border:1px solid var(--cb-border);border-radius:8px;padding:1rem;background:#F8FAFC;">
              <label style="display:flex;align-items:center;gap:0.6rem;cursor:pointer;">
                <input type="radio" name="payment_method" value="transferencia" onchange="togglePaymentTab('transferencia')">
                <strong style="font-size:0.92rem;color:var(--cb-navy);">Transferencia Bancaria Directa (BCP / BBVA / Interbank)</strong>
              </label>

              <div id="paymentBoxBanco" style="display:none;margin-top:0.75rem;background:#FFFFFF;border:1px solid #E2E8F0;border-radius:6px;padding:1rem;font-size:0.82rem;color:#334155;line-height:1.7;">
                <div><strong>Banco BCP Soles:</strong> Cta: 191-23456789-0-12 &bull; CCI: 00219100234567890123</div>
                <div><strong>Banco BBVA Soles:</strong> Cta: 0011-0456-0200123456 &bull; CCI: 01145600020012345678</div>
                <div><strong>Titular de Cuenta:</strong> MAK-PC ENTERPRISES S.A.C. &bull; RUC: 20409456520 &bull; Tumbes</div>
              </div>
            </div>

            <!-- OPCIÓN 3: PAGO CONTRAENTREGA EN TALLER -->
            <div style="border:1px solid var(--cb-border);border-radius:8px;padding:1rem;background:#F8FAFC;">
              <label style="display:flex;align-items:center;gap:0.6rem;cursor:pointer;">
                <input type="radio" name="payment_method" value="contraentrega" onchange="togglePaymentTab('contraentrega')">
                <strong style="font-size:0.92rem;color:var(--cb-navy);">Pago Contraentrega en Taller Central (Efectivo o POS)</strong>
              </label>
              <div id="paymentBoxContraentrega" style="display:none;margin-top:0.75rem;background:#FFFFFF;border:1px solid #E2E8F0;border-radius:6px;padding:0.85rem;font-size:0.82rem;color:#475569;">
                Paga presencialmente al retirar en nuestra sede central en Cal. Simón Bolívar Nro. 461 Int. 001, Cercado de Tumbes. Aceptamos efectivo y todas las tarjetas por POS físico.
              </div>
            </div>

          </div>

        </div>

        <!-- BOTÓN PRINCIPAL DE ENVÍO DEL FORMULARIO -->
        <button type="submit" id="btnSubmitOrder" class="cb-btn-hero-primary" style="width:100%;justify-content:center;padding:1rem 2rem;font-size:1.05rem;font-weight:900;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
          <span id="btnSubmitOrderText">Pagar con Culqi Online &amp; Emitir Comprobante SUNAT</span> &rarr;
        </button>

      </form>

      <!-- COLUMNA DERECHA: RESUMEN DE ORDEN -->
      <div style="position:sticky;top:90px;">
        <div style="background:#FFFFFF;border-radius:var(--cb-radius);border:1px solid var(--cb-border);box-shadow:var(--cb-shadow);padding:1.75rem;">
          
          <h2 style="font-family:var(--cb-font-display);font-size:1.2rem;font-weight:900;color:var(--cb-navy);margin:0 0 1rem;padding-bottom:0.75rem;border-bottom:1px solid #F1F5F9;">
            Resumen del Pedido (<span id="chkItemsCount">0</span>)
          </h2>

          <div id="chkItemsList" style="max-height:280px;overflow-y:auto;margin-bottom:1rem;padding-right:0.25rem;">
            <!-- Inyectado por JS -->
          </div>

          <div style="display:flex;flex-direction:column;gap:0.6rem;font-size:0.88rem;color:#475569;border-top:1px solid #F1F5F9;padding-top:1rem;margin-bottom:1rem;">
            <div style="display:flex;justify-content:space-between;">
              <span>Subtotal:</span>
              <strong style="color:var(--cb-navy);" id="chkSubtotal">S/ 0.00</strong>
            </div>
            <div style="display:flex;justify-content:space-between;">
              <span>Costo de Envío:</span>
              <strong style="color:var(--cb-navy);" id="chkShipping">S/ 10.00</strong>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:0.8rem;color:var(--cb-text-muted);">
              <span>IGV (18% incluido SUNAT):</span>
              <span id="chkIgv">S/ 0.00</span>
            </div>
          </div>

          <!-- Total Destacado -->
          <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:var(--cb-radius-sm);padding:1rem 1.25rem;margin-bottom:1.25rem;">
            <div style="display:flex;align-items:baseline;justify-content:space-between;">
              <span style="font-size:0.95rem;font-weight:800;color:var(--cb-navy);">TOTAL FINAL:</span>
              <div style="font-family:var(--cb-font-display);font-size:1.75rem;font-weight:900;color:var(--cb-navy);">
                <span style="font-size:1rem;color:var(--cb-cyan);">S/</span> <span id="chkTotal">0.00</span>
              </div>
            </div>
          </div>

          <!-- Garantías de Facturación SUNAT -->
          <div style="font-size:0.78rem;color:#64748B;line-height:1.6;border-top:1px solid #F1F5F9;padding-top:1rem;">
            <strong style="color:var(--cb-navy);display:block;margin-bottom:0.35rem;">Garantías MAK-PC ENTERPRISES S.A.C.:</strong>
            &bull; Comprobante electrónico oficial SUNAT (Boleta o Factura) con código QR y Hash SHA-256.<br>
            &bull; Descuento de stock en tiempo real en nuestro almacén central de Tumbes.<br>
            &bull; Constancia de pago y voucher Culqi emitidos con código de autorización.<br>
            &bull; Envío inmediato o recojo en Cal. Simón Bolívar Nro. 461 Int. 001.
          </div>

        </div>
      </div>

    </div>

    <!-- PANTALLA DE CONFIRMACIÓN DE COMPRA EXITOSA (Con Comprobante SUNAT y Voucher) -->
    <div id="checkoutSuccessScreen" style="display:none;background:#FFFFFF;border-radius:var(--cb-radius-lg);border:1px solid var(--cb-border);box-shadow:var(--cb-shadow-lg);padding:3rem 2.5rem;text-align:center;max-width:850px;margin:0 auto;">
      
      <div style="width:72px;height:72px;background:rgba(16,185,129,0.1);color:#10B981;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
      </div>

      <div style="display:inline-flex;align-items:center;gap:0.5rem;background:rgba(16,185,129,0.12);color:#059669;font-size:0.8rem;font-weight:800;padding:0.35rem 0.95rem;border-radius:999px;letter-spacing:1px;text-transform:uppercase;margin-bottom:1rem;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span>PAGO APROBADO &bull; COMPROBANTE SUNAT EMITIDO</span>
      </div>

      <h2 style="font-family:var(--cb-font-display);font-size:2rem;font-weight:900;color:var(--cb-navy);margin:0 0 0.5rem;">
        ¡Gracias por tu compra en MAK-PC ENTERPRISES!
      </h2>

      <p style="color:#475569;font-size:0.95rem;line-height:1.6;max-width:620px;margin:0 auto 1.75rem;">
        Tu pago ha sido procesado exitosamente a través de la pasarela Culqi (Sandbox). Se ha emitido tu comprobante electrónico con validez legal ante la SUNAT y se ha reservado el inventario en almacén.
      </p>

      <!-- RECUADRO DESTACADO DEL COMPROBANTE SUNAT -->
      <div style="background:#F0F9FF;border:2px solid #BAE6FD;border-radius:10px;padding:1.5rem;margin-bottom:1.75rem;text-align:left;display:grid;grid-template-columns:1.2fr 1fr;gap:1.5rem;align-items:center;">
        <div>
          <span style="font-size:0.75rem;font-weight:800;color:#0369A1;text-transform:uppercase;letter-spacing:0.5px;">Comprobante Electrónico Emitido</span>
          <div style="font-family:var(--cb-font-display);font-size:1.35rem;font-weight:900;color:var(--cb-navy);margin:0.25rem 0;" id="successComprobanteTitulo">
            Boleta de Venta Electrónica
          </div>
          <div style="font-size:1.2rem;font-weight:900;color:#05A9E9;margin-bottom:0.5rem;" id="successComprobanteNumero">
            B001-00000001
          </div>
          <div style="font-size:0.8rem;color:#475569;">
            <strong>Emisor:</strong> MAK-PC ENTERPRISES S.A.C. (RUC 20409456520)<br>
            <strong>Dirección:</strong> Cal. Simón Bolívar Nro. 461 Int. 001, Cercado de Tumbes<br>
            <strong>Estado SUNAT:</strong> <span style="color:#059669;font-weight:700;">Aceptado / Emitido</span>
          </div>
        </div>

        <div style="background:#FFFFFF;border:1px solid #E2E8F0;border-radius:8px;padding:1rem;font-size:0.8rem;color:#334155;line-height:1.6;">
          <div style="font-weight:800;color:var(--cb-navy);margin-bottom:0.35rem;border-bottom:1px solid #F1F5F9;padding-bottom:0.25rem;">
            Voucher de Transacción Culqi
          </div>
          <strong>Código Pedido:</strong> <span id="successOrderCode" style="font-family:monospace;"><?= $orderCode ?></span><br>
          <strong>ID Transacción:</strong> <span id="successChargeId" style="font-family:monospace;">chr_test_...</span><br>
          <strong>Cód. Autorización:</strong> <span id="successAuthCode" style="font-weight:700;">AUTH-123456</span><br>
          <strong>Medio:</strong> <span id="successBrand">YAPE / TARJETA</span><br>
          <strong>Total Pagado:</strong> <strong style="color:var(--cb-navy);font-size:0.95rem;" id="successTotalAmount">S/ 0.00</strong>
        </div>
      </div>

      <!-- BOTONES DE ACCIÓN POST-PAGO -->
      <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
        
        <!-- BOTÓN PRINCIPAL: VER E IMPRIMIR COMPROBANTE SUNAT -->
        <a href="#" id="btnVerComprobanteSunat" target="_blank" class="cb-btn-hero-primary" style="padding:1rem 2rem;font-size:1.05rem;background:#161D45;border-color:#161D45;box-shadow:0 4px 14px rgba(22,29,69,0.25);">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
          <span>Ver e Imprimir Comprobante SUNAT (Boleta/Factura)</span> &rarr;
        </a>

        <!-- BOTÓN WHATSAPP -->
        <a href="#" id="btnSuccessWhatsApp" target="_blank" class="cb-btn-hero-primary" style="padding:1rem 1.75rem;background:#25D366;border-color:#25D366;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
          <span>Enviar Comprobante por WhatsApp</span> &rarr;
        </a>

        <!-- VOLVER A LA TIENDA -->
        <a href="<?= url('tienda') ?>" class="cb-btn-hero-outline" style="padding:1rem 1.5rem;">
          <span>Seguir Comprando</span>
        </a>

      </div>

    </div>

  </div>

</div>

<!-- OVERLAY DE CARGA DE PAGO -->
<div id="paymentLoadingOverlay" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(15,23,42,0.75);z-index:99999;backdrop-filter:blur(4px);align-items:center;justify-content:center;color:#FFFFFF;flex-direction:column;gap:1rem;">
  <div style="width:48px;height:48px;border:4px solid rgba(255,255,255,0.2);border-top-color:var(--cb-gold);border-radius:50%;animation:spin 0.8s linear infinite;"></div>
  <div style="font-family:var(--cb-font-display);font-size:1.25rem;font-weight:800;" id="loadingOverlayTitle">
    Procesando Transacción con Culqi...
  </div>
  <div style="font-size:0.88rem;color:#CBD5E1;max-width:380px;text-align:center;">
    Validando cargo y generando Boleta / Factura Electrónica SUNAT con código QR y Hash SHA-256...
  </div>
</div>

<style>
@keyframes spin {
  to { transform: rotate(360deg); }
}
</style>

<script>
const CULQI_PUBLIC_KEY = '<?= CULQI_PUBLIC_KEY ?>';
let currentShippingCost = 10;
let lastGeneratedOrderCode = '<?= $orderCode ?>';

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

function cargarProductoPrueba() {
  const demoCart = [{
    id: 1,
    name: 'Monitor Gamer Samsung Odyssey G3 24" 144Hz 1ms',
    price: 689.00,
    qty: 1,
    sku: 'MON-SAM-G3-24'
  }];
  localStorage.setItem('makpc_cart', JSON.stringify(demoCart));
  window.dispatchEvent(new Event('cartUpdated'));
  document.getElementById('emptyCartNotice').style.display = 'none';
  document.getElementById('checkoutMainWrapper').style.display = 'block';
  renderCheckoutSummary();
}

function toggleComprobanteFields() {
  const isFactura = document.querySelector('input[name="tipo_comprobante"]:checked').value === 'factura';
  document.getElementById('fieldsBoleta').style.display = isFactura ? 'none' : 'grid';
  document.getElementById('fieldsFactura').style.display = isFactura ? 'grid' : 'none';

  document.getElementById('doc_numero').required = !isFactura;
  document.getElementById('cliente_nombre').required = !isFactura;
  document.getElementById('ruc_numero').required = isFactura;
  document.getElementById('razon_social').required = isFactura;
}

// Búsqueda en Vivo de DNI (RENIEC) y RUC (SUNAT)
async function buscarDocEnReniecSunat(tipo) {
  const isDni = (tipo === 'dni');
  const numInput = isDni ? document.getElementById('doc_numero') : document.getElementById('ruc_numero');
  const badge = isDni ? document.getElementById('dniStatusBadge') : document.getElementById('rucStatusBadge');
  const btn = isDni ? document.getElementById('btnBuscarDni') : document.getElementById('btnBuscarRuc');
  const val = (numInput.value || '').trim();

  if (isDni && val.length !== 8) {
    alert('Ingrese los 8 dígitos de su DNI para realizar la consulta oficial.');
    numInput.focus();
    return;
  }
  if (!isDni && val.length !== 11) {
    alert('Ingrese los 11 dígitos del RUC para realizar la consulta oficial.');
    numInput.focus();
    return;
  }

  const originalBtnHtml = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="fa-spin"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a10 10 0 0 1 10 10"></path></svg> <span>...</span>';
  badge.style.display = 'inline-block';
  badge.style.color = '#F59E0B';
  badge.textContent = 'Consultando...';

  try {
    const resp = await fetch('<?= url("consulta-documento") ?>?tipo=' + tipo + '&numero=' + encodeURIComponent(val));
    const data = await resp.json();
    if (data.success) {
      badge.style.color = '#10B981';
      badge.textContent = '✓ ' + (data.origen || 'Verificado');
      if (isDni) {
        document.getElementById('cliente_nombre').value = data.nombre_completo;
        document.getElementById('cliente_nombre').style.background = '#ECFDF5';
        setTimeout(() => document.getElementById('cliente_nombre').style.background = '', 1500);
      } else {
        document.getElementById('razon_social').value = data.razon_social;
        document.getElementById('direccion_fiscal').value = data.direccion || '';
        document.getElementById('razon_social').style.background = '#ECFDF5';
        setTimeout(() => document.getElementById('razon_social').style.background = '', 1500);
      }
    } else {
      badge.style.color = '#EF4444';
      badge.textContent = data.message || 'No encontrado';
    }
  } catch (e) {
    badge.style.color = '#EF4444';
    badge.textContent = 'Error de conexión';
  } finally {
    btn.disabled = false;
    btn.innerHTML = originalBtnHtml;
  }
}

// Auto-trigger cuando se completan los 8 o 11 dígitos
document.addEventListener('DOMContentLoaded', function() {
  const dniInp = document.getElementById('doc_numero');
  const rucInp = document.getElementById('ruc_numero');
  if (dniInp) {
    dniInp.addEventListener('input', function() {
      if (this.value.trim().length === 8) {
        buscarDocEnReniecSunat('dni');
      }
    });
  }
  if (rucInp) {
    rucInp.addEventListener('input', function() {
      if (this.value.trim().length === 11) {
        buscarDocEnReniecSunat('ruc');
      }
    });
  }
});

function togglePaymentTab(type) {
  document.getElementById('paymentBoxCulqi').style.display = (type === 'culqi') ? 'block' : 'none';
  document.getElementById('paymentBoxBanco').style.display = (type === 'transferencia') ? 'block' : 'none';
  document.getElementById('paymentBoxContraentrega').style.display = (type === 'contraentrega') ? 'block' : 'none';

  const btnText = document.getElementById('btnSubmitOrderText');
  if (type === 'culqi') {
    btnText.textContent = 'Pagar con Culqi Online & Emitir Comprobante SUNAT';
  } else if (type === 'transferencia') {
    btnText.textContent = 'Confirmar Orden por Transferencia Bancaria';
  } else {
    btnText.textContent = 'Confirmar Orden Contraentrega en Taller';
  }
}

function renderCheckoutSummary() {
  const cart = getCart();
  const listEl = document.getElementById('chkItemsList');
  const countEl = document.getElementById('chkItemsCount');
  
  if (cart.length === 0) {
    document.getElementById('emptyCartNotice').style.display = 'block';
    document.getElementById('checkoutGrid').style.display = 'none';
    return;
  } else {
    document.getElementById('emptyCartNotice').style.display = 'none';
    document.getElementById('checkoutGrid').style.display = 'grid';
  }

  const totalQty = cart.reduce((acc, i) => acc + (parseInt(i.qty) || 1), 0);
  if (countEl) countEl.textContent = totalQty;

  let subtotal = 0;
  listEl.innerHTML = '';

  cart.forEach(item => {
    const price = parseFloat(item.price) || 0;
    const qty = parseInt(item.qty) || 1;
    const itemSub = price * qty;
    subtotal += itemSub;

    const row = document.createElement('div');
    row.style.display = 'flex';
    row.style.justifyContent = 'space-between';
    row.style.alignItems = 'center';
    row.style.padding = '0.5rem 0';
    row.style.borderBottom = '1px solid #F1F5F9';
    row.style.fontSize = '0.85rem';

    row.innerHTML = `
      <div style="flex:1;padding-right:0.5rem;">
        <div style="font-weight:700;color:var(--cb-navy);">${escapeHtml(item.name)}</div>
        <div style="font-size:0.75rem;color:var(--cb-text-muted);">${qty}x ${formatMoney(price)}</div>
      </div>
      <div style="font-weight:800;color:var(--cb-navy);">${formatMoney(itemSub)}</div>
    `;
    listEl.appendChild(row);
  });

  updateCheckoutTotals(subtotal);
}

function updateCheckoutTotals(customSubtotal) {
  const cart = getCart();
  let subtotal = customSubtotal || cart.reduce((acc, i) => acc + (parseFloat(i.price || 0) * (parseInt(i.qty) || 1)), 0);
  
  const shipMethod = document.querySelector('input[name="shipping_method"]:checked')?.value || 'tumbes_express';
  
  if (shipMethod === 'recojo') {
    currentShippingCost = 0;
    document.getElementById('deliveryAddressFields').style.display = 'none';
    document.getElementById('direccion_distrito').required = false;
    document.getElementById('direccion_calle').required = false;
  } else {
    document.getElementById('deliveryAddressFields').style.display = 'grid';
    document.getElementById('direccion_distrito').required = true;
    document.getElementById('direccion_calle').required = true;

    if (shipMethod === 'provincias') {
      currentShippingCost = 18;
    } else {
      currentShippingCost = (subtotal >= 300) ? 0 : 10;
    }
  }

  const labelCostTumbes = document.getElementById('labelCostTumbes');
  if (labelCostTumbes) {
    labelCostTumbes.textContent = (subtotal >= 300) ? 'GRATIS (Promo > S/ 300)' : 'S/ 10.00';
    labelCostTumbes.style.color = (subtotal >= 300) ? 'var(--cb-success)' : 'var(--cb-navy)';
  }

  const total = subtotal + currentShippingCost;
  const igv = total * 0.18 / 1.18;

  document.getElementById('chkSubtotal').textContent = formatMoney(subtotal);
  document.getElementById('chkShipping').textContent = (currentShippingCost === 0) ? 'GRATIS' : formatMoney(currentShippingCost);
  document.getElementById('chkIgv').textContent = formatMoney(igv);
  document.getElementById('chkTotal').textContent = Number(total).toLocaleString('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function validarDatosFacturacion() {
  const tipoComp = document.querySelector('input[name="tipo_comprobante"]:checked').value;
  const clienteTelefono = document.getElementById('cliente_telefono').value.trim();
  const clienteCorreo = document.getElementById('cliente_correo').value.trim();

  if (!clienteTelefono || !clienteCorreo) {
    alert('Por favor completa el teléfono y correo electrónico para la facturación.');
    return null;
  }

  let docNumero = '';
  let clienteNombre = '';

  if (tipoComp === 'factura') {
    docNumero = document.getElementById('ruc_numero').value.trim();
    clienteNombre = document.getElementById('razon_social').value.trim();
    if (!docNumero || docNumero.length !== 11) {
      alert('Para Factura Electrónica debes ingresar un RUC válido de 11 dígitos.');
      return null;
    }
    if (!clienteNombre) {
      alert('Debes ingresar la Razón Social de la empresa.');
      return null;
    }
  } else {
    docNumero = document.getElementById('doc_numero').value.trim();
    clienteNombre = document.getElementById('cliente_nombre').value.trim();
    if (!docNumero) {
      alert('Por favor ingresa tu número de DNI o documento.');
      return null;
    }
    if (!clienteNombre) {
      alert('Por favor ingresa tu nombre completo.');
      return null;
    }
  }

  const shipMethod = document.querySelector('input[name="shipping_method"]:checked')?.value || 'tumbes_express';
  const dep = document.getElementById('direccion_departamento')?.value.trim() || 'Tumbes';
  const dist = document.getElementById('direccion_distrito')?.value.trim() || 'Tumbes';
  const calle = document.getElementById('direccion_calle')?.value.trim() || 'Cal. Simón Bolívar Nro. 461 Int. 001';
  const ref = document.getElementById('direccion_referencia')?.value.trim() || '';

  return {
    tipo_comprobante: tipoComp,
    numero_documento: docNumero,
    cliente_nombre: clienteNombre,
    cliente_telefono: clienteTelefono,
    cliente_correo: clienteCorreo,
    shipping_method: shipMethod,
    direccion_departamento: dep,
    direccion_distrito: dist,
    direccion_calle: calle,
    direccion_referencia: ref
  };
}

// INICIAR MODAL OFICIAL CULQI
function iniciarPagoCulqiModal() {
  const formData = validarDatosFacturacion();
  if (!formData) return;

  const cart = getCart();
  if (cart.length === 0) {
    alert('Tu carrito está vacío.');
    return;
  }

  const subtotal = cart.reduce((acc, i) => acc + (parseFloat(i.price || 0) * (parseInt(i.qty) || 1)), 0);
  const total = subtotal + currentShippingCost;
  const montoCentavos = Math.round(total * 100);

  if (typeof Culqi === 'undefined') {
    alert('Cargando pasarela Culqi... Por favor reintenta en un momento.');
    return;
  }

  Culqi.publicKey = CULQI_PUBLIC_KEY;
  Culqi.settings({
    title: 'MAKPC Enterprises S.A.C.',
    currency: 'PEN',
    amount: montoCentavos,
    order: ''
  });

  Culqi.options({
    lang: 'es',
    installments: false,
    paymentMethods: {
      tarjeta: true,
      yape: true,
      billetera: true,
      bancaMovil: false,
      agente: false,
      cuotealo: false
    }
  });

  Culqi.open();
}

// CALLBACK OFICIAL DE CULQI JS SDK
window.culqi = function() {
  if (Culqi.token) {
    const tokenId = Culqi.token.id;
    Culqi.close();
    ejecutarCargoBackend(tokenId);
  } else if (Culqi.order) {
    const orderId = Culqi.order.id;
    Culqi.close();
    ejecutarCargoBackend(orderId);
  } else if (Culqi.error) {
    const msg = Culqi.error.user_message || Culqi.error.merchant_message || 'Transacción denegada';
    alert('Culqi: ' + msg);
  }
};

// SIMULACIÓN DE PAGO DIRECTA SANDBOX (1-CLICK)
function simularPagoPruebaDirecto() {
  const formData = validarDatosFacturacion();
  if (!formData) return;

  const cart = getCart();
  if (cart.length === 0) {
    alert('Tu carrito está vacío.');
    return;
  }

  // Generar token sandbox simulado
  const testTokenId = 'tkn_test_yape_sandbox_' + Math.random().toString(36).substring(2, 10);
  ejecutarCargoBackend(testTokenId);
}

// LLAMADA AL BACKEND PARA REGISTRAR PEDIDO, DESCONTAR STOCK Y EMITIR COMPROBANTE SUNAT
function ejecutarCargoBackend(tokenId) {
  const formData = validarDatosFacturacion();
  if (!formData) return;

  const cart = getCart();
  const overlay = document.getElementById('paymentLoadingOverlay');
  overlay.style.display = 'flex';

  const payload = {
    token_id: tokenId,
    order_code: lastGeneratedOrderCode,
    tipo_comprobante: formData.tipo_comprobante,
    numero_documento: formData.numero_documento,
    cliente_nombre: formData.cliente_nombre,
    cliente_telefono: formData.cliente_telefono,
    cliente_correo: formData.cliente_correo,
    shipping_method: formData.shipping_method,
    direccion_departamento: formData.direccion_departamento,
    direccion_distrito: formData.direccion_distrito,
    direccion_calle: formData.direccion_calle,
    direccion_referencia: formData.direccion_referencia,
    items: cart
  };

  fetch('<?= url("tienda/procesar-pago-culqi") ?>', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    },
    body: JSON.stringify(payload)
  })
  .then(res => res.json())
  .then(data => {
    overlay.style.display = 'none';
    if (data.success) {
      mostrarPantallaExito(data, formData);
    } else {
      alert('Error en el cobro: ' + (data.message || 'No se pudo procesar la transacción'));
    }
  })
  .catch(err => {
    overlay.style.display = 'none';
    alert('Error al comunicar con el servidor: ' + err.message);
  });
}

function mostrarPantallaExito(res, formData) {
  // Llenar datos de comprobante SUNAT y voucher
  document.getElementById('successOrderCode').textContent = res.codigo_pedido || lastGeneratedOrderCode;
  document.getElementById('successComprobanteTitulo').textContent = res.tipo_comprobante || 'Comprobante Electrónico';
  document.getElementById('successComprobanteNumero').textContent = res.numero_comprobante || 'B001-00000001';
  document.getElementById('successChargeId').textContent = res.charge_id || 'chr_test_sandbox';
  document.getElementById('successAuthCode').textContent = res.authorization_code || 'AUTH-TEST';
  document.getElementById('successBrand').textContent = res.brand || 'YAPE SANDBOX';
  document.getElementById('successTotalAmount').textContent = 'S/ ' + Number(res.total || 0).toLocaleString('es-PE', { minimumFractionDigits: 2 });

  // Enlace directo al comprobante legal SUNAT y voucher
  const urlComprobante = res.comprobante_url || '<?= url("tienda/comprobante") ?>/' + (res.codigo_pedido || lastGeneratedOrderCode);
  document.getElementById('btnVerComprobanteSunat').href = urlComprobante;

  // Mensaje para WhatsApp de coordinación
  let waText = `¡HOLA MAKPC ENTERPRISES TALLER TUMBES!\n`;
  waText += `He realizado el pago online de mi orden *${res.codigo_pedido || lastGeneratedOrderCode}*:\n\n`;
  waText += `*Comprobante Emitido:* ${res.numero_comprobante} (${res.tipo_comprobante})\n`;
  waText += `*Cliente:* ${formData.cliente_nombre}\n`;
  waText += `*Doc:* ${formData.numero_documento}\n`;
  waText += `*Total Pagado:* S/ ${res.total}\n`;
  waText += `*Voucher Culqi:* ${res.charge_id} (${res.brand})\n`;
  waText += `*Comprobante Web:* ${urlComprobante}\n\n`;
  waText += `Favor de proceder con la preparación y despacho express. ¡Gracias!`;

  document.getElementById('btnSuccessWhatsApp').href = `https://wa.me/51975513327?text=${encodeURIComponent(waText)}`;

  // Ocultar formulario y mostrar confirmación
  document.getElementById('checkoutGrid').style.display = 'none';
  document.getElementById('checkoutSuccessScreen').style.display = 'block';

  // Vaciar carrito
  localStorage.removeItem('makpc_cart');
  window.dispatchEvent(new Event('cartUpdated'));

  window.scrollTo({ top: 40, behavior: 'smooth' });
}

function handleFormSubmit(e) {
  e.preventDefault();
  const payMethod = document.querySelector('input[name="payment_method"]:checked')?.value || 'culqi';
  
  if (payMethod === 'culqi') {
    iniciarPagoCulqiModal();
  } else {
    // Modo tradicional (Transferencia o Contraentrega)
    const formData = validarDatosFacturacion();
    if (!formData) return;
    
    // Simular pedido offline / contraentrega
    const fakeToken = 'offline_' + payMethod + '_' + Date.now();
    ejecutarCargoBackend(fakeToken);
  }
}

function escapeHtml(str) {
  if (!str) return '';
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

document.addEventListener('DOMContentLoaded', function() {
  renderCheckoutSummary();
});
</script>
