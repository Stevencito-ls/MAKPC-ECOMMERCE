/**
 * ==============================================================================
 * MAK-PC Enterprises S.A.C. - Controlador de Workspace Unificado (app.js)
 * Flujo Express para Mostrador y Laboratorio (< 2 minutos por atención)
 * ==============================================================================
 */

document.addEventListener('DOMContentLoaded', () => {
  // Estado local reactivo
  const state = {
    ordenes: [],
    currentOrden: null,
    currentRecibo: null,
    isOnline: false
  };

  // Referencias a elementos del DOM
  const dom = {
    // Estado de conexión
    apiStatusDot: document.getElementById('api-status-dot'),
    apiStatusText: document.getElementById('api-status-text'),

    // KPIs Superiores
    kpiEnTaller: document.getElementById('kpi-en-taller'),
    kpiListos: document.getElementById('kpi-listos'),
    kpiEntregados: document.getElementById('kpi-entregados'),
    kpiPorCobrar: document.getElementById('kpi-por-cobrar'),
    contadorTotal: document.getElementById('contador-ordenes-total'),

    // Búsqueda y Filtros
    inputSearch: document.getElementById('input-search-taller'),
    selectFilterEstado: document.getElementById('filter-estado-taller'),
    btnAbrirNuevoIngreso: document.getElementById('btn-nuevo-ingreso') || document.getElementById('btn-abrir-nuevo-ingreso'),
    tablaTallerTbody: document.getElementById('taller-orders-tbody'),

    // Modal 1: Recepción Express
    modalNuevoIngreso: 'modal-nuevo-ingreso',
    formRecepcion: document.getElementById('form-recepcion-equipo'),
    inputDni: document.getElementById('cliente-doc'),
    btnBuscarDni: document.getElementById('btn-buscar-dni'),
    inputNombre: document.getElementById('cliente-nombre'),
    inputTelefono: document.getElementById('cliente-telefono'),
    inputDireccion: document.getElementById('cliente-direccion'),
    inputEmail: document.getElementById('cliente-email'),
    tipoDocSelect: document.getElementById('cliente-tipo-doc'),

    // Modal 2: Atención Técnica / Diagnóstico
    modalTecnico: 'modal-actualizar-tecnico',
    formTecnico: document.getElementById('form-actualizar-tecnico'),
    modalTecIdOrden: document.getElementById('modal-tec-orden-id'),
    modalTecCodigo: document.getElementById('modal-tec-codigo'),
    modalTecCliente: document.getElementById('modal-tec-cliente'),
    modalTecEquipo: document.getElementById('modal-tec-equipo'),
    modalTecMotivo: document.getElementById('modal-tec-motivo'),
    modalTecDiagnostico: document.getElementById('modal-tec-diagnostico'),
    modalTecSolucion: document.getElementById('modal-tec-solucion'),
    modalTecEstado: document.getElementById('modal-tec-estado'),
    btnQuickMarcarReparado: document.getElementById('btn-quick-marcar-reparado'),

    // Modal 3: Cobro y Emisión de Recibo A5
    modalCobro: 'modal-cobro-recibo',
    formCobro: document.getElementById('form-cobro-recibo'),
    cobroOrdenId: document.getElementById('cobro-orden-id'),
    cobroReciboId: document.getElementById('cobro-recibo-id'),
    cobroCorrelativoPreview: document.getElementById('cobro-correlativo-preview'),
    cobroClienteInfo: document.getElementById('cobro-cliente-info'),
    cobroEquipoInfo: document.getElementById('cobro-equipo-info'),
    cobroConcepto: document.getElementById('cobro-concepto'),
    cobroMontoTotal: document.getElementById('cobro-monto-total'),
    cobroMontoACuenta: document.getElementById('cobro-monto-a-cuenta'),
    cobroMontoSaldo: document.getElementById('cobro-monto-saldo'),
    cobroMontoLetras: document.getElementById('cobro-monto-letras'),
    cobroMetodoPago: document.getElementById('cobro-metodo-pago'),
    cobroMarcarEntregado: document.getElementById('cobro-marcar-entregado')
  };

  /**
   * Inicialización del sistema
   */
  const init = async () => {
    // El usuario global (window.currentUser) y la UI ahora vienen directamente desde PHP SSO

    setupEventListeners();
    await checkApiHealth();
    await loadDashboard();
    await loadOrdenes();
  };

  /**
   * Abrir Modal de Recepción Express
   */
  const abrirNuevoIngreso = () => {
    const rolActual = (window.currentUser && window.currentUser.rol ? String(window.currentUser.rol).toLowerCase() : '');
    if (rolActual && rolActual !== 'admin' && rolActual !== 'vendedor') {
    if (window.currentUser && String(window.currentUser.rol).toLowerCase() !== 'admin' && String(window.currentUser.rol).toLowerCase() !== 'vendedor') {
      alert("Acceso Denegado: Solo los Administradores o Vendedores pueden recepcionar nuevos equipos.");
      return;
    }
    if (dom.formRecepcion) {
      dom.formRecepcion.reset();
    }
    if (window.toggleOtroDispositivo) window.toggleOtroDispositivo();

    // Asegurar que las tarjetas de acordeón estén abiertas
    document.querySelectorAll('.accordion-step-card').forEach(c => c.classList.add('open'));
    if (window.irAPaso) window.irAPaso(1);

    // Reset de status badge de lookup
    const feedbackEl = document.getElementById('doc-lookup-status');
    if (feedbackEl) {
      feedbackEl.className = 'doc-feedback-badge';
      feedbackEl.innerHTML = '<i class="ph-bold ph-lightning"></i> <span>Escriba 8 dígitos (DNI) u 11 (RUC) para autocompletar</span>';
    }

    Utils.openModal(dom.modalNuevoIngreso);
    if (window.updateUSDEquivalents) window.updateUSDEquivalents();
    if (window.updateModalPasoBadges) window.updateModalPasoBadges();

    setTimeout(() => {
      const inputDoc = document.getElementById('cliente-doc');
      if (inputDoc) inputDoc.focus();
    }, 150);
  };

  /**
   * Navegación asistida entre pasos del modal de ingreso
   */
  const irAPaso = (stepNumber) => {
    const cardId = `acc-card-${stepNumber}`;
    const card = document.getElementById(cardId);
    if (card) {
      card.classList.add('open');
      setTimeout(() => {
        card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      }, 50);
    }

    // Actualizar tabs superiores
    document.querySelectorAll('.step-nav-btn').forEach(btn => {
      const step = parseInt(btn.getAttribute('data-step'), 10);
      if (step === stepNumber) {
        btn.classList.add('active');
      } else {
        btn.classList.remove('active');
      }
    });
  };
  window.irAPaso = irAPaso;

  /**
   * Actualización en tiempo real de badges de completado y resumen en el pie del modal
   */
  const updateModalPasoBadges = () => {
    const doc = (dom.inputDni?.value || '').replace(/\D/g, '').trim();
    const nombre = (dom.inputNombre?.value || '').trim();
    const telefono = (dom.inputTelefono?.value || '').trim();

    const marca = (document.getElementById('equipo-marca')?.value || '').trim();
    const modelo = (document.getElementById('equipo-modelo')?.value || '').trim();
    const tipoSelect = document.getElementById('equipo-tipo');
    const tipo = tipoSelect ? tipoSelect.options[tipoSelect.selectedIndex]?.text || 'Equipo' : 'Equipo';

    const motivo = (document.getElementById('orden-motivo')?.value || '').trim();

    // 1. Validación Paso 1 (Cliente)
    const paso1Ok = doc.length >= 8 && nombre.length >= 3 && telefono.length === 9;
    const badgeCliente = document.getElementById('step-badge-cliente');
    const badgeNav1 = document.getElementById('badge-nav-1');
    const tab1 = document.getElementById('btn-step-tab-1');

    if (badgeCliente) {
      badgeCliente.className = `step-pill ${paso1Ok ? 'completed' : ''}`;
      badgeCliente.innerHTML = paso1Ok
        ? `<span style="color:#059669;font-weight:700;">✓</span> Listo`
        : `<span class="step-pill-dot"></span> Incompleto`;
    }
    if (badgeNav1) {
      badgeNav1.className = `step-pill ${paso1Ok ? 'completed' : ''}`;
      badgeNav1.innerHTML = paso1Ok ? `<span style="color:#059669;font-weight:700;">✓</span> Listo` : `<span class="step-pill-dot"></span> Pendiente`;
    }
    if (tab1) {
      if (paso1Ok) tab1.classList.add('completed');
      else tab1.classList.remove('completed');
    }

    // 2. Validación Paso 2 (Equipo)
    const paso2Ok = marca.length >= 2 && modelo.length >= 2;
    const badgeEquipo = document.getElementById('step-badge-equipo');
    const badgeNav2 = document.getElementById('badge-nav-2');
    const tab2 = document.getElementById('btn-step-tab-2');

    if (badgeEquipo) {
      badgeEquipo.className = `step-pill ${paso2Ok ? 'completed' : ''}`;
      badgeEquipo.innerHTML = paso2Ok
        ? `<span style="color:#059669;font-weight:700;">✓</span> Listo`
        : `<span class="step-pill-dot"></span> Incompleto`;
    }
    if (badgeNav2) {
      badgeNav2.className = `step-pill ${paso2Ok ? 'completed' : ''}`;
      badgeNav2.innerHTML = paso2Ok ? `<span style="color:#059669;font-weight:700;">✓</span> Listo` : `<span class="step-pill-dot"></span> Pendiente`;
    }
    if (tab2) {
      if (paso2Ok) tab2.classList.add('completed');
      else tab2.classList.remove('completed');
    }

    // 3. Validación Paso 3 (Diagnóstico)
    const paso3Ok = motivo.length >= 4;
    const badgeDiag = document.getElementById('step-badge-diagnostico');
    const badgeNav3 = document.getElementById('badge-nav-3');
    const tab3 = document.getElementById('btn-step-tab-3');

    if (badgeDiag) {
      badgeDiag.className = `step-pill ${paso3Ok ? 'completed' : ''}`;
      badgeDiag.innerHTML = paso3Ok
        ? `<span style="color:#059669;font-weight:700;">✓</span> Listo`
        : `<span class="step-pill-dot"></span> Incompleto`;
    }
    if (badgeNav3) {
      badgeNav3.className = `step-pill ${paso3Ok ? 'completed' : ''}`;
      badgeNav3.innerHTML = paso3Ok ? `<span style="color:#059669;font-weight:700;">✓</span> Listo` : `<span class="step-pill-dot"></span> Pendiente`;
    }
    if (tab3) {
      if (paso3Ok) tab3.classList.add('completed');
      else tab3.classList.remove('completed');
    }

    // Resumen dinámico en el footer del modal
    const summaryName = document.getElementById('summary-preview-name');
    const summaryDevice = document.getElementById('summary-preview-device');
    if (summaryName) {
      summaryName.textContent = nombre ? (nombre.length > 25 ? nombre.substring(0, 25) + '...' : nombre) : 'Sin asignar';
    }
    if (summaryDevice) {
      summaryDevice.textContent = marca ? `${tipo}: ${marca} ${modelo}` : tipo;
    }
  };
  window.updateModalPasoBadges = updateModalPasoBadges;

  /**
   * Búsqueda ágil y automática de cliente por DNI/RUC con apisperu.com
   */
  const handleBuscarCliente = async () => {
    const rawDoc = (dom.inputDni?.value || '').replace(/\D/g, '').trim();
    if (!rawDoc) {
      Utils.showToast('Atención', 'Ingrese un número de documento para consultar.', 'warning');
      return;
    }

    if (rawDoc.length === 11 && dom.tipoDocSelect) {
      dom.tipoDocSelect.value = 'RUC';
    } else if (rawDoc.length === 8 && dom.tipoDocSelect) {
      dom.tipoDocSelect.value = 'DNI';
    }

    const feedbackEl = document.getElementById('doc-lookup-status');
    if (feedbackEl) {
      feedbackEl.className = 'doc-feedback-badge loading';
      feedbackEl.innerHTML = `<span>⏳</span> <span>Consultando en <strong>apisperu.com</strong>...</span>`;
    }

    if (dom.btnBuscarDni) {
      dom.btnBuscarDni.disabled = true;
      dom.btnBuscarDni.innerHTML = `<span>⏳</span>`;
    }

    try {
      const res = await API.getClienteByDocumento(rawDoc);
      if (res && res.data) {
        const c = res.data;
        const nombreRes = c.nombres_razon_social || c.nombre_completo || '';
        dom.inputNombre.value = nombreRes;
        if (c.direccion) dom.inputDireccion.value = c.direccion;
        if (c.telefono) dom.inputTelefono.value = c.telefono;
        if (c.email) dom.inputEmail.value = c.email;
        if (c.tipo_documento || c.tipo) {
          dom.tipoDocSelect.value = (c.tipo_documento || c.tipo).toUpperCase();
        }

        // Desencadenar eventos para actualizar contadores de caracteres
        ['cliente-nombre', 'cliente-direccion', 'cliente-telefono', 'cliente-email'].forEach(id => {
          const el = document.getElementById(id);
          if (el) el.dispatchEvent(new Event('input', { bubbles: true }));
        });

        const origen = c.origen || 'apisperu.com (Oficial)';
        if (feedbackEl) {
          feedbackEl.className = 'doc-feedback-badge success';
          feedbackEl.innerHTML = `<span style="color:#059669;font-weight:700;">✓</span> <span><strong>${origen}:</strong> ${nombreRes}</span>`;
        }

        Utils.showToast(c.encontrado_local ? 'Cliente Registrado' : 'Autocompletado con Éxito', `${nombreRes} (${origen})`, 'success');

        if (!dom.inputTelefono.value.trim()) {
          setTimeout(() => dom.inputTelefono.focus(), 150);
        }
      } else {
        throw new Error('No se encontró registro');
      }
    } catch (error) {
      if (feedbackEl) {
        feedbackEl.className = 'doc-feedback-badge not-found';
        feedbackEl.innerHTML = `<span>ℹ️</span> <span>Cliente nuevo. Complete los datos manualmente.</span>`;
      }
      Utils.showToast('Cliente Nuevo', 'No se encontró registro en RENIEC/SUNAT. Complete los datos manualmente.', 'info');
      dom.inputNombre.focus();
    } finally {
      if (dom.btnBuscarDni) {
        dom.btnBuscarDni.disabled = false;
        dom.btnBuscarDni.innerHTML = `<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg> <span>Buscar</span>`;
      }
      updateModalPasoBadges();
    }
  };

  /**
   * Validaciones estrictas en tiempo real acorde al esquema MySQL y normativas
   */
  const setupStrictInputValidations = () => {
    const inputDoc = dom.inputDni;
    const selectTipoDoc = dom.tipoDocSelect;
    const docCounter = document.getElementById('cliente-doc-counter');
    const inputTelefono = dom.inputTelefono;
    const telCounter = document.getElementById('cliente-telefono-counter');
    const telHint = document.getElementById('cliente-telefono-hint');

    // 1. Configuración dinámica según Tipo de Documento
    const aplicarReglasTipoDoc = () => {
      if (!selectTipoDoc || !inputDoc) return;
      const tipo = selectTipoDoc.value;

      if (tipo === 'DNI') {
        inputDoc.maxLength = 8;
        inputDoc.inputMode = 'numeric';
        inputDoc.placeholder = '8 dígitos (ej: 77534805)';
        inputDoc.value = inputDoc.value.replace(/\D/g, '').slice(0, 8);
        if (docCounter) docCounter.textContent = `${inputDoc.value.length} / 8`;
      } else if (tipo === 'RUC') {
        inputDoc.maxLength = 11;
        inputDoc.inputMode = 'numeric';
        inputDoc.placeholder = '11 dígitos (ej: 20409456520)';
        inputDoc.value = inputDoc.value.replace(/\D/g, '').slice(0, 11);
        if (docCounter) docCounter.textContent = `${inputDoc.value.length} / 11`;
      } else if (tipo === 'CE') {
        inputDoc.maxLength = 12;
        inputDoc.inputMode = 'text';
        inputDoc.placeholder = '9 a 12 caracteres (Carnet Extranjería)';
        inputDoc.value = inputDoc.value.replace(/[^a-zA-Z0-9]/g, '').slice(0, 12).toUpperCase();
        if (docCounter) docCounter.textContent = `${inputDoc.value.length} / 12`;
      } else if (tipo === 'PASAPORTE') {
        inputDoc.maxLength = 12;
        inputDoc.inputMode = 'text';
        inputDoc.placeholder = '6 a 12 caracteres alfanuméricos';
        inputDoc.value = inputDoc.value.replace(/[^a-zA-Z0-9]/g, '').slice(0, 12).toUpperCase();
        if (docCounter) docCounter.textContent = `${inputDoc.value.length} / 12`;
      }
    };

    if (selectTipoDoc) {
      selectTipoDoc.addEventListener('change', () => {
        aplicarReglasTipoDoc();
        updateModalPasoBadges();
      });
      aplicarReglasTipoDoc();
    }

    // 2. Control estricto de teclas en Número de Documento (BLOQUEAR LETRAS EN TIEMPO REAL)
    if (inputDoc) {
      inputDoc.addEventListener('keydown', (e) => {
        const tipo = selectTipoDoc?.value || 'DNI';
        const controlKeys = ['Backspace', 'Tab', 'Enter', 'Escape', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Delete', 'Home', 'End'];
        if (controlKeys.includes(e.key)) return;
        if ((e.ctrlKey || e.metaKey) && ['a', 'c', 'v', 'x', 'z'].includes(e.key.toLowerCase())) return;

        if (tipo === 'DNI' || tipo === 'RUC') {
          // Bloquear terminantemente cualquier carácter no numérico
          if (!/^[0-9]$/.test(e.key)) {
            e.preventDefault();
            return false;
          }
        } else {
          // CE o Pasaporte: solo alfanumérico
          if (!/^[a-zA-Z0-9]$/.test(e.key)) {
            e.preventDefault();
            return false;
          }
        }
      });

      // Interceptar pegado (Paste) para sanitizar inmediatamente
      inputDoc.addEventListener('paste', (e) => {
        e.preventDefault();
        const tipo = selectTipoDoc?.value || 'DNI';
        const pastedText = (e.clipboardData || window.clipboardData).getData('text') || '';
        let cleanText = '';
        if (tipo === 'DNI') {
          cleanText = pastedText.replace(/\D/g, '').slice(0, 8);
        } else if (tipo === 'RUC') {
          cleanText = pastedText.replace(/\D/g, '').slice(0, 11);
        } else {
          cleanText = pastedText.replace(/[^a-zA-Z0-9]/g, '').slice(0, 12).toUpperCase();
        }
        document.execCommand('insertText', false, cleanText);
      });

      let debounceTimer = null;
      let ultimoDocConsultado = '';

      inputDoc.addEventListener('input', () => {
        const tipo = selectTipoDoc?.value || 'DNI';
        let val = inputDoc.value;

        if (tipo === 'DNI') {
          val = val.replace(/\D/g, '').slice(0, 8);
        } else if (tipo === 'RUC') {
          val = val.replace(/\D/g, '').slice(0, 11);
        } else {
          val = val.replace(/[^a-zA-Z0-9]/g, '').slice(0, 12).toUpperCase();
        }
        inputDoc.value = val;

        // Auto-cambio si el usuario pega 11 dígitos y está en DNI
        if (val.length === 11 && tipo === 'DNI' && selectTipoDoc) {
          selectTipoDoc.value = 'RUC';
          aplicarReglasTipoDoc();
        }

        const maxLen = inputDoc.maxLength || 8;
        if (docCounter) {
          docCounter.textContent = `${val.length} / ${maxLen}`;
          if (val.length === maxLen) {
            docCounter.classList.add('valid');
          } else {
            docCounter.classList.remove('valid');
          }
        }

        // Auto-búsqueda con apisperu.com al completar dígitos exactos
        if ((tipo === 'DNI' && val.length === 8) || (tipo === 'RUC' && val.length === 11)) {
          if (val !== ultimoDocConsultado) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
              ultimoDocConsultado = val;
              handleBuscarCliente();
            }, 250);
          }
        }

        updateModalPasoBadges();
      });

      inputDoc.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
          e.preventDefault();
          handleBuscarCliente();
        }
      });
    }

    // 3. Control estricto de Teléfono / WhatsApp (Bloquear letras, solo 9 dígitos numéricos)
    if (inputTelefono) {
      inputTelefono.maxLength = 9;
      inputTelefono.inputMode = 'numeric';

      inputTelefono.addEventListener('keydown', (e) => {
        const controlKeys = ['Backspace', 'Tab', 'Enter', 'Escape', 'ArrowLeft', 'ArrowRight', 'Delete', 'Home', 'End'];
        if (controlKeys.includes(e.key)) return;
        if ((e.ctrlKey || e.metaKey) && ['a', 'c', 'v', 'x', 'z'].includes(e.key.toLowerCase())) return;

        // Bloquear letras
        if (!/^[0-9]$/.test(e.key)) {
          e.preventDefault();
          return false;
        }
      });

      inputTelefono.addEventListener('paste', (e) => {
        e.preventDefault();
        const text = (e.clipboardData || window.clipboardData).getData('text') || '';
        const clean = text.replace(/\D/g, '').slice(0, 9);
        document.execCommand('insertText', false, clean);
      });

      inputTelefono.addEventListener('input', () => {
        inputTelefono.value = inputTelefono.value.replace(/\D/g, '').slice(0, 9);
        const len = inputTelefono.value.length;
        if (telCounter) telCounter.textContent = `${len} / 9`;

        if (telHint) {
          if (len === 9 && inputTelefono.value.startsWith('9')) {
            telHint.textContent = '✓ Celular válido WhatsApp';
            telHint.className = 'field-hint valid';
          } else if (len > 0 && !inputTelefono.value.startsWith('9')) {
            telHint.textContent = '⚠️ Los celulares de Perú inician con 9';
            telHint.className = 'field-hint';
          } else {
            telHint.textContent = '9 dígitos (móvil de Perú)';
            telHint.className = 'field-hint';
          }
        }
        updateModalPasoBadges();
      });
    }

    // 4. Contadores de caracteres para todos los campos según límites de la base de datos
    const camposConLimite = [
      { id: 'cliente-nombre', max: 180, counterId: 'cliente-nombre-counter' },
      { id: 'cliente-direccion', max: 255, counterId: 'cliente-direccion-counter' },
      { id: 'cliente-email', max: 100, counterId: 'cliente-email-counter' },
      { id: 'equipo-marca', max: 60, counterId: 'equipo-marca-counter' },
      { id: 'equipo-modelo', max: 80, counterId: 'equipo-modelo-counter' },
      { id: 'equipo-serie', max: 80, counterId: 'equipo-serie-counter', uppercase: true },
      { id: 'orden-motivo', max: 500, counterId: 'motivo-counter' },
      { id: 'equipo-observaciones', max: 500, counterId: 'observaciones-counter' }
    ];

    camposConLimite.forEach(c => {
      const el = document.getElementById(c.id);
      const counterEl = document.getElementById(c.counterId);
      if (el) {
        el.maxLength = c.max;
        const updateCount = () => {
          if (c.uppercase) el.value = el.value.toUpperCase();
          if (counterEl) {
            counterEl.textContent = `${el.value.length} / ${c.max}`;
            if (el.value.length >= c.max) {
              counterEl.classList.add('limit');
            } else {
              counterEl.classList.remove('limit');
            }
          }
        };
        el.addEventListener('input', updateCount);
        el.addEventListener('change', updateCount);
        updateCount();
      }
    });

    // 5. Control financiero: Adelanto no puede superar Costo Estimado
    const inputCosto = document.getElementById('orden-costo-estimado');
    const inputAdelanto = document.getElementById('orden-monto-adelanto');

    const validarFinanzas = () => {
      let costo = parseFloat(inputCosto?.value) || 0;
      let adelanto = parseFloat(inputAdelanto?.value) || 0;

      if (costo < 0) { costo = 0; if (inputCosto) inputCosto.value = '0.00'; }
      if (adelanto < 0) { adelanto = 0; if (inputAdelanto) inputAdelanto.value = '0.00'; }

      if (costo > 0 && adelanto > costo) {
        if (inputAdelanto) inputAdelanto.value = costo.toFixed(2);
        adelanto = costo;
        Utils.showToast('Validación', 'El adelanto inicial no puede ser mayor al costo estimado.', 'warning');
      }

      const saldo = Math.max(0, costo - adelanto);
      const tc = window.tipoCambioUSD || 3.75;

      const saldoEl = document.getElementById('orden-saldo-calc');
      const saldoUsdEl = document.getElementById('orden-saldo-usd');
      const costoUsdEl = document.getElementById('orden-costo-usd');
      const adelantoUsdEl = document.getElementById('orden-adelanto-usd');

      if (saldoEl) saldoEl.textContent = `S/. ${saldo.toFixed(2)}`;
      if (saldoUsdEl) saldoUsdEl.textContent = `$ ${(saldo / tc).toFixed(2)}`;
      if (costoUsdEl) costoUsdEl.textContent = `$ ${(costo / tc).toFixed(2)}`;
      if (adelantoUsdEl) adelantoUsdEl.textContent = `$ ${(adelanto / tc).toFixed(2)}`;
    };

    if (inputCosto) inputCosto.addEventListener('input', validarFinanzas);
    if (inputAdelanto) inputAdelanto.addEventListener('input', validarFinanzas);
  };

  /**
   * Configuración de acordeones, botones de pasos y chips rápidos
   */
  const setupModalIngresoAssist = () => {
    // 1. Toggles de cabeceras de acordeón
    document.querySelectorAll('.accordion-step-header[data-toggle]').forEach(header => {
      header.addEventListener('click', () => {
        const targetId = header.getAttribute('data-toggle');
        const card = document.getElementById(targetId);
        if (card) {
          card.classList.toggle('open');
        }
      });
    });

    // 2. Botón Expandir / Contraer Todo
    const btnToggleAll = document.getElementById('btn-toggle-all-accordions');
    if (btnToggleAll) {
      btnToggleAll.addEventListener('click', () => {
        const cards = document.querySelectorAll('.accordion-step-card');
        const allOpen = Array.from(cards).every(c => c.classList.contains('open'));
        cards.forEach(c => {
          if (allOpen) {
            c.classList.remove('open');
          } else {
            c.classList.add('open');
          }
        });
        const span = btnToggleAll.querySelector('span');
        if (allOpen) {
          if (span) span.textContent = 'Expandir Todo';
        } else {
          if (span) span.textContent = 'Contraer Todo';
        }
      });
    }

    // 3. Pestañas de pasos superior (Paso 1, Paso 2, Paso 3)
    document.querySelectorAll('.step-nav-btn[data-step]').forEach(btn => {
      btn.addEventListener('click', () => {
        const step = parseInt(btn.getAttribute('data-step'), 10);
        irAPaso(step);
      });
    });

    // 4. Botones internos "Continuar a..."
    document.querySelectorAll('.btn-step-next[data-goto]').forEach(btn => {
      btn.addEventListener('click', () => {
        const nextStep = parseInt(btn.getAttribute('data-goto'), 10);
        irAPaso(nextStep);
      });
    });

    // 5. Chips rápidos de texto para técnicos (Accesorios, fallas y estado estético)
    document.querySelectorAll('.quick-chip[data-append-target]').forEach(chip => {
      chip.addEventListener('click', (e) => {
        e.preventDefault();
        const targetId = chip.getAttribute('data-append-target');
        const text = chip.getAttribute('data-text');
        const input = document.getElementById(targetId);
        if (input && text) {
          const currentVal = input.value.trim();
          if (!currentVal) {
            input.value = text;
          } else if (!currentVal.toLowerCase().includes(text.toLowerCase())) {
            input.value = currentVal + ', ' + text;
          }
          input.dispatchEvent(new Event('input', { bubbles: true }));
          input.focus();
          updateModalPasoBadges();
        }
      });
    });

    // 6. Escuchar inputs clave para actualizar badges y preview
    const inputsToWatch = [
      dom.inputDni, dom.inputNombre, dom.inputTelefono,
      document.getElementById('equipo-marca'),
      document.getElementById('equipo-modelo'),
      document.getElementById('equipo-tipo'),
      document.getElementById('orden-motivo')
    ];
    inputsToWatch.forEach(inp => {
      if (inp) {
        inp.addEventListener('input', updateModalPasoBadges);
        inp.addEventListener('change', updateModalPasoBadges);
      }
    });
  };

  /**
   * Registrar Orden y Cliente (Express)
   */
  const handleRegistrarOrden = async (e) => {
    if (e && e.preventDefault) e.preventDefault();

    const btnSubmit = dom.formRecepcion?.querySelector('button[type="submit"]');
    if (btnSubmit) {
      btnSubmit.disabled = true;
      btnSubmit.innerHTML = `<span class="loading-spinner"></span> Registrando...`;
    }

    try {
      const tipoDoc = dom.tipoDocSelect ? dom.tipoDocSelect.value : 'DNI';
      const numDoc = (dom.inputDni?.value || '').trim();
      const nombreCliente = (dom.inputNombre?.value || '').trim();
      const telefonoCliente = (dom.inputTelefono?.value || '').replace(/\D/g, '').trim();

      if (!numDoc) {
        throw new Error('El número de documento es obligatorio.');
      }
      if (tipoDoc === 'DNI' && numDoc.length !== 8) {
        throw new Error('El DNI debe tener exactamente 8 dígitos.');
      }
      if (tipoDoc === 'RUC' && numDoc.length !== 11) {
        throw new Error('El RUC debe tener exactamente 11 dígitos.');
      }
      if (!nombreCliente) {
        throw new Error('El nombre o razón social del cliente es obligatorio.');
      }
      if (!telefonoCliente || telefonoCliente.length < 9) {
        throw new Error('El teléfono/WhatsApp del cliente es obligatorio y debe tener 9 dígitos.');
      }

      const clientePayload = {
        tipo_documento: tipoDoc,
        numero_documento: numDoc,
        nombres_razon_social: nombreCliente,
        telefono: telefonoCliente,
        direccion: (dom.inputDireccion?.value || '').trim(),
        email: (dom.inputEmail?.value || '').trim()
      };

      const clienteRes = await API.guardarCliente(clientePayload);
      const clienteId = clienteRes.data ? clienteRes.data.id : null;

      if (!clienteId) {
        throw new Error('No se pudo identificar o registrar al cliente.');
      }

      // Validar equipo
      const tipoEquipo = document.getElementById('equipo-tipo')?.value || 'Laptop';
      const marcaEquipo = (document.getElementById('equipo-marca')?.value || '').trim();
      const modeloEquipo = (document.getElementById('equipo-modelo')?.value || '').trim();
      const motivoIngreso = (document.getElementById('orden-motivo')?.value || '').trim();

      if (!marcaEquipo) throw new Error('La marca del equipo es obligatoria.');
      if (!modeloEquipo) throw new Error('El modelo del equipo es obligatorio.');
      if (!motivoIngreso) throw new Error('La falla reportada o motivo de ingreso es obligatorio.');

      const costoEstimado = parseFloat(document.getElementById('orden-costo-estimado')?.value) || 0;
      let montoAdelanto = parseFloat(document.getElementById('orden-monto-adelanto')?.value) || 0;

      if (montoAdelanto > costoEstimado) {
        montoAdelanto = costoEstimado;
      }

      const ordenPayload = {
        cliente_id: clienteId,
        tipo_equipo: tipoEquipo,
        marca: marcaEquipo,
        modelo: modeloEquipo,
        numero_serie: (document.getElementById('equipo-serie')?.value || '').trim(),
        password_equipo: (document.getElementById('equipo-password')?.value || '').trim(),
        accesorios_dejados: (document.getElementById('equipo-accesorios')?.value || '').trim(),
        motivo_ingreso: motivoIngreso,
        observaciones_esteticas: (document.getElementById('orden-observaciones')?.value || '').trim(),
        costo_estimado: costoEstimado,
        monto_adelanto: montoAdelanto,
        prioridad: document.getElementById('orden-prioridad')?.value || 'NORMAL',
        estado: 'RECEPCIONADO'
      };

      const ordenRes = await API.crearOrden(ordenPayload);
      Utils.showToast('¡Ingreso Registrado!', `Orden generada: ${ordenRes.data.codigo_orden}`, 'success');

      Utils.closeModal(dom.modalNuevoIngreso);
      if (dom.formRecepcion) dom.formRecepcion.reset();

      await loadOrdenes();
      await loadDashboard();
    } catch (error) {
      Utils.showToast('Error', error.message, 'error');
    } finally {
      if (btnSubmit) {
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = `<i class="ph-bold ph-floppy-disk"></i> Registrar y Generar Orden`;
      }
    }
  };

  /**
   * Configuración de eventos de UI
   */
  const setupEventListeners = () => {
    // Abrir Modal de Recepción Express
    if (dom.btnAbrirNuevoIngreso) {
      dom.btnAbrirNuevoIngreso.addEventListener('click', abrirNuevoIngreso);
    }

    // Búsqueda ágil de cliente por DNI/RUC
    if (dom.btnBuscarDni) {
      dom.btnBuscarDni.addEventListener('click', handleBuscarCliente);
    }

    // Validaciones estrictas de límites y bloqueo de letras en tiempo real
    setupStrictInputValidations();

    // Asistente por pasos y acordeones interactivos
    setupModalIngresoAssist();

    // Envío del Formulario de Recepción
    if (dom.formRecepcion) {
      dom.formRecepcion.addEventListener('submit', handleRegistrarOrden);
    }

    // Filtros de búsqueda en tiempo real
    if (dom.inputSearch) {
      dom.inputSearch.addEventListener('input', debounce(() => loadOrdenes(), 250));
    }
    if (dom.selectFilterEstado) {
      dom.selectFilterEstado.addEventListener('change', () => loadOrdenes());
    }

    // Toggle de input de 'Otro Dispositivo'
    const equipoTipoSelect = document.getElementById('equipo-tipo');
    const equipoTipoOtro = document.getElementById('equipo-tipo-otro');
    
    window.toggleOtroDispositivo = () => {
      if (equipoTipoSelect && equipoTipoOtro) {
        if (equipoTipoSelect.value === 'OTRO') {
          equipoTipoOtro.style.display = 'block';
          equipoTipoOtro.required = true;
          // Pequeño retardo para asegurar que el DOM actualice la UI
          setTimeout(() => equipoTipoOtro.focus(), 50);
        } else {
          equipoTipoOtro.style.display = 'none';
          equipoTipoOtro.required = false;
          equipoTipoOtro.value = '';
        }
      }
    };

    if (equipoTipoSelect) {
      equipoTipoSelect.addEventListener('change', window.toggleOtroDispositivo);
    }

    // Modal Técnico: Guardar Diagnóstico
    if (dom.formTecnico) {
      dom.formTecnico.addEventListener('submit', handleGuardarDiagnostico);
    }

    // Modal Técnico: Botón de un solo clic para Marcar REPARADO
    if (dom.btnQuickMarcarReparado) {
      dom.btnQuickMarcarReparado.addEventListener('click', async () => {
        dom.modalTecEstado.value = 'REPARADO';
        if (!dom.modalTecSolucion.value.trim()) {
          dom.modalTecSolucion.value = 'Servicio técnico de reparación completado satisfactoriamente.';
        }
        await handleGuardarDiagnostico(new Event('submit'));
      });
    }

    // Modal Cobro: Recálculo en tiempo real de saldo y letras
    if (dom.cobroMontoTotal && dom.cobroMontoACuenta) {
      const recalcularCobro = () => {
        const total = parseFloat(dom.cobroMontoTotal.value) || 0;
        const aCuenta = parseFloat(dom.cobroMontoACuenta.value) || 0;
        const saldo = Math.max(0, total - aCuenta);

        dom.cobroMontoSaldo.value = `S/. ${saldo.toFixed(2)}`;
        dom.cobroMontoLetras.value = Utils.numeroALetrasClient(total);
        if (window.updateUSDEquivalents) window.updateUSDEquivalents();
      };

      dom.cobroMontoTotal.addEventListener('input', recalcularCobro);
      dom.cobroMontoACuenta.addEventListener('input', recalcularCobro);
    }

    // Modal Cobro: Envío de cobro y emisión
    if (dom.formCobro) {
      dom.formCobro.addEventListener('submit', handleProcesarCobroYRecibo);
    }

    // Cierre de modales con botón .btn-close-modal o clic en backdrop
    document.querySelectorAll('.btn-close-modal').forEach(btn => {
      btn.addEventListener('click', (e) => {
        const modal = e.target.closest('.modal-backdrop');
        if (modal) Utils.closeModal(modal.id);
      });
    });

    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
      backdrop.addEventListener('click', (e) => {
        if (e.target === backdrop) {
          Utils.closeModal(backdrop.id);
        }
      });
    });
  };

  /**
   * Indicador visual de estado online
   */
  const setOnlineStatus = (isOnline) => {
    state.isOnline = isOnline;
    if (dom.apiStatusDot && dom.apiStatusText) {
      if (isOnline) {
        dom.apiStatusDot.className = 'status-dot';
        dom.apiStatusText.textContent = 'Conectado';
      } else {
        dom.apiStatusDot.className = 'status-dot offline';
        dom.apiStatusText.textContent = 'Modo Local / Desconectado';
      }
    }
  };

  /**
   * Health Check inicial de la API
   */
  const checkApiHealth = async () => {
    try {
      await API.checkHealth();
      setOnlineStatus(true);
    } catch (e) {
      setOnlineStatus(false);
    }
  };

  /**
   * Carga de KPIs métricos superiores
   */
  const loadDashboard = async () => {
    try {
      const res = await API.getDashboardMetrics();
      setOnlineStatus(true);
      const metrics = res.data;

      const estados = metrics.ordenes_por_estado || {};
      const enTaller = (estados.RECEPCIONADO || 0) + (estados.EN_DIAGNOSTICO || 0) + (estados.EN_REPARACION || 0) + (estados.ESPERA_REPUESTOS || 0);

      dom.kpiEnTaller.textContent = enTaller;
      dom.kpiListos.textContent = metrics.equipos_listos_entrega || 0;
      dom.kpiEntregados.textContent = estados.ENTREGADO || 0;
      dom.kpiPorCobrar.textContent = Utils.formatMoney(metrics.finanzas?.total_por_cobrar || 0);
    } catch (error) {
      console.warn("Error al cargar KPIs:", error);
    }
  };

  /**
   * Carga y renderizado de la tabla operativa de taller
   */
  const loadOrdenes = async () => {
    if (!dom.tablaTallerTbody) return;

    try {
      const filters = {
        q: dom.inputSearch ? dom.inputSearch.value.trim() : '',
        estado: dom.selectFilterEstado ? dom.selectFilterEstado.value : ''
      };

      const res = await API.getOrdenes(filters);
      setOnlineStatus(true);
      state.ordenes = res.data || [];

      if (dom.contadorTotal) {
        dom.contadorTotal.textContent = `${state.ordenes.length} órdenes en lista`;
      }

      if (state.ordenes.length === 0) {
        dom.tablaTallerTbody.innerHTML = `
          <tr>
            <td colspan="8" class="text-center" style="padding: 2.5rem; color: var(--slate-500);">
              <i class="ph-bold ph-tray" style="font-size: 2rem; color: var(--slate-400); display: block; margin-bottom: 0.5rem;"></i>
              No se encontraron órdenes con el criterio ingresado.
            </td>
          </tr>
        `;
        return;
      }

      dom.tablaTallerTbody.innerHTML = state.ordenes.map(ord => {
        const tieneRecibo = !!ord.recibo_id;
        const saldo = parseFloat(ord.monto_saldo) || 0;
        
        // BOTÓN ÚNICO DE ACCIÓN INTELIGENTE POR FILA
        let botonAccion = '';

        if (ord.estado === 'REPARADO') {
          // Equipo reparado -> Listo para cobrar y emitir recibo A5
          const btnWhatsApp = ord.cliente_telefono ? `
            <button class="btn btn-sm" style="background-color: #25D366; color: white; width: 100%; margin-top: 5px; box-shadow: 0 2px 8px rgba(37, 211, 102, 0.35); border: none;" onclick="App.notificarWhatsApp(${ord.id})">
              <i class="ph-bold ph-whatsapp-logo"></i> Notificar Cliente
            </button>
          ` : '';

          botonAccion = `
            <button class="btn btn-success btn-sm" style="box-shadow: 0 2px 8px rgba(16, 185, 129, 0.35); width: 100%;" onclick="App.cobrarYEntregar(${ord.id})">
              <i class="ph-bold ph-receipt"></i> Cobrar y Recibo
            </button>
            ${btnWhatsApp}
          `;
        } else if (ord.estado === 'ENTREGADO') {
          // Equipo entregado -> Ver o reimprimir comprobante A5
          botonAccion = `
            <button class="btn btn-cyan btn-sm" style="width: 100%;" onclick="App.verReciboA5(${ord.recibo_id || 0}, ${ord.id})">
              <i class="ph-bold ph-printer"></i> Recibo A5
            </button>
          `;
        } else {
          // Equipo en proceso (RECEPCIONADO, EN_DIAGNOSTICO, EN_REPARACION, etc.) -> Atender / Diagnóstico
          botonAccion = `
            <button class="btn btn-primary btn-sm" style="width: 100%;" onclick="App.atenderOrden(${ord.id})">
              <i class="ph-bold ph-wrench"></i> Atender / Diagnóstico
            </button>
          `;
        }

        return `
          <tr>
            <td>
              <strong style="color: var(--navy-800); font-family: var(--font-mono); font-size: 0.92rem;">${ord.codigo_orden}</strong>
            </td>
            <td>
              <div style="font-weight: 700; color: var(--navy-900);">${escapeHtml(ord.cliente_nombre)}</div>
              <small style="color: var(--slate-500);"><i class="ph ph-phone"></i> ${escapeHtml(ord.cliente_telefono || '')} &bull; ${escapeHtml(ord.cliente_documento || '')}</small>
            </td>
            <td>
              <div style="font-weight: 600;">${escapeHtml(ord.marca)} ${escapeHtml(ord.modelo)}</div>
              <small style="color: var(--slate-500);">${ord.tipo_equipo}</small>
            </td>
            <td style="max-width: 220px;">
              <div style="font-size: 0.8rem; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${escapeHtml(ord.motivo_ingreso)}">
                ${escapeHtml(ord.motivo_ingreso)}
              </div>
            </td>
            <td>
              <span class="status-badge ${ord.estado}">${ord.estado.replace(/_/g, ' ')}</span>
            </td>
            <td>
              ${tieneRecibo 
                ? `<div style="font-weight: 700; font-size: 0.8rem; color: var(--navy-800);">Recibo Nº ${ord.numero_recibo}</div>
                   <div style="font-size: 0.75rem; color: ${saldo > 0 ? 'var(--danger)' : 'var(--success)'}; font-weight: 700;">
                     ${saldo > 0 ? `Saldo: ${Utils.formatMoney(saldo)}` : `Cancelado Total`}
                   </div>`
                : `<div style="font-weight: 600; font-size: 0.8rem; color: var(--slate-600);">Est: ${Utils.formatMoney(ord.costo_estimado || 0)}</div>
                   <div style="font-size: 0.75rem; color: ${(ord.monto_adelanto > 0) ? 'var(--success)' : 'var(--slate-400)'}; font-weight: ${(ord.monto_adelanto > 0) ? '700' : '400'};">
                     A cuenta: ${Utils.formatMoney(ord.monto_adelanto || 0)}
                   </div>`
              }
            </td>
            <td style="font-size: 0.8rem; color: var(--slate-600);">
              ${Utils.formatDate(ord.fecha_ingreso)}
            </td>
            <td>
              ${botonAccion}
            </td>
          </tr>
        `;
      }).join('');
    } catch (error) {
      dom.tablaTallerTbody.innerHTML = `<tr><td colspan="8" class="text-center" style="padding: 2rem; color: var(--danger);">Error de conexión: ${error.message}</td></tr>`;
    }
  };

  /**
   * Acción 1: Atender Orden / Diagnóstico Técnico
   */
  const atenderOrden = async (ordenId) => {
    const rolActual = (window.currentUser && window.currentUser.rol ? String(window.currentUser.rol).toLowerCase() : '');
    if (rolActual && rolActual !== 'admin' && rolActual !== 'tecnico') {
    if (window.currentUser && String(window.currentUser.rol).toLowerCase() !== 'admin' && String(window.currentUser.rol).toLowerCase() !== 'tecnico') {
      alert("Acceso Denegado: Solo los Administradores o Técnicos pueden atender y diagnosticar equipos.");
      return;
    }
    try {
      const res = await API.getOrdenById(ordenId);
      const ord = res.data;
      state.currentOrden = ord;

      dom.modalTecIdOrden.value = ord.id;
      dom.modalTecCodigo.textContent = ord.codigo_orden;
      dom.modalTecCliente.textContent = `${ord.cliente_nombre} (${ord.cliente_documento})`;
      dom.modalTecEquipo.textContent = `${ord.tipo_equipo} ${ord.marca} ${ord.modelo} ${ord.numero_serie ? '(S/N: ' + ord.numero_serie + ')' : ''}`;
      dom.modalTecMotivo.textContent = ord.motivo_ingreso;

      dom.modalTecDiagnostico.value = ord.diagnostico_tecnico || '';
      dom.modalTecSolucion.value = ord.solucion_tecnica || '';
      dom.modalTecEstado.value = ord.estado;

      Utils.openModal(dom.modalTecnico);
    } catch (error) {
      Utils.showToast('Error', 'No se pudo cargar la orden: ' + error.message, 'error');
    }
  };

  /**
   * Guardar Diagnóstico Técnico
   */
  const handleGuardarDiagnostico = async (e) => {
    if (e && e.preventDefault) e.preventDefault();
    
    const ordenId = dom.modalTecIdOrden.value;
    const payload = {
      estado: dom.modalTecEstado.value,
      diagnostico_tecnico: dom.modalTecDiagnostico.value.trim(),
      solucion_tecnica: dom.modalTecSolucion.value.trim(),
      tecnico_id: window.currentUser ? window.currentUser.id : null
    };

    try {
      await API.actualizarEstadoOrden(ordenId, payload);
      Utils.showToast('Guardado', `Orden ${state.currentOrden.codigo_orden} actualizada a ${payload.estado}.`, 'success');
      Utils.closeModal(dom.modalTecnico);
      await loadOrdenes();
      await loadDashboard();
    } catch (error) {
      Utils.showToast('Error al Guardar', error.message, 'error');
    }
  };

  /**
   * Acción 2: Cobrar y Emitir Recibo A5 (Paso Único Integrado)
   */
  const cobrarYEntregar = async (ordenId) => {
    const rolActual = (window.currentUser && window.currentUser.rol ? String(window.currentUser.rol).toLowerCase() : '');
    if (rolActual && rolActual !== 'admin' && rolActual !== 'vendedor') {
    if (window.currentUser && String(window.currentUser.rol).toLowerCase() !== 'admin' && String(window.currentUser.rol).toLowerCase() !== 'vendedor') {
      alert("Acceso Denegado: Solo los Administradores o Vendedores/Caja pueden realizar cobros y entregas.");
      return;
    }
    try {
      const resOrd = await API.getOrdenById(ordenId);
      const ord = resOrd.data;
      state.currentOrden = ord;

      // Si ya tiene recibo emitido con saldo
      if (ord.recibo_id) {
        const resRecibo = await API.getReciboById(ord.recibo_id);
        const rec = resRecibo.data;
        state.currentRecibo = rec;

        dom.cobroOrdenId.value = ord.id;
        dom.cobroReciboId.value = rec.recibo_id;
        dom.cobroCorrelativoPreview.textContent = `Nº ${rec.numero_recibo}`;
        dom.cobroClienteInfo.textContent = `${rec.cliente_nombre} - ${rec.tipo_documento}: ${rec.numero_documento}`;
        dom.cobroEquipoInfo.textContent = `${rec.tipo_equipo} ${rec.marca} ${rec.modelo}`;
        dom.cobroConcepto.value = rec.concepto;

        dom.cobroMontoTotal.value = parseFloat(rec.monto_total).toFixed(2);
        dom.cobroMontoACuenta.value = parseFloat(rec.monto_a_cuenta).toFixed(2);
        dom.cobroMontoSaldo.value = `S/. ${parseFloat(rec.monto_saldo).toFixed(2)}`;
        dom.cobroMontoLetras.value = rec.monto_letras || Utils.numeroALetrasClient(rec.monto_total);
      } else {
        // Generar nuevo recibo
        const resCorr = await API.getSiguienteCorrelativo();
        const corr = resCorr.data;

        dom.cobroOrdenId.value = ord.id;
        dom.cobroReciboId.value = '';
        dom.cobroCorrelativoPreview.textContent = `Nº ${corr.numero_recibo}`;
        dom.cobroClienteInfo.textContent = `${ord.cliente_nombre} - ${ord.cliente_tipo_doc}: ${ord.cliente_documento}`;
        dom.cobroEquipoInfo.textContent = `${ord.tipo_equipo} ${ord.marca} ${ord.modelo}`;
        
        dom.cobroConcepto.value = ord.solucion_tecnica || `Servicio técnico y mantenimiento para ${ord.tipo_equipo} ${ord.marca} ${ord.modelo}.`;
        
        // Usar los montos reales de la orden ingresada
        const estimadoTotal = parseFloat(ord.costo_estimado) || 0;
        const adelantoPrevio = parseFloat(ord.monto_adelanto) || 0;
        const saldoCalculado = Math.max(0, estimadoTotal - adelantoPrevio);

        dom.cobroMontoTotal.value = estimadoTotal > 0 ? estimadoTotal.toFixed(2) : '';
        dom.cobroMontoACuenta.value = adelantoPrevio > 0 ? adelantoPrevio.toFixed(2) : '0.00';
        dom.cobroMontoSaldo.value = `S/. ${saldoCalculado.toFixed(2)}`;
        dom.cobroMontoLetras.value = Utils.numeroALetrasClient(estimadoTotal);
      }

      Utils.openModal(dom.modalCobro);
      if (window.updateUSDEquivalents) window.updateUSDEquivalents();
    } catch (error) {
      Utils.showToast('Error', 'No se pudo preparar el cobro: ' + error.message, 'error');
    }
  };

  /**
   * Confirmar Cobro, Registrar Comprobante e Imprimir Recibo A5
   */
  const handleProcesarCobroYRecibo = async (e) => {
    e.preventDefault();

    const ordenId = dom.cobroOrdenId.value;
    const reciboExistenteId = dom.cobroReciboId.value;
    const total = parseFloat(dom.cobroMontoTotal.value);
    const aCuenta = parseFloat(dom.cobroMontoACuenta.value) || 0.00;
    const metodoPago = dom.cobroMetodoPago.value;
    const marcarEntregado = dom.cobroMarcarEntregado ? dom.cobroMarcarEntregado.checked : true;

    try {
      let reciboFinalId;

      if (reciboExistenteId) {
        // Liquidar saldo de recibo existente
        const saldoAbonar = Math.max(0, total - aCuenta);
        if (saldoAbonar > 0) {
          const resLiq = await API.liquidarSaldo(parseInt(reciboExistenteId), {
            monto_abonado: saldoAbonar,
            metodo_pago: metodoPago,
            marcar_entregado: marcarEntregado
          });
          reciboFinalId = resLiq.data.recibo_id;
        } else {
          // El recibo ya está cancelado, solo actualizamos el estado de la orden si se requiere
          if (marcarEntregado) {
            await API.actualizarEstadoOrden(ordenId, { estado: 'ENTREGADO' });
          }
          reciboFinalId = reciboExistenteId;
        }
      } else {
        // Emitir nuevo recibo oficial
        const resEmit = await API.emitirRecibo({
          orden_servicio_id: ordenId,
          usuario_emisor_id: window.currentUser ? window.currentUser.id : null, // ID dinámico del usuario autenticado
          usuario_emisor_id: window.currentUser ? window.currentUser.id : null, // Fix hardcoded ID
          concepto: dom.cobroConcepto.value.trim(),
          monto_total: total,
          monto_a_cuenta: total, // Al entregar se cancela el total
          monto_letras: dom.cobroMontoLetras.value.trim(),
          metodo_pago: metodoPago,
          dias_garantia: 90,
          marcar_entregado: marcarEntregado
        });
        reciboFinalId = resEmit.data.recibo_id;
      }

      Utils.showToast('¡Cobro Completado!', 'Recibo emitido y equipo entregado con éxito.', 'success');
      Utils.closeModal(dom.modalCobro);

      await loadOrdenes();
      await loadDashboard();

      // Abrir de inmediato la ventana de impresión A5 nativa
      verReciboA5(reciboFinalId);
    } catch (error) {
      Utils.showToast('Error en Cobro', error.message, 'error');
    }
  };

  /**
   * Acción 3: Abrir / Imprimir Recibo A5 Oficial
   */
  const verReciboA5 = (reciboId, ordenId = null) => {
    let printUrl = `recibo.html?autoprint=1`;
    if (reciboId && reciboId > 0) {
      printUrl += `&id=${reciboId}`;
    } else if (ordenId) {
      printUrl += `&orden_id=${ordenId}`;
    }
    window.open(printUrl, '_blank', 'width=800,height=900,scrollbars=yes,resizable=yes');
  };

  /**
   * Utilidad de escape HTML contra XSS
   */
  const escapeHtml = (text) => {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  };

  /**
   * Debounce para buscador
   */
  function debounce(func, wait) {
    let timeout;
    return function (...args) {
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(this, args), wait);
    };
  }

  /**
   * Acción 4: Notificar al cliente vía WhatsApp
   */
  const notificarWhatsApp = (ordenId) => {
    try {
      const ord = state.ordenes.find(o => o.id === ordenId);
      if (!ord || !ord.cliente_telefono) {
        Utils.showToast('WhatsApp', 'No se encontró el número de teléfono del cliente.', 'warning');
        return;
      }

      const estimadoTotal = parseFloat(ord.costo_estimado) || 0;
      const adelanto = parseFloat(ord.monto_adelanto) || 0;
      const saldo = Math.max(0, estimadoTotal - adelanto);
      
      const mensaje = `Hola ${ord.cliente_nombre}, te saludamos de MAK-PC Enterprises. Tu equipo ${ord.tipo_equipo} ${ord.marca} ${ord.modelo} ya se encuentra REPARADO y listo para recojo. Tu saldo pendiente es de S/. ${saldo.toFixed(2)}. ¡Te esperamos!`;
      
      let telefono = ord.cliente_telefono.replace(/\D/g, '');
      // Si el teléfono es de Perú y tiene 9 dígitos, agregar el prefijo +51
      if (telefono.length === 9 && !telefono.startsWith('51')) {
          telefono = '51' + telefono;
      }

      const url = `https://wa.me/${telefono}?text=${encodeURIComponent(mensaje)}`;
      window.open(url, '_blank');
      
      Utils.showToast('WhatsApp', 'Abriendo chat con el cliente...', 'success');
    } catch (error) {
      console.error(error);
    }
  };

  // Exponer métodos al objeto global App
  window.App = {
    abrirNuevoIngreso,
    atenderOrden,
    cobrarYEntregar,
    verReciboA5,
    notificarWhatsApp
  };

  // Iniciar
  init();
});
