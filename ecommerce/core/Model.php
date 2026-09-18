<?php
/**
 * Modelo Base - CRUD genérico
 */
class Model {
    /** @var PDO */
    protected $db;
    /** @var string */
    protected $table;
    /** @var string */
    protected $primaryKey = 'id';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * @param string|null $orderBy
     * @return array
     */
    public function all($orderBy = null) {
        $sql = "SELECT * FROM {$this->table}";
        if ($orderBy) $sql .= " ORDER BY $orderBy";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * @param mixed $id
     * @return array|false
     */
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * @param string $column
     * @param mixed $value
     * @return array
     */
    public function where($column, $value) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} = ?");
        $stmt->execute([$value]);
        return $stmt->fetchAll();
    }

    /**
     * @param string $column
     * @param mixed $value
     * @return array|false
     */
    public function findWhere($column, $value) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} = ? LIMIT 1");
        $stmt->execute([$value]);
        return $stmt->fetch();
    }

    /**
     * @param array $data
     * @return string|false
     */
    public function create($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_values($data));
        return $this->db->lastInsertId();
    }

    /**
     * @param mixed $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        $set = implode(' = ?, ', array_keys($data)) . ' = ?';
        $sql = "UPDATE {$this->table} SET $set WHERE {$this->primaryKey} = ?";
        $stmt = $this->db->prepare($sql);
        $values = array_values($data);
        $values[] = $id;
        return $stmt->execute($values);
    }

    /**
     * @param mixed $id
     * @return bool
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?");
        return $stmt->execute([$id]);
    }

    /**
     * @param string|null $where
     * @param array $params
     * @return int
     */
    public function count($where = null, $params = []) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        if ($where) $sql .= " WHERE $where";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)($stmt->fetch()['total'] ?? 0);
    }

    /**
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * @param string $sql
     * @param array $params
     * @return array|false
     */
    public function queryOne($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    /**
     * @param string $sql
     * @param array $params
     * @return bool
     */
    public function execute($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * @param string|array $columns
     * @param string $term
     * @return array
     */
    public function search($columns, $term) {
        $conditions = [];
        $params = [];
        foreach ((array)$columns as $col) {
            $conditions[] = "$col LIKE ?";
            $params[] = "%$term%";
        }
        $sql = "SELECT * FROM {$this->table} WHERE " . implode(' OR ', $conditions);
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * @return PDO
     */
    public function getPdo() {
        return $this->db;
    }
}
