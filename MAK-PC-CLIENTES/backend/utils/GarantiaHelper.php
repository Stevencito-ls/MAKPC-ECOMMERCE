<?php
declare(strict_types=1);

namespace Utils;

use DateTime;
use DateTimeInterface;
use Exception;
use InvalidArgumentException;

/**
 * Helper para gestión y cálculo de garantías legales de servicio técnico
 * Conforme al Código de Protección y Defensa del Consumidor (Ley Nº 29571)
 */
class GarantiaHelper {
    public const DIAS_GARANTIA_DEFECTO = 90;

    /**
     * Calcula la fecha exacta de vencimiento sumando los días calendario especificados (90 días por defecto)
     * 
     * @param string|DateTimeInterface $fechaInicio Fecha de entrega o emisión (ej: '2026-09-07' o DateTime)
     * @param int $dias Cantidad de días calendario (por defecto 90)
     * @return string Fecha calculada en formato 'Y-m-d'
     */
    public static function calcularFechaVencimiento(string|DateTimeInterface $fechaInicio = 'now', int $dias = self::DIAS_GARANTIA_DEFECTO): string {
        if ($dias < 0) {
            throw new InvalidArgumentException("Los días de garantía no pueden ser negativos.");
        }

        if ($fechaInicio instanceof DateTimeInterface) {
            $fecha = DateTime::createFromInterface($fechaInicio);
        } else {
            try {
                $fecha = new DateTime($fechaInicio);
            } catch (Exception $e) {
                throw new InvalidArgumentException("Formato de fecha de inicio inválido: " . $fechaInicio);
            }
        }

        $fecha->modify("+{$dias} days");
        return $fecha->format('Y-m-d');
    }

    /**
     * Evalúa el estado de vigencia de una garantía a partir de su fecha de vencimiento
     * 
     * @param string $fechaVencimiento Fecha de vencimiento (ej: '2026-12-06')
     * @param string $fechaConsulta Fecha contra la que se evalúa (por defecto hoy)
     * @return array Resumen del estado de garantía con días restantes y mensaje amigable
     */
    public static function validarEstadoGarantia(string $fechaVencimiento, string $fechaConsulta = 'now'): array {
        try {
            $vencimiento = new DateTime($fechaVencimiento);
            $vencimiento->setTime(23, 59, 59); // Fin del día de vencimiento

            $consulta = new DateTime($fechaConsulta);
            $consulta->setTime(0, 0, 0); // Inicio del día de consulta
        } catch (Exception $e) {
            throw new InvalidArgumentException("Fecha proporcionada inválida: " . $e->getMessage());
        }

        $diff = $consulta->diff($vencimiento);
        $diasRestantes = (int)$diff->format('%r%a');

        $activa = ($diasRestantes >= 0);

        if (!$activa) {
            $estado = 'CADUCADA';
            $diasVencidos = abs($diasRestantes);
            $mensaje = "Garantía vencida hace {$diasVencidos} día(s) (Caducó el " . $vencimiento->format('d/m/Y') . ").";
        } elseif ($diasRestantes <= 7) {
            $estado = 'POR_VENCER';
            $mensaje = "Garantía próxima a caducar: le restan {$diasRestantes} día(s) (Vence el " . $vencimiento->format('d/m/Y') . ").";
        } else {
            $estado = 'VIGENTE';
            $mensaje = "Garantía activa: {$diasRestantes} días restantes de cobertura (Vence el " . $vencimiento->format('d/m/Y') . ").";
        }

        return [
            'activa'                       => $activa,
            'estado'                       => $estado,
            'dias_restantes'               => $diasRestantes,
            'fecha_vencimiento_raw'        => $vencimiento->format('Y-m-d'),
            'fecha_vencimiento_formateada' => $vencimiento->format('d/m/Y'),
            'mensaje'                      => $mensaje
        ];
    }

    /**
     * Retorna el texto legal formal estandarizado para el pie de comprobante A5
     * 
     * @param int $dias Días calendario otorgados
     * @return string Cláusula de protección legal
     */
    public static function getClausulaLegal(int $dias = self::DIAS_GARANTIA_DEFECTO): string {
        return sprintf(
            "La presente garantía cubre exclusivamente el servicio técnico realizado y los repuestos suministrados por un periodo de %d días calendario a partir de la fecha de entrega, conforme al Código de Protección y Defensa del Consumidor (Ley Nº 29571). No cubre daños por fluctuaciones eléctricas, derrame de líquidos, manipulación no autorizada o golpes posteriores.",
            $dias
        );
    }
}
