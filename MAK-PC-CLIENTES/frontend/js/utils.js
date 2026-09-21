/**
 * ==============================================================================
 * MAK-PC Enterprises S.A.C. - Utilidades de Interfaz y Formato (utils.js)
 * ==============================================================================
 */

const Utils = (() => {
  /**
   * Formateador de moneda en Soles (S/.)
   */
  const formatMoney = (amount) => {
    const num = parseFloat(amount) || 0;
    return `S/. ${num.toFixed(2)}`;
  };

  /**
   * Formateador de fecha amigable (DD/MM/YYYY HH:MM)
   */
  const formatDate = (dateString) => {
    if (!dateString) return '-';
    const d = new Date(dateString.replace(' ', 'T'));
    if (isNaN(d.getTime())) return dateString;
    return d.toLocaleDateString('es-PE', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });
  };

  /**
   * Sistema de Notificaciones Toast
   */
  const showToast = (title, message, type = 'info', duration = 4000) => {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `toast ${type}`;

    const iconMap = {
      success: 'ph-check-circle',
      error: 'ph-warning-circle',
      warning: 'ph-warning',
      info: 'ph-info'
    };

    toast.innerHTML = `
      <i class="ph-bold ${iconMap[type] || 'ph-info'}" style="font-size: 1.4rem; color: var(--${type === 'success' ? 'success' : type === 'error' ? 'danger' : type === 'warning' ? 'warning' : 'cyan-500'});"></i>
      <div class="toast-content">
        <h4>${title}</h4>
        <p>${message}</p>
      </div>
    `;

    container.appendChild(toast);

    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateX(100%)';
      setTimeout(() => toast.remove(), 300);
    }, duration);
  };

  /**
   * Conversor local rápido de números a letras (para respuesta instantánea en el input)
   */
  const numeroALetrasClient = (monto) => {
    const unidades = ['', 'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE', 'DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISÉIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE', 'VEINTE'];
    const decenas = ['', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
    const centenas = ['', 'CIEN', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];

    const num = Math.round((parseFloat(monto) || 0) * 100) / 100;
    const entero = Math.floor(num);
    const centavos = Math.round((num - entero) * 100).toString().padStart(2, '0');

    const convertirDecenas = (n) => {
      if (n <= 20) return unidades[n];
      if (n < 30) return n === 20 ? 'VEINTE' : 'VEINTI' + unidades[n % 10];
      const d = Math.floor(n / 10);
      const u = n % 10;
      return u === 0 ? decenas[d] : `${decenas[d]} Y ${unidades[u]}`;
    };

    const convertirCentenas = (n) => {
      if (n === 0) return '';
      if (n === 100) return 'CIEN';
      if (n > 100) {
        const c = Math.floor(n / 100);
        const r = n % 100;
        const txtC = c === 1 ? 'CIENTO' : centenas[c];
        return r > 0 ? `${txtC} ${convertirDecenas(r)}` : txtC;
      }
      return convertirDecenas(n);
    };

    const convertirMiles = (n) => {
      if (n >= 1000) {
        const m = Math.floor(n / 1000);
        const r = n % 1000;
        const txtM = m === 1 ? 'MIL' : `${convertirCentenas(m)} MIL`;
        return r > 0 ? `${txtM} ${convertirCentenas(r)}` : txtM;
      }
      return convertirCentenas(n);
    };

    let literal = entero === 0 ? 'CERO' : convertirMiles(entero);
    return `SON ${literal.trim()} CON ${centavos}/100 SOLES`;
  };

  /**
   * Helper para abrir y cerrar modales
   */
  const openModal = (modalId) => {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }
  };

  const closeModal = (modalId) => {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.classList.remove('active');
      document.body.style.overflow = '';
    }
  };

  return {
    formatMoney,
    formatDate,
    showToast,
    numeroALetrasClient,
    openModal,
    closeModal
  };
})();
