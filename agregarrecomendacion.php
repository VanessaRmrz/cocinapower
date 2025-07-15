<?php
include 'conexion.php';

function extraerIdYoutube($url) {
    if (preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $match)) {
        return $match[1];
    }
    return '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'] ?? '';
    $descripcion = $_POST['descripcion'];
    $enlace = $_POST['enlace_youtube'];
    $recomendacion_text = $_POST['recomendacion_text'] ?? null;
    $enviado_por = trim($_POST['enviado_por'] ?? '');
    if ($enviado_por === '') {
        $enviado_por = 'Anónimo';
    }

    $video_id = extraerIdYoutube($enlace);
    $imagen = $video_id ? "https://img.youtube.com/vi/$video_id/hqdefault.jpg" : '';

    $stmtOrden = $pdo->query("SELECT MAX(orden_mes) AS max_orden FROM recetas WHERE tipo = 'recomendacion'");
    $max = $stmtOrden->fetch(PDO::FETCH_ASSOC)['max_orden'] ?? 0;
    $orden = $max + 1;

    $fecha = date('Y-m-d');

    $stmt = $pdo->prepare("INSERT INTO recetas 
        (titulo, autor, descripcion, enlace_youtube, imagen_url, tipo, enviado_por, fecha_recomendacion, orden_mes, recomendacion_text)
        VALUES (?, ?, ?, ?, ?, 'recomendacion', ?, ?, ?, ?)");
    $stmt->execute([
        $titulo,
        $autor,
        $descripcion,
        $enlace,
        $imagen,
        $enviado_por,
        $fecha,
        $orden,
        $recomendacion_text
    ]);

    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Receta Recomendada</title>
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



    .agregar-recomendacion-container {
      margin: 100px auto 3rem;
      max-width: 700px;
      background: rgba(20, 20, 30, 0.92);
      padding: 2.5rem 2rem;
      border-radius: 14px;
      box-shadow: 0 0 30px #ff4a4aaa;
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
      box-shadow: 0 0 10px #ff4a4aaa;
    }

    .agregar-recomendacion-container button:hover {
      background: #cc0000;
    }

    footer {
      text-align: center;
      padding: 2rem 1rem;
      font-size: 0.9rem;
      background: rgba(0,0,0,0.6);
      color: #ccc;
    }

    @media (max-width: 768px) {
      .agregar-recomendacion-container {
        margin: 110px auto 2rem;
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
  <a href="index.php" class="volver-fijo"><i class="fas fa-arrow-left"></i> Volver</a>

  </div>


  <div class="agregar-recomendacion-container">
    <h2>Agregar Receta Recomendada del Mes</h2>
    <form method="post">
      <input name="titulo" placeholder="Nombre de la receta" required>
      <input name="autor" placeholder="Autor de la receta (opcional)">
      <textarea name="descripcion" placeholder="Descripción o ingredientes principales" required></textarea>
      <input name="enlace_youtube" placeholder="URL del video en YouTube (https://...)" required>
      <textarea name="recomendacion_text" placeholder="Comentario adicional (opcional)"></textarea>
      <input name="enviado_por" placeholder="Nombre de quien recomienda (opcional)">
      <button type="submit"><i class="fas fa-save"></i> Guardar Receta</button>
    </form>
  </div>

  <footer>
    <p>Curso: Conceptualización de servicios en la nube</p>
    <p>Nombre: Vanessa Itzarahí Gómez Ramírez</p>
    <p>&copy; <?= date('Y') ?> Todos los derechos reservados</p>
  </footer>

</body>
</html>
