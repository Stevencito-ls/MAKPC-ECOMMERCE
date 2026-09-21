<?php
declare(strict_types=1);

require_once __DIR__ . '/../backend/utils/NumeroALetrasHelper.php';
require_once __DIR__ . '/../backend/utils/GarantiaHelper.php';

use Utils\NumeroALetrasHelper;
use Utils\GarantiaHelper;

echo "=========================================================\n";
echo " PRUEBAS UNITARIAS - FASE 3: LÓGICA AUXILIAR MAK-PC\n";
echo "=========================================================\n\n";

// 1. PRUEBAS DEL CONVERSOR DE NÚMEROS A LETRAS
echo "[1] TEST: Conversor de Números a Letras (Moneda Peruana)\n";
echo "---------------------------------------------------------\n";

$casosPrueba = [
    '1.00'       => "SON UN CON 00/100 SOLES",
    '15.00'      => "SON QUINCE CON 00/100 SOLES",
    '25.50'      => "SON VEINTICINCO CON 50/100 SOLES",
    '100.00'     => "SON CIEN CON 00/100 SOLES",
    '120.00'     => "SON CIENTO VEINTE CON 00/100 SOLES",
    '150.50'     => "SON CIENTO CINCUENTA CON 50/100 SOLES",
    '485.75'     => "SON CUATROCIENTOS OCHENTA Y CINCO CON 75/100 SOLES",
    '1000.00'    => "SON MIL CON 00/100 SOLES",
    '1520.20'    => "SON MIL QUINIENTOS VEINTE CON 20/100 SOLES",
    '10500.00'   => "SON DIEZ MIL QUINIENTOS CON 00/100 SOLES",
    '1000000.00' => "SON UN MILLÓN CON 00/100 SOLES"
];

$errores = 0;
foreach ($casosPrueba as $monto => $esperado) {
    $resultado = NumeroALetrasHelper::convertir($monto);
    $ok = ($resultado === $esperado);
    printf("Monto: %10.2f => %s\n", $monto, $resultado);
    if (!$ok) {
        echo "  [ERROR] Esperado: {$esperado}\n";
        $errores++;
    }
}

if ($errores === 0) {
    echo "\n>>> [OK] Todos los casos de conversión de moneda pasaron al 100%.\n\n";
} else {
    echo "\n>>> [FALLO] Se detectaron {$errores} discrepancias.\n\n";
}

// 2. PRUEBAS DEL HELPER DE GARANTÍA LEGAL (LEY 29571)
echo "[2] TEST: Helper de Garantía Legal (90 días calendario)\n";
echo "---------------------------------------------------------\n";

$fechaInicio = '2026-09-07';
$fechaVencimiento = GarantiaHelper::calcularFechaVencimiento($fechaInicio, 90);
echo "Fecha de Entrega: {$fechaInicio}\n";
echo "Fecha Vencimiento (+90 días): {$fechaVencimiento}\n";

// Validación de estado de garantía
$test1 = GarantiaHelper::validarEstadoGarantia($fechaVencimiento, '2026-09-10'); // Recién iniciada
echo "Evaluación (2026-09-10): " . $test1['mensaje'] . " [Estado: {$test1['estado']}]\n";

$test2 = GarantiaHelper::validarEstadoGarantia($fechaVencimiento, '2026-12-04'); // Por vencer (2 días)
echo "Evaluación (2026-12-04): " . $test2['mensaje'] . " [Estado: {$test2['estado']}]\n";

$test3 = GarantiaHelper::validarEstadoGarantia($fechaVencimiento, '2026-12-10'); // Vencida
echo "Evaluación (2026-12-10): " . $test3['mensaje'] . " [Estado: {$test3['estado']}]\n";

echo "\nCláusula Legal Generada:\n";
echo GarantiaHelper::getClausulaLegal(90) . "\n\n";
echo "=========================================================\n";
