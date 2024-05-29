<?php
include('auth.php');
include('conexion.php');

// Recuperar los datos del evento
$idEvento = $_GET['idEvento']; // Obtener el id del evento de la URL
$query = "SELECT * FROM Evento WHERE idEvento = ?";
$params = array($idEvento);
$stmt = sqlsrv_query($con, $query, $params);
$evento = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if (!$evento) {
    echo "Evento no encontrado.";
    exit;
}

// Función para obtener el nombre de la carrera basada en su ID
function obtenerNombreCarrera($idCarrera, $con) {
    $query = "SELECT nombre FROM carrera WHERE idCarrera = ?";
    $params = array($idCarrera);
    $stmt = sqlsrv_query($con, $query, $params);
    
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmt);
    
    return $row ? $row['nombre'] : null;
}

// Obtener el nombre de la carrera
$carreraNombre = obtenerNombreCarrera($evento['fk_carrera'], $con);

// Si hay sesión, recuperar ID del usuario
$id = $_SESSION['id'];

// Consulta SQL para obtener la foto de perfil del usuario basado en el ID
$queryuser = "SELECT foto_perfil FROM usuario WHERE idUsuario = ?";
$stmtuser = sqlsrv_prepare($con, $queryuser, array($id));

if ($stmtuser && sqlsrv_execute($stmtuser)) {
    if ($rowuser = sqlsrv_fetch_array($stmtuser, SQLSRV_FETCH_ASSOC)) {
        $fotoPerfil = $rowuser['foto_perfil'] ? $rowuser['foto_perfil'] : 'images/default-profile.png';  // Usar imagen por defecto si no hay foto de perfil
    } else {
        $fotoPerfil = 'images/default-profile.png';
    }
} else {
    $fotoPerfil = 'images/default-profile.png';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Editar Evento</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="img/favicon.ico" rel="icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/ROMAN.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="container-fluid p-0">
        <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-2 py-lg-0 px-lg-5">
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"><img src="images/dailytec.jfif"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between px-0 px-lg-3" id="navbarCollapse">
                <a class="nav-item nav-link active"><img src="images/escudo.png" class="size"></a>
                <a href="index.html" class="nav-item nav-link active"><img src="images/dailytec.jfif" class="size"></a>
                <div class="navbar-nav mr-auto py-0">
                    <a href="index.php" class="nav-item nav-link active">Eventos</a>
                    <a href="calendario.php" class="nav-item nav-link">Calendario</a>
                    <a href="single.html" class="nav-item nav-link">Historial</a>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown">Perfil</a>
                        <div class="dropdown-menu rounded-0 m-0">
                            <a href="perfil.php" class="dropdown-item">Mi perfil</a>
                            <a href="gestU.php" class="dropdown-item">Gestionar Usuarios</a>
                            <a href="Login.php" class="dropdown-item">Cerrar Sesión</a>
                        </div>
                    </div>
                    <a href="perfil.php" class="nav-item nav-link">
                        <img src="<?php echo htmlspecialchars($fotoPerfil); ?>" class="size2">
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <div id="div1">
        <a class="#div1 letras" href="index.php"><span class="iconoflecha"><img src="images/flecha.png" alt="flecha" class="iconoflecha"></span> Regresar</a>
    </div>

    <div class="container principal">
        <form action="updateEvento.php" method="POST" enctype="multipart/form-data">
            <h2 class="datoseventotitulo">Datos del evento</h2>

            <input type="hidden" name="id" value="<?php echo htmlspecialchars($idEvento); ?>">

            <div class="form-group row">
                <label for="nombre" class="col-sm-2 col-form-label">Nombre:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($evento['nombre']); ?>" required>
                </div>
            </div>

            <div class="form-group row">
                <label for="check-creditos" class="col-sm-2 col-form-label">Libera créditos</label>
                <div class="col-sm-10">
                    <input type="checkbox" class="form-check-input" id="check-creditos" name="creditos" <?php echo ($evento['liberacion_creditos'] == 1) ? 'checked' : ''; ?> onchange="updateHiddenInput(this)">
                    <input type="hidden" id="hidden-creditos" name="hidden-creditos" value="<?php echo $evento['liberacion_creditos']; ?>">
                </div>
            </div>

            <div class="form-group row">
                <label for="imagen" class="col-sm-2 col-form-label">Selecciona una imagen:</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control-file" id="imagen" name="imagen" accept="image/*" onchange="mostrarImagen(this)">
                    <div id="vista-previa-contenedor">
                        <img id="vista-previa-imagen" src="<?php echo htmlspecialchars($evento['banner']); ?>" alt="Vista previa de imagen" class="img-fluid mt-2">
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="descripcion" class="col-sm-2 col-form-label">Descripción:</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required><?php echo htmlspecialchars($evento['descripcion']); ?></textarea>
                </div>
            </div>

            <div class="form-group row">
                <label for="fecha_inicio" class="col-sm-2 col-form-label">Fecha Inicio:</label>
                <div class="col-sm-4">
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo htmlspecialchars($evento['fecha_inicio']->format('Y-m-d')); ?>" required>
                </div>
                <label for="fecha_fin" class="col-sm-2 col-form-label">Fecha Fin:</label>
                <div class="col-sm-4">
                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo htmlspecialchars($evento['fecha_fin']->format('Y-m-d')); ?>" required>
                </div>
            </div>

            <div class="form-group row">
                <label for="carrera" class="col-sm-2 col-form-label">Carrera:</label>
                <div class="col-sm-10">
                    <select class="form-control" id="carrera" name="carrera" required>
                        <option value="Arquitectura" <?php if ($carreraNombre == 'Arquitectura') echo 'selected'; ?>>Arquitectura</option>
                        <option value="Ingeniería Bioquímica" <?php if ($carreraNombre == 'Ingeniería Bioquímica') echo 'selected'; ?>>Ingeniería Bioquímica</option>
                        <option value="Ingeniería Civil" <?php if ($carreraNombre == 'Ingeniería Civil') echo 'selected'; ?>>Ingeniería Civil</option>
                        <option value="Ingeniería Eléctrica" <?php if ($carreraNombre == 'Ingeniería Eléctrica') echo 'selected'; ?>>Ingeniería Eléctrica</option>
                        <option value="Ingeniería en Gestión Empresarial" <?php if ($carreraNombre == 'Ingeniería en Gestión Empresarial') echo 'selected'; ?>>Ingeniería en Gestión Empresarial</option>
                        <option value="Ingeniería en Sistemas Computacionales" <?php if ($carreraNombre == 'Ingeniería en Sistemas Computacionales') echo 'selected'; ?>>Ingeniería en Sistemas Computacionales</option>
                        <option value="Ingeniería Industrial" <?php if ($carreraNombre == 'Ingeniería Industrial') echo 'selected'; ?>>Ingeniería Industrial</option>
                        <option value="Ingeniería Mecatrónica" <?php if ($carreraNombre == 'Ingeniería Mecatrónica') echo 'selected'; ?>>Ingeniería Mecatrónica</option>
                        <option value="Ingeniería Química" <?php if ($carreraNombre == 'Ingeniería Química') echo 'selected'; ?>>Ingeniería Química</option>
                        <option value="Licenciatura en Administración" <?php if ($carreraNombre == 'Licenciatura en Administración') echo 'selected'; ?>>Licenciatura en Administración</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="tipo_evento" class="col-sm-2 col-form-label">Tipo de Evento:</label>
                <div class="col-sm-10">
                    <select class="form-control" id="tipo_evento" name="tipo_evento" required>
                        <option value="Eventos oficiales" <?php if ($evento['tipo_evento'] == 'Eventos oficiales') echo 'selected'; ?>>Eventos oficiales</option>
                        <option value="Extraescolares" <?php if ($evento['tipo_evento'] == 'Extraescolares') echo 'selected'; ?>>Extraescolares</option>
                        <option value="Tutorías" <?php if ($evento['tipo_evento'] == 'Tutorías') echo 'selected'; ?>>Tutorías</option>
                        <option value="Jornada Académica" <?php if ($evento['tipo_evento'] == 'Jornada Académica') echo 'selected'; ?>>Jornada Académica</option>
                        <option value="Vinculación" <?php if ($evento['tipo_evento'] == 'Vinculación') echo 'selected'; ?>>Vinculación</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-12 text-center">
                    <button type="submit" class="btn btn-warning">Guardar</button>
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                    <button type="button" class="btn btn-danger" onclick="eliminarEvento(<?php echo $idEvento; ?>)">Eliminar</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function updateHiddenInput(checkbox) {
            var hiddenInput = document.getElementById('hidden-creditos');
            hiddenInput.value = checkbox.checked ? '1' : '0';
        }

        function mostrarImagen(input) {
            var vistaPreviaContenedor = document.getElementById('vista-previa-contenedor');
            var vistaPreviaImagen = document.getElementById('vista-previa-imagen');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    vistaPreviaImagen.src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function eliminarEvento(idEvento) {
            if (confirm('¿Estás seguro de que deseas eliminar este evento?')) {
                window.location.href = 'eliminarEvento.php?id=' + idEvento;
            }
        }
    </script>
</body>
</html>
