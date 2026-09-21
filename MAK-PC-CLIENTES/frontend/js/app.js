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
    setupEventListeners();
    await checkApiHealth();
    await loadDashboard();
    await loadOrdenes();
  };

  /**
   * Abrir Modal de Recepción Express
   */
  const abrirNuevoIngreso = () => {
    if (dom.formRecepcion) {
      dom.formRecepcion.reset();
    }
    Utils.openModal(dom.modalNuevoIngreso);
    setTimeout(() => {
      const inputDoc = document.getElementById('cliente-doc');
      if (inputDoc) inputDoc.focus();
    }, 150);
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
    if (dom.inputDni) {
      dom.inputDni.addEventListener('blur', () => {
        if (dom.inputDni.value.trim().length >= 8 && !dom.inputNombre.value.trim()) {
          handleBuscarCliente();
        }
      });
      dom.inputDni.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
          e.preventDefault();
          handleBuscarCliente();
        }
      });
    }

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
          botonAccion = `
            <button class="btn btn-success btn-sm" style="box-shadow: 0 2px 8px rgba(16, 185, 129, 0.35); width: 100%;" onclick="App.cobrarYEntregar(${ord.id})">
              <i class="ph-bold ph-receipt"></i> Cobrar y Recibo
            </button>
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
                : `<span style="font-size: 0.75rem; color: var(--slate-400); font-style: italic;">Sin Comprobante</span>`
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
   * Búsqueda ágil de cliente por DNI/RUC
   */
  const handleBuscarCliente = async () => {
    const doc = dom.inputDni.value.trim();
    if (!doc) {
      Utils.showToast('Atención', 'Ingrese un documento para consultar.', 'warning');
      return;
    }

    dom.btnBuscarDni.disabled = true;
    dom.btnBuscarDni.innerHTML = `<i class="ph-bold ph-spinner" style="animation: spin 1s infinite linear;"></i>`;

    try {
      const res = await API.getClienteByDocumento(doc);
      if (res.data) {
        const c = res.data;
        dom.inputNombre.value = c.nombres_razon_social || '';
        dom.inputTelefono.value = c.telefono || '';
        dom.inputDireccion.value = c.direccion || '';
        dom.inputEmail.value = c.email || '';
        dom.tipoDocSelect.value = c.tipo_documento || 'DNI';
        Utils.showToast('Cliente Encontrado', `${c.nombres_razon_social}`, 'success');
      }
    } catch (error) {
      Utils.showToast('Cliente Nuevo', 'No se encontró registro previo. Complete los datos para crearlo automáticamente.', 'info');
      dom.inputNombre.focus();
    } finally {
      dom.btnBuscarDni.disabled = false;
      dom.btnBuscarDni.innerHTML = `<i class="ph-bold ph-magnifying-glass"></i>`;
    }
  };

  /**
   * Registro Express de Equipo y Nueva Orden
   */
  const handleRegistrarOrden = async (e) => {
    e.preventDefault();

    const doc = dom.inputDni.value.trim();
    const nombres = dom.inputNombre.value.trim();
    const telefono = dom.inputTelefono.value.trim();

    if (!doc || !nombres || !telefono) {
      Utils.showToast('Validación', 'Documento, Nombre y Teléfono son obligatorios.', 'error');
      return;
    }

    const btnSubmit = dom.formRecepcion.querySelector('button[type="submit"]');
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = `<i class="ph-bold ph-spinner" style="animation: spin 1s infinite linear;"></i> Registrando...`;

    try {
      // 1. Guardar o recuperar cliente
      const clienteRes = await API.guardarCliente({
        tipo_documento: dom.tipoDocSelect.value,
        numero_documento: doc,
        nombres_razon_social: nombres,
        telefono: telefono,
        direccion: dom.inputDireccion.value.trim(),
        email: dom.inputEmail.value.trim()
      });

      // 2. Crear Orden de Servicio
      const ordenPayload = {
        cliente_id: clienteRes.data.id,
        recepcionista_id: 3, // Ana Recepción
        tecnico_id: document.getElementById('orden-tecnico-asignado').value || 2,
        tipo_equipo: document.getElementById('equipo-tipo').value,
        marca: document.getElementById('equipo-marca').value.trim(),
        modelo: document.getElementById('equipo-modelo').value.trim(),
        numero_serie: document.getElementById('equipo-serie').value.trim(),
        accesorios_dejados: document.getElementById('equipo-accesorios').value.trim(),
        password_equipo: document.getElementById('equipo-password').value.trim(),
        motivo_ingreso: document.getElementById('orden-motivo').value.trim(),
        observaciones_esteticas: document.getElementById('equipo-observaciones').value.trim(),
        costo_estimado: parseFloat(document.getElementById('orden-costo-estimado')?.value) || 0,
        monto_adelanto: parseFloat(document.getElementById('orden-monto-adelanto')?.value) || 0,
        prioridad: document.getElementById('orden-prioridad').value,
        estado: 'RECEPCIONADO'
      };

      const ordenRes = await API.crearOrden(ordenPayload);
      Utils.showToast('¡Ingreso Registrado!', `Orden generada: ${ordenRes.data.codigo_orden}`, 'success');

      Utils.closeModal(dom.modalNuevoIngreso);
      dom.formRecepcion.reset();

      await loadOrdenes();
      await loadDashboard();
    } catch (error) {
      Utils.showToast('Error', error.message, 'error');
    } finally {
      btnSubmit.disabled = false;
      btnSubmit.innerHTML = `<i class="ph-bold ph-floppy-disk"></i> Registrar y Generar Orden`;
    }
  };

  /**
   * Acción 1: Atender Orden / Diagnóstico Técnico
   */
  const atenderOrden = async (ordenId) => {
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
      tecnico_id: 2
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
        dom.cobroMontoTotal.value = '150.00';
        dom.cobroMontoACuenta.value = '0.00';
        dom.cobroMontoSaldo.value = 'S/. 150.00';
        dom.cobroMontoLetras.value = Utils.numeroALetrasClient(150.00);
      }

      Utils.openModal(dom.modalCobro);
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
        const resLiq = await API.liquidarSaldo(parseInt(reciboExistenteId), {
          monto_abonado: saldoAbonar > 0 ? saldoAbonar : total,
          metodo_pago: metodoPago,
          marcar_entregado: marcarEntregado
        });
        reciboFinalId = resLiq.data.recibo_id;
      } else {
        // Emitir nuevo recibo oficial
        const resEmit = await API.emitirRecibo({
          orden_servicio_id: ordenId,
          usuario_emisor_id: 3, // Ana Recepción
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

  // Exponer métodos al objeto global App
  window.App = {
    abrirNuevoIngreso,
    atenderOrden,
    cobrarYEntregar,
    verReciboA5
  };

  // Iniciar
  init();
});
