<?php
/**
 * MAKPC - Auto-instalador de Base de Datos
 * Ejecutar una sola vez: http://localhost/MAKPC/install.php
 */

$host = 'localhost';
$user = 'root';
$pass = '';

$messages = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['auto'])) {
    try {
        // Conectar sin seleccionar DB
        $pdo = new PDO("mysql:host=$host", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]);

        // Leer SQL
        $sqlFile = __DIR__ . '/sql/taller_servicios.sql';
        if (!file_exists($sqlFile)) {
            throw new Exception("Archivo SQL no encontrado: $sqlFile");
        }

        $sql = file_get_contents($sqlFile);

        // Ejecutar cada sentencia
        $pdo->exec($sql);

        $messages[] = ['type' => 'success', 'text' => '✅ Base de datos "taller_servicios" creada exitosamente.'];
        $messages[] = ['type' => 'success', 'text' => '✅ Tablas creadas: usuarios, clientes, equipos, ordenes_servicio, detalle_componentes, categorias_productos, productos, tickets_soporte.'];
        $messages[] = ['type' => 'success', 'text' => '✅ Datos de prueba insertados correctamente.'];
        $messages[] = ['type' => 'success', 'text' => '✅ Vista de auditoría creada.'];
        $messages[] = ['type' => 'info', 'text' => '🔑 Administrador: admin / admin123'];
        $messages[] = ['type' => 'info', 'text' => '🔑 Técnico de Taller: tecnico / tecnico123'];
        $messages[] = ['type' => 'info', 'text' => '🔑 Vendedor Comercial: vendedor / vendedor123'];
        $success = true;

    } catch (PDOException $e) {
        $messages[] = ['type' => 'error', 'text' => '❌ Error de conexión MySQL: ' . $e->getMessage()];
        $messages[] = ['type' => 'warning', 'text' => '⚠️ Asegúrate de que XAMPP MySQL esté ejecutándose.'];
    } catch (Exception $e) {
        $messages[] = ['type' => 'error', 'text' => '❌ Error: ' . $e->getMessage()];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAKPC - Instalador</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --yellow: #FCC827; --blue: #161D45; --celeste: #05A9E9; --white: #FAFAFA; --lavender: #E7E9F7; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--blue); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .installer { background: var(--white); border-radius: 20px; padding: 48px; max-width: 560px; width: 100%; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .logo-area { text-align: center; margin-bottom: 32px; }
        .logo-area img { height: 100px; margin-bottom: 12px; }
        .logo-area h1 { font-size: 1.5rem; font-weight: 800; color: var(--blue); }
        .logo-area p { color: #8E9394; font-size: 0.9rem; margin-top: 4px; }
        .msg { padding: 12px 16px; border-radius: 10px; margin-bottom: 10px; font-size: 0.85rem; font-weight: 500; }
        .msg.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .msg.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .msg.warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .msg.info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .btn-install { display: block; width: 100%; padding: 16px; background: var(--yellow); color: var(--blue); border: none; border-radius: 12px; font-family: inherit; font-size: 1rem; font-weight: 800; cursor: pointer; transition: all 0.3s; margin-top: 20px; }
        .btn-install:hover { background: #e5b620; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(252, 200, 39, 0.4); }
        .btn-go { display: block; width: 100%; padding: 16px; background: var(--celeste); color: var(--white); border: none; border-radius: 12px; font-family: inherit; font-size: 1rem; font-weight: 700; cursor: pointer; text-decoration: none; text-align: center; margin-top: 12px; transition: all 0.3s; }
        .btn-go:hover { background: var(--blue); }
        .prereqs { background: var(--lavender); border-radius: 12px; padding: 20px; margin: 20px 0; }
        .prereqs h3 { font-size: 0.85rem; font-weight: 700; color: var(--blue); margin-bottom: 10px; }
        .prereqs ul { list-style: none; font-size: 0.8rem; color: #555; }
        .prereqs ul li { padding: 4px 0; }
        .prereqs ul li::before { content: '✔ '; color: var(--celeste); font-weight: 700; }
    </style>
</head>
<body>
    <div class="installer">
        <div class="logo-area">
            <img src="assets/img/logo.png" alt="MAKPC Logo">
            <h1>Instalador del Sistema</h1>
            <p>Configuración automática de la base de datos</p>
        </div>

        <?php if (!empty($messages)): ?>
            <?php foreach ($messages as $msg): ?>
                <div class="msg <?= $msg['type'] ?>"><?= $msg['text'] ?></div>
            <?php endforeach; ?>
            <?php if ($success): ?>
                <a href="index.php" class="btn-go">🚀 Ir al Sistema MAKPC</a>
            <?php endif; ?>
        <?php else: ?>
            <div class="prereqs">
                <h3>Requisitos previos</h3>
                <ul>
                    <li>XAMPP instalado y ejecutándose</li>
                    <li>Apache activo</li>
                    <li>MySQL activo (puerto 3306)</li>
                    <li>Usuario root sin contraseña (default XAMPP)</li>
                </ul>
            </div>
            <form method="POST">
                <button type="submit" class="btn-install">⚡ Instalar Base de Datos</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
