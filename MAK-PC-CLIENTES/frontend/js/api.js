/**
 * ==============================================================================
 * MAK-PC Enterprises S.A.C. - Capa de Comunicación API REST (api.js)
 * Detección universal para Laragon, Apache, XAMPP y PHP CLI
 * ==============================================================================
 */

const API = (() => {
  /**
   * Detección inteligente de la ruta base del Backend
   */
  const detectarBaseUrl = () => {
    const origin = window.location.origin;       // Ej: http://localhost o http://localhost:8000
    const pathname = window.location.pathname;   // Ej: /index.html o /MAK-PC/frontend/index.html

    // 1. Si se corre con el servidor embebido de PHP (php -S localhost:8000)
    if (origin.includes(':8000')) {
      return origin;
    }

    // 2. Si se ejecuta en subcarpeta /MAK-PC/
    if (pathname.toLowerCase().includes('/mak-pc/')) {
      return `${origin}/MAK-PC/frontend/api.php`;
    }

    // 3. Si DocumentRoot de Laragon está configurado directamente en /frontend
    return `${origin}/api.php`;
  };

  let baseUrl = detectarBaseUrl();

  /**
   * Permite configurar manualmente la URL base
   */
  const setBaseUrl = (url) => {
    baseUrl = url.replace(/\/+$/, '');
  };

  const getBaseUrl = () => baseUrl;

  /**
   * Construye la URL exacta compatible con cualquier servidor
   */
  const construirUrl = (endpoint, base = baseUrl) => {
    const [routePart, queryPart] = endpoint.split('?');
    const cleanRoute = routePart.startsWith('/') ? routePart : `/${routePart}`;

    if (base.includes('.php')) {
      const separator = base.includes('?') ? '&' : '?';
      return `${base}${separator}r=${encodeURIComponent(cleanRoute)}${queryPart ? '&' + queryPart : ''}`;
    }

    return `${base}${cleanRoute}${queryPart ? '?' + queryPart : ''}`;
  };

  /**
   * Wrapper genérico para peticiones HTTP con auto-fallback de rutas
   */
  const request = async (endpoint, options = {}) => {
    const defaultHeaders = {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    };

    const config = {
      ...options,
      headers: {
        ...defaultHeaders,
        ...options.headers
      }
    };

    // Lista de rutas candidatas para auto-recuperación si la primaria falla
    const candidateBases = [
      baseUrl,
      'api.php',
      `${window.location.origin}/api.php`,
      `${window.location.origin}/MAK-PC/frontend/api.php`,
      `${window.location.origin}/MAK-PC/backend/index.php`,
      `${window.location.origin}/backend/index.php`,
      'http://localhost/api.php',
      'http://localhost/MAK-PC/backend/index.php',
      'http://localhost:8000'
    ];
    // Eliminar duplicados
    const uniqueCandidates = [...new Set(candidateBases)];

    let lastError = null;

    for (const candidateBase of uniqueCandidates) {
      const url = construirUrl(endpoint, candidateBase);

      try {
        const response = await fetch(url, config);

        // Validar si la respuesta es JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
          continue; // Probar siguiente ruta candidata
        }

        const data = await response.json();

        if (response.ok && data.success) {
          // Si una ruta alternativa tuvo éxito, actualizar baseUrl para futuras peticiones
          if (baseUrl !== candidateBase) {
            baseUrl = candidateBase;
          }
          return data;
        }

        // Si el backend devolvió un error JSON explícito
        if (data && !data.success) {
          throw new Error(data.message || `Error en la solicitud: ${response.status}`);
        }
      } catch (err) {
        lastError = err;
        // Si el error es de lógica de negocio (ej. validación de campos), propagarlo inmediatamente
        if (err.message && !err.message.includes('Failed to fetch') && !err.message.includes('NetworkError') && !err.message.includes('Ruta no encontrada') && !err.message.includes('404')) {
          throw err;
        }
      }
    }

    console.error(`[API ERROR] No se pudo conectar con ningún endpoint backend para ${endpoint}:`, lastError);
    throw lastError || new Error(`No se pudo establecer conexión con el backend en ${endpoint}`);
  };

  return {
    setBaseUrl,
    getBaseUrl,

    // ---- SALUD Y ESTADO ----
    checkHealth: () => request('/api/health'),

    // ---- DASHBOARD ----
    getDashboardMetrics: () => request('/api/dashboard/resumen'),

    // ---- CLIENTES ----
    buscarClientes: (query = '') => request(`/api/clientes?q=${encodeURIComponent(query)}`),
    getClienteByDocumento: (doc) => request(`/api/clientes/documento/${encodeURIComponent(doc)}`),
    getClienteById: (id) => request(`/api/clientes/${id}`),
    guardarCliente: (clienteData) => request('/api/clientes', {
      method: 'POST',
      body: JSON.stringify(clienteData)
    }),

    // ---- ÓRDENES DE SERVICIO ----
    getOrdenes: (filters = {}) => {
      const params = new URLSearchParams();
      if (filters.estado) params.append('estado', filters.estado);
      if (filters.q) params.append('q', filters.q);
      if (filters.tecnico_id) params.append('tecnico_id', filters.tecnico_id);
      const queryStr = params.toString() ? `?${params.toString()}` : '';
      return request(`/api/ordenes${queryStr}`);
    },
    getOrdenById: (id) => request(`/api/ordenes/${id}`),
    crearOrden: (ordenData) => request('/api/ordenes', {
      method: 'POST',
      body: JSON.stringify(ordenData)
    }),
    actualizarEstadoOrden: (id, payload) => request(`/api/ordenes/${id}/estado`, {
      method: 'PUT',
      body: JSON.stringify(payload)
    }),

    // ---- RECIBOS Y LIQUIDACIÓN ----
    getSiguienteCorrelativo: () => request('/api/recibos/siguiente-correlativo'),
    getReciboById: (id) => request(`/api/recibos/${id}`),
    getReciboByOrdenId: (ordenId) => request(`/api/recibos/orden/${ordenId}`),
    emitirRecibo: (reciboData) => request('/api/recibos', {
      method: 'POST',
      body: JSON.stringify(reciboData)
    }),
    liquidarSaldo: (reciboId, liquidacionData) => request(`/api/recibos/${reciboId}/liquidar-saldo`, {
      method: 'PUT',
      body: JSON.stringify(liquidacionData)
    }),

    // ---- UTILIDADES ----
    convertirNumeroALetras: (monto) => request(`/api/util/numero-a-letras?monto=${encodeURIComponent(monto)}`),
    validarGarantia: (vencimiento) => request(`/api/util/validar-garantia?vencimiento=${encodeURIComponent(vencimiento)}`)
  };
})();