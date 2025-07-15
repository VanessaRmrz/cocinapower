<?php 
include 'conexion.php';

if (!isset($_GET['id'])) exit('ID no especificado.');

$id = intval($_GET['id']);

$stmt = $pdo->prepare("DELETE FROM recetas WHERE id = ?");
$stmt->execute([$id]);

header('Location: index.php');
exit;
