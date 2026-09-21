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
        $numeroDoc = trim($data['numero_documento'] ?? '');
        if (empty($numeroDoc)) {
            throw new Exception("El número de documento es obligatorio");
        }

        $existente = $this->getByDocumento($numeroDoc);
        if ($existente) {
            // Actualizar datos de contacto si cambiaron
            $this->update((int)$existente['id'], $data);
            return $this->getById((int)$existente['id']);
        }

        $nuevoId = $this->create($data);
        return $this->getById($nuevoId);
    }

    public function create(array $data): int {
        $tipoDoc = $data['tipo_documento'] ?? 'DNI';
        $numeroDoc = trim($data['numero_documento'] ?? '');
        $nombres = trim($data['nombres_razon_social'] ?? '');
        $telefono = trim($data['telefono'] ?? '');
        $telefonoSec = !empty($data['telefono_secundario']) ? trim($data['telefono_secundario']) : null;
        $email = !empty($data['email']) ? trim($data['email']) : null;
        $direccion = !empty($data['direccion']) ? trim($data['direccion']) : null;

        if (empty($numeroDoc) || empty($nombres) || empty($telefono)) {
            throw new Exception("Documento, nombres y teléfono principal son obligatorios.");
        }

        $sql = "INSERT INTO clientes (tipo_documento, numero_documento, nombres_razon_social, telefono, telefono_secundario, email, direccion)
                VALUES (:tipo, :num, :nombres, :tel, :tel_sec, :email, :dir)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':tipo'    => $tipoDoc,
            ':num'     => $numeroDoc,
            ':nombres' => $nombres,
            ':tel'     => $telefono,
            ':tel_sec' => $telefonoSec,
            ':email'   => $email,
            ':dir'     => $direccion
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
