<?php
declare(strict_types=1);

namespace Utils;

use InvalidArgumentException;

/**
 * Helper para conversión precisa de cifras numéricas a formato literal
 * Adaptado a la normativa comercial y contable peruana (Soles - S/.)
 */
class NumeroALetrasHelper {
    private static array $UNIDADES = [
        '', 'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE',
        'DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISÉIS', 'DIECISIETE',
        'DIECIOCHO', 'DIECINUEVE', 'VEINTE'
    ];

    private static array $DECENAS = [
        '', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'
    ];

    private static array $CENTENAS = [
        '', 'CIEN', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS',
        'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'
    ];

    /**
     * Convierte un monto numérico al formato formal de comprobante de pago
     * Ejemplo: 150.50 -> "SON CIENTO CINCUENTA CON 50/100 SOLES"
     * 
     * @param float|int|string $monto Monto en Soles
     * @param string $moneda Nombre de la moneda (por defecto "SOLES")
     * @return string Texto formal autogenerado
     */
    public static function convertir(float|int|string $monto, string $moneda = 'SOLES'): string {
        $montoNumerico = (float)$monto;

        if ($montoNumerico < 0) {
            throw new InvalidArgumentException("El monto a convertir no puede ser negativo.");
        }

        if ($montoNumerico > 999999999.99) {
            throw new InvalidArgumentException("El monto excede el límite máximo soportado (999,999,999.99).");
        }

        // Redondear a 2 decimales para precisión contable
        $montoNumerico = round($montoNumerico, 2);

        // Separar parte entera y parte decimal
        $entero = (int)floor($montoNumerico);
        $centavos = (int)round(($montoNumerico - $entero) * 100);
        $centavosFormateados = sprintf("%02d", $centavos);

        // Si el entero es 0
        if ($entero === 0) {
            $literalEntero = "CERO";
        } else {
            $literalEntero = self::convertirGrupoMillones($entero);
        }

        return sprintf("SON %s CON %s/100 %s", trim($literalEntero), $centavosFormateados, strtoupper($moneda));
    }

    /**
     * Procesa números hasta 999,999,999 (millones)
     */
    private static function convertirGrupoMillones(int $numero): string {
        if ($numero >= 1000000) {
            $millones = (int)floor($numero / 1000000);
            $resto = $numero % 1000000;

            if ($millones === 1) {
                $textoMillones = "UN MILLÓN";
            } else {
                $textoMillones = self::convertirGrupoMiles($millones) . " MILLONES";
            }

            if ($resto > 0) {
                return $textoMillones . " " . self::convertirGrupoMiles($resto);
            }
            return $textoMillones;
        }

        return self::convertirGrupoMiles($numero);
    }

    /**
     * Procesa números hasta 999,999 (miles)
     */
    private static function convertirGrupoMiles(int $numero): string {
        if ($numero >= 1000) {
            $miles = (int)floor($numero / 1000);
            $resto = $numero % 1000;

            if ($miles === 1) {
                $textoMiles = "MIL";
            } else {
                $textoMiles = self::convertirCentenas($miles) . " MIL";
            }

            if ($resto > 0) {
                return $textoMiles . " " . self::convertirCentenas($resto);
            }
            return $textoMiles;
        }

        return self::convertirCentenas($numero);
    }

    /**
     * Procesa números del 1 al 999
     */
    private static function convertirCentenas(int $numero): string {
        if ($numero === 0) {
            return '';
        }

        if ($numero === 100) {
            return 'CIEN';
        }

        if ($numero > 100) {
            $centena = (int)floor($numero / 100);
            $resto = $numero % 100;

            $textoCentena = ($centena === 1) ? 'CIENTO' : self::$CENTENAS[$centena];

            if ($resto > 0) {
                return $textoCentena . ' ' . self::convertirDecenas($resto);
            }
            return $textoCentena;
        }

        return self::convertirDecenas($numero);
    }

    /**
     * Procesa números del 1 al 99
     */
    private static function convertirDecenas(int $numero): string {
        if ($numero <= 20) {
            return self::$UNIDADES[$numero];
        }

        if ($numero < 30) {
            $unidad = $numero % 10;
            return ($unidad === 0) ? 'VEINTE' : 'VEINTI' . self::$UNIDADES[$unidad];
        }

        $decena = (int)floor($numero / 10);
        $unidad = $numero % 10;

        if ($unidad === 0) {
            return self::$DECENAS[$decena];
        }

        return self::$DECENAS[$decena] . ' Y ' . self::$UNIDADES[$unidad];
    }
}
