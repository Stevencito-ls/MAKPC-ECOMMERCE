<?php
class Producto extends Model {
    protected $table = 'productos';
    protected $primaryKey = 'id_producto';

    public function listarConCategoria($filtros = []) {
        $sql = "SELECT p.*, cp.nombre as categoria_nombre, cp.slug as categoria_slug, cp.icono as categoria_icono
                FROM productos p
                INNER JOIN categorias_productos cp ON p.id_categoria = cp.id_categoria
                WHERE p.activo = 1";
        $params = [];

        if (!empty($filtros['categoria'])) {
            $sql .= " AND cp.slug = ?";
            $params[] = $filtros['categoria'];
        }
        if (!empty($filtros['marca'])) {
            $sql .= " AND p.marca = ?";
            $params[] = $filtros['marca'];
        }
        if (!empty($filtros['precio_min'])) {
            $sql .= " AND p.precio >= ?";
            $params[] = $filtros['precio_min'];
        }
        if (!empty($filtros['precio_max'])) {
            $sql .= " AND p.precio <= ?";
            $params[] = $filtros['precio_max'];
        }
        if (!empty($filtros['busqueda'])) {
            $sql .= " AND (p.nombre LIKE ? OR p.marca LIKE ? OR p.descripcion LIKE ?)";
            $params[] = "%{$filtros['busqueda']}%";
            $params[] = "%{$filtros['busqueda']}%";
            $params[] = "%{$filtros['busqueda']}%";
        }
        if (!empty($filtros['disponibilidad'])) {
            if ($filtros['disponibilidad'] === 'stock') {
                $sql .= " AND p.stock > 0";
            } elseif ($filtros['disponibilidad'] === 'oferta') {
                $sql .= " AND p.precio_anterior IS NOT NULL AND p.precio_anterior > p.precio";
            }
        }

        $orden = $filtros['orden'] ?? 'relevancia';
        switch ($orden) {
            case 'precio_asc':  $sql .= " ORDER BY p.precio ASC"; break;
            case 'precio_desc': $sql .= " ORDER BY p.precio DESC"; break;
            case 'vendidos':    $sql .= " ORDER BY p.veces_vendido DESC"; break;
            case 'nuevos':      $sql .= " ORDER BY p.creado_en DESC"; break;
            default:            $sql .= " ORDER BY p.destacado DESC, p.veces_vendido DESC"; break;
        }

        return $this->query($sql, $params);
    }

    public function destacados($limit = 8) {
        $limit = (int)$limit;
        return $this->query(
            "SELECT p.*, cp.nombre as categoria_nombre FROM productos p
             INNER JOIN categorias_productos cp ON p.id_categoria = cp.id_categoria
             WHERE p.activo = 1 AND p.destacado = 1 ORDER BY p.veces_vendido DESC LIMIT $limit"
        );
    }

    public function marcasDisponibles() {
        return $this->query("SELECT DISTINCT marca, COUNT(*) as total FROM productos WHERE activo = 1 GROUP BY marca ORDER BY total DESC");
    }

    /**
     * @param string $slug
     * @return array
     */
    public function porSlug($slug) {
        return $this->query(
            "SELECT p.*, cp.nombre as categoria_nombre FROM productos p
             INNER JOIN categorias_productos cp ON p.id_categoria = cp.id_categoria
             WHERE p.slug = ? AND p.activo = 1", [$slug]
        );
    }

    /**
     * Listado administrativo para Admin y Vendedor con filtros de búsqueda, categoría y stock
     */
    public function listarAdmin($busqueda = '', $idCategoria = null, $stockFiltro = '') {
        $sql = "SELECT p.*, cp.nombre as categoria_nombre 
                FROM productos p
                LEFT JOIN categorias_productos cp ON p.id_categoria = cp.id_categoria
                WHERE 1=1";
        $params = [];

        if (!empty($busqueda)) {
            $sql .= " AND (p.nombre LIKE ? OR p.marca LIKE ? OR p.slug LIKE ?)";
            $params[] = "%{$busqueda}%";
            $params[] = "%{$busqueda}%";
            $params[] = "%{$busqueda}%";
        }

        if (!empty($idCategoria)) {
            $sql .= " AND p.id_categoria = ?";
            $params[] = (int)$idCategoria;
        }

        if ($stockFiltro === 'agotado') {
            $sql .= " AND p.stock <= 0";
        } elseif ($stockFiltro === 'bajo') {
            $sql .= " AND p.stock > 0 AND p.stock <= 5";
        } elseif ($stockFiltro === 'disponible') {
            $sql .= " AND p.stock > 5";
        }

        $sql .= " ORDER BY p.id_producto DESC";

        return $this->query($sql, $params);
    }

    /**
     * Verificar si un slug ya existe para otro producto
     */
    public function slugExiste($slug, $idExcluir = null) {
        $sql = "SELECT COUNT(*) as total FROM productos WHERE slug = ?";
        $params = [$slug];
        if ($idExcluir) {
            $sql .= " AND id_producto != ?";
            $params[] = (int)$idExcluir;
        }
        $res = $this->query($sql, $params);
        return (!empty($res) && (int)$res[0]['total'] > 0);
    }

    /**
     * Alternar estado activo / inactivo
     */
    public function toggleActivo($id) {
        $id = (int)$id;
        $sql = "UPDATE productos SET activo = IF(activo = 1, 0, 1) WHERE id_producto = ?";
        return $this->execute($sql, [$id]);
    }

    /**
     * Contar productos por estado de stock
     */
    public function contarPorStock($estado = 'bajo') {
        if ($estado === 'agotado') {
            $res = $this->query("SELECT COUNT(*) as total FROM productos WHERE stock <= 0 AND activo = 1");
        } else {
            // Bajo stock (1 a 5 unidades)
            $res = $this->query("SELECT COUNT(*) as total FROM productos WHERE stock > 0 AND stock <= 5 AND activo = 1");
        }
        return (int)($res[0]['total'] ?? 0);
    }
}
