/**
 * MAKPC - Motor Inteligente de Ensamble y Prevención de Cuello de Botella
 * Archivo: assets/js/pc-builder.js
 */

(function() {
  'use strict';

  // Estado global del armador
  const state = {
    selectedComponents: {
      procesador: null,
      placa: null,
      ram: null,
      gpu: null,
      almacenamiento: null,
      psu: null,
      case: null,
      cooler: null
    },
    selectedAccessories: [],
    currentCategorySelecting: null
  };

  const categoryIcons = {
    procesador: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="16" x="4" y="4" rx="2"/><rect width="6" height="6" x="9" y="9" rx="1"/><path d="M15 2v2"/><path d="M15 20v2"/><path d="M2 15h2"/><path d="M2 9h2"/><path d="M20 15h2"/><path d="M20 9h2"/><path d="M9 2v2"/><path d="M9 20v2"/></svg>',
    placa: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 7h.01"/><path d="M17 7h.01"/><path d="M7 17h.01"/><path d="M17 17h.01"/><path d="M10 10h4v4h-4z"/></svg>',
    ram: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 19v-3"/><path d="M10 19v-3"/><path d="M14 19v-3"/><path d="M18 19v-3"/><rect width="20" height="12" x="2" y="4" rx="2"/></svg>',
    gpu: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/></svg>',
    almacenamiento: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>',
    psu: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
    case: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="20" x="5" y="2" rx="2"/><line x1="9" x2="9.01" y1="6" y2="6"/><line x1="13" x2="15" y1="6" y2="6"/></svg>',
    cooler: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>'
  };

  // Inicialización cuando el DOM esté listo
  document.addEventListener('DOMContentLoaded', function() {
    initBuilder();
  });

  function initBuilder() {
    // Verificar si hay hash en la URL (#presets o #studio)
    const hash = window.location.hash;
    if (hash === '#studio') {
      switchBuilderTab('studio');
    } else {
      switchBuilderTab('presets');
    }

    // Cargar por defecto el preset Gaming Entry para que el estudio tenga datos pre-cargados
    if (window.MAKPC_PRESETS && window.MAKPC_PRESETS.gaming_entry) {
      applyPresetData('gaming_entry', false); // false para no cambiar de pestaña automáticamente al inicio
    }
  }

  // Cambio de pestañas
  window.switchBuilderTab = function(tabName) {
    const secPresets = document.getElementById('sectionPresets');
    const secStudio = document.getElementById('sectionStudio');
    const secAcc = document.getElementById('sectionAccessories');

    const btnPresets = document.getElementById('tabBtnPresets');
    const btnStudio = document.getElementById('tabBtnStudio');
    const btnAcc = document.getElementById('tabBtnAccessories');

    if (!secPresets || !secStudio || !secAcc) return;

    // Ocultar todos
    secPresets.style.display = 'none';
    secStudio.style.display = 'none';
    secAcc.style.display = 'none';

    btnPresets.classList.remove('active');
    btnStudio.classList.remove('active');
    btnAcc.classList.remove('active');

    if (tabName === 'presets') {
      secPresets.style.display = 'block';
      btnPresets.classList.add('active');
      window.location.hash = 'presets';
    } else if (tabName === 'studio') {
      secStudio.style.display = 'block';
      btnStudio.classList.add('active');
      window.location.hash = 'studio';
    } else if (tabName === 'accessories') {
      secAcc.style.display = 'block';
      btnAcc.classList.add('active');
      window.location.hash = 'accessories';
    }

    window.scrollTo({ top: 120, behavior: 'smooth' });
  };

  // Cargar preset predefinido
  window.loadPreset = function(presetKey) {
    applyPresetData(presetKey, true);
  };

  function applyPresetData(presetKey, switchTab) {
    const preset = window.MAKPC_PRESETS ? window.MAKPC_PRESETS[presetKey] : null;
    if (!preset) return;

    const comps = window.MAKPC_COMPONENTS || {};

    // Asignar cada componente
    for (const [catKey, compId] of Object.entries(preset.componentes)) {
      if (comps[catKey]) {
        const item = comps[catKey].find(c => c.id === compId);
        if (item) {
          state.selectedComponents[catKey] = item;
        }
      }
    }

    renderAllSlots();
    recalculateSystemMetrics();

    if (switchTab) {
      switchBuilderTab('studio');
      showToast(`Configuración cargada: ${preset.titulo}`);
    }
  }

  // Reiniciar armador
  window.resetBuilder = function() {
    for (const key of Object.keys(state.selectedComponents)) {
      state.selectedComponents[key] = null;
    }
    state.selectedAccessories = [];
    
    // Desmarcar tarjetas de accesorios
    document.querySelectorAll('.cb-acc-card').forEach(c => c.classList.remove('added'));
    document.querySelectorAll('.cb-btn-toggle-acc').forEach(b => {
      b.textContent = '➕ Agregar';
    });

    renderAllSlots();
    recalculateSystemMetrics();
    showToast('El armador ha sido restablecido.');
  };

  // Renderizar todos los slots de componentes
  function renderAllSlots() {
    for (const [catKey, item] of Object.entries(state.selectedComponents)) {
      const card = document.getElementById(`slotCard_${catKey}`);
      const body = document.getElementById(`slotBody_${catKey}`);
      const badge = document.getElementById(`slotStatusBadge_${catKey}`);

      if (!card || !body) continue;

      if (item) {
        card.classList.add('filled');
        if (badge) {
          badge.textContent = '✓ Seleccionado';
          badge.style.color = '#10B981';
        }

        // Construir píldoras de especificaciones
        let pillsHtml = '';
        if (item.socket) pillsHtml += `<span class="cb-slot-spec-pill">Socket: ${item.socket}</span>`;
        if (item.ram_type) pillsHtml += `<span class="cb-slot-spec-pill">${item.ram_type}</span>`;
        if (item.tdp) pillsHtml += `<span class="cb-slot-spec-pill">TDP: ${item.tdp}W</span>`;
        if (item.watts) pillsHtml += `<span class="cb-slot-spec-pill">${item.watts}W</span>`;
        if (item.vram) pillsHtml += `<span class="cb-slot-spec-pill">${item.vram}</span>`;
        if (item.capacidad) pillsHtml += `<span class="cb-slot-spec-pill">${item.capacidad}</span>`;
        if (item.fans) pillsHtml += `<span class="cb-slot-spec-pill">${item.fans}</span>`;

        body.innerHTML = `
          <div class="cb-slot-selected-info">
            <div class="cb-slot-item-thumb">${categoryIcons[catKey] || '📦'}</div>
            <div class="cb-slot-item-details">
              <div class="cb-slot-item-title">${escapeHtml(item.nombre)}</div>
              <div class="cb-slot-item-specs">${pillsHtml}</div>
            </div>
          </div>
          <div class="cb-slot-price-col">
            <div class="cb-slot-price">
              <span style="font-size:0.85rem;color:var(--cb-cyan);">S/</span> ${parseFloat(item.precio).toFixed(2)}
            </div>
          </div>
          <div class="cb-slot-actions">
            <button type="button" class="cb-btn-select-slot" onclick="openComponentPicker('${catKey}')">
              Cambiar
            </button>
            <button type="button" class="cb-btn-remove-slot" onclick="removeComponent('${catKey}')" title="Quitar componente">
              ✕
            </button>
          </div>
        `;
      } else {
        card.classList.remove('filled');
        if (badge) {
          badge.textContent = 'Pendiente';
          badge.style.color = '#94A3B8';
        }

        body.innerHTML = `
          <div class="cb-slot-empty-state">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.35;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>No has seleccionado ningún componente en este slot.</span>
          </div>
          <div class="cb-slot-actions">
            <button type="button" class="cb-btn-select-slot" onclick="openComponentPicker('${catKey}')">
              <span>Elegir</span> &rarr;
            </button>
          </div>
        `;
      }
    }
  }

  // Quitar componente individual
  window.removeComponent = function(catKey) {
    state.selectedComponents[catKey] = null;
    renderAllSlots();
    recalculateSystemMetrics();
  };

  // Abrir modal de selección
  window.openComponentPicker = function(catKey) {
    state.currentCategorySelecting = catKey;
    const modal = document.getElementById('componentPickerModal');
    const titleEl = document.getElementById('pickerCategoryTitle');
    const listEl = document.getElementById('pickerItemList');
    const socketBadge = document.getElementById('pickerSocketBadge');
    const hintEl = document.getElementById('pickerCompatibilityHint');

    if (!modal || !listEl) return;

    const comps = window.MAKPC_COMPONENTS ? window.MAKPC_COMPONENTS[catKey] : [];
    const currentCpu = state.selectedComponents.procesador;
    const currentPlaca = state.selectedComponents.placa;

    titleEl.innerHTML = `${categoryIcons[catKey] || ''} Seleccionar ${capitalize(catKey)}`;

    // Manejo de compatibilidad de sockets y memorias
    let requiredSocket = null;
    let requiredRamType = null;

    if (currentCpu) {
      requiredSocket = currentCpu.socket;
      requiredRamType = currentCpu.ram_type;
    } else if (currentPlaca) {
      requiredSocket = currentPlaca.socket;
      requiredRamType = currentPlaca.ram_type;
    }

    if (socketBadge) {
      if (requiredSocket && (catKey === 'placa' || catKey === 'procesador')) {
        socketBadge.style.display = 'block';
        socketBadge.textContent = `Filtro de Socket: ${requiredSocket}`;
      } else if (requiredRamType && catKey === 'ram') {
        socketBadge.style.display = 'block';
        socketBadge.textContent = `Memoria Requerida: ${requiredRamType}`;
      } else {
        socketBadge.style.display = 'none';
      }
    }

    if (hintEl) {
      hintEl.textContent = `Selecciona la mejor opción para tu presupuesto`;
    }

    // Renderizar lista de opciones
    listEl.innerHTML = '';

    if (!comps || comps.length === 0) {
      listEl.innerHTML = '<div style="padding:2rem;text-align:center;color:#94A3B8;">No hay componentes registrados en esta categoría.</div>';
    } else {
      comps.forEach(item => {
        let isIncompatible = false;
        let incompReason = '';

        // Validar socket en placas
        if (catKey === 'placa' && requiredSocket && item.socket !== requiredSocket) {
          isIncompatible = true;
          incompReason = `Incompatible (Tu procesador requiere socket ${requiredSocket})`;
        }
        // Validar socket en CPU si ya eligió placa
        if (catKey === 'procesador' && currentPlaca && item.socket !== currentPlaca.socket) {
          isIncompatible = true;
          incompReason = `Incompatible (Tu placa requiere socket ${currentPlaca.socket})`;
        }
        // Validar tipo de RAM (DDR4 vs DDR5)
        if (catKey === 'ram' && requiredRamType && item.ram_type !== requiredRamType) {
          isIncompatible = true;
          incompReason = `Incompatible (Requiere memorias ${requiredRamType})`;
        }

        const itemEl = document.createElement('div');
        itemEl.className = `cb-picker-item ${isIncompatible ? 'incompatible' : ''}`;
        
        let pills = '';
        if (item.socket) pills += `<span class="cb-slot-spec-pill">Socket ${item.socket}</span>`;
        if (item.ram_type) pills += `<span class="cb-slot-spec-pill">${item.ram_type}</span>`;
        if (item.tdp) pills += `<span class="cb-slot-spec-pill">${item.tdp}W TDP</span>`;
        if (item.watts) pills += `<span class="cb-slot-spec-pill">${item.watts}W</span>`;
        if (item.vram) pills += `<span class="cb-slot-spec-pill">${item.vram}</span>`;
        if (item.capacidad) pills += `<span class="cb-slot-spec-pill">${item.capacidad}</span>`;

        itemEl.innerHTML = `
          <div style="flex:1;">
            <div style="font-family:var(--cb-font-display);font-size:1rem;font-weight:700;color:var(--cb-navy);">
              ${escapeHtml(item.nombre)}
            </div>
            <div style="font-size:0.8rem;color:#64748B;margin:0.25rem 0;">
              ${escapeHtml(item.descripcion || '')}
            </div>
            <div style="display:flex;gap:0.35rem;flex-wrap:wrap;">
              ${pills}
              ${isIncompatible ? `<span style="background:#FEE2E2;color:#DC2626;font-size:0.72rem;font-weight:700;padding:0.15rem 0.5rem;border-radius:4px;display:inline-flex;align-items:center;gap:0.25rem;"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> ${incompReason}</span>` : ''}
            </div>
          </div>
          <div style="text-align:right;flex-shrink:0;">
            <div style="font-family:var(--cb-font-display);font-size:1.3rem;font-weight:900;color:var(--cb-navy);">
              S/ ${parseFloat(item.precio).toFixed(2)}
            </div>
            <button 
              type="button" 
              class="cb-btn-hero-primary" 
              style="font-size:0.82rem;padding:0.45rem 1rem;margin-top:0.4rem;${isIncompatible ? 'opacity:0.5;pointer-events:none;' : ''}"
            >
              Seleccionar
            </button>
          </div>
        `;

        if (!isIncompatible) {
          itemEl.addEventListener('click', function() {
            selectComponent(catKey, item.id);
          });
        }

        listEl.appendChild(itemEl);
      });
    }

    modal.classList.add('open');
  };

  window.closeComponentPicker = function() {
    const modal = document.getElementById('componentPickerModal');
    if (modal) modal.classList.remove('open');
  };

  window.closeComponentPickerOnBackdrop = function(e) {
    if (e.target.id === 'componentPickerModal') {
      closeComponentPicker();
    }
  };

  // Seleccionar componente
  window.selectComponent = function(catKey, compId) {
    const comps = window.MAKPC_COMPONENTS ? window.MAKPC_COMPONENTS[catKey] : [];
    const item = comps.find(c => c.id === compId);

    if (item) {
      state.selectedComponents[catKey] = item;

      // Si cambió de procesador y la placa o ram actual ya no son compatibles, avisar y resetear placa/ram
      if (catKey === 'procesador') {
        const placa = state.selectedComponents.placa;
        const ram = state.selectedComponents.ram;
        if (placa && placa.socket !== item.socket) {
          state.selectedComponents.placa = null;
          showToast(`Placa madre deseleccionada porque requiere socket ${item.socket}`);
        }
        if (ram && ram.ram_type !== item.ram_type) {
          state.selectedComponents.ram = null;
          showToast(`Memoria RAM deseleccionada porque requiere ${item.ram_type}`);
        }
      }

      renderAllSlots();
      recalculateSystemMetrics();
      closeComponentPicker();
      showToast(`Componente añadido: ${item.nombre}`);
    }
  };

  // RECALCULAR MÉTRICAS DEL SISTEMA (CUELLO DE BOTELLA, WATTS, RENDIMIENTO Y PRECIO)
  function recalculateSystemMetrics() {
    const cpu = state.selectedComponents.procesador;
    const gpu = state.selectedComponents.gpu;
    const psu = state.selectedComponents.psu;
    const ram = state.selectedComponents.ram;

    // 1. CÁLCULO DE CUELLO DE BOTELLA
    const scoreBadge = document.getElementById('bottleneckScoreBadge');
    const barFill = document.getElementById('bottleneckBarFill');
    const msgBox = document.getElementById('bottleneckMsgBox');

    if (scoreBadge && barFill && msgBox) {
      if (!cpu && !gpu) {
        scoreBadge.className = 'cb-bottleneck-badge optimal';
        scoreBadge.textContent = 'En espera de CPU / GPU';
        barFill.style.width = '100%';
        barFill.style.background = '#10B981';
        msgBox.className = 'cb-monitor-status-msg optimal';
        msgBox.textContent = 'Selecciona un procesador y tarjeta gráfica para calcular la sinergia y descartar cuello de botella.';
      } else if (cpu && !gpu) {
        scoreBadge.className = 'cb-bottleneck-badge optimal';
        scoreBadge.textContent = 'Gráficos de CPU';
        barFill.style.width = '100%';
        barFill.style.background = '#10B981';
        msgBox.className = 'cb-monitor-status-msg optimal';
        msgBox.textContent = 'Operando con gráficos integrados. Excelente balance para ofimática y productividad sin cuello de botella.';
      } else if (!cpu && gpu) {
        scoreBadge.className = 'cb-bottleneck-badge warning';
        scoreBadge.textContent = 'Falta Procesador';
        barFill.style.width = '50%';
        barFill.style.background = '#F59E0B';
        msgBox.className = 'cb-monitor-status-msg warning';
        msgBox.textContent = 'Selecciona un procesador para evaluar si alcanzará la velocidad de tu tarjeta gráfica.';
      } else {
        // CPU y GPU ambos presentes: Comparar power_score
        const cpuScore = cpu.power_score || 50;
        const gpuScore = gpu.power_score || 20;

        // Desbalance
        const diff = gpuScore - cpuScore;

        if (diff > 22) {
          // GPU muy superior al CPU -> Cuello de botella en procesador
          const pct = Math.min(42, Math.round(diff * 1.25));
          scoreBadge.className = 'cb-bottleneck-badge danger';
          scoreBadge.textContent = `Cuello Botella CPU ~${pct}%`;
          barFill.style.width = `${Math.max(20, 100 - pct * 1.8)}%`;
          barFill.style.background = '#EF4444';
          msgBox.className = 'cb-monitor-status-msg danger';
          msgBox.innerHTML = `⚠️ <strong>Desbalance detectado (~${pct}%):</strong> Tu procesador limitará los FPS de tu tarjeta gráfica en juegos y render. Te recomendamos subir a un procesador de mayor potencia para que tu inversión en la GPU rinda al 100%.`;
        } else if (diff < -30 && gpu.id !== 'gpu-integrada') {
          // CPU muy superior a una GPU modesta dedicada
          scoreBadge.className = 'cb-bottleneck-badge warning';
          scoreBadge.textContent = 'GPU Modesta';
          barFill.style.width = '85%';
          barFill.style.background = '#F59E0B';
          msgBox.className = 'cb-monitor-status-msg warning';
          msgBox.innerHTML = `💡 <strong>Margen disponible:</strong> Tu procesador tiene potencia de sobra. Si juegas títulos AAA en alta resolución, podrías equipar una GPU más potente para un equilibrio perfecto.`;
        } else {
          // Balance Óptimo
          scoreBadge.className = 'cb-bottleneck-badge optimal';
          scoreBadge.textContent = '✓ Balance Óptimo';
          barFill.style.width = '100%';
          barFill.style.background = '#10B981';
          msgBox.className = 'cb-monitor-status-msg optimal';
          msgBox.innerHTML = `✅ <strong>¡Excelente sinergia de hardware!</strong> Tu procesador y tarjeta gráfica se encuentran perfectamente equilibrados, sin desperdicio de dinero ni cuello de botella.`;
        }
      }
    }

    // 2. CÁLCULO DE WATTS Y FUENTE DE PODER
    let totalWatts = 65; // Base del sistema (placa, ram, ventiladores, ssd)
    if (cpu) totalWatts += (cpu.tdp || 65);
    if (gpu) totalWatts += (gpu.tdp || 0);

    const minPsuWatts = Math.max(450, Math.ceil((totalWatts * 1.25) / 50) * 50);

    const wattEstEl = document.getElementById('wattageEstVal');
    const wattMinPsuEl = document.getElementById('wattageMinPsuVal');
    const wattAlertBox = document.getElementById('wattageAlertBox');

    if (wattEstEl) wattEstEl.textContent = totalWatts;
    if (wattMinPsuEl) wattMinPsuEl.textContent = minPsuWatts;

    if (wattAlertBox) {
      if (psu && psu.watts < totalWatts) {
        wattAlertBox.style.display = 'block';
        wattAlertBox.innerHTML = `⚠️ <strong>Peligro:</strong> Tu fuente seleccionada (${psu.watts}W) es menor al consumo pico estimado (${totalWatts}W). El equipo podría apagarse. Se requiere mínimo ${minPsuWatts}W.`;
      } else if (psu && psu.watts < minPsuWatts) {
        wattAlertBox.style.display = 'block';
        wattAlertBox.style.background = '#FFFBEB';
        wattAlertBox.style.borderColor = '#FDE68A';
        wattAlertBox.style.color = '#92400E';
        wattAlertBox.innerHTML = `⚠️ <strong>Margen ajustado:</strong> Tu fuente (${psu.watts}W) cubre el consumo pero deja poco margen. Una fuente de ${minPsuWatts}W garantizará mayor vida útil.`;
      } else {
        wattAlertBox.style.display = 'none';
      }
    }

    // 3. MEDIDORES DE RENDIMIENTO POR USO
    let bmOfimatica = 70;
    let bmGaming1080 = 25;
    let bmGaming1440 = 10;
    let bmRender = 20;

    if (cpu) {
      bmOfimatica = Math.min(100, Math.round(cpu.power_score * 1.15));
      bmRender = Math.min(100, Math.round(cpu.power_score * 0.9));
    }
    if (gpu) {
      bmGaming1080 = Math.min(100, Math.round(gpu.power_score * 1.2));
      bmGaming1440 = Math.min(100, Math.round(gpu.power_score * 0.95));
      bmRender = Math.min(100, Math.round((bmRender * 0.45) + (gpu.power_score * 0.55)));
    }
    if (ram) {
      if (ram.capacidad.includes('32GB') || ram.capacidad.includes('64GB')) {
        bmRender = Math.min(100, bmRender + 8);
        bmOfimatica = 100;
      }
    }

    updateMeter('bmOfimatica', bmOfimatica);
    updateMeter('bmGaming1080', bmGaming1080);
    updateMeter('bmGaming1440', bmGaming1440);
    updateMeter('bmRender', bmRender);

    // 4. CÁLCULO DE PRECIO TOTAL
    let compTotal = 0;
    for (const item of Object.values(state.selectedComponents)) {
      if (item) compTotal += parseFloat(item.precio || 0);
    }

    let accTotal = 0;
    state.selectedAccessories.forEach(acc => {
      accTotal += parseFloat(acc.price || 0);
    });

    const grandTotal = compTotal + accTotal;

    const priceEl = document.getElementById('builderTotalPrice');
    const withAccEl = document.getElementById('builderTotalWithAcc');
    const accBadge = document.getElementById('accCountBadge');

    if (priceEl) priceEl.textContent = compTotal.toFixed(2);
    if (withAccEl) withAccEl.textContent = grandTotal.toFixed(2);
    if (accBadge) accBadge.textContent = state.selectedAccessories.length;
  }

  function updateMeter(idPrefix, val) {
    const valEl = document.getElementById(`${idPrefix}Val`);
    const barEl = document.getElementById(`${idPrefix}Bar`);
    if (valEl) valEl.textContent = `${val}%`;
    if (barEl) barEl.style.width = `${val}%`;
  }

  // ACCESORIOS: Alternar adición de accesorio
  window.toggleAccessory = function(id, name, price, icon) {
    const index = state.selectedAccessories.findIndex(a => a.id === id);
    const card = document.getElementById(`accCard_${id}`);
    const btn = document.getElementById(`btnAcc_${id}`);

    if (index >= 0) {
      // Remover
      state.selectedAccessories.splice(index, 1);
      if (card) card.classList.remove('added');
      if (btn) {
        btn.textContent = '+ Agregar';
      }
      showToast(`Accesorio removido: ${name}`);
    } else {
      // Agregar
      state.selectedAccessories.push({ id, name, price, icon });
      if (card) card.classList.add('added');
      if (btn) {
        btn.textContent = '✓ Agregado';
      }
      showToast(`Accesorio añadido al setup: ${name}`);
    }

    recalculateSystemMetrics();
  };

  // COTIZACIÓN POR WHATSAPP
  window.sendBuilderToWhatsApp = function(e) {
    if (e) e.preventDefault();

    const comps = state.selectedComponents;
    let text = `*HOLA MAKPC, DESEO COTIZAR MI CONFIGURACIÓN DE PC A MEDIDA:*\n\n`;

    let total = 0;
    let count = 0;

    for (const [key, item] of Object.entries(comps)) {
      if (item) {
        count++;
        total += parseFloat(item.precio);
        text += `• *${capitalize(key)}:* ${item.nombre} (S/ ${parseFloat(item.precio).toFixed(2)})\n`;
      }
    }

    if (count === 0) {
      alert('Por favor selecciona al menos un componente en el armador antes de cotizar.');
      return;
    }

    if (state.selectedAccessories.length > 0) {
      text += `\n*ACCESORIOS & PERIFÉRICOS:*\n`;
      state.selectedAccessories.forEach(acc => {
        total += parseFloat(acc.price);
        text += `• ${acc.name} (S/ ${parseFloat(acc.price).toFixed(2)})\n`;
      });
    }

    text += `\n*TOTAL ESTIMADO:* S/ ${total.toFixed(2)}\n`;
    text += `\n_¿Tienen stock disponible para entrega y testeo en Tumbes / Envíos a Nivel Nacional?_`;

    const waUrl = `https://wa.me/51975513327?text=${encodeURIComponent(text)}`;
    window.open(waUrl, '_blank');
  };

  // AÑADIR ENSAMBLE COMPLETO AL CARRITO
  window.addCustomPcToCart = function() {
    const comps = state.selectedComponents;
    let count = 0;
    let total = 0;
    const partsList = [];

    for (const [key, item] of Object.entries(comps)) {
      if (item) {
        count++;
        total += parseFloat(item.precio);
        partsList.push(`${capitalize(key)}: ${item.nombre}`);
      }
    }

    if (count < 3) {
      alert('Te sugerimos seleccionar al menos Procesador, Placa y Memoria RAM antes de añadir al carrito.');
      return;
    }

    // Agregar accesorios al total
    state.selectedAccessories.forEach(acc => {
      total += parseFloat(acc.price);
      partsList.push(`Accesorio: ${acc.name}`);
    });

    const baseUrl = (window.MAKPC_BASE_URL || '/MAKPC/').replace(/\/$/, '') + '/';
    const pcItem = {
      id: 'custom-pc-' + Date.now(),
      name: `Ensamble PC Personalizado (${count} Componentes)`,
      price: total,
      icon: baseUrl + 'assets/img/productos/preset_2.jpg',
      qty: 1,
      details: partsList.slice(0, 4).join(', ') + (partsList.length > 4 ? '...' : '')
    };

    // Obtener carrito de localStorage o inicializar
    let cart = [];
    try {
      cart = JSON.parse(localStorage.getItem('makpc_cart')) || [];
    } catch (e) {
      cart = [];
    }

    cart.push(pcItem);
    localStorage.setItem('makpc_cart', JSON.stringify(cart));

    // Despachar evento para que app.js actualice el contador y drawer
    window.dispatchEvent(new Event('cartUpdated'));

    showToast('¡Ensamble personalizado añadido al carrito!');
    
    // Abrir drawer del carrito sincronizado con app.js
    if (typeof window.openCartDrawer === 'function') {
      window.openCartDrawer();
    } else {
      const drawer = document.getElementById('cartDrawer');
      const overlay = document.getElementById('cartDrawerOverlay');
      if (drawer) drawer.classList.add('open');
      if (overlay) overlay.classList.add('active');
    }
  };

  // Utilidades
  function showToast(msg) {
    const toast = document.getElementById('cartToast');
    const toastMsg = document.getElementById('cartToastMsg');
    if (toast && toastMsg) {
      toastMsg.textContent = msg;
      toast.classList.add('show');
      setTimeout(() => {
        toast.classList.remove('show');
      }, 3000);
    }
  }

  function capitalize(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  function escapeHtml(str) {
    if (!str) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

})();
