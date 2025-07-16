<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids_eliminar'])) {
    $ids = $_POST['ids_eliminar'];
    if (!empty($ids) && is_array($ids)) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("DELETE FROM recetas WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        header("Location: listacompleta.php");
        exit;
    }
}

$stmt = $pdo->query("SELECT * FROM recetas ORDER BY tipo, autor, titulo");
$recetas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Lista completa de recetas - CocinaPower</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="icon" href="icon.png" />
 <style>
  body {
    margin: 0;
    font-family: 'Georgia', serif;
    background: url('fondo.jpeg') no-repeat center center fixed;
    background-size: cover;
    color: #eee;
    min-height: 100vh;
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
  }

  .acciones-detalle {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
    padding: 1rem;
    background: rgba(0, 0, 0, 0.5);
  }

  .acciones-detalle button,
  .acciones-detalle a {
    background: #ff5c5c;
    color: white;
    padding: 8px 14px;
    border-radius: 6px;
    border: none;
    font-weight: bold;
    text-decoration: none;
    box-shadow: 0 0 6px #ff4a4a;
    cursor: pointer;
  }

  .acciones-detalle .confirmar {
    background: #ffd700;
    color: #000;
  }

  .container {
    margin: 100px auto 0rem;
    max-width: 1100px;
    background: rgba(10, 10, 20, 0.85);
    border-radius: 14px;
    box-shadow: 0 0 40px #ff4a4aaa;
    color: #fff;
    padding: 2rem;
  }

  .titulo-lista {
    margin-bottom: 2rem;
    text-align: center;
    color: #ffd363;
    font-size: 2rem;
    text-shadow: 0 0 6px #ffd363aa;
  }

  .lista-completa {
    display: flex;
    flex-direction: column;
    gap: 1.2rem;
  }

  .fila-cancion {
    display: flex;
    align-items: center;
    background: rgba(20, 20, 30, 0.9);
    padding: 1rem;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(255, 249, 214, 0.53);
    gap: 1rem;
    cursor: pointer;
    transition: transform 0.2s ease;
    flex-wrap: wrap;
  }

  .fila-cancion:hover {
    transform: scale(1.015);
  }

  .numero {
    width: 30px;
    text-align: center;
    font-weight: bold;
    color: #ffd700;
  }

  .miniatura img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #ff4a4a;
  }

  .datos {
    flex: 1;
    min-width: 200px;
  }

  .titulo-artista {
    font-size: 1.1rem;
    font-weight: bold;
    color: #ff4a4a;
    text-shadow: 0 0 5px #ff4a4aaa;
  }

  .tipo-receta {
    font-size: 0.9rem;
    color: #eee;
  }

  .acciones-mini {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
  }

  .acciones-mini a {
    background: rgb(228, 146, 46);
    color: white;
    padding: 6px 12px;
    font-size: 0.9rem;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    text-align: center;
    box-shadow: 0 0 6px #ff0000aa;
  }

  .acciones-mini a:hover {
    background: #cc0000;
  }

  footer {
    text-align: center;
    padding: 2rem 1rem;
    font-size: 0.9rem;
    background: rgba(0,0,0,0.6);
    color: #ccc;
  }

  /* 📱 RESPONSIVE */
  @media (max-width: 768px) {
    .container {
      margin: 2rem 1rem;
      max-width: 100%;
      padding: 1.5rem;
    }

    .volver-fijo {
      left: 1rem;
      font-size: 0.9rem;
      padding: 5px 10px;
    }

    .fila-cancion {
      flex-direction: column;
      align-items: flex-start;
    }

    .acciones-mini {
      flex-direction: row;
      flex-wrap: wrap;
      gap: 0.5rem;
      width: 100%;
      justify-content: space-between;
    }

    .titulo-artista {
      font-size: 1rem;
    }

    .acciones-detalle {
      flex-direction: column;
      gap: 0.5rem;
      align-items: center;
    }
  }
</style>

</head>
<body>

  <div class="header">
    <a href="index.php"><img src="logo.png" class="logo" alt="Logo CocinaPower" /></a>
  <a href="index.php" class="volver-fijo"><i class="fas fa-arrow-left"></i> Volver</a>

    <div class="acciones-detalle">
      <button id="btn-editar"><i class="fas fa-edit"></i> Editar</button>
     <button id="btn-agregar"><i class="fas fa-plus"></i> Agregar</button>
      <button id="btn-eliminar"><i class="fas fa-trash"></i> Eliminar</button>
      <button id="btn-cancelar" style="display:none;">Cancelar</button>
      <button class="confirmar" id="confirmar-editar" style="display:none;">Editar receta seleccionada</button>
      <button class="confirmar eliminar" id="confirmar-eliminar" style="display:none;">Eliminar recetas seleccionadas</button>
    </div>
  </div>

 
  <main class="container">

    <h1 class="titulo-lista">Lista Completa de Recetas</h1>
    <form id="form-acciones" method="POST">
      <div class="lista-completa">
        <?php $i = 1; foreach($recetas as $r): ?>
          <article class="fila-cancion" data-id="<?= $r['id'] ?>" onclick="onClickReceta(event, <?= $r['id'] ?>)">
            <input type="checkbox" class="chk-select" name="ids_eliminar[]" value="<?= $r['id'] ?>" style="display:none;" />
            <div class="numero"><?= $i++ ?></div>
            <div class="miniatura"><img src="<?= htmlspecialchars($r['imagen_url']) ?>" alt="Miniatura"></div>
            <div class="datos">
              <div class="titulo-artista"><?= htmlspecialchars($r['titulo']) ?> – <?= htmlspecialchars($r['autor']) ?></div>
              <div class="tipo-receta">Tipo: <em><?= htmlspecialchars($r['tipo']) ?></em></div>
            </div>
            <div class="acciones-mini">
              <a href="<?= htmlspecialchars($r['enlace_youtube']) ?>" target="_blank" onclick="event.stopPropagation();"><i class="fab fa-youtube"></i> YouTube</a>
              <a href="detalle.php?id=<?= $r['id'] ?>" onclick="event.stopPropagation();"><i class="fas fa-search"></i> Detalles</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </form>
  </main>

  <footer>
    <p>Curso: Conceptualización de servicios en la nube</p>
    <p>Nombre: Vanessa Itzarahí Gómez Ramírez</p>
    <p>&copy; <?= date('Y') ?> Todos los derechos reservados</p>
  </footer>

  <script>
    const body = document.body;
    const btnEditar = document.getElementById('btn-editar');
    const btnEliminar = document.getElementById('btn-eliminar');
    const btnCancelar = document.getElementById('btn-cancelar');
    const confirmarEditar = document.getElementById('confirmar-editar');
    const confirmarEliminar = document.getElementById('confirmar-eliminar');
    const form = document.getElementById('form-acciones');
    const checkboxes = form.querySelectorAll('.chk-select');
    let modoActual = "ninguno";

    btnEditar.addEventListener('click', () => activarModo('editar'));
    btnEliminar.addEventListener('click', () => activarModo('eliminar'));
    btnCancelar.addEventListener('click', () => desactivarModos());

    function activarModo(modo) {
      modoActual = modo;
      body.classList.toggle('modo-editar', modo === 'editar');
      body.classList.toggle('modo-eliminar', modo === 'eliminar');
      confirmarEditar.style.display = modo === 'editar' ? 'inline-block' : 'none';
      confirmarEliminar.style.display = modo === 'eliminar' ? 'inline-block' : 'none';
      btnCancelar.style.display = 'inline-block';
      btnEditar.style.display = btnEliminar.style.display = 'none';
      checkboxes.forEach(chk => { chk.checked = false; chk.style.display = 'inline-block'; });
    }

    function desactivarModos() {
      modoActual = "ninguno";
      confirmarEditar.style.display = confirmarEliminar.style.display = 'none';
      btnCancelar.style.display = 'none';
      btnEditar.style.display = btnEliminar.style.display = 'inline-block';
      checkboxes.forEach(chk => { chk.checked = false; chk.style.display = 'none'; });
    }

    checkboxes.forEach(chk => {
      chk.addEventListener('change', e => {
        if (modoActual === 'editar' && e.target.checked) {
          checkboxes.forEach(c => { if (c !== e.target) c.checked = false; });
        }
      });
    });

    function onClickReceta(event, id) {
      if (modoActual === "ninguno") window.location.href = 'detalle.php?id=' + id;
      else event.stopPropagation();
    }

confirmarEditar.addEventListener('click', () => {
  const seleccionado = [...checkboxes].find(chk => chk.checked);
  if (!seleccionado) {
    return Swal.fire({ icon: 'warning', title: 'Selecciona una receta para editar' });
  }

  const id = seleccionado.value;

  // Buscar el elemento article correspondiente para extraer el tipo
  const fila = seleccionado.closest('.fila-cancion');
  const tipo = fila.querySelector('.tipo-receta em')?.textContent?.trim();

  if (tipo === 'top') {
    window.location.href = 'editar-top.php?id=' + id;
  } else if (tipo === 'recomendacion') {
    window.location.href = 'editar-recomendacion.php?id=' + id;
  } else {
    Swal.fire({ icon: 'error', title: 'Tipo de receta desconocido' });
  }
});


    confirmarEliminar.addEventListener('click', () => {
      const seleccionados = [...checkboxes].filter(chk => chk.checked);
      if (seleccionados.length === 0) {
        return Swal.fire({ icon: 'warning', title: 'Selecciona al menos una receta para eliminar' });
      }
      Swal.fire({
        title: `¿Eliminar ${seleccionados.length} receta(s)?`,
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          const formPost = document.createElement('form');
          formPost.method = 'POST';
          formPost.action = 'listacompleta.php';
          seleccionados.forEach(chk => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids_eliminar[]';
            input.value = chk.value;
            formPost.appendChild(input);
          });
          document.body.appendChild(formPost);
          formPost.submit();
        }
      });
    });

    const btnAgregar = document.getElementById('btn-agregar');

btnAgregar.addEventListener('click', (e) => {
  e.preventDefault(); // evitar que se envíe o redireccione

  Swal.fire({
    title: '¿Qué tipo de receta quieres agregar?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Agregar al Top',
    cancelButtonText: 'Cancelar',
    showDenyButton: true,
    denyButtonText: 'Agregar a Recomendación',
    confirmButtonColor: '#28a745',
    denyButtonColor: '#ff7f50',
    cancelButtonColor: '#aaa'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = 'agregartop.php';
    } else if (result.isDenied) {
      window.location.href = 'agregarrecomendacion.php';
    }
  });
});

  </script>

</body>
</html>
