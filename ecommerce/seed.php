<?php
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Database.php';

try {
    $pdo = Database::getInstance()->getConnection();
    // 1. Crear Categorías
    $pdo->exec("INSERT IGNORE INTO categorias_productos (id_categoria, nombre, slug, descripcion, activo) VALUES 
    (1, 'Laptops', 'laptops', 'Laptops y Portátiles', 1),
    (2, 'Componentes PC', 'componentes', 'Componentes para armar tu PC', 1),
    (3, 'Periféricos', 'perifericos', 'Teclados, ratones y accesorios', 1)");

    // 2. Crear Productos
    $pdo->exec("INSERT IGNORE INTO productos (id_categoria, nombre, slug, marca, descripcion, precio, precio_anterior, stock, imagen, destacado, activo) VALUES 
    (1, 'Laptop Gamer Asus ROG', 'laptop-gamer-asus-rog', 'Asus', 'Potente laptop para gaming.', 4500.00, 4800.00, 10, 'prod_1.jpg', 1, 1),
    (2, 'Procesador Intel Core i7', 'procesador-intel-core-i7', 'Intel', 'Rendimiento extremo.', 1200.00, 1350.00, 15, 'prod_2.jpg', 1, 1),
    (1, 'Laptop HP Pavilion', 'laptop-hp-pavilion', 'HP', 'Ideal para trabajo de oficina.', 2800.00, 3100.00, 8, 'prod_3.jpg', 1, 1),
    (3, 'Teclado Mecánico RGB', 'teclado-mecanico-rgb', 'Logitech', 'Teclado para gamers con luces RGB.', 250.00, 300.00, 20, 'prod_4.jpg', 1, 1),
    (2, 'Tarjeta de Video RTX 4060', 'tarjeta-video-rtx-4060', 'NVIDIA', 'Gráficos ultra realistas.', 1800.00, 2000.00, 5, 'prod_5.jpg', 1, 1),
    (3, 'Mouse Inalámbrico', 'mouse-inalambrico', 'Genius', 'Comodidad sin cables.', 85.00, 100.00, 30, 'prod_6.jpg', 1, 1),
    (2, 'Memoria RAM 16GB DDR4', 'memoria-ram-16gb-ddr4', 'Corsair', 'Más velocidad para tus juegos.', 350.00, 400.00, 18, 'prod_7.jpg', 1, 1),
    (1, 'Laptop Lenovo ThinkPad', 'laptop-lenovo-thinkpad', 'Lenovo', 'La mejor para desarrolladores.', 3200.00, 3500.00, 6, 'prod_8.jpg', 1, 1)");

    echo "<h1 style='color:green;'>¡Base de datos sembrada con éxito!</h1>";
    echo "<p>Vuelve al E-commerce y recarga la página. Ya podrás ver los productos y las imágenes.</p>";
    echo "<a href='" . url('') . "'>Volver al E-commerce</a>";

} catch (Exception $e) {
    echo "<h1 style='color:red;'>Error al sembrar la base de datos:</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}
