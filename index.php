<?php
include 'conexion.php';

// Configuración de paginación
$recetas_por_pagina = 5;
$pagina_actual = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $recetas_por_pagina;

// Total de recetas tipo 'top'
$stmtCount = $pdo->query("SELECT COUNT(*) FROM recetas WHERE tipo = 'top'");
$total_recetas = $stmtCount->fetchColumn();
$total_paginas = ceil($total_recetas / $recetas_por_pagina);

// Recomendaciones
$stmRec = $pdo->query("SELECT * FROM recetas WHERE tipo = 'recomendacion' ORDER BY orden_mes ASC");
$recs = $stmRec->fetchAll(PDO::FETCH_ASSOC);

// Top recetas paginadas
$stmtPag = $pdo->prepare("SELECT * FROM recetas WHERE tipo = 'top' ORDER BY autor, titulo LIMIT :limit OFFSET :offset");
$stmtPag->bindValue(':limit', $recetas_por_pagina, PDO::PARAM_INT);
$stmtPag->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmtPag->execute();
$recetas = $stmtPag->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <title>CocinaPower</title>
  <link rel="stylesheet" href="style.css"/>
  <link rel="icon" href="icon.png"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
<div class="fondo"></div>

<!-- HEADER FUERA DEL CONTAINER -->
<div class="header">
  <div class="header-left">
    <a href="."><img src="logo1.png" class="logo" alt="Logo CocinaPower"/></a>
    
  </div>

  <div class="header-right">
    <nav class="menu">
      <a href="#recomendaciones">Inicio</a>

      <div class="menu-item">
        <a href="#top-canciones" class="top-link">Top Recetas</a>
        <div class="submenu-top">
           <div class="hover-bridge"></div>
          <a href="listacompleta.php">Lista completa</a>
        </div>
      </div>

      <a href="#contacto">Contacto</a>
      <span class="separador">|</span>

      <div class="menu-hamb">
        ☰
        <div class="submenu">
          <a href="agregarrecomendacion.php?tipo=recomendacion">Agregar<br>Recomendación</a>
          <a href="agregartop.php?tipo=top">Agregar<br>Receta</a>
        </div>
      </div>
    </nav>
  </div>
</div>

<!-- CONTENIDO PRINCIPAL -->
<div class="container">
  <main>

    <!-- Recomendaciones -->
    <section id="recomendaciones" class="recomendacion">
      <h2>Recetas recomendadas del mes</h2>
      <div class="slider">
        <?php foreach($recs as $i => $c): ?>
          <div class="slide <?= $i===0?'active':'' ?>" onclick="window.location='detalle.php?id=<?= $c['id'] ?>'">
            <p class="recom-sent">
              <?= !empty($c['recomendacion_text']) ? htmlspecialchars($c['recomendacion_text']) :
                'Te recomiendo la receta "'.htmlspecialchars($c['titulo']).'"'; ?>
            </p>

            <div class="descripcion">
              <h3><?=htmlspecialchars($c['titulo'])?></h3>
              <p><?=htmlspecialchars($c['descripcion'])?></p>
            </div>

            <div class="video-embed">
              <?php preg_match('/v=([^&]+)/',$c['enlace_youtube'],$m); $vid = $m[1] ?? ''; ?>
              <iframe src="https://www.youtube.com/embed/<?= $vid ?>" frameborder="0" allowfullscreen></iframe>
            </div>

           <div class="botones-slide" onclick="event.stopPropagation();">
  <a class="botonyoutube" href="<?= htmlspecialchars($c['enlace_youtube'])?>" target="_blank">
    <img src="youtube.png" class="youtube-icon"> Ver en YouTube
  </a>
  <a class="boton-detalles" href="detalle.php?id=<?= $c['id'] ?>">
    <i class="fas fa-search"></i> <span>Ver detalles</span>
  </a>
</div>

          </div>
        <?php endforeach; ?>
      </div>

      <div class="nav-flechas">
        <button id="prev">←</button>
        <button id="next">→</button>
      </div>
    </section>

    <!-- Top Recetas -->
    <section id="top-canciones" class="top-canciones">
      <h2 data-text="Top recetas del mes">Top recetas</h2>
      <div class="lista-canciones">
        <?php foreach($recetas as $c): ?>
          <article class="cancion" onclick="window.location='detalle.php?id=<?= $c['id'] ?>'">
            <img src="<?=htmlspecialchars($c['imagen_url'])?>" alt="Miniatura de receta"/>
            <div class="info">
              <h3><?=htmlspecialchars($c['titulo'])?></h3>
              <p><?=htmlspecialchars($c['descripcion'])?></p>
              <div class="acciones-mini">
                <a class="botonyoutube" href="<?=htmlspecialchars($c['enlace_youtube'])?>" target="_blank" onclick="event.stopPropagation();">
                  <img src="youtube.png" class="youtube-icon"> Ver en YouTube
                </a>
                <a class="boton-detalles boton-detalles-top" href="detalle.php?id=<?= $c['id'] ?>" onclick="event.stopPropagation();">
                  <i class="fas fa-search"></i> <span>Ver detalles</span>
                </a>
              </div>
            </div>
          </article>
        <?php endforeach; ?>

        <!-- Paginación -->
        <div class="paginacion">
          <?php if ($pagina_actual > 1): ?>
            <a href="?pagina=<?= $pagina_actual - 1 ?>#top-canciones" class="btn-pag">« Anterior</a>
          <?php endif; ?>

          <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <?php if ($i == $pagina_actual): ?>
              <span class="btn-pag activo"><?= $i ?></span>
            <?php else: ?>
              <a href="?pagina=<?= $i ?>#top-canciones" class="btn-pag"><?= $i ?></a>
            <?php endif; ?>
          <?php endfor; ?>

          <?php if ($pagina_actual < $total_paginas): ?>
            <a href="?pagina=<?= $pagina_actual + 1 ?>#top-canciones" class="btn-pag">Siguiente »</a>
          <?php endif; ?>
        </div>

        <div class="btn-ver-lista-container">
          <a href="listacompleta.php" class="btn-ver-lista">Ver lista completa de recetas</a>
        </div>
      </div>
    </section>

    <div class="separator"></div>

<section id="contacto" class="contacto">
  <h2>Contacto</h2>
  <p>
    ¿Quieres recomendar alguna receta?
    <a href="agregarrecomendacion.php?tipo=recomendacion">Llena este formulario</a>
  </p>
</section>

  </main>
</div>

<!-- FOOTER FUERA DEL CONTAINER -->
<footer>
  <p>Curso: Conceptualización de servicios en la nube</p>
  <p>Nombre: Vanessa Itzarahí Gómez Ramírez</p>
  <p>&copy; <?= date('Y') ?> Todos los derechos reservados</p>
</footer>

<script>
  let idx = 0;
  const slides = document.querySelectorAll('.slider .slide');
  const prev = document.getElementById('prev');
  const next = document.getElementById('next');

  function mostrarSlide(index) {
    slides.forEach(s => s.classList.remove('active'));
    slides[index].classList.add('active');
  }

  prev.onclick = () => {
    idx = (idx - 1 + slides.length) % slides.length;
    mostrarSlide(idx);
    reiniciarAutoplay();
  };

  next.onclick = () => {
    idx = (idx + 1) % slides.length;
    mostrarSlide(idx);
    reiniciarAutoplay();
  };

  function autoplay() {
    idx = (idx + 1) % slides.length;
    mostrarSlide(idx);
  }

  let interval = setInterval(autoplay, 6000); // cada 6 segundos

  function reiniciarAutoplay() {
    clearInterval(interval);
    interval = setInterval(autoplay, 6000);
  }

  mostrarSlide(idx);


  document.addEventListener("DOMContentLoaded", () => {
    const hamb = document.querySelector(".menu-hamb");
    hamb.addEventListener("click", () => {
      const menu = document.querySelector(".menu");
      menu.classList.toggle("show");
    });
  });

</script>

</body>
</html>
