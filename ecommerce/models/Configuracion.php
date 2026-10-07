<?php
class Configuracion extends Model {
    protected $table = 'configuracion_empresa';
    protected $primaryKey = 'id';

    public function getConfig() {
        $sql = "SELECT * FROM {$this->table} LIMIT 1";
        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }
}
