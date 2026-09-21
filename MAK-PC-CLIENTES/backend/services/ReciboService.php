<?php
declare(strict_types=1);

namespace Services;

use Config\Database;
use Utils\NumeroALetrasHelper;
use Utils\GarantiaHelper;
use PDO;
use Exception;
use DateTime;

class ReciboService {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Obtiene el siguiente correlativo secuencial con bloqueo atómico
     */
    public function getSiguienteCorrelativo(): array {
        $stmt = $this->db->query("SELECT ultimo_correlativo_recibo, dias_garantia_defecto, clausula_garantia FROM configuracion_empresa WHERE id = 1 LIMIT 1");
        $config = $stmt->fetch();

        $ultimo = $config ? (int)$config['ultimo_correlativo_recibo'] : 400;
        $siguiente = $ultimo + 1;
        $numeroFormateado = sprintf("%06d", $siguiente);
        $diasGarantia = $config ? (int)$config['dias_garantia_defecto'] : GarantiaHelper::DIAS_GARANTIA_DEFECTO;

        return [
            'correlativo_int'      => $siguiente,
            'numero_recibo'        => $numeroFormateado,
            'dias_garantia'        => $diasGarantia,
            'fecha_vencimiento_sugerida' => GarantiaHelper::calcularFechaVencimiento('now', $diasGarantia),
            'clausula_garantia'    => !empty($config['clausula_garantia']) ? $config['clausula_garantia'] : GarantiaHelper::getClausulaLegal($diasGarantia)
        ];
    }

    /**
     * Emite un nuevo recibo de servicio con correlativo estricto de 6 dígitos
     */
    public function emitirRecibo(array $data): array {
        $ordenId = (int)($data['orden_servicio_id'] ?? 0);
        $usuarioEmisorId = (int)($data['usuario_emisor_id'] ?? 0);
        $concepto = trim($data['concepto'] ?? '');
        $montoTotal = (float)($data['monto_total'] ?? 0.00);
        $montoACuenta = (float)($data['monto_a_cuenta'] ?? 0.00);
        $metodoPago = $data['metodo_pago'] ?? 'EFECTIVO';
        $diasGarantia = isset($data['dias_garantia']) ? (int)$data['dias_garantia'] : GarantiaHelper::DIAS_GARANTIA_DEFECTO;

        if ($ordenId <= 0) {
            throw new Exception("El ID de la orden de servicio es obligatorio.");
        }
        if ($usuarioEmisorId <= 0) {
            throw new Exception("El usuario emisor es obligatorio.");
        }
        if (empty($concepto)) {
            throw new Exception("El concepto del recibo es obligatorio.");
        }
        if ($montoTotal <= 0) {
            throw new Exception("El monto total debe ser mayor a 0.00 soles.");
        }
        if ($montoACuenta < 0 || $montoACuenta > $montoTotal) {
            throw new Exception("El monto a cuenta no puede ser menor a 0 ni mayor al total.");
        }

        // FASE 3: Generación automática de monto en letras si no viene especificado
        $montoLetras = !empty(trim($data['monto_letras'] ?? ''))
            ? trim($data['monto_letras'])
            : NumeroALetrasHelper::convertir($montoTotal);

        // Obtener datos del cliente a través de la orden
        $stmtOrd = $this->db->prepare("SELECT cliente_id, estado FROM ordenes_servicio WHERE id = :id LIMIT 1");
        $stmtOrd->execute([':id' => $ordenId]);
        $orden = $stmtOrd->fetch();
        if (!$orden) {
            throw new Exception("La orden de servicio especificada no existe.");
        }
        $clienteId = (int)$orden['cliente_id'];

        // Calcular estado de pago
        $saldo = round($montoTotal - $montoACuenta, 2);
        if ($saldo <= 0.00) {
            $estadoPago = 'CANCELADO_TOTAL';
        } elseif ($montoACuenta > 0) {
            $estadoPago = 'PAGO_PARCIAL';
        } else {
            $estadoPago = 'PENDIENTE';
        }

        // FASE 3: Cálculo automático de fecha de vencimiento de garantía (90 días calendario)
        $fechaVencGarantia = !empty($data['fecha_vencimiento_garantia'])
            ? $data['fecha_vencimiento_garantia']
            : GarantiaHelper::calcularFechaVencimiento('now', $diasGarantia);

        // Iniciar Transacción para garantizar correlativo único
        $this->db->beginTransaction();
        try {
            // Bloqueo y obtención del correlativo
            $stmtLock = $this->db->query("SELECT ultimo_correlativo_recibo, clausula_garantia FROM configuracion_empresa WHERE id = 1 FOR UPDATE");
            $config = $stmtLock->fetch();
            $ultimoCorrelativo = (int)($config['ultimo_correlativo_recibo'] ?? 400);
            $nuevoCorrelativo = $ultimoCorrelativo + 1;
            $numeroRecibo = sprintf("%06d", $nuevoCorrelativo);
            $clausulaLegal = !empty($data['clausula_legal']) ? $data['clausula_legal'] : ($config['clausula_garantia'] ?? GarantiaHelper::getClausulaLegal($diasGarantia));

            // Insertar Recibo
            $sqlInsert = "INSERT INTO recibos (
                            numero_recibo, numero_correlativo_int, orden_servicio_id, cliente_id,
                            usuario_emisor_id, concepto, monto_total, monto_letras, monto_a_cuenta,
                            metodo_pago, estado_pago, fecha_emision, dias_garantia, fecha_vencimiento_garantia,
                            clausula_legal
                        ) VALUES (
                            :numero_recibo, :correlativo_int, :orden_id, :cliente_id,
                            :usuario_id, :concepto, :monto_total, :monto_letras, :monto_a_cuenta,
                            :metodo_pago, :estado_pago, NOW(), :dias_garantia, :fecha_venc,
                            :clausula
                        )";

            $stmt = $this->db->prepare($sqlInsert);
            $stmt->execute([
                ':numero_recibo'   => $numeroRecibo,
                ':correlativo_int' => $nuevoCorrelativo,
                ':orden_id'        => $ordenId,
                ':cliente_id'      => $clienteId,
                ':usuario_id'      => $usuarioEmisorId,
                ':concepto'        => $concepto,
                ':monto_total'     => $montoTotal,
                ':monto_letras'    => $montoLetras,
                ':monto_a_cuenta'  => $montoACuenta,
                ':metodo_pago'     => $metodoPago,
                ':estado_pago'     => $estadoPago,
                ':dias_garantia'   => $diasGarantia,
                ':fecha_venc'      => $fechaVencGarantia,
                ':clausula'        => $clausulaLegal
            ]);

            $reciboId = (int)$this->db->lastInsertId();

            // Actualizar último correlativo en la empresa
            $this->db->exec("UPDATE configuracion_empresa SET ultimo_correlativo_recibo = {$nuevoCorrelativo} WHERE id = 1");

            // Si el pago es total o si se solicita, marcar la orden como ENTREGADO
            if (!empty($data['marcar_entregado']) && $data['marcar_entregado'] === true) {
                $this->db->exec("UPDATE ordenes_servicio SET estado = 'ENTREGADO', fecha_entrega = NOW() WHERE id = {$ordenId}");
            }

            $this->db->commit();

            return $this->getById($reciboId);
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Obtiene el recibo completo listo para visualización e impresión A5
     */
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM v_recibos_completos WHERE recibo_id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $recibo = $stmt->fetch();
        return $recibo ?: null;
    }

    public function getByOrdenId(int $ordenId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM v_recibos_completos WHERE orden_id = :orden_id AND anulado = FALSE ORDER BY recibo_id DESC LIMIT 1");
        $stmt->execute([':orden_id' => $ordenId]);
        $recibo = $stmt->fetch();
        return $recibo ?: null;
    }

    /**
     * Liquidación de saldo pendiente (cuando el cliente abona el monto restante al recoger el equipo)
     */
    public function liquidarSaldo(int $id, float $montoAbonado, string $metodoPago = 'EFECTIVO', bool $marcarOrdenEntregada = true): array {
        $recibo = $this->getById($id);
        if (!$recibo) {
            throw new Exception("Recibo de servicio no encontrado.");
        }

        if ($recibo['anulado']) {
            throw new Exception("No se puede liquidar un recibo anulado.");
        }

        $saldoActual = (float)$recibo['monto_saldo'];
        if ($saldoActual <= 0.00) {
            throw new Exception("Este recibo ya se encuentra totalmente cancelado (Saldo: S/. 0.00).");
        }

        if ($montoAbonado <= 0) {
            throw new Exception("El monto abonado debe ser mayor a 0.");
        }

        $nuevoACuenta = (float)$recibo['monto_a_cuenta'] + $montoAbonado;
        if ($nuevoACuenta > (float)$recibo['monto_total']) {
            throw new Exception("El monto abonado supera el saldo pendiente (Saldo actual: S/. " . number_format($saldoActual, 2) . ").");
        }

        $nuevoSaldo = (float)$recibo['monto_total'] - $nuevoACuenta;
        $nuevoEstadoPago = ($nuevoSaldo <= 0.00) ? 'CANCELADO_TOTAL' : 'PAGO_PARCIAL';

        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("UPDATE recibos SET monto_a_cuenta = :a_cuenta, estado_pago = :estado, metodo_pago = :metodo WHERE id = :id");
            $stmt->execute([
                ':a_cuenta' => $nuevoACuenta,
                ':estado'   => $nuevoEstadoPago,
                ':metodo'   => $metodoPago,
                ':id'       => $id
            ]);

            if ($marcarOrdenEntregada && $nuevoEstadoPago === 'CANCELADO_TOTAL') {
                $ordenId = (int)$recibo['orden_id'];
                $this->db->exec("UPDATE ordenes_servicio SET estado = 'ENTREGADO', fecha_entrega = NOW() WHERE id = {$ordenId}");
            }

            $this->db->commit();
            return $this->getById($id);
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Anulación de comprobante
     */
    public function anular(int $id, string $motivo): bool {
        if (empty(trim($motivo))) {
            throw new Exception("Debe especificar el motivo de anulación.");
        }

        $stmt = $this->db->prepare("UPDATE recibos SET anulado = TRUE, motivo_anulacion = :motivo WHERE id = :id");
        return $stmt->execute([
            ':motivo' => trim($motivo),
            ':id'     => $id
        ]);
    }
}
