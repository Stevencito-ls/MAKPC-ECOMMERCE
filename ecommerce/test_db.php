<?php
$pdo = new PDO('mysql:host=localhost;dbname=makpc_enterprises_db;charset=utf8mb4', 'root', '');
print_r($pdo->query('SELECT COUNT(*) FROM categorias')->fetch());
