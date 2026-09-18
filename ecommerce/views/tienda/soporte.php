<?php
$flash = getFlash();
if ($flash): ?>
  <div style="max-width:1360px;margin:1rem auto;padding:0 1.5rem;">
    <div class="alert alert-<?= e($flash['type']) ?>">
      <span><?= e($flash['message']) ?></span>
    </div>
  </div>
<?php endif; ?>

<div style="max-width:1360px;margin:1.5rem auto 3.5rem;padding:0 1.5rem;">
  
  <!-- HERO BANNER SERVICIO TÉCNICO -->
  <div style="background:linear-gradient(135deg, #161D45 0%, #0D122E 70%, #05A9E9 150%);border-radius:var(--cb-radius-lg);padding:3rem 2.5rem;color:#FFFFFF;margin-bottom:2.5rem;border:1px solid rgba(255,255,255,0.1);box-shadow:var(--cb-shadow-lg);position:relative;overflow:hidden;">
    <span style="background:var(--cb-gold);color:var(--cb-navy);font-weight:800;padding:0.35rem 0.85rem;border-radius:999px;font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;display:inline-flex;align-items:center;gap:0.4rem;">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
      LABORATORIO & TALLER ESPECIALIZADO
    </span>
    
    <h1 style="font-family:var(--cb-font-display);font-size:2.2rem;font-weight:900;color:#ffffff;margin:1rem 0 0.5rem 0;line-height:1.2;">
      Servicio Técnico Oficial & Atención al Cliente MAKPC
    </h1>
    
    <p style="color:#CBD5E1;max-width:700px;line-height:1.6;font-size:0.95rem;margin-bottom:1.5rem;">
      Diagnóstico profesional, mantenimiento preventivo y reparación de laptops, tarjetas gráficas y PCs de alto rendimiento con trazabilidad por número de serie para cada repuesto.
    </p>

    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
      <a href="https://wa.me/51975513327?text=Hola%20MAKPC,%20necesito%20asistencia%20tecnica%20para%20mi%20computadora" target="_blank" class="cb-btn-hero-primary">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
        <span>Chatear con Soporte Técnico en Vivo</span>
      </a>
      <a href="<?= url('tienda/crear-pc') ?>" class="cb-btn-hero-outline">
        <span>Cotizar Ensamble de PC Nueva &rarr;</span>
      </a>
    </div>
  </div>

  <!-- RASTREADOR DE ÓRDENES Y TICKETS EN VIVO -->
  <div style="background:#FFFFFF;border-radius:var(--cb-radius);border:1px solid var(--cb-border);box-shadow:var(--cb-shadow-sm);padding:2rem;margin-bottom:2.5rem;" id="rastreador-en-linea">
    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1.25rem;">
      <div style="width:40px;height:40px;background:rgba(5,169,233,0.1);color:var(--cb-cyan);border-radius:8px;display:flex;align-items:center;justify-content:center;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
      </div>
      <div>
        <h2 style="font-family:var(--cb-font-display);font-size:1.3rem;font-weight:900;color:var(--cb-navy);margin:0;">
          Rastrear Estado de Orden de Servicio o Ticket
        </h2>
        <span style="font-size:0.82rem;color:var(--cb-text-muted);">
          Consulta el avance técnico de tu computadora o solicitud de garantía en tiempo real
        </span>
      </div>
    </div>

    <!-- Formulario de Consulta -->
    <form action="<?= url('tienda/soporte') ?>" method="GET" style="display:flex;gap:0.75rem;max-width:700px;margin-bottom:1.25rem;flex-wrap:wrap;">
      <input 
        type="text" 
        name="buscar" 
        class="form-control" 
        value="<?= e($terminoBusqueda ?? '') ?>" 
        placeholder="Ej: ORD-2026-0001, TCK-2026-0001 o tu número de teléfono..." 
        style="flex:1;min-width:240px;padding:0.7rem 1rem;font-size:0.9rem;"
        required
      >
      <button type="submit" class="cb-btn-hero-primary" style="padding:0.7rem 1.5rem;white-space:nowrap;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <span>Consultar Avance</span>
      </button>
      <?php if (!empty($terminoBusqueda)): ?>
        <a href="<?= url('tienda/soporte') ?>" style="display:inline-flex;align-items:center;padding:0.7rem 1rem;border:1px solid var(--cb-border);border-radius:var(--cb-radius-sm);color:var(--cb-text-muted);text-decoration:none;font-size:0.85rem;background:#F8FAFC;">
          Limpiar
        </a>
      <?php endif; ?>
    </form>

    <!-- RESULTADO DE ORDEN DE SERVICIO ENCONTRADA -->
    <?php if (!empty($resultadoOrden)): 
      $estado = $resultadoOrden['estado'];
      $pct = 25;
      if ($estado === 'En Reparacion') $pct = 65;
      elseif ($estado === 'Terminado') $pct = 90;
      elseif ($estado === 'Entregado') $pct = 100;
    ?>
      <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:var(--cb-radius);padding:1.5rem;margin-top:1.5rem;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:0.75rem;">
          <div>
            <span style="font-size:0.75rem;font-weight:800;color:var(--cb-cyan);letter-spacing:1px;text-transform:uppercase;">ORDEN DE SERVICIO TÉCNICO</span>
            <h3 style="font-family:var(--cb-font-display);font-size:1.4rem;font-weight:900;color:var(--cb-navy);margin:0.2rem 0 0;">
              Código: <?= e($resultadoOrden['codigo_orden']) ?>
            </h3>
          </div>
          <span style="background:var(--cb-navy);color:var(--cb-gold);font-weight:800;padding:0.4rem 0.9rem;border-radius:999px;font-size:0.82rem;">
            Estado: <?= e($estado) ?>
          </span>
        </div>

        <!-- Barra de Progreso de Taller -->
        <div style="margin-bottom:1.5rem;">
          <div style="display:flex;justify-content:space-between;font-size:0.75rem;font-weight:700;color:var(--cb-text-muted);margin-bottom:0.5rem;text-transform:uppercase;">
            <span style="<?= ($pct >= 25) ? 'color:var(--cb-cyan);' : '' ?>">1. Recepcionado</span>
            <span style="<?= ($pct >= 50) ? 'color:var(--cb-cyan);' : '' ?>">2. Diagnóstico</span>
            <span style="<?= ($pct >= 65) ? 'color:var(--cb-cyan);' : '' ?>">3. En Reparación</span>
            <span style="<?= ($pct >= 90) ? 'color:var(--cb-success);' : '' ?>">4. Listo para Retiro</span>
          </div>
          <div style="height:10px;background:#E2E8F0;border-radius:999px;overflow:hidden;">
            <div style="height:100%;width:<?= $pct ?>%;background:linear-gradient(90deg, var(--cb-cyan), var(--cb-gold));border-radius:999px;transition:width 0.5s ease;"></div>
          </div>
        </div>

        <!-- Ficha de Detalles de la Orden -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:1rem;font-size:0.85rem;color:#334155;background:#FFFFFF;border:1px solid #E2E8F0;border-radius:8px;padding:1.25rem;">
          <div>
            <strong>Cliente:</strong> <?= e($resultadoOrden['cliente_nombre'] ?? 'Cliente MAKPC') ?><br>
            <strong>Equipo:</strong> <?= e(($resultadoOrden['marca'] ?? '') . ' ' . ($resultadoOrden['modelo'] ?? '')) ?><br>
            <strong>Fecha Recepción:</strong> <?= e(date('d/m/Y H:i', strtotime($resultadoOrden['fecha_recepcion']))) ?>
          </div>
          <div>
            <strong>Falla Reportada:</strong> <?= e($resultadoOrden['falla_reportada'] ?: 'Revisión general') ?><br>
            <strong>Diagnóstico:</strong> <?= e($resultadoOrden['diagnostico'] ?: 'En proceso de evaluación técnica') ?><br>
            <strong>Técnico Responsable:</strong> <?= e($resultadoOrden['tecnico_responsable'] ?: 'Laboratorio Central') ?>
          </div>
          <div>
            <strong>Costo Total Estimado:</strong> S/ <?= number_format((float)($resultadoOrden['costo_total'] ?? 0), 2) ?><br>
            <strong>Adelanto Abonado:</strong> S/ <?= number_format((float)($resultadoOrden['adelanto'] ?? 0), 2) ?><br>
            <strong>Garantía:</strong> <?= (int)($resultadoOrden['garantia_meses'] ?? 3) ?> meses
          </div>
        </div>

        <div style="margin-top:1rem;display:flex;justify-content:flex-end;">
          <a 
            href="https://wa.me/51975513327?text=Hola%20MAKPC,%20deseo%20consultar%20el%20avance%20de%20mi%20orden:%20<?= urlencode($resultadoOrden['codigo_orden']) ?>" 
            target="_blank" 
            class="cb-btn-hero-primary"
            style="font-size:0.85rem;padding:0.6rem 1.2rem;background:#25D366;border-color:#25D366;"
          >
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
            <span>Consultar con el Técnico a Cargo en WhatsApp</span>
          </a>
        </div>
      </div>
    <?php elseif (!empty($resultadoPedido)): 
      $despacho = $resultadoPedido['estado_despacho'] ?: 'En preparación';
      $pctPed = 25;
      if ($despacho === 'En preparación') $pctPed = 50;
      elseif ($despacho === 'Enviado') $pctPed = 75;
      elseif ($despacho === 'Entregado') $pctPed = 100;
    ?>
      <div style="background:#F0F9FF;border:1px solid #BAE6FD;border-radius:var(--cb-radius);padding:1.5rem;margin-top:1.5rem;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:0.75rem;">
          <div>
            <span style="font-size:0.75rem;font-weight:800;color:#0284C7;letter-spacing:1px;text-transform:uppercase;">VENTA E-COMMERCE &bull; MAKPC STORE</span>
            <h3 style="font-family:var(--cb-font-display);font-size:1.4rem;font-weight:900;color:var(--cb-navy);margin:0.2rem 0 0;">
              Orden: <?= e($resultadoPedido['codigo_pedido']) ?>
            </h3>
          </div>
          <div style="display:flex;gap:0.5rem;align-items:center;">
            <span style="background:#10B981;color:#fff;font-weight:800;padding:0.35rem 0.85rem;border-radius:999px;font-size:0.8rem;">
              Pago: <?= e($resultadoPedido['estado_pago']) ?>
            </span>
            <span style="background:var(--cb-navy);color:#fff;font-weight:800;padding:0.35rem 0.85rem;border-radius:999px;font-size:0.8rem;">
              Despacho: <?= e($despacho) ?>
            </span>
          </div>
        </div>

        <!-- Barra de Progreso de Despacho -->
        <div style="margin-bottom:1.5rem;">
          <div style="display:flex;justify-content:space-between;font-size:0.75rem;font-weight:700;color:var(--cb-text-muted);margin-bottom:0.5rem;text-transform:uppercase;">
            <span style="<?= ($pctPed >= 25) ? 'color:#0284C7;' : '' ?>">1. Pago Aprobado</span>
            <span style="<?= ($pctPed >= 50) ? 'color:#0284C7;' : '' ?>">2. En Preparación (Taller)</span>
            <span style="<?= ($pctPed >= 75) ? 'color:#0284C7;' : '' ?>">3. Enviado / En Ruta</span>
            <span style="<?= ($pctPed >= 100) ? 'color:var(--cb-success);' : '' ?>">4. Entregado</span>
          </div>
          <div style="height:10px;background:#E2E8F0;border-radius:999px;overflow:hidden;">
            <div style="height:100%;width:<?= $pctPed ?>%;background:linear-gradient(90deg, #05A9E9, #10B981);border-radius:999px;transition:width 0.5s ease;"></div>
          </div>
        </div>

        <!-- Ficha de Detalles del Pedido -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:1rem;font-size:0.85rem;color:#334155;background:#FFFFFF;border:1px solid #BAE6FD;border-radius:8px;padding:1.25rem;">
          <div>
            <strong>Cliente:</strong> <?= e($resultadoPedido['cliente_nombre']) ?><br>
            <strong>Documento:</strong> <?= e($resultadoPedido['tipo_documento']) ?> <?= e($resultadoPedido['numero_documento']) ?><br>
            <strong>Destino:</strong> <?= e($resultadoPedido['direccion_distrito']) ?>, <?= e($resultadoPedido['direccion_departamento']) ?>
          </div>
          <div>
            <strong>Medio de Pago:</strong> <?= e($resultadoPedido['culqi_brand'] ?: $resultadoPedido['metodo_pago']) ?><br>
            <strong>Comprobante:</strong> <strong style="color:var(--cb-navy);"><?= e($resultadoPedido['comprobante_numero'] ?: 'Emitido') ?></strong><br>
            <strong>Fecha de Compra:</strong> <?= e(date('d/m/Y H:i', strtotime($resultadoPedido['creado_en']))) ?>
          </div>
          <div>
            <strong>Total Pagado:</strong> <strong style="font-size:1.1rem;color:var(--cb-navy);">S/ <?= number_format((float)$resultadoPedido['total'], 2) ?></strong><br>
            <strong>Entrega:</strong> <?= e($resultadoPedido['metodo_envio']) ?>
          </div>
        </div>

        <div style="margin-top:1rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.75rem;">
          <a 
            href="<?= url("tienda/comprobante/{$resultadoPedido['codigo_pedido']}") ?>" 
            target="_blank" 
            class="cb-btn-hero-primary"
            style="font-size:0.85rem;padding:0.6rem 1.2rem;background:var(--cb-navy);border-color:var(--cb-navy);"
          >
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
            <span>Ver e Imprimir Comprobante SUNAT (Boleta/Factura)</span>
          </a>

          <a 
            href="https://wa.me/51975513327?text=Hola%20MAKPC,%20consulto%20por%20mi%20orden%20web:%20<?= urlencode($resultadoPedido['codigo_pedido']) ?>" 
            target="_blank" 
            class="cb-btn-hero-primary"
            style="font-size:0.85rem;padding:0.6rem 1.2rem;background:#25D366;border-color:#25D366;"
          >
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
            <span>Coordinar Despacho por WhatsApp</span>
          </a>
        </div>
      </div>
    <?php elseif (!empty($resultadoTicket)): ?>
      <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:var(--cb-radius);padding:1.5rem;margin-top:1.5rem;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;flex-wrap:wrap;gap:0.75rem;">
          <div>
            <span style="font-size:0.75rem;font-weight:800;color:var(--cb-gold);letter-spacing:1px;text-transform:uppercase;">TICKET DE ATENCIÓN AL CLIENTE</span>
            <h3 style="font-family:var(--cb-font-display);font-size:1.3rem;font-weight:900;color:var(--cb-navy);margin:0.2rem 0 0;">
              Ticket #<?= e($resultadoTicket['codigo_ticket']) ?> &mdash; <?= e($resultadoTicket['asunto']) ?>
            </h3>
          </div>
          <span style="background:var(--cb-navy);color:#fff;font-weight:800;padding:0.35rem 0.8rem;border-radius:6px;font-size:0.8rem;">
            <?= e($resultadoTicket['estado']) ?>
          </span>
        </div>

        <div style="font-size:0.85rem;color:#475569;background:#FFFFFF;border:1px solid #E2E8F0;border-radius:8px;padding:1.25rem;line-height:1.6;">
          <strong>Solicitante:</strong> <?= e($resultadoTicket['nombre_solicitante']) ?> (<?= e($resultadoTicket['telefono_solicitante']) ?>)<br>
          <strong>Fecha de Registro:</strong> <?= e(date('d/m/Y H:i', strtotime($resultadoTicket['creado_en']))) ?><br>
          <strong>Detalle:</strong> <?= nl2br(e($resultadoTicket['descripcion'])) ?>
          <?php if (!empty($resultadoTicket['respuesta'])): ?>
            <div style="margin-top:0.75rem;padding-top:0.75rem;border-top:1px dashed #CBD5E1;color:var(--cb-navy);">
              <strong>Respuesta Técnica Oficial:</strong><br>
              <?= nl2br(e($resultadoTicket['respuesta'])) ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php elseif (!empty($terminoBusqueda)): ?>
      <div style="background:#FEF2F2;border:1px solid #FCA5A5;border-radius:var(--cb-radius);padding:1.25rem;color:#991B1B;font-size:0.88rem;display:flex;align-items:center;gap:0.75rem;margin-top:1.25rem;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <div>
          No encontramos órdenes o tickets con el criterio <strong>"<?= e($terminoBusqueda) ?>"</strong>. Verifica el número de ticket/orden o regístralo a continuación para atención inmediata.
        </div>
      </div>
    <?php endif; ?>

  </div>

  <!-- GRID DE SOPORTE & FORMULARIO -->
  <div style="display:grid;grid-template-columns:1.3fr 1fr;gap:2rem;align-items:start;">
    
    <!-- FORMULARIO DE TICKET -->
    <div style="background:#FFFFFF;border-radius:var(--cb-radius);border:1px solid var(--cb-border);box-shadow:var(--cb-shadow-sm);padding:2rem;">
      <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:1.25rem;padding-bottom:1rem;border-bottom:1px solid #F1F5F9;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--cb-cyan)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        <h3 style="font-family:var(--cb-font-display);font-size:1.2rem;font-weight:800;color:var(--cb-navy);margin:0;">
          Registrar Solicitud de Reparación o Garantía
        </h3>
      </div>

      <form action="<?= url('tienda/soporte') ?>" method="POST">
        <?= csrf_field() ?>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
          <div>
            <label style="display:block;font-size:0.82rem;font-weight:700;color:var(--cb-navy);margin-bottom:0.35rem;">Nombres y Apellidos *</label>
            <input type="text" id="nombre_solicitante" name="nombre_solicitante" class="form-control" required placeholder="Ej: Juan Pérez">
          </div>

          <div>
            <label style="display:block;font-size:0.82rem;font-weight:700;color:var(--cb-navy);margin-bottom:0.35rem;">WhatsApp de Contacto *</label>
            <input type="text" id="telefono_solicitante" name="telefono_solicitante" class="form-control" required placeholder="Ej: 987 654 321">
          </div>

          <div>
            <label style="display:block;font-size:0.82rem;font-weight:700;color:var(--cb-navy);margin-bottom:0.35rem;">Correo Electrónico</label>
            <input type="email" id="correo_solicitante" name="correo_solicitante" class="form-control" placeholder="Ej: cliente@correo.com">
          </div>

          <div>
            <label style="display:block;font-size:0.82rem;font-weight:700;color:var(--cb-navy);margin-bottom:0.35rem;">Tipo de Requerimiento</label>
            <select name="tipo" id="tipo" class="form-control">
              <option value="Soporte Tecnico">Reparación / Mantenimiento en Taller</option>
              <option value="Consulta">Consulta sobre Componentes y Precios</option>
              <option value="Garantia">Validación de Garantía por Serie</option>
              <option value="Reclamo">Atención Comercial</option>
            </select>
          </div>

          <div style="grid-column:1 / -1;">
            <label style="display:block;font-size:0.82rem;font-weight:700;color:var(--cb-navy);margin-bottom:0.35rem;">Asunto / Equipo a Atender *</label>
            <input type="text" id="asunto" name="asunto" class="form-control" required placeholder="Ej: Laptop ASUS TUF no enciende tras corte eléctrico">
          </div>

          <div style="grid-column:1 / -1;">
            <label style="display:block;font-size:0.82rem;font-weight:700;color:var(--cb-navy);margin-bottom:0.35rem;">Descripción Detallada del Problema *</label>
            <textarea id="descripcion" name="descripcion" class="form-control" required style="min-height:120px;" placeholder="Indique modelo exacto del equipo, síntomas de la falla y antecedentes..."></textarea>
          </div>
        </div>

        <div style="margin-top:1.5rem;display:flex;justify-content:flex-end;">
          <button type="submit" class="cb-btn-hero-primary" style="padding:0.75rem 1.75rem;">
            <span>Enviar Solicitud de Soporte</span> &rarr;
          </button>
        </div>
      </form>
    </div>

    <!-- COLUMNA DERECHA: INFORMACIÓN DEL TALLER Y GARANTÍAS -->
    <div style="display:flex;flex-direction:column;gap:1.5rem;">
      
      <!-- Sede y Atención -->
      <div style="background:#FFFFFF;border-radius:var(--cb-radius);border:1px solid var(--cb-border);box-shadow:var(--cb-shadow-sm);padding:1.5rem;">
        <h4 style="font-family:var(--cb-font-display);font-size:1.05rem;font-weight:800;color:var(--cb-navy);margin:0 0 1rem;display:flex;align-items:center;gap:0.45rem;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--cb-cyan)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
          Sede Principal & Atención Presencial
        </h4>
        
        <div style="font-size:0.85rem;color:#475569;line-height:1.7;display:flex;flex-direction:column;gap:0.6rem;">
          <div>
            <strong>Dirección de Taller:</strong><br>
            Cal. Simón Bolívar Nro. 461 Int. 001, Cercado de Tumbes, Tumbes.
          </div>
          <div>
            <strong>Teléfonos & WhatsApp:</strong><br>
            WhatsApp & Asesoría Directa: +51 975 513 327
          </div>
          <div>
            <strong>Horario de Operación:</strong><br>
            Lunes a Sábado: 9:00 AM &ndash; 8:00 PM (Recepción continua)
          </div>
        </div>
      </div>

      <!-- Especialidades de Taller -->
      <div style="background:#FFFFFF;border-radius:var(--cb-radius);border:1px solid var(--cb-border);box-shadow:var(--cb-shadow-sm);padding:1.5rem;">
        <h4 style="font-family:var(--cb-font-display);font-size:1.05rem;font-weight:800;color:var(--cb-navy);margin:0 0 1rem;display:flex;align-items:center;gap:0.45rem;">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--cb-gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
          Especialidades Técnicas MAKPC
        </h4>

        <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:0.75rem;font-size:0.85rem;color:#334155;">
          <li style="display:flex;align-items:flex-start;gap:0.5rem;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--cb-cyan)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <div><strong>Mantenimiento Preventivo:</strong> Limpieza por ultrasonido, cambio de pasta térmica Arctic MX-4 y termal pads de alta conductividad.</div>
          </li>
          <li style="display:flex;align-items:flex-start;gap:0.5rem;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--cb-cyan)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <div><strong>Upgrades de Velocidad:</strong> Migración de sistemas a SSD NVMe Gen4 sin pérdida de archivos y ampliación de RAM DDR4/DDR5.</div>
          </li>
          <li style="display:flex;align-items:flex-start;gap:0.5rem;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--cb-cyan)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <div><strong>Reparación a Nivel de Componente:</strong> Diagnóstico de circuitos integrados, MOSFETs, reballing y reparación de bisagras.</div>
          </li>
          <li style="display:flex;align-items:flex-start;gap:0.5rem;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--cb-cyan)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:2px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <div><strong>Trazabilidad por Serie:</strong> Cada pieza instalada cuenta con registro fotográfico y garantía formal en boleta/factura.</div>
          </li>
        </ul>
      </div>

    </div>

  </div>

</div>
