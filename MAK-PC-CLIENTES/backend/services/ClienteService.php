<?php
declare(strict_types=1);

namespace Services;

use Config\Database;
use PDO;
use Exception;

class ClienteService {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Búsqueda ágil de clientes por DNI, RUC, nombre o teléfono
     */
    public function search(string $query): array {
        $cleanQuery = trim($query);
        if (empty($cleanQuery)) {
            return $this->getAll(20);
        }

        $sql = "SELECT id, tipo_documento, numero_documento, nombres_razon_social, telefono, telefono_secundario, email, direccion, created_at
                FROM clientes
                WHERE numero_documento LIKE :exactQuery
                   OR numero_documento LIKE :likeQuery
                   OR nombres_razon_social LIKE :likeQuery
                   OR telefono LIKE :likeQuery
                ORDER BY id DESC
                LIMIT 25";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':exactQuery' => $cleanQuery,
            ':likeQuery'  => "%{$cleanQuery}%"
        ]);

        return $stmt->fetchAll();
    }

    public function getAll(int $limit = 50): array {
        $stmt = $this->db->prepare("SELECT * FROM clientes ORDER BY id DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $client = $stmt->fetch();
        return $client ?: null;
    }

    public function getByDocumento(string $numeroDocumento): ?array {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE numero_documento = :doc LIMIT 1");
        $stmt->execute([':doc' => trim($numeroDocumento)]);
        $client = $stmt->fetch();
        return $client ?: null;
    }

    /**
     * Registra o actualiza cliente de forma rápida en el mostrador
     */
    public function findOrCreate(array $data): array {
        $data = $this->validarDatosCliente($data);
        $numeroDoc = $data['numero_documento'];

        $existente = $this->getByDocumento($numeroDoc);
        if ($existente) {
            // Actualizar datos de contacto si cambiaron
            $this->update((int)$existente['id'], $data);
            return $this->getById((int)$existente['id']);
        }

        $nuevoId = $this->create($data);
        return $this->getById($nuevoId);
    }

    /**
     * Valida y normaliza datos de cliente contra el esquema de base de datos
     */
    public function validarDatosCliente(array $data): array {
        $tipoDoc = strtoupper(trim($data['tipo_documento'] ?? 'DNI'));
        if (!in_array($tipoDoc, ['DNI', 'RUC', 'CE', 'PASAPORTE'], true)) {
            $tipoDoc = 'DNI';
        }

        $numeroDoc = trim((string)($data['numero_documento'] ?? ''));
        if (empty($numeroDoc)) {
            throw new Exception("El número de documento es obligatorio.");
        }

        // Validaciones estrictas por tipo de documento acorde a BD y normas peruanas
        if ($tipoDoc === 'DNI') {
            if (!preg_match('/^[0-9]{8}$/', $numeroDoc)) {
                throw new Exception("El DNI debe contener exactamente 8 dígitos numéricos (sin letras ni espacios).");
            }
        } elseif ($tipoDoc === 'RUC') {
            if (!preg_match('/^(10|15|17|20)[0-9]{9}$/', $numeroDoc)) {
                throw new Exception("El RUC debe contener exactamente 11 dígitos numéricos válidos iniciando con 10, 15, 17 o 20.");
            }
        } elseif ($tipoDoc === 'CE') {
            $numeroDoc = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $numeroDoc));
            if (strlen($numeroDoc) < 9 || strlen($numeroDoc) > 12) {
                throw new Exception("El Carnet de Extranjería debe tener entre 9 y 12 caracteres alfanuméricos.");
            }
        } elseif ($tipoDoc === 'PASAPORTE') {
            $numeroDoc = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $numeroDoc));
            if (strlen($numeroDoc) < 6 || strlen($numeroDoc) > 12) {
                throw new Exception("El Pasaporte debe tener entre 6 y 12 caracteres alfanuméricos.");
            }
        }

        if (strlen($numeroDoc) > 15) {
            throw new Exception("El número de documento no puede exceder 15 caracteres (límite de base de datos).");
        }

        // Nombres / Razón Social: VARCHAR(180) NOT NULL
        $nombres = trim((string)($data['nombres_razon_social'] ?? ''));
        if (empty($nombres)) {
            throw new Exception("El nombre o razón social es obligatorio.");
        }
        if (mb_strlen($nombres) > 180) {
            $nombres = mb_substr($nombres, 0, 180);
        }

        // Teléfono: VARCHAR(20) NOT NULL - Solo dígitos
        $telefonoRaw = trim((string)($data['telefono'] ?? ''));
        $telefono = preg_replace('/\D/', '', $telefonoRaw);
        if (empty($telefono)) {
            throw new Exception("El número de teléfono es obligatorio y debe contener solo números.");
        }
        if (strlen($telefono) > 20) {
            $telefono = substr($telefono, 0, 20);
        }

        // Dirección: VARCHAR(255) NULL
        $direccion = !empty($data['direccion']) ? trim((string)$data['direccion']) : null;
        if ($direccion !== null && mb_strlen($direccion) > 255) {
            $direccion = mb_substr($direccion, 0, 255);
        }

        // Email: VARCHAR(100) NULL
        $email = !empty($data['email']) ? trim((string)$data['email']) : null;
        if ($email !== null) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("El formato del correo electrónico es inválido.");
            }
            if (strlen($email) > 100) {
                $email = substr($email, 0, 100);
            }
        }

        // Teléfono secundario: VARCHAR(20) NULL
        $telefonoSec = !empty($data['telefono_secundario']) ? preg_replace('/\D/', '', (string)$data['telefono_secundario']) : null;
        if ($telefonoSec !== null && strlen($telefonoSec) > 20) {
            $telefonoSec = substr($telefonoSec, 0, 20);
        }

        return [
            'tipo_documento'       => $tipoDoc,
            'numero_documento'     => $numeroDoc,
            'nombres_razon_social' => $nombres,
            'telefono'             => $telefono,
            'telefono_secundario'  => $telefonoSec,
            'email'                => $email,
            'direccion'            => $direccion
        ];
    }

    public function create(array $data): int {
        $validado = $this->validarDatosCliente($data);

        $sql = "INSERT INTO clientes (tipo_documento, numero_documento, nombres_razon_social, telefono, telefono_secundario, email, direccion)
                VALUES (:tipo, :num, :nombres, :tel, :tel_sec, :email, :dir)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':tipo'    => $validado['tipo_documento'],
            ':num'     => $validado['numero_documento'],
            ':nombres' => $validado['nombres_razon_social'],
            ':tel'     => $validado['telefono'],
            ':tel_sec' => $validado['telefono_secundario'],
            ':email'   => $validado['email'],
            ':dir'     => $validado['direccion']
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE clientes SET 
                    tipo_documento = COALESCE(:tipo, tipo_documento),
                    nombres_razon_social = COALESCE(:nombres, nombres_razon_social),
                    telefono = COALESCE(:tel, telefono),
                    telefono_secundario = :tel_sec,
                    email = :email,
                    direccion = :dir
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'      => $id,
            ':tipo'    => $data['tipo_documento'] ?? null,
            ':nombres' => !empty($data['nombres_razon_social']) ? trim($data['nombres_razon_social']) : null,
            ':tel'     => !empty($data['telefono']) ? trim($data['telefono']) : null,
            ':tel_sec' => !empty($data['telefono_secundario']) ? trim($data['telefono_secundario']) : null,
            ':email'   => !empty($data['email']) ? trim($data['email']) : null,
            ':dir'     => !empty($data['direccion']) ? trim($data['direccion']) : null
        ]);
    }
}
