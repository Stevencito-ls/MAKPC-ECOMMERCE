<?php
declare(strict_types=1);

namespace Services;

use Config\Database;
use PDO;
use Exception;

class OrdenService {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Genera código único secuencial anual para la orden: ej. ORD-2026-0001
     */
    private function generarCodigoOrden(): string {
        $anio = date('Y');
        $prefix = "ORD-{$anio}-";

        $stmt = $this->db->prepare("SELECT codigo_orden FROM ordenes_servicio WHERE codigo_orden LIKE :prefix ORDER BY id DESC LIMIT 1");
        $stmt->execute([':prefix' => "{$prefix}%"]);
        $ultimo = $stmt->fetchColumn();

        if ($ultimo) {
            $numero = (int)substr($ultimo, strlen($prefix));
            $siguiente = $numero + 1;
        } else {
            $siguiente = 1;
        }

        return sprintf("%s%04d", $prefix, $siguiente);
    }

    /**
     * Listado general de órdenes con filtros avanzados
     */
    public function getAll(array $filters = []): array {
        $where = [];
        $params = [];

        if (!empty($filters['estado'])) {
            $where[] = "o.estado = :estado";
            $params[':estado'] = $filters['estado'];
        }

        if (!empty($filters['cliente_id'])) {
            $where[] = "o.cliente_id = :cliente_id";
            $params[':cliente_id'] = (int)$filters['cliente_id'];
        }

        if (!empty($filters['tecnico_id'])) {
            $where[] = "o.tecnico_id = :tecnico_id";
            $params[':tecnico_id'] = (int)$filters['tecnico_id'];
        }

        if (!empty($filters['query'])) {
            $where[] = "(o.codigo_orden LIKE :query OR c.nombres_razon_social LIKE :query OR c.numero_documento LIKE :query OR o.marca LIKE :query OR o.modelo LIKE :query OR o.numero_serie LIKE :query)";
            $params[':query'] = "%" . trim($filters['query']) . "%";
        }

        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
        $limit = isset($filters['limit']) ? (int)$filters['limit'] : 50;

        $sql = "SELECT 
                    o.*,
                    c.nombres_razon_social AS cliente_nombre,
                    c.tipo_documento AS cliente_tipo_doc,
                    c.numero_documento AS cliente_documento,
                    c.telefono AS cliente_telefono,
                    u_rec.nombre_completo AS recepcionista_nombre,
                    u_tec.nombre_completo AS tecnico_nombre,
                    r.id AS recibo_id,
                    r.numero_recibo,
                    r.monto_total,
                    r.monto_a_cuenta,
                    r.monto_saldo,
                    r.estado_pago
                FROM ordenes_servicio o
                INNER JOIN clientes c ON o.cliente_id = c.id
                INNER JOIN usuarios u_rec ON o.recepcionista_id = u_rec.id
                LEFT JOIN usuarios u_tec ON o.tecnico_id = u_tec.id
                LEFT JOIN recibos r ON r.orden_servicio_id = o.id AND r.anulado = FALSE
                {$whereClause}
                ORDER BY o.id DESC
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Obtiene detalle completo de una orden
     */
    public function getById(int $id): ?array {
        $sql = "SELECT 
                    o.*,
                    c.nombres_razon_social AS cliente_nombre,
                    c.tipo_documento AS cliente_tipo_doc,
                    c.numero_documento AS cliente_documento,
                    c.telefono AS cliente_telefono,
                    c.direccion AS cliente_direccion,
                    c.email AS cliente_email,
                    u_rec.nombre_completo AS recepcionista_nombre,
                    u_tec.nombre_completo AS tecnico_nombre,
                    r.id AS recibo_id,
                    r.numero_recibo,
                    r.monto_total,
                    r.monto_a_cuenta,
                    r.monto_saldo,
                    r.estado_pago,
                    r.fecha_emision AS recibo_fecha_emision,
                    r.fecha_vencimiento_garantia
                FROM ordenes_servicio o
                INNER JOIN clientes c ON o.cliente_id = c.id
                INNER JOIN usuarios u_rec ON o.recepcionista_id = u_rec.id
                LEFT JOIN usuarios u_tec ON o.tecnico_id = u_tec.id
                LEFT JOIN recibos r ON r.orden_servicio_id = o.id AND r.anulado = FALSE
                WHERE o.id = :id
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Registra el ingreso de un equipo al taller
     */
    public function create(array $data): array {
        if (empty($data['cliente_id'])) {
            throw new Exception("El ID de cliente es requerido.");
        }
        if (empty($data['recepcionista_id'])) {
            throw new Exception("El ID de recepcionista es requerido.");
        }

        // Tipo de equipo: ENUM('LAPTOP','PC_ESCRITORIO','ALL_IN_ONE','IMPRESORA','MONITOR','SERVIDOR','OTRO')
        $tiposPermitidos = ['LAPTOP','PC_ESCRITORIO','ALL_IN_ONE','IMPRESORA','MONITOR','SERVIDOR','OTRO'];
        $tipoEquipo = strtoupper(trim((string)($data['tipo_equipo'] ?? 'LAPTOP')));
        if (!in_array($tipoEquipo, $tiposPermitidos, true)) {
            $tipoEquipo = 'OTRO';
        }

        // Marca: VARCHAR(60) NOT NULL
        $marca = trim((string)($data['marca'] ?? ''));
        if (empty($marca)) {
            throw new Exception("La marca del equipo es obligatoria.");
        }
        if (mb_strlen($marca) > 60) {
            $marca = mb_substr($marca, 0, 60);
        }

        // Modelo: VARCHAR(80) NOT NULL
        $modelo = trim((string)($data['modelo'] ?? ''));
        if (empty($modelo)) {
            throw new Exception("El modelo del equipo es obligatorio.");
        }
        if (mb_strlen($modelo) > 80) {
            $modelo = mb_substr($modelo, 0, 80);
        }

        // Número de Serie: VARCHAR(80) NULL
        $serie = !empty($data['numero_serie']) ? strtoupper(trim((string)$data['numero_serie'])) : null;
        if ($serie !== null && strlen($serie) > 80) {
            $serie = substr($serie, 0, 80);
        }

        // Password: VARCHAR(100) NULL
        $password = !empty($data['password_equipo']) ? trim((string)$data['password_equipo']) : null;
        if ($password !== null && mb_strlen($password) > 100) {
            $password = mb_substr($password, 0, 100);
        }

        // Accesorios: TEXT NULL
        $accesorios = !empty($data['accesorios_dejados']) ? trim((string)$data['accesorios_dejados']) : null;
        if ($accesorios !== null && mb_strlen($accesorios) > 500) {
            $accesorios = mb_substr($accesorios, 0, 500);
        }

        // Motivo de Ingreso: TEXT NOT NULL
        $motivo = trim((string)($data['motivo_ingreso'] ?? ''));
        if (empty($motivo)) {
            throw new Exception("Debe especificar el motivo de ingreso o falla reportada.");
        }
        if (mb_strlen($motivo) > 1000) {
            $motivo = mb_substr($motivo, 0, 1000);
        }

        // Observaciones Estéticas: TEXT NULL
        $observaciones = !empty($data['observaciones_esteticas']) ? trim((string)$data['observaciones_esteticas']) : null;
        if ($observaciones !== null && mb_strlen($observaciones) > 1000) {
            $observaciones = mb_substr($observaciones, 0, 1000);
        }

        // Costo Estimado & Adelanto: DECIMAL(10,2)
        $costoEstimado = !empty($data['costo_estimado']) ? max(0.00, round((float)$data['costo_estimado'], 2)) : 0.00;
        $montoAdelanto = !empty($data['monto_adelanto']) ? max(0.00, round((float)$data['monto_adelanto'], 2)) : 0.00;

        if ($costoEstimado > 0 && $montoAdelanto > $costoEstimado) {
            throw new Exception("El monto de adelanto (S/. {$montoAdelanto}) no puede ser mayor al costo estimado (S/. {$costoEstimado}).");
        }

        // Prioridad: ENUM('BAJA','MEDIA','ALTA','URGENTE')
        $prioridades = ['BAJA','MEDIA','ALTA','URGENTE'];
        $prioridad = strtoupper(trim((string)($data['prioridad'] ?? 'MEDIA')));
        if (!in_array($prioridad, $prioridades, true)) {
            $prioridad = 'MEDIA';
        }

        $codigo = $this->generarCodigoOrden();

        $sql = "INSERT INTO ordenes_servicio (
                    codigo_orden, cliente_id, recepcionista_id, tecnico_id,
                    tipo_equipo, marca, modelo, numero_serie, accesorios_dejados,
                    password_equipo, motivo_ingreso, observaciones_esteticas,
                    costo_estimado, monto_adelanto, estado, prioridad, fecha_ingreso
                ) VALUES (
                    :codigo, :cliente_id, :recepcionista_id, :tecnico_id,
                    :tipo_equipo, :marca, :modelo, :numero_serie, :accesorios_dejados,
                    :password_equipo, :motivo_ingreso, :observaciones_esteticas,
                    :costo_estimado, :monto_adelanto, :estado, :prioridad, NOW()
                )";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':codigo'                => $codigo,
            ':cliente_id'            => (int)$data['cliente_id'],
            ':recepcionista_id'      => (int)$data['recepcionista_id'],
            ':tecnico_id'            => !empty($data['tecnico_id']) ? (int)$data['tecnico_id'] : null,
            ':tipo_equipo'           => $tipoEquipo,
            ':marca'                 => $marca,
            ':modelo'                => $modelo,
            ':numero_serie'          => $serie,
            ':accesorios_dejados'    => $accesorios,
            ':password_equipo'       => $password,
            ':motivo_ingreso'        => $motivo,
            ':observaciones_esteticas' => $observaciones,
            ':costo_estimado'        => $costoEstimado,
            ':monto_adelanto'        => $montoAdelanto,
            ':estado'                => $data['estado'] ?? 'RECEPCIONADO',
            ':prioridad'             => $prioridad
        ]);

        $ordenId = (int)$this->db->lastInsertId();
        return $this->getById($ordenId);
    }

    /**
     * Actualiza el flujo y estado técnico de la orden
     */
    public function updateEstado(int $id, string $nuevoEstado, ?string $diagnostico = null, ?string $solucion = null, ?int $tecnicoId = null): array {
        $orden = $this->getById($id);
        if (!$orden) {
            throw new Exception("Orden de servicio no encontrada.");
        }

        $camposFecha = [];
        if ($nuevoEstado === 'EN_DIAGNOSTICO' && empty($orden['fecha_diagnostico'])) {
            $camposFecha[] = "fecha_diagnostico = NOW()";
        } elseif ($nuevoEstado === 'REPARADO' && empty($orden['fecha_reparacion'])) {
            $camposFecha[] = "fecha_reparacion = NOW()";
        } elseif ($nuevoEstado === 'ENTREGADO' && empty($orden['fecha_entrega'])) {
            $camposFecha[] = "fecha_entrega = NOW()";
        }

        $extraFechaSql = !empty($camposFecha) ? ", " . implode(", ", $camposFecha) : "";

        $sql = "UPDATE ordenes_servicio SET 
                    estado = :estado,
                    diagnostico_tecnico = COALESCE(:diagnostico, diagnostico_tecnico),
                    solucion_tecnica = COALESCE(:solucion, solucion_tecnica),
                    tecnico_id = COALESCE(:tecnico_id, tecnico_id)
                    {$extraFechaSql}
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id'          => $id,
            ':estado'      => $nuevoEstado,
            ':diagnostico' => $diagnostico,
            ':solucion'    => $solucion,
            ':tecnico_id'  => $tecnicoId
        ]);

        return $this->getById($id);
    }
}
