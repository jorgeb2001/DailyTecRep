<?php
session_start();  // Iniciar la sesión PHP

if (!isset($_SESSION['email'])) {
    // Si no hay sesión de usuario, redirigir al login
    header("Location: Login.php");
    exit();
}

// Si hay sesión, recuperar ID del usuario
$id = $_SESSION['id'];

// Incluir el archivo de conexión a la base de datos
include('conexion.php');

// Función para obtener el ID de la carrera basada en su nombre
function obtenerIdCarrera($nombreCarrera, $con) {
    $query = "SELECT idCarrera FROM carrera WHERE nombre = ?";
    $params = array($nombreCarrera);
    $stmt = sqlsrv_query($con, $query, $params);
    
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmt);
    
    return $row ? $row['idCarrera'] : null;
}

// Obtener datos del formulario
$nom = $_POST["nombre"];
$des = $_POST["descripcion"];
$fecha_inicio = $_POST["fecha_inicio"];
$fecha_fin = $_POST["fecha_fin"];
$carreraNombre = $_POST["carrera"];  // Obtener el nombre de la carrera
$cred = $_POST["hidden-creditos"];
$tipo_evento = $_POST["tipo_evento"];  // Obtener el valor real del combobox de tipo_evento

// Obtener el ID de la carrera basada en el nombre
$carreraId = obtenerIdCarrera($carreraNombre, $con);

if ($carreraId === null) {
    echo "Carrera no encontrada";
    exit();
}

// Verificar si se ha enviado un archivo
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    // Ruta donde se almacenarán las imágenes subidas
    $upload_dir = "uploads/";

    // Obtener información del archivo subido
    $file_name = $_FILES['imagen']['name'];
    $file_tmp = $_FILES['imagen']['tmp_name'];

    // Mover el archivo subido al directorio de carga
    if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
        // La imagen se ha subido correctamente, guardamos la ruta en la base de datos
        $banner_url = $upload_dir . $file_name;
    } else {
        echo "Error al subir la imagen";
        exit();
    }
} else {
    echo "No se ha seleccionado ninguna imagen o ocurrió un error durante la carga";
    exit();
}

// Preparar la consulta SQL para insertar el evento en la base de datos
$query = "INSERT INTO Evento (nombre, descripcion, fecha_inicio, fecha_fin, liberacion_creditos, fk_carrera, fk_creador, banner, tipo_evento) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Parámetros para la consulta
$params = array($nom, $des, $fecha_inicio, $fecha_fin, $cred, $carreraId, $id, $banner_url, $tipo_evento);

// Ejecutar la consulta
$res = sqlsrv_query($con, $query, $params);

if ($res) {
    // Redirigir a index.php con un mensaje de éxito
    header("Location: index.php?message=Evento%20creado%20con%20exito");
    exit();
} else {
    // Mostrar errores si la consulta falla
    $errors = sqlsrv_errors();
    echo "Error al insertar: " . print_r($errors, true);
}
?>
