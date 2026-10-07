/**
 * ==============================================================================
 * MAK-PC Enterprises S.A.C. - Currency Converter (currency.js)
 * Script para actualizar automáticamente el tipo de cambio del dólar al día.
 * ==============================================================================
 */

const CurrencyManager = (() => {
    let exchangeRate = 0.27; // Tasa por defecto (aprox) si la API falla

    // Obtiene la tasa de cambio universal del día (PEN a USD)
    const fetchExchangeRate = async () => {
        try {
            const response = await fetch('https://open.er-api.com/v6/latest/PEN');
            const data = await response.json();
            if (data && data.rates && data.rates.USD) {
                exchangeRate = data.rates.USD;
                console.log(`[Currency] Tipo de cambio actualizado: 1 PEN = ${exchangeRate} USD`);
            }
        } catch (error) {
            console.warn('[Currency] Error obteniendo tipo de cambio, usando tasa por defecto.', error);
        }
    };

    // Convierte un monto en soles a dólares
    const convertToUSD = (soles) => {
        if (!soles || isNaN(soles)) return "0.00";
        return (parseFloat(soles) * exchangeRate).toFixed(2);
    };

    // Actualiza los elementos del DOM que muestran el equivalente en dólares
    const updateUSDEquivalents = () => {
        // Recepción
        const costoInput = document.getElementById('orden-costo-estimado');
        const adelantoInput = document.getElementById('orden-monto-adelanto');
        const costoUSD = document.getElementById('orden-costo-usd');
        const adelantoUSD = document.getElementById('orden-adelanto-usd');

        if (costoInput && costoUSD) {
            costoUSD.textContent = `$ ${convertToUSD(costoInput.value)}`;
        }
        if (adelantoInput && adelantoUSD) {
            adelantoUSD.textContent = `$ ${convertToUSD(adelantoInput.value)}`;
        }

        // Cobro
        const cobroTotalInput = document.getElementById('cobro-monto-total');
        const cobroACuentaInput = document.getElementById('cobro-monto-a-cuenta');
        const cobroSaldoInput = document.getElementById('cobro-monto-saldo');
        
        const cobroTotalUSD = document.getElementById('cobro-total-usd');
        const cobroACuentaUSD = document.getElementById('cobro-acuenta-usd');
        const cobroSaldoUSD = document.getElementById('cobro-saldo-usd');

        if (cobroTotalInput && cobroTotalUSD) {
            cobroTotalUSD.textContent = `$ ${convertToUSD(cobroTotalInput.value)}`;
        }
        if (cobroACuentaInput && cobroACuentaUSD) {
            cobroACuentaUSD.textContent = `$ ${convertToUSD(cobroACuentaInput.value)}`;
        }
        if (cobroSaldoInput && cobroSaldoUSD) {
            const saldoVal = cobroSaldoInput.value.replace(/[^0-9.-]+/g,"");
            cobroSaldoUSD.textContent = `$ ${convertToUSD(saldoVal)}`;
        }
    };

    // Inicializa el observador
    const init = async () => {
        await fetchExchangeRate();

        // Agregar event listeners a todos los inputs relevantes
        const inputs = [
            'orden-costo-estimado',
            'orden-monto-adelanto',
            'cobro-monto-total',
            'cobro-monto-a-cuenta'
        ];

        inputs.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', updateUSDEquivalents);
            }
        });
        
        // Exponer función manual por si otros scripts modifican los inputs programáticamente
        window.updateUSDEquivalents = updateUSDEquivalents;
    };

    return {
        init,
        convertToUSD,
        updateUSDEquivalents
    };
})();

document.addEventListener('DOMContentLoaded', () => {
    CurrencyManager.init();
});
