<?php
    include('auth.php');
    include('conexion.php');


// Obtener el ID de la actividad y el ID del evento de la URL
$idActividad = $_GET['idActividad'];
$idEvento = $_GET['idEvento'];

$query = "SELECT nombre, descripcion, imagen, modalidad, lugar, fecha_inicio, fecha_fin, hora_inicio, hora_fin 
          FROM Actividad 
          WHERE idActividad = ?";
$params = array($idActividad);
$result = sqlsrv_query($con, $query, $params);

if ($result && $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $nombre = $row['nombre'];
    $descripcion = $row['descripcion'];
    $imagen = $row['imagen'];
    $modalidad = $row['modalidad'];
    $lugar = $row['lugar'];
    $fecha_inicio = $row['fecha_inicio']->format('Y-m-d');
    $fecha_fin = $row['fecha_fin']->format('Y-m-d');
    $hora_inicio = $row['hora_inicio']->format('H:i');
    $hora_fin = $row['hora_fin'] ? $row['hora_fin']->format('H:i') : '';
} else {
    die("Error al recuperar la actividad: " . print_r(sqlsrv_errors(), true));
}

sqlsrv_free_stmt($result);
sqlsrv_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Editar Actividad</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <link href="img/favicon.ico" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">  
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/ROMAN.css" rel="stylesheet" type="text/css">

    <style>
        .form-container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #000;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #fff;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: white;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
            color: white;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
            background-color: #333;
            color: #fff;
        }

        .form-group .checkbox-group {
            display: flex;
            align-items: center;
        }

        .form-group .checkbox-group input {
            width: auto;
            margin-right: 10px;
        }

        .form-group img {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .btn-submit, .btn-cancel, .btn-delete {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            grid-column: span 2;
            text-align: center;
        }

        .btn-submit {
            background-color: #fff;
            color: #000;
        }

        .btn-submit:hover {
            background-color: #ccc;
        }

        .btn-cancel {
            background-color: #ff4500;
            color: #fff;
        }

        .btn-cancel:hover {
            background-color: #ff6347;
        }

        .btn-delete {
            background-color: #dc143c;
            color: #fff;
        }

        .btn-delete:hover {
            background-color: #ff0000;
        }
    </style>
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
                    <a href="perfil.php" class="nav-item nav-link"><img src="<?php echo htmlspecialchars($fotoPerfil); ?>" class="size2"></a>
                    <a href="perfil.php" class="nav-item nav-link">
                        <div id="username" name="username"> <?php echo htmlspecialchars($nombreCompleto); ?></div>
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <div id="div1">
        <a class="#div1 letras" href="detallesEvento.php?idEvento=<?php echo $idEvento; ?>"><span class="iconoflecha"><img src="images/flecha.png" alt="flecha" class="iconoflecha"></span> Regresar</a>
    </div>

    <div class="principal">
        <div class="form-container">
            <form action="conexionEditarAct.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="idActividad" value="<?php echo $idActividad; ?>">
                <input type="hidden" name="idEvento" value="<?php echo $idEvento; ?>">
                <h2 class="datoseventotitulo">Editar Actividad</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
                    </div>
                    <div class="form-group full-width">
                        <label for="imagen">Selecciona una imagen:</label>
                        <input type="file" id="imagen" name="imagen" accept="image/*" onchange="mostrarImagen(this)">
                        <img id="vista-previa-imagen" src="<?php echo htmlspecialchars($imagen); ?>" alt="Vista previa de imagen" name="imgEvento">
                    </div>
                    <div class="form-group full-width">
                        <label for="descripcion">Descripción:</label>
                        <textarea id="descripcion" name="descripcion" rows="4" required><?php echo htmlspecialchars($descripcion); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="fecha_inicio">Fecha Inicio:</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_fin">Fecha Fin:</label>
                        <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="hora_inicio">Hora de inicio:</label>
                        <input type="time" id="hora_inicio" name="hora_inicio" value="<?php echo htmlspecialchars($hora_inicio); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="hora_fin">Hora de fin:</label>
                        <input type="time" id="hora_fin" name="hora_fin" value="<?php echo htmlspecialchars($hora_fin); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="modalidad">Modalidad:</label>
                        <select id="modalidad" name="modalidad" required>
                            <option value="">Seleccionar..</option>
                            <option value="Presencial" <?php echo ($modalidad == 'Presencial') ? 'selected' : ''; ?>>Presencial</option>
                            <option value="Virtual" <?php echo ($modalidad == 'Virtual') ? 'selected' : ''; ?>>Virtual</option>
                            <option value="Presencial y Virtual" <?php echo ($modalidad == 'Presencial y Virtual') ? 'selected' : ''; ?>>Presencial y Virtual</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="lugar">Lugar:</label>
                        <input type="text" id="lugar" name="lugar" value="<?php echo htmlspecialchars($lugar); ?>" required>
                    </div>
                    <button type="submit" class="btn-submit">Guardar</button>
                    <a href="detallesEvento.php?idEvento=<?php echo $idEvento; ?>" class="btn-cancel">Cancelar</a>
                    <a href="deleteActividad.php?idActividad=<?php echo $idActividad; ?>&idEvento=<?php echo $idEvento; ?>" class="btn-delete" onclick="return confirm('¿Estás seguro de que deseas eliminar esta actividad?');">Eliminar</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function mostrarImagen(input) {
            const vistaPrevia = document.getElementById('vista-previa-imagen');
            if (input.files && input.files[0]) {
                const lector = new FileReader();
                lector.onload = function(e) {
                    vistaPrevia.src = e.target.result;
                }
                lector.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
