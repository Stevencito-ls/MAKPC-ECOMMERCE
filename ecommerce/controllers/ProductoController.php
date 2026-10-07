<?php
/**
 * ProductoController - Gestión Integral de Productos e Inventario
 * Exclusivo para roles: 'admin' y 'vendedor'
 */
class ProductoController extends Controller {

    /** @var Producto */
    private $productoModel;
    /** @var Categoria */
    private $categoriaModel;

    public function __construct() {
        // Solo administradores y vendedores tienen acceso al catálogo comercial
        $this->requireRole(['admin', 'vendedor']);
        $this->productoModel = new Producto();
        $this->categoriaModel = new Categoria();
    }

    /**
     * Listado administrativo de productos
     */
    public function index() {
        $busqueda = trim($this->input('q', ''));
        $categoriaId = $this->input('cat', null);
        $stockFiltro = $this->input('stock', '');

        $productos = $this->productoModel->listarAdmin($busqueda, $categoriaId, $stockFiltro);
        $categorias = $this->categoriaModel->all('nombre ASC');

        // Métricas de inventario para el vendedor/admin
        $totalProductos = $this->productoModel->count();
        $productosActivos = $this->productoModel->count('activo = 1');
        $stockBajo = $this->productoModel->contarPorStock('bajo');
        $agotados = $this->productoModel->contarPorStock('agotado');

        $this->view('productos/index', [
            'title' => 'Gestión de Productos & Catálogo | MAKPC',
            'productos' => $productos,
            'categorias' => $categorias,
            'busqueda' => $busqueda,
            'categoriaSeleccionada' => $categoriaId,
            'stockFiltro' => $stockFiltro,
            'totalProductos' => $totalProductos,
            'productosActivos' => $productosActivos,
            'stockBajo' => $stockBajo,
            'agotados' => $agotados
        ]);
    }

    /**
     * Registrar un nuevo producto en el catálogo
     */
    public function crear() {
        $categorias = $this->categoriaModel->all('nombre ASC');

        if ($this->isPost()) {
            // Verificar CSRF
            if (!verify_csrf($this->input('csrf_token'))) {
                setFlash('danger', 'Token de seguridad expirado. Intente nuevamente.');
                $this->redirect('producto/crear');
            }

            $nombre = trim($this->input('nombre', ''));
            $idCategoria = (int)$this->input('id_categoria', 0);
            $precio = (float)$this->input('precio', 0);
            $precioAnterior = $this->input('precio_anterior') ? (float)$this->input('precio_anterior') : null;
            $stock = (int)$this->input('stock', 0);
            $marca = trim($this->input('marca', ''));
            $descripcion = trim($this->input('descripcion', ''));
            $etiqueta = trim($this->input('etiqueta', '')) ?: null;
            $destacado = $this->input('destacado') ? 1 : 0;
            $activo = $this->input('activo') ? 1 : 0;

            if (empty($nombre) || $idCategoria <= 0 || $precio <= 0) {
                setFlash('danger', 'Nombre, Categoría y Precio válido son campos requeridos.');
                $this->redirect('producto/crear');
            }

            // Generar slug URL amigable
            $slugBase = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $nombre), '-'));
            $slug = $slugBase;
            $i = 1;
            while ($this->productoModel->slugExiste($slug)) {
                $slug = "{$slugBase}-{$i}";
                $i++;
            }

            // Gestión de Imagen (Subida o Preset)
            $nombreImagen = $this->procesarImagenSubida();
            if (!$nombreImagen) {
                $nombreImagen = trim($this->input('imagen_preset', 'prod_1.jpg'));
            }

            $nuevoId = $this->productoModel->create([
                'id_categoria' => $idCategoria,
                'nombre' => $nombre,
                'slug' => $slug,
                'descripcion' => $descripcion,
                'precio' => $precio,
                'precio_anterior' => $precioAnterior,
                'stock' => $stock,
                'marca' => $marca ?: 'MAKPC',
                'imagen' => $nombreImagen,
                'etiqueta' => $etiqueta,
                'destacado' => $destacado,
                'activo' => $activo,
                'calificacion' => 5.0,
                'num_resenas' => 1
            ]);

            setFlash('success', "¡Producto '{$nombre}' creado con éxito en el catálogo!");
            $this->redirect('producto');
        }

        $this->view('productos/crear', [
            'title' => 'Subir Nuevo Producto | Catálogo MAKPC',
            'categorias' => $categorias
        ]);
    }

    /**
     * Modificar datos, precio, stock o imagen de un producto
     */
    public function editar($id = null) {
        $id = (int)$id;
        if ($id <= 0) {
            $this->redirect('producto');
        }

        $producto = $this->productoModel->find($id);
        if (!$producto) {
            setFlash('danger', 'El producto seleccionado no existe.');
            $this->redirect('producto');
        }

        $categorias = $this->categoriaModel->all('nombre ASC');

        if ($this->isPost()) {
            if (!verify_csrf($this->input('csrf_token'))) {
                setFlash('danger', 'Token de seguridad expirado.');
                $this->redirect("producto/editar/{$id}");
            }

            $nombre = trim($this->input('nombre', ''));
            $idCategoria = (int)$this->input('id_categoria', 0);
            $precio = (float)$this->input('precio', 0);
            $precioAnterior = $this->input('precio_anterior') ? (float)$this->input('precio_anterior') : null;
            $stock = (int)$this->input('stock', 0);
            $marca = trim($this->input('marca', ''));
            $descripcion = trim($this->input('descripcion', ''));
            $etiqueta = trim($this->input('etiqueta', '')) ?: null;
            $destacado = $this->input('destacado') ? 1 : 0;
            $activo = $this->input('activo') ? 1 : 0;

            if (empty($nombre) || $idCategoria <= 0 || $precio <= 0) {
                setFlash('danger', 'Nombre, Categoría y Precio válido son requeridos.');
                $this->redirect("producto/editar/{$id}");
            }

            // Slug
            $slug = trim($this->input('slug', ''));
            if (empty($slug)) {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $nombre), '-'));
            }
            if ($this->productoModel->slugExiste($slug, $id)) {
                $slug = $slug . '-' . $id;
            }

            // Nueva imagen si se subió
            $nombreImagen = $this->procesarImagenSubida();
            if (!$nombreImagen) {
                $preset = trim($this->input('imagen_preset', ''));
                $nombreImagen = !empty($preset) ? $preset : $producto['imagen'];
            }

            $this->productoModel->update($id, [
                'id_categoria' => $idCategoria,
                'nombre' => $nombre,
                'slug' => $slug,
                'descripcion' => $descripcion,
                'precio' => $precio,
                'precio_anterior' => $precioAnterior,
                'stock' => $stock,
                'marca' => $marca,
                'imagen' => $nombreImagen,
                'etiqueta' => $etiqueta,
                'destacado' => $destacado,
                'activo' => $activo
            ]);

            setFlash('success', "¡Producto '{$nombre}' actualizado correctamente!");
            $this->redirect('producto');
        }

        $this->view('productos/editar', [
            'title' => "Editar Producto #{$id} | MAKPC",
            'producto' => $producto,
            'categorias' => $categorias
        ]);
    }

    /**
     * Alternar estado Activo / Inactivo rápidamente
     */
    public function toggle($id = null) {
        $id = (int)$id;
        if ($id > 0) {
            $this->productoModel->toggleActivo($id);
            setFlash('info', 'Estado del producto modificado.');
        }
        $this->redirect('producto');
    }

    /**
     * Desactivar o eliminar producto (Solo Admin)
     */
    public function eliminar($id = null) {
        $this->requireRole('admin');
        $id = (int)$id;
        if ($id > 0) {
            $this->productoModel->delete($id);
            setFlash('success', 'Producto eliminado exitosamente.');
        }
        $this->redirect('producto');
    }

    /**
     * Procesador seguro de subida de imágenes
     */
    private function procesarImagenSubida() {
        if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $file = $_FILES['imagen'];
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowedMimes)) {
            return null;
        }

        $ext = match($mime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            default      => 'jpg'
        };

        $nombreArchivo = 'prod_up_' . time() . '_' . rand(100, 999) . '.' . $ext;
        $destino = __DIR__ . '/../assets/img/productos/' . $nombreArchivo;

        if (move_uploaded_file($file['tmp_name'], $destino)) {
            return $nombreArchivo;
        }

        return null;
    }

    /**
     * Exportar plantilla CSV (Excel) para carga masiva
     */
    public function exportarPlantilla() {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=plantilla_productos.csv');
        
        // Agregar BOM para que Excel lea UTF-8 correctamente
        echo "\xEF\xBB\xBF";
        
        $output = fopen('php://output', 'w');
        fputcsv($output, [
            'nombre', 'id_categoria', 'marca', 'descripcion', 
            'precio', 'precio_anterior', 'stock', 'dias_garantia', 
            'imagen_preset', 'etiqueta', 'destacado', 'activo'
        ]);
        
        // Fila de ejemplo
        fputcsv($output, [
            'Laptop Gamer RTX 3060', '1', 'Asus', 'Laptop de alto rendimiento', 
            '3500.00', '4000.00', '10', '365', 
            'prod_1.jpg', 'OFERTA', '1', '1'
        ]);
        
        fclose($output);
        exit;
    }

    /**
     * Importar productos masivamente desde CSV (Plantilla Excel)
     */
    public function importarCSV() {
        if (!$this->isPost()) {
            $this->redirect('producto');
        }

        if (!verify_csrf($this->input('csrf_token'))) {
            setFlash('danger', 'Token de seguridad expirado.');
            $this->redirect('producto');
        }

        if (!isset($_FILES['archivo_csv']) || $_FILES['archivo_csv']['error'] !== UPLOAD_ERR_OK) {
            setFlash('danger', 'No se ha seleccionado un archivo CSV válido.');
            $this->redirect('producto');
        }

        $fileTmp = $_FILES['archivo_csv']['tmp_name'];
        $handle = fopen($fileTmp, 'r');
        if ($handle === false) {
            setFlash('danger', 'No se pudo leer el archivo CSV.');
            $this->redirect('producto');
        }

        // Remover posible BOM
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        $header = fgetcsv($handle);
        if (!$header || count($header) < 10) {
            setFlash('danger', 'El archivo no tiene el formato correcto o faltan columnas.');
            fclose($handle);
            $this->redirect('producto');
        }

        $importados = 0;
        $errores = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 12) continue; // Ignorar filas vacías o incompletas

            $nombre = trim($row[0]);
            $idCategoria = (int)$row[1];
            $precio = (float)$row[4];

            if (empty($nombre) || $idCategoria <= 0 || $precio <= 0) {
                $errores++;
                continue;
            }

            // Slug
            $slugBase = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $nombre), '-'));
            $slug = $slugBase;
            $i = 1;
            while ($this->productoModel->slugExiste($slug)) {
                $slug = "{$slugBase}-{$i}";
                $i++;
            }

            $this->productoModel->create([
                'nombre' => $nombre,
                'id_categoria' => $idCategoria,
                'slug' => $slug,
                'marca' => trim($row[2]) ?: 'MAKPC',
                'descripcion' => trim($row[3]),
                'precio' => $precio,
                'precio_anterior' => !empty($row[5]) ? (float)$row[5] : null,
                'stock' => (int)$row[6],
                'dias_garantia' => (int)$row[7],
                'imagen' => trim($row[8]) ?: 'prod_1.jpg',
                'etiqueta' => trim($row[9]) ?: null,
                'destacado' => (int)$row[10] ? 1 : 0,
                'activo' => (int)$row[11] ? 1 : 0,
                'calificacion' => 5.0,
                'num_resenas' => 1
            ]);
            $importados++;
        }

        fclose($handle);
        setFlash('success', "Importación masiva completada: {$importados} productos importados, {$errores} omitidos.");
        $this->redirect('producto');
    }
}
