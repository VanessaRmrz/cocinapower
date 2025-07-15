<?php
$host = 'dpg-d1rask15pdvs73fa0i4g-a.oregon-postgres.render.com';
$db   = 'cocinapower';
$user = 'vanessa';
$pass = '41ghfUoaV4UTZsp807mz4OZPiJ7BXyOx';
$port = '5432';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar con la base de datos en Render: " . $e->getMessage());
}
?>
