<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: Login.php");
    exit();
}

$id = $_SESSION['id'];
include('conexion.php');

$queryuser = "SELECT nombre, apellido_paterno, fk_acceso, foto_perfil FROM usuario WHERE idUsuario = ?";
$stmt = sqlsrv_prepare($con, $queryuser, array($id));

if ($stmt && sqlsrv_execute($stmt)) {
    if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $nombreUsuario = $row['nombre'];
        $apellidoPaterno = $row['apellido_paterno'];
        $nombreCompleto = $nombreUsuario . ' ' . $apellidoPaterno;
        $nvAcceso = $row['fk_acceso'];
        $fotoPerfil = $row['foto_perfil'] ? $row['foto_perfil'] : 'images/default-profile.png';

        if ($nvAcceso != 1) {
            header("Location: Login.php");
            exit();
        }
    } else {
        $nombreCompleto = "Usuario Desconocido";
        header("Location: Login.php");
        exit();
    }
} else {
    $nombreCompleto = "Error al recuperar el nombre de usuario";
    header("Location: Login.php");
    exit();
}

// Form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars(trim($_POST["nombre"]));
    $des = htmlspecialchars(trim($_POST["descripcion"]));
    $modalidad = htmlspecialchars(trim($_POST["modalidad"]));
    $lugar = htmlspecialchars(trim($_POST["lugar"]));
    $fecha_inicio = htmlspecialchars(trim($_POST["fecha_inicio"]));
    $fecha_fin = htmlspecialchars(trim($_POST["fecha_fin"]));
    $hora_inicio = htmlspecialchars(trim($_POST["hora_inicio"]));
    $hora_fin = htmlspecialchars(trim($_POST["hora_fin"]));
    $idEvento = intval($_POST['idEvento']); // Obtener ID del evento del campo oculto del formulario

    // Validar modalidad
    $allowed_modalidades = ['Presencial', 'Virtual', 'Presencial y Virtual'];
    if (!in_array($modalidad, $allowed_modalidades)) {
        echo "Modalidad no permitida.";
        exit();
    }

    // Manejo de imagen
    $imagen_nombre = $_FILES['imagen']['name'];
    $imagen_temporal = $_FILES['imagen']['tmp_name'];
    $imagen_destino = 'uploads/' . $imagen_nombre;

    if (move_uploaded_file($imagen_temporal, $imagen_destino)) {
        // Imagen subida con éxito
    } else {
        echo "Error al cargar la imagen.";
        exit();
    }

    // Preparar y ejecutar consulta SQL
    $query = "INSERT INTO Actividad (nombre, descripcion, imagen, modalidad, lugar, fecha_inicio, fecha_fin, hora_inicio, hora_fin, fk_evento, fk_creador) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $paramsActividad = array($nom, $des, $imagen_destino, $modalidad, $lugar, $fecha_inicio, $fecha_fin, $hora_inicio, $hora_fin, $idEvento, $id);
    $res = sqlsrv_prepare($con, $query, $paramsActividad);

    if ($res) {
        if (sqlsrv_execute($res)) {
            header("Location: detallesEvento.php?idEvento=" . $idEvento);
            exit();
        } else {
            $errors = sqlsrv_errors();
            echo "Error al insertar: " . print_r($errors, true);
        }
    } else {
        $errors = sqlsrv_errors();
        echo "Error al preparar la consulta: " . print_r($errors, true);
    }
}
?>
