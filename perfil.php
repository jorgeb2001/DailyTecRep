<?php
include('auth.php');
    include('conexion.php');
include('conexion.php');

$id = $_SESSION['id'];

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['imagen']['tmp_name'])) {
    $rutaImagen = 'profiles/' . $_FILES['imagen']['name'];

    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaImagen)) {
        $queryActualizarImagen = "UPDATE usuario SET foto_perfil = ? WHERE idUsuario = ?";
        $stmt = sqlsrv_prepare($con, $queryActualizarImagen, array($rutaImagen, $id));

        if ($stmt && sqlsrv_execute($stmt)) {
            // La imagen se ha guardado correctamente en la base de datos
        } else {
            // Error al actualizar la ruta de la imagen en la base de datos
        }
    } else {
        // Error al mover la imagen al directorio de destino
    }
} else {
    // Código para manejar el caso en que no se haya enviado una imagen
}

$queryuser = "SELECT U.nombre, U.apellido_paterno, U.apellido_materno, U.correo, nv.rol, U.no_control, C.nombre AS carrera, U.foto_perfil
    FROM usuario U
    INNER JOIN Alumno A ON A.fk_usuario = U.idUsuario
    INNER JOIN CARRERA C ON C.idCarrera = A.fk_carrera
    INNER JOIN NvAcceso NV ON NV.nvAcceso = U.fk_acceso
    WHERE U.idUsuario = ?;";

$stmt = sqlsrv_prepare($con, $queryuser, array($id));

if ($stmt && sqlsrv_execute($stmt)) {
    if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $nombreUsuario = $row['nombre'];
        $apellidoPaterno = $row['apellido_paterno'];
        $apellidoMaterno = $row['apellido_materno'];
        $nombreCompleto = $nombreUsuario . ' ' . $apellidoPaterno;
        $correo = $row['correo'];
        $rol = $row['rol'];
        $nctrl = $row['no_control'];
        $carrera = $row['carrera'];
        $fotoPerfil = $row['foto_perfil']; // Ruta de la foto de perfil
    } else {
        $nombreCompleto = "Usuario Desconocido";
    }
} else {
    $nombreCompleto = "Error al recuperar el nombre de usuario";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Perfil de Usuario</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">  

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <style>
        body {
    font-family: 'Montserrat', sans-serif;
    background-color: #121212; /* Color de fondo negro */
    color: #f0f0f0; /* Color de texto blanco/gris claro */
}

.navbar {
    background-color: #1f1f1f; /* Color de fondo de la barra de navegación negro más claro */
    margin-bottom: 30px;
}

.profile-container {
    max-width: 600px;
    margin: 0 auto;
    background-color: #1f1f1f; /* Fondo del contenedor negro más claro */
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* Sombra con más opacidad */
    text-align: center;
}

.profile-container h2 {
    margin-bottom: 20px;
    color: #ff6600; /* Título en naranja */
}

.profile-container img {
    border-radius: 50%;
    margin-bottom: 20px;
    width: 150px;
    height: 150px;
    object-fit: cover;
    border: 3px solid #ff6600; /* Borde de imagen en naranja */
}

.profile-container .form-group {
    margin-bottom: 20px;
}

.profile-container .form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
    color: #ff6600; /* Etiquetas en naranja */
}

.profile-container .form-group input[type="file"] {
    display: block;
    margin: 0 auto;
    color: #f0f0f0; /* Texto blanco/gris claro */
}

.profile-container .form-group button {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #ff6600; /* Botón en naranja */
    color: #fff; /* Texto del botón en blanco */
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.profile-container .form-group button:hover {
    background-color: #cc5200; /* Botón en naranja más oscuro al pasar el ratón */
}

.profile-details {
    text-align: left;
}

.profile-details .detail {
    margin-bottom: 15px;
}

.profile-details .detail span {
    display: block;
    font-weight: bold;
    color: #ff6600; /* Texto en naranja */
}
    </style>
</head>

<body>
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-2 py-lg-0 px-lg-5"> 
        <a class="nav-item active"><img src="images/escudo.png" class="size"></a>
        <a href="index.html" class="nav-item active"><img src="images/dailytec.jfif" class="size"></a> 
        <div class="navbar-nav mr-auto py-0">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item nav-link active"><a class="nav-link" href="index.php">EVENTOS</a></li>
                <li class="nav-item nav-link active"><a class="nav-link" href="historial.php">HISTORIAL</a></li>
                <li class="nav-item dropdown nav-link active">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">Perfil</a>
                    <div class="dropdown-menu rounded-0 m-0">
                        <a href="perfil.php" class="dropdown-item">Mi perfil</a>
                        <a href="gestU.php" class="dropdown-item">Gestionar Usuarios</a>
                        <a href="Login.php" class="dropdown-item">Cerrar Sesión</a>
                    </div>
                </li>
                <li><a href="perfil.php" class="nav-item nav-link"><img src="<?php echo $fotoPerfil ?>" class="size2"></a></li>
            </ul>
        </div>
        <div>&nbsp; <?php echo htmlspecialchars($nombreCompleto); ?></div>
        <form class="form-inline my-2 my-lg-0">
            <div class="input-group ml-auto d-none d-lg-flex nav-link nav-item active" style="width: 100%; max-width: 300px;">
                <a class="nav-link dropdown-toggle fa fa-search" data-toggle="dropdown">&nbsp; Buscar</a>
                <div class="dropdown-menu rounded-0 m-0">
                    <a href="#micarrera" class="dropdown-item">Mi carrera</a>
                    <a href="#creds" class="dropdown-item">Libera Créditos</a>
                    <a href="#oficial" class="dropdown-item">Eventos oficiales</a>
                    <a href="#extra" class="dropdown-item">Extraescolares</a>
                    <a href="#tutos" class="dropdown-item">Tutorías</a>
                    <a href="#jornada" class="dropdown-item">Jornada Académica</a>
                    <a href="#vinc" class="dropdown-item">Vinculación</a>
                </div>
            </div>
        </form>
    </nav>
    <!-- Navbar End -->

    <div class="profile-container">
        <h2>Perfil de Usuario</h2>
        <img src="<?php echo $fotoPerfil; ?>" alt="Foto de Perfil">
        <form action="perfil.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="imagen">Subir Imagen:</label>
                <input type="file" id="imagen" name="imagen" accept="image/*" onchange="mostrarImagen(this)">
            </div>
            <div class="form-group">
                <button type="submit">Subir Imagen</button>
            </div>
        </form>
        <div class="profile-details">
            <div class="detail">
                <span>Nombre:</span> <?php echo htmlspecialchars($nombreCompleto); ?>
            </div>
            <div class="detail">
                <span>Correo:</span> <?php echo htmlspecialchars($correo); ?>
            </div>
            <div class="detail">
                <span>Rol:</span> <?php echo htmlspecialchars($rol); ?>
            </div>
            <div class="detail">
                <span>Carrera:</span> <?php echo htmlspecialchars($carrera); ?>
            </div>
            <div class="detail">
                <span>Número de Control:</span> <?php echo htmlspecialchars($nctrl); ?>
            </div>
        </div>
    </div>

    <script>
        function mostrarImagen(input) {
            const vistaPrevia = document.querySelector('.profile-container img');
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
