<?php
include 'conexion.php';
if (!isset($_GET['id'])) exit('ID no especificado.');
$id = intval($_GET['id']);

$stm = $pdo->prepare("SELECT * FROM recetas WHERE id = ?");
$stm->execute([$id]);
$r = $stm->fetch(PDO::FETCH_ASSOC);

if (!$r) exit('Receta no encontrada');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title><?= htmlspecialchars($r['titulo']) ?> – <?= htmlspecialchars($r['autor']) ?></title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="icon" href="icon.png" />
  <style>
    /* Diseño tipo Music Power basado en la imagen subida */
    body {
      margin: 0;
      font-family: 'Georgia', serif;
      background: url('fondo.jpeg') no-repeat center center fixed;
      background-size: cover;
      color: #eee;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .acciones-detalle {
      margin-left: auto;
      display: flex;
      gap: 1rem;
    }
    .acciones-detalle a {
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
      padding: 8px 16px;
      background: #ff5c5c;
      color: #fff;
      border-radius: 6px;
      font-weight: 600;
      text-decoration: none;
      box-shadow: 0 0 6px #ff4a4a;
      transition: background-color 0.3s ease;
    }
    .acciones-detalle a:hover {
      background: #ff2a2a;
    }

    .container {
      flex: 1;
      margin: 90px auto 3rem;
      max-width: 1100px;
      background: rgba(10, 10, 20, 0.85);
      padding: 2rem 3rem;
      border-radius: 14px;
      box-shadow: 0 0 40px #ff4a4aaa;
      color: #fff;
      display: flex;
      gap: 4rem;
      flex-wrap: wrap;
      justify-content: center;
    }

    .detalle-info {
      flex: 1 1 300px;
      max-width: 380px;
      text-align: center;
    }
    .miniatura-circular {
      width: 140px;
      height: 140px;
      margin: 0 auto 1rem;
      border-radius: 50%;
      overflow: hidden;
      border: 4px solid #ff4a4a;
      box-shadow: 0 0 15px #ff4a4aaa;
    }
    .miniatura-circular img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      filter: grayscale(10%) brightness(90%);
      transition: filter 0.3s ease;
    }
    .miniatura-circular img:hover {
      filter: grayscale(0) brightness(100%);
    }

    .detalle-info h2 {
      font-size: 2.4rem;
      color: #ff4a4a;
      margin-bottom: 0.25rem;
      font-weight: 700;
      text-shadow: 0 0 6px #ff4a4aaa;
    }
    .detalle-info h3 {
      font-size: 1.3rem;
      color: #ffd363;
      font-weight: 600;
      margin-bottom: 1.6rem;
      text-shadow: 0 0 5px #ffd363aa;
    }

    .texto-descripcion {
      font-size: 1.1rem;
      color: #ddd;
      border-left: 4px solid #ffd363;
      padding-left: 1rem;
      line-height: 1.6;
      text-align: justify;
      min-height: 6rem;
      margin-bottom: 1.5rem;
    }

    .texto-recomendado {
      font-size: 0.9rem;
      color: #f5d371;
      font-style: italic;
      margin-top: 1rem;
      text-align: center;
    }

    .detalle-video {
      flex: 2 1 600px;
      max-width: 700px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 1rem;
    }

    .detalle-video iframe {
      width: 100%;
      height: 390px;
      border-radius: 14px;
      box-shadow: 0 0 30px #ff4a4aaa;
      border: none;
    }

    .botonyoutube {
      display: inline-flex;
      align-items: center;
      gap: 0.8rem;
      background-color: #ff0000;
      color: white;
      padding: 12px 22px;
      border-radius: 30px;
      font-weight: 700;
      font-family: 'Georgia', serif;
      font-size: 1.1rem;
      text-decoration: none !important;
      box-shadow: 0 0 12px #ff0000cc;
      transition: background-color 0.3s ease;
    }
    .botonyoutube:hover {
      background-color: #cc0000;
      box-shadow: 0 0 20px #cc0000ff;
    }
    .youtube-icon {
      height: 24px;
      width: 24px;
      filter: drop-shadow(0 0 1px black);
    }

    /* Responsive */
    @media (max-width: 1200px) {
      
    .header {
      height: auto;
    }
      .container {
        flex-direction: column;
        max-width: 95vw;
        margin: 100px auto 2rem;
        padding: 1.5rem 2rem;
      }
      .detalle-info, .detalle-video {
        max-width: 100%;
        flex: none;
      }
      .detalle-video iframe {
        height: 240px;
      }
      .detalle-info h2 {
        font-size: 2rem;
      }
      .detalle-info h3 {
        font-size: 1.1rem;
      }
      .texto-descripcion {
        font-size: 1rem;
      }
      .botonyoutube {
        font-size: 1rem;
        padding: 10px 18px;
      }
    }
  </style>
</head>
<body>
  <header class="header">
    <a href="index.php"><img src="logo.png" class="logo" alt="Logo CocinaPower" /></a>
      <?php
    $volver = isset($_GET['volver']) ? $_GET['volver'] : 'index.php';
  ?>
  <a href="<?= htmlspecialchars($volver) ?>" class="volver-fijo"><i class="fas fa-arrow-left"></i> Volver</a>

    <div class="acciones-detalle">
      <?php if ($r['tipo'] === 'recomendacion'): ?>
        <a href="editar-recomendacion.php?id=<?= $r['id'] ?>"><i class="fas fa-edit"></i> Editar</a>
      <?php else: ?>
        <a href="editar-top.php?id=<?= $r['id'] ?>"><i class="fas fa-edit"></i> Editar</a>
      <?php endif; ?>
      <a href="#" onclick="confirmarEliminacion(<?= $r['id'] ?>); return false;">
        <i class="fas fa-trash"></i> Eliminar
      </a>
    </div>
  </header>


  <main class="container">
    <section class="detalle-info">
      <div class="miniatura-circular">
        <img src="<?= htmlspecialchars($r['imagen_url']) ?>" alt="Imagen de <?= htmlspecialchars($r['titulo']) ?>" />
      </div>
      <h2><?= htmlspecialchars($r['titulo']) ?></h2>
      <h3><?= htmlspecialchars($r['autor']) ?></h3>

      <p class="texto-descripcion"><?= nl2br(htmlspecialchars($r['descripcion'])) ?></p>
      <?php if (!empty($r['enviado_por'])): ?>
        <p class="texto-recomendado"><strong>Recomendado por:</strong> <?= htmlspecialchars($r['enviado_por']) ?></p>
      <?php endif; ?>
    </section>

    <section class="detalle-video">
      <?php
        $video_id = '';
        if (preg_match('/v=([^&]+)/', $r['enlace_youtube'], $m)) {
          $video_id = $m[1];
        } elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $r['enlace_youtube'], $m)) {
          $video_id = $m[1];
        }
      ?>
      <?php if ($video_id): ?>
        <iframe
          src="https://www.youtube.com/embed/<?= htmlspecialchars($video_id) ?>"
          frameborder="0"
          allowfullscreen
          title="<?= htmlspecialchars($r['titulo']) ?> - Video"
        ></iframe>
      <?php else: ?>
        <p>Video no disponible.</p>
      <?php endif; ?>

      <a
        class="botonyoutube"
        href="<?= htmlspecialchars($r['enlace_youtube']) ?>"
        target="_blank"
        rel="noopener noreferrer"
      >
        <img src="youtube.png" alt="YouTube Icon" class="youtube-icon" /> Ver en YouTube
      </a>
    </section>
  </main>

  <footer>
    <p>Curso: Conceptualización de servicios en la nube</p>
    <p>Nombre: Vanessa Itzarahí Gómez Ramírez</p>
    <p>&copy; <?= date('Y') ?> Todos los derechos reservados</p>
  </footer>

  <script>
    function confirmarEliminacion(id) {
      Swal.fire({
        title: '¿Estás segura de eliminar esta receta?',
        text: "No podrás revertir esta acción.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = 'eliminar.php?id=' + id;
        }
      });
    }
  </script>
</body>
</html>
