<?php
include 'conexion.php';

if (!isset($_GET['id'])) {
    exit('ID no especificado.');
}

$id = intval($_GET['id']);

$stmt = $pdo->prepare("SELECT * FROM recetas WHERE id = ? AND tipo = 'recomendacion'");
$stmt->execute([$id]);
$c = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$c) {
    exit('Receta no encontrada o no es una recomendación.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $descripcion = $_POST['descripcion'];
    $enlace = $_POST['enlace_youtube'];
    $recomendacion_text = $_POST['recomendacion_text'];
    $enviado_por = trim($_POST['enviado_por']);
    if ($enviado_por === '') {
        $enviado_por = 'Anónimo';
    }

    if (preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $enlace, $match)) {
        $video_id = $match[1];
    } else {
        $video_id = '';
    }

    $imagen = $video_id ? "https://img.youtube.com/vi/$video_id/hqdefault.jpg" : '';
    $orden = $c['orden_mes'];
    $fecha = $c['fecha_recomendacion'];

    $stmtUpdate = $pdo->prepare("UPDATE recetas SET
        titulo = ?, autor = ?, descripcion = ?, enlace_youtube = ?, imagen_url = ?, enviado_por = ?, recomendacion_text = ?
        WHERE id = ? AND tipo = 'recomendacion'");
    $stmtUpdate->execute([
        $titulo,
        $autor,
        $descripcion,
        $enlace,
        $imagen,
        $enviado_por,
        $recomendacion_text,
        $id
    ]);

    header('Location: detalle.php?id=' . $id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <!-- Aquí agregamos la meta viewport para móviles -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>Editar Recomendación</title>
  <link rel="stylesheet" href="style.css">
  <link rel="icon" href="icon.png"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: 'Georgia', serif;
      background: url('fondo.jpeg') no-repeat center center fixed;
      background-size: cover;
      color: #eee;
      min-height: 100vh;
    }

    .header {
      position: fixed;
      top: 0;
      width: 100%;
      background: rgba(0,0,0,0.85);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 2rem;
      z-index: 1000;
    }

    .volver-fijo {
      position: fixed;
      top: 15px;
      left: 15rem;
      background: #ffd700;
      color: #000;
      padding: 6px 14px;
      border-radius: 6px;
      font-weight: bold;
      text-decoration: none;
      box-shadow: 0 0 8px #ffd700aa;
      z-index: 1000;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      transition: left 0.3s ease;
    }

    .agregar-recomendacion-container {
      margin: 100px auto 3rem;
      max-width: 700px;
      background: rgba(20, 20, 30, 0.92);
      padding: 2.5rem 2rem;
      border-radius: 14px;
      box-shadow: 0 0 30px #ff4a4aaa;
      box-sizing: border-box;
    }

    .agregar-recomendacion-container h2 {
      text-align: center;
      color: #ffd363;
      font-size: 2rem;
      margin-bottom: 2rem;
      text-shadow: 0 0 5px #ffd363aa;
    }

    .agregar-recomendacion-container form {
      display: flex;
      flex-direction: column;
      gap: 1.2rem;
    }

    .agregar-recomendacion-container input,
    .agregar-recomendacion-container textarea {
      background: rgba(255,255,255,0.08);
      color: #fff;
      border: 1px solid #ffd363aa;
      border-radius: 8px;
      padding: 12px;
      font-family: 'Georgia', serif;
      font-size: 1rem;
      width: 100%;
      box-sizing: border-box;
      resize: vertical;
    }

    .agregar-recomendacion-container input::placeholder,
    .agregar-recomendacion-container textarea::placeholder {
      color: #ccc;
    }

    .agregar-recomendacion-container button {
      background: #ff5c5c;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.3s ease;
      margin: 0 auto;
      box-shadow: 0 0 10px #ff4a4aaa;
      width: auto;
      min-width: 150px;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      justify-content: center;
    }

    .agregar-recomendacion-container button:hover {
      background: #cc0000;
    }

    .label-text {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
      color: #ffd700;
    }

    .campo-pequeño {
      width: 50%;
      max-width: 500px;
      box-sizing: border-box;
    }

    footer {
      text-align: center;
      padding: 2rem 1rem;
      font-size: 0.9rem;
      background: rgba(0,0,0,0.6);
      color: #ccc;
    }

    /* Media Queries para responsividad */
    @media (max-width: 768px) {
      .agregar-recomendacion-container {
        margin: 110px 1rem 2rem;
        max-width: 100%;
        padding: 1.5rem;
      }

      .volver-fijo {
        left: 1rem;
        font-size: 0.9rem;
      }

      .campo-pequeño {
        width: 100%;
      }
    }

    @media (max-width: 1200px) {
      .agregar-recomendacion-container {
        margin: 130px auto 2rem;
        padding: 1.5rem;
      }
      .volver-fijo {
        left: 1rem;
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body>

  <div class="header detalle-header">
    <a href="index.php"><img src="logo.png" class="logo" alt="Logo CocinaPower"/></a>
    <a href="detalle.php?id=<?= $id ?>" class="volver-fijo"><i class="fas fa-arrow-left"></i> Volver</a>
  </div>

  <div class="agregar-recomendacion-container">
    <h2>Editar Recomendación del Mes</h2>

    <form method="post">
      <label>
        <span class="label-text">Título:</span>
        <input name="titulo" required value="<?= htmlspecialchars($c['titulo']) ?>">
      </label>

      <label>
        <span class="label-text">Autor:</span>
        <input name="autor" value="<?= htmlspecialchars($c['autor']) ?>">
      </label>

      <label>
        <span class="label-text">Descripción:</span>
        <textarea name="descripcion" required><?= htmlspecialchars($c['descripcion']) ?></textarea>
      </label>

      <label>
        <span class="label-text">URL de YouTube:</span>
        <input name="enlace_youtube" required value="<?= htmlspecialchars($c['enlace_youtube']) ?>">
      </label>

      <label>
        <span class="label-text">Texto personalizado:</span>
        <textarea name="recomendacion_text"><?= htmlspecialchars($c['recomendacion_text']) ?></textarea>
      </label>

      <label class="campo-pequeño">
        <span class="label-text">Recomendado por:</span>
        <input name="enviado_por" placeholder="Nombre de quien recomienda (opcional)" value="<?= htmlspecialchars($c['enviado_por']) ?>">
      </label>

      <button type="submit"><i class="fas fa-save"></i> Guardar Cambios</button>
    </form>
  </div>

  <footer>
    <p>Curso: Conceptualización de servicios en la nube</p>
    <p>Nombre: Vanessa Itzarahí Gómez Ramírez</p>
    <p>&copy; <?= date('Y') ?> Todos los derechos reservados</p>
  </footer>

</body>
</html>
