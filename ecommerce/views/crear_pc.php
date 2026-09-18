<?php
/**
 * MAKPC - Estudio Interactivo "Crea tu PC"
 * Sistema Inteligente Anti-Cuello de Botella & Presupuesto a Medida
 * @var array $componentes
 * @var array $presets
 * @var array $accesorios
 */
?>

<div class="cb-builder-page">
  
  <!-- CABECERA DEL ESTUDIO CREA TU PC -->
  <div class="cb-builder-header">
    <div class="cb-builder-header-top">
      <div>
        <div class="cb-chip gold" style="margin-bottom:0.75rem;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
          <span>ASESOR DE HARDWARE & COMPATIBILIDAD INTELIGENTE</span>
        </div>
        <h1>Arma tu PC a Medida con <span class="gold">Cero Cuello de Botella</span></h1>
      </div>
      <div style="text-align:right;">
        <span style="font-size:0.8rem;color:#94A3B8;display:block;">TALLER MAKPC</span>
        <span style="color:var(--cb-cyan);font-weight:700;font-size:0.9rem;">✓ Ensamble & Testeo Gratis</span>
      </div>
    </div>

    <p>
      Diseña tu computadora paso a paso o elige una configuración probada. Nuestro sistema detecta automáticamente la compatibilidad física de sockets, calcula el consumo real en Watts y te advierte si algún componente limitará el rendimiento de los demás, garantizando que tu dinero rinda al 100%.
    </p>

    <!-- TABS PRINCIPALES -->
    <div class="cb-builder-tabs">
      <button type="button" class="cb-tab-btn active" id="tabBtnPresets" onclick="switchBuilderTab('presets')">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
        <span>1. Opciones Recomendadas</span>
      </button>
      <button type="button" class="cb-tab-btn" id="tabBtnStudio" onclick="switchBuilderTab('studio')">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
        <span>2. Armador Paso a Paso</span>
      </button>
      <button type="button" class="cb-tab-btn" id="tabBtnAccessories" onclick="switchBuilderTab('accessories')">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
        <span>3. Accesorios & Setup</span>
      </button>
    </div>
  </div>

  <!-- ========================================================
       SECCIÓN 1: OPCIONES RECOMENDADAS (PRESETS)
       ======================================================== -->
  <section id="sectionPresets" class="cb-builder-section">
    <div class="cb-section-header">
      <div>
        <h2 class="cb-section-title">Configuraciones Recomendadas por Ingenieros</h2>
        <p style="font-size:0.88rem;color:var(--cb-text-muted);margin-top:0.25rem;">
          Ensambles optimizados para cada presupuesto y propósito. Cárgalas al armador para personalizarlas a tu gusto.
        </p>
      </div>
    </div>

    <div class="cb-presets-grid">
      <?php foreach ($presets as $pKey => $p): ?>
        <article class="cb-preset-card <?= ($pKey === 'gaming_entry') ? 'highlight' : '' ?>">
          <div class="cb-preset-head">
            <span class="cb-preset-badge" style="background:<?= $p['badge_color'] ?>;color:<?= ($pKey === 'gaming_entry') ? 'var(--cb-navy)' : '#fff' ?>;">
              <?= e($p['badge']) ?>
            </span>
            <div style="height:140px;border-radius:var(--cb-radius-sm);overflow:hidden;margin:0.75rem 0;background:#F1F5F9;">
              <img src="<?= asset('img/productos/' . ($p['imagen'] ?? 'preset_1.jpg')) ?>" alt="<?= e($p['titulo']) ?>" style="width:100%;height:100%;object-fit:cover;" loading="lazy">
            </div>
            <h3 class="cb-preset-title"><?= e($p['titulo']) ?></h3>
            <p class="cb-preset-sub"><?= e($p['subtitulo']) ?></p>
          </div>

          <div class="cb-preset-body">
            
            <!-- Bottleneck Gauge -->
            <div class="cb-bottleneck-indicator <?= $p['bottleneck_class'] ?>">
              <span class="cb-bottleneck-badge <?= $p['bottleneck_class'] ?>">
                <?= e($p['bottleneck_badge']) ?>
              </span>
              <span class="cb-bottleneck-text">
                <?= e($p['bottleneck_desc']) ?>
              </span>
            </div>

            <!-- Specs Checklist -->
            <div style="font-size:0.75rem;font-weight:700;color:var(--cb-text-muted);text-transform:uppercase;margin-bottom:0.5rem;letter-spacing:0.5px;">
              Puntos Destacados:
            </div>
            <ul class="cb-preset-specs">
              <?php foreach ($p['puntos_clave'] as $pk): ?>
                <li><?= e($pk) ?></li>
              <?php endforeach; ?>
            </ul>

            <!-- Benchmark Bars -->
            <div style="background:#F8FAFC;border-radius:var(--cb-radius-sm);padding:0.75rem;margin-bottom:1.25rem;">
              <div style="font-size:0.72rem;font-weight:700;color:var(--cb-text-muted);text-transform:uppercase;margin-bottom:0.4rem;">
                Desempeño Estimado:
              </div>
              <div style="display:flex;flex-direction:column;gap:0.4rem;font-size:0.75rem;">
                <div>
                  <div style="display:flex;justify-content:space-between;margin-bottom:2px;">
                    <span>Ofimática / Multitarea</span>
                    <strong><?= $p['rendimiento']['ofimatica'] ?>%</strong>
                  </div>
                  <div class="cb-meter-track"><div class="cb-meter-progress" style="width:<?= $p['rendimiento']['ofimatica'] ?>%;background:var(--cb-cyan);"></div></div>
                </div>
                <div>
                  <div style="display:flex;justify-content:space-between;margin-bottom:2px;">
                    <span>Gaming 1080p</span>
                    <strong><?= $p['rendimiento']['gaming_1080p'] ?>%</strong>
                  </div>
                  <div class="cb-meter-track"><div class="cb-meter-progress" style="width:<?= $p['rendimiento']['gaming_1080p'] ?>%;background:var(--cb-gold);"></div></div>
                </div>
                <div>
                  <div style="display:flex;justify-content:space-between;margin-bottom:2px;">
                    <span>Render 3D / Video</span>
                    <strong><?= $p['rendimiento']['render_3d'] ?>%</strong>
                  </div>
                  <div class="cb-meter-track"><div class="cb-meter-progress" style="width:<?= $p['rendimiento']['render_3d'] ?>%;background:#8B5CF6;"></div></div>
                </div>
              </div>
            </div>

            <!-- Price & Action -->
            <div class="cb-preset-price-box">
              <div class="cb-preset-price-row">
                <div>
                  <span style="font-size:0.75rem;color:#94A3B8;text-decoration:line-through;display:block;">
                    S/ <?= number_format($p['precio_regular'], 2) ?>
                  </span>
                  <div class="cb-preset-price">
                    <span style="font-size:0.95rem;color:var(--cb-cyan);">S/</span> <?= number_format($p['precio'], 2) ?>
                  </div>
                </div>
                <span class="cb-preset-saving">
                  Ahorras S/ <?= number_format($p['ahorro'], 2) ?>
                </span>
              </div>

              <button 
                type="button" 
                class="cb-btn-load-preset"
                onclick="loadPreset('<?= $pKey ?>')"
              >
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                <span>Cargar en el Armador</span> &rarr;
              </button>
            </div>

          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- ========================================================
       SECCIÓN 2: ARMADOR INTERACTIVO PASO A PASO (EL ESTUDIO)
       ======================================================== -->
  <section id="sectionStudio" class="cb-builder-section" style="display:none;">
    
    <div class="cb-studio-layout">
      
      <!-- COLUMNA IZQUIERDA: SLOTS DE COMPONENTES -->
      <div class="cb-slots-container">
        
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.5rem;">
          <h2 class="cb-section-title" style="font-size:1.25rem;">Componentes del Ensamble</h2>
          <button type="button" class="btn-drawer-outline" style="font-size:0.8rem;padding:0.35rem 0.75rem;display:inline-flex;align-items:center;gap:0.35rem;" onclick="resetBuilder()">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
            <span>Reiniciar Armador</span>
          </button>
        </div>

        <?php
        $slotDefinitions = [
            'procesador' => ['num' => 1, 'name' => 'Procesador (CPU)', 'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="16" x="4" y="4" rx="2"/><rect width="6" height="6" x="9" y="9" rx="1"/><path d="M15 2v2"/><path d="M15 20v2"/><path d="M2 15h2"/><path d="M2 9h2"/><path d="M20 15h2"/><path d="M20 9h2"/><path d="M9 2v2"/><path d="M9 20v2"/></svg>', 'desc' => 'El cerebro de tu computadora. Determina la velocidad general y la plataforma.'],
            'placa' => ['num' => 2, 'name' => 'Placa Madre (Motherboard)', 'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 7h.01"/><path d="M17 7h.01"/><path d="M7 17h.01"/><path d="M17 17h.01"/><path d="M10 10h4v4h-4z"/></svg>', 'desc' => 'Conecta todos los componentes. Debe coincidir en socket con el procesador.'],
            'ram' => ['num' => 3, 'name' => 'Memoria RAM', 'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 19v-3"/><path d="M10 19v-3"/><path d="M14 19v-3"/><path d="M18 19v-3"/><rect width="20" height="12" x="2" y="4" rx="2"/></svg>', 'desc' => 'Memoria rápida para multitarea y juegos. Mínimo 16GB en Dual Channel recomendado.'],
            'gpu' => ['num' => 4, 'name' => 'Tarjeta Gráfica (GPU / Video)', 'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/></svg>', 'desc' => 'Responsable de los gráficos y fotogramas por segundo (FPS) en juegos y render.'],
            'almacenamiento' => ['num' => 5, 'name' => 'Almacenamiento (SSD NVMe)', 'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>', 'desc' => 'Unidad sólida ultrarrápida para que Windows y tus programas carguen al instante.'],
            'psu' => ['num' => 6, 'name' => 'Fuente de Poder (PSU)', 'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>', 'desc' => 'Suministra energía limpia y protegida. Debe cubrir los Watts del sistema con margen.'],
            'case' => ['num' => 7, 'name' => 'Gabinete (Case)', 'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="20" x="5" y="2" rx="2"/><line x1="9" x2="9.01" y1="6" y2="6"/><line x1="13" x2="15" y1="6" y2="6"/></svg>', 'desc' => 'Chasis con flujo de aire optimizado (Mesh) y panel de vidrio templado.'],
            'cooler' => ['num' => 8, 'name' => 'Refrigeración / Cooler CPU', 'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>', 'desc' => 'Mantiene el procesador a bajas temperaturas bajo cargas pesadas.']
        ];
        ?>

        <?php foreach ($slotDefinitions as $catKey => $slot): ?>
          <div class="cb-slot-card" id="slotCard_<?= $catKey ?>">
            
            <div class="cb-slot-header">
              <div class="cb-slot-meta">
                <span class="cb-slot-step-num"><?= $slot['num'] ?></span>
                <span class="cb-slot-category-icon"><?= $slot['icon'] ?></span>
                <div>
                  <div class="cb-slot-category-name"><?= $slot['name'] ?></div>
                  <div class="cb-slot-category-desc"><?= $slot['desc'] ?></div>
                </div>
              </div>
              <span id="slotStatusBadge_<?= $catKey ?>" style="font-size:0.75rem;font-weight:700;color:#94A3B8;">
                Pendiente
              </span>
            </div>

            <div class="cb-slot-body" id="slotBody_<?= $catKey ?>">
              <!-- Estado Vacío Inicial -->
              <div class="cb-slot-empty-state">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.35;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span>No has seleccionado ningún componente en este slot.</span>
              </div>

              <div class="cb-slot-actions">
                <button type="button" class="cb-btn-select-slot" onclick="openComponentPicker('<?= $catKey ?>')">
                  <span>Elegir</span> &rarr;
                </button>
              </div>
            </div>

          </div>
        <?php endforeach; ?>

      </div>

      <!-- COLUMNA DERECHA: MONITOR INTELIGENTE, WATTS & COTIZACIÓN -->
      <aside class="cb-summary-sidebar">
        
        <!-- 1. MONITOR ANTI CUELLO DE BOTELLA -->
        <div class="cb-bottleneck-monitor-card">
          <div class="cb-monitor-header">
            <span class="cb-monitor-title" style="display:inline-flex;align-items:center;gap:0.4rem;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--cb-cyan)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
              <span>Monitor Anti Cuello de Botella</span>
            </span>
            <span id="bottleneckScoreBadge" class="cb-bottleneck-badge optimal">
              100% Óptimo
            </span>
          </div>

          <div class="cb-bottleneck-bar-container">
            <div id="bottleneckBarFill" class="cb-bottleneck-bar-fill" style="width:100%;background:#10B981;"></div>
          </div>

          <div id="bottleneckMsgBox" class="cb-monitor-status-msg optimal">
            Tu configuración se encuentra balanceada. El procesador y la tarjeta gráfica trabajarán en sincronía sin limitar los fotogramas.
          </div>
        </div>

        <!-- 2. CALCULADOR DE WATTS & FUENTE RECOMENDADA -->
        <div class="cb-wattage-card">
          <div class="cb-monitor-header">
            <span class="cb-monitor-title" style="display:inline-flex;align-items:center;gap:0.4rem;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--cb-gold)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
              <span>Consumo & Fuente de Poder</span>
            </span>
          </div>

          <div class="cb-wattage-grid">
            <div class="cb-wattage-box">
              <span class="cb-wattage-label">Consumo Estimado</span>
              <div class="cb-wattage-val"><span id="wattageEstVal">120</span>W</div>
            </div>
            <div class="cb-wattage-box">
              <span class="cb-wattage-label">Fuente Mínima</span>
              <div class="cb-wattage-val" style="color:var(--cb-cyan);"><span id="wattageMinPsuVal">450</span>W</div>
            </div>
          </div>

          <div id="wattageAlertBox" style="display:none;margin-top:0.75rem;font-size:0.78rem;background:#FEF2F2;border:1px solid #FECACA;color:#991B1B;padding:0.5rem;border-radius:6px;align-items:center;gap:0.4rem;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            <span><strong>Atención:</strong> La fuente seleccionada queda muy justa para el consumo pico de tu equipo. Te recomendamos una fuente con al menos 100W más de margen.</span>
          </div>
        </div>

        <!-- 3. BARRAS DE RENDIMIENTO ESTIMADO -->
        <div class="cb-benchmarks-card">
          <div style="font-size:0.75rem;font-weight:800;color:var(--cb-navy);text-transform:uppercase;margin-bottom:0.75rem;letter-spacing:0.5px;">
            Potencia Estimada por Tarea:
          </div>

          <div class="cb-meter-row">
            <div class="cb-meter-labels">
              <span>Ofimática / Multitarea</span>
              <span id="bmOfimaticaVal">100%</span>
            </div>
            <div class="cb-meter-track">
              <div class="cb-meter-progress" id="bmOfimaticaBar" style="width:100%;background:var(--cb-cyan);"></div>
            </div>
          </div>

          <div class="cb-meter-row">
            <div class="cb-meter-labels">
              <span>Gaming 1080p Competitivo</span>
              <span id="bmGaming1080Val">40%</span>
            </div>
            <div class="cb-meter-track">
              <div class="cb-meter-progress" id="bmGaming1080Bar" style="width:40%;background:var(--cb-gold);"></div>
            </div>
          </div>

          <div class="cb-meter-row">
            <div class="cb-meter-labels">
              <span>Gaming 1440p / 4K Ultra</span>
              <span id="bmGaming1440Val">15%</span>
            </div>
            <div class="cb-meter-track">
              <div class="cb-meter-progress" id="bmGaming1440Bar" style="width:15%;background:#10B981;"></div>
            </div>
          </div>

          <div class="cb-meter-row">
            <div class="cb-meter-labels">
              <span>Render 3D / Edición Video</span>
              <span id="bmRenderVal">30%</span>
            </div>
            <div class="cb-meter-track">
              <div class="cb-meter-progress" id="bmRenderBar" style="width:30%;background:#8B5CF6;"></div>
            </div>
          </div>
        </div>

        <!-- 4. TARJETA DE COTIZACIÓN & ACCIONES -->
        <div class="cb-quote-card">
          <div class="cb-quote-total-row">
            <div>
              <span class="cb-quote-label">Total del Ensamble:</span>
              <div style="font-size:0.75rem;color:#94A3B8;">Incluye Ensamble & Testeo Oficial</div>
            </div>
            <div class="cb-quote-total-val">
              <span style="font-size:1.1rem;color:var(--cb-cyan);">S/</span> <span id="builderTotalPrice">0.00</span>
            </div>
          </div>

          <div class="cb-quote-btns">
            <button type="button" class="cb-btn-quote-cart" onclick="addCustomPcToCart()">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
              <span>Añadir Ensamble al Carrito</span>
            </button>
            <a href="#" id="btnBuilderWhatsApp" target="_blank" class="cb-btn-quote-wa" onclick="sendBuilderToWhatsApp(event)">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
              <span>Cotizar por WhatsApp Oficial</span>
            </a>
            <button type="button" class="cb-btn-quote-print" onclick="window.print()">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
              <span>Imprimir Proforma / PDF</span>
            </button>
          </div>
        </div>

      </aside>

    </div>

  </section>

  <!-- ========================================================
       SECCIÓN 3: ACCESORIOS PARA COMPLETAR EL SETUP
       ======================================================== -->
  <section id="sectionAccessories" class="cb-builder-section" style="display:none;">
    
    <div class="cb-section-header">
      <div>
        <h2 class="cb-section-title">Completa tu Setup con Accesorios Originales</h2>
        <p style="font-size:0.88rem;color:var(--cb-text-muted);margin-top:0.25rem;">
          Suma monitores, teclados mecánicos, auriculares o estabilizadores de voltaje a tu cotización con un solo clic.
        </p>
      </div>
      <div style="font-size:0.9rem;font-weight:700;color:var(--cb-navy);">
        Accesorios Seleccionados: <strong id="accCountBadge" style="color:var(--cb-cyan);">0</strong>
      </div>
    </div>

    <!-- Monitores -->
    <h3 style="font-family:var(--cb-font-display);font-size:1.15rem;font-weight:800;color:var(--cb-navy);margin:1.5rem 0 0.75rem;display:flex;align-items:center;gap:0.45rem;">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--cb-cyan)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
      <span>Monitores Gaming & Productividad</span>
    </h3>
    <div class="cb-acc-grid">
      <?php foreach ($accesorios['monitores'] as $acc): ?>
        <div class="cb-acc-card" id="accCard_<?= $acc['id'] ?>">
          <div class="cb-acc-top">
            <span class="cb-acc-tag"><?= e($acc['img_badge']) ?></span>
          </div>
          <div style="height:110px;display:flex;align-items:center;justify-content:center;margin:0.5rem 0;background:#F8FAFC;border-radius:6px;overflow:hidden;padding:6px;">
            <img src="<?= asset('img/productos/' . ($acc['imagen'] ?? 'prod_5.jpg')) ?>" alt="<?= e($acc['nombre']) ?>" style="max-height:100%;max-width:100%;object-fit:contain;" loading="lazy">
          </div>
          <h4 class="cb-acc-title"><?= e($acc['nombre']) ?></h4>
          <p class="cb-acc-desc"><?= e($acc['descripcion']) ?></p>
          <div class="cb-acc-foot">
            <div class="cb-acc-price">
              <span style="font-size:0.85rem;color:var(--cb-cyan);">S/</span> <?= number_format($acc['precio'], 2) ?>
            </div>
            <button 
              type="button" 
              class="cb-btn-toggle-acc" 
              id="btnAcc_<?= $acc['id'] ?>"
              onclick="toggleAccessory('<?= $acc['id'] ?>', '<?= addslashes(e($acc['nombre'])) ?>', <?= (float)$acc['precio'] ?>, '<?= asset('img/productos/' . ($acc['imagen'] ?? 'prod_5.jpg')) ?>')"
            >
              <span>+ Agregar</span>
            </button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Periféricos -->
    <h3 style="font-family:var(--cb-font-display);font-size:1.15rem;font-weight:800;color:var(--cb-navy);margin:2rem 0 0.75rem;display:flex;align-items:center;gap:0.45rem;">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--cb-cyan)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
      <span>Teclados, Mouse y Auriculares</span>
    </h3>
    <div class="cb-acc-grid">
      <?php foreach ($accesorios['perifericos'] as $acc): ?>
        <div class="cb-acc-card" id="accCard_<?= $acc['id'] ?>">
          <div class="cb-acc-top">
            <span class="cb-acc-tag"><?= e($acc['img_badge']) ?></span>
          </div>
          <div style="height:110px;display:flex;align-items:center;justify-content:center;margin:0.5rem 0;background:#F8FAFC;border-radius:6px;overflow:hidden;padding:6px;">
            <img src="<?= asset('img/productos/' . ($acc['imagen'] ?? 'prod_10.jpg')) ?>" alt="<?= e($acc['nombre']) ?>" style="max-height:100%;max-width:100%;object-fit:contain;" loading="lazy">
          </div>
          <h4 class="cb-acc-title"><?= e($acc['nombre']) ?></h4>
          <p class="cb-acc-desc"><?= e($acc['descripcion']) ?></p>
          <div class="cb-acc-foot">
            <div class="cb-acc-price">
              <span style="font-size:0.85rem;color:var(--cb-cyan);">S/</span> <?= number_format($acc['precio'], 2) ?>
            </div>
            <button 
              type="button" 
              class="cb-btn-toggle-acc" 
              id="btnAcc_<?= $acc['id'] ?>"
              onclick="toggleAccessory('<?= $acc['id'] ?>', '<?= addslashes(e($acc['nombre'])) ?>', <?= (float)$acc['precio'] ?>, '<?= asset('img/productos/' . ($acc['imagen'] ?? 'prod_10.jpg')) ?>')"
            >
              <span>+ Agregar</span>
            </button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Protección Eléctrica -->
    <h3 style="font-family:var(--cb-font-display);font-size:1.15rem;font-weight:800;color:var(--cb-navy);margin:2rem 0 0.75rem;display:flex;align-items:center;gap:0.45rem;">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--cb-cyan)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
      <span>Protección Eléctrica & Respaldo</span>
    </h3>
    <div class="cb-acc-grid">
      <?php foreach ($accesorios['proteccion'] as $acc): ?>
        <div class="cb-acc-card" id="accCard_<?= $acc['id'] ?>">
          <div class="cb-acc-top">
            <span class="cb-acc-tag"><?= e($acc['img_badge']) ?></span>
          </div>
          <div style="height:110px;display:flex;align-items:center;justify-content:center;margin:0.5rem 0;background:#F8FAFC;border-radius:6px;overflow:hidden;padding:6px;">
            <img src="<?= asset('img/productos/' . ($acc['imagen'] ?? 'prod_9.jpg')) ?>" alt="<?= e($acc['nombre']) ?>" style="max-height:100%;max-width:100%;object-fit:contain;" loading="lazy">
          </div>
          <h4 class="cb-acc-title"><?= e($acc['nombre']) ?></h4>
          <p class="cb-acc-desc"><?= e($acc['descripcion']) ?></p>
          <div class="cb-acc-foot">
            <div class="cb-acc-price">
              <span style="font-size:0.85rem;color:var(--cb-cyan);">S/</span> <?= number_format($acc['precio'], 2) ?>
            </div>
            <button 
              type="button" 
              class="cb-btn-toggle-acc" 
              id="btnAcc_<?= $acc['id'] ?>"
              onclick="toggleAccessory('<?= $acc['id'] ?>', '<?= addslashes(e($acc['nombre'])) ?>', <?= (float)$acc['precio'] ?>, '<?= asset('img/productos/' . ($acc['imagen'] ?? 'prod_9.jpg')) ?>')"
            >
              <span>+ Agregar</span>
            </button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Barra Inferior de Acción -->
    <div style="margin-top:2.5rem;background:#FFFFFF;border:1px solid var(--cb-border);border-radius:var(--cb-radius);padding:1.5rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
      <div>
        <div style="font-size:0.85rem;color:var(--cb-text-muted);">Configuración + Accesorios:</div>
        <div style="font-family:var(--cb-font-display);font-size:1.6rem;font-weight:900;color:var(--cb-navy);">
          S/ <span id="builderTotalWithAcc">0.00</span>
        </div>
      </div>
      <div style="display:flex;gap:0.75rem;">
        <button type="button" class="cb-btn-hero-outline" onclick="switchBuilderTab('studio')" style="color:var(--cb-navy);border-color:var(--cb-navy);">
          &larr; Volver al Armador
        </button>
        <button type="button" class="cb-btn-hero-primary" onclick="addCustomPcToCart()">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
          <span>Añadir Todo al Carrito</span>
        </button>
      </div>
    </div>

  </section>

</div>

<!-- ========================================================
     MODAL DE SELECCIÓN DE COMPONENTES
     ======================================================== -->
<div class="cb-component-picker-modal" id="componentPickerModal" onclick="closeComponentPickerOnBackdrop(event)">
  <div class="cb-picker-box">
    
    <div class="cb-picker-header">
      <h3 id="pickerCategoryTitle">Seleccionar Componente</h3>
      <button type="button" class="cb-btn-close-picker" onclick="closeComponentPicker()" aria-label="Cerrar modal">&times;</button>
    </div>

    <div class="cb-picker-filter-bar">
      <div style="font-size:0.82rem;color:var(--cb-text-muted);" id="pickerCompatibilityHint">
        Mostrando componentes disponibles
      </div>
      <div style="font-size:0.78rem;font-weight:700;color:var(--cb-cyan);" id="pickerSocketBadge">
        Filtro Activo
      </div>
    </div>

    <div class="cb-picker-list" id="pickerItemList">
      <!-- Inyectado dinámicamente por pc-builder.js -->
    </div>

  </div>
</div>

<!-- DATA EMBEDDED PARA EL MOTOR JAVASCRIPT -->
<script>
  window.MAKPC_COMPONENTS = <?= json_encode($componentes, JSON_UNESCAPED_UNICODE) ?>;
  window.MAKPC_PRESETS = <?= json_encode($presets, JSON_UNESCAPED_UNICODE) ?>;
  window.MAKPC_ACCESORIOS = <?= json_encode($accesorios, JSON_UNESCAPED_UNICODE) ?>;
  window.MAKPC_BASE_URL = <?= json_encode(url()) ?>;
</script>
