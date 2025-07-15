<?php
include('conexion.php');

// Obtener todas las recetas ordenadas por ID
$stmt = $pdo->query("SELECT * FROM recetas ORDER BY id");
$recetas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<ul>
<?php foreach ($recetas as $r): ?>
    <li>
        <strong><?= htmlspecialchars($r['titulo']) ?></strong> - <?= htmlspecialchars($r['autor']) ?> (<?= htmlspecialchars($r['tipo']) ?>)
        <a href="editar-top.php?id=<?= $r['id'] ?>">Editar</a> |
        <a href="eliminar.php?id=<?= $r['id'] ?>" onclick="return confirm('¿Eliminar esta receta?');">Eliminar</a>
    </li>
<?php endforeach; ?>
</ul>
<a href="agregartop.php">Agregar nueva receta</a>
