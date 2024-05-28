<?php
include('auth.php');
include('conexion.php');

// Obtener los datos del formulario
$idEvento = $_POST['id'];
$nombre = $_POST['nombre'];
$creditos = $_POST['hidden-creditos'];
$descripcion = $_POST['descripcion'];
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];
$carrera = $_POST['carrera'];  // Aquí obtenemos el nombre de la carrera
$tipo_evento = $_POST['tipo_evento'];  // Aquí obtenemos el valor del tipo de evento

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

// Obtener el ID de la carrera
$carreraId = obtenerIdCarrera($carrera, $con);
if ($carreraId === null) {
    echo "Carrera no encontrada";
    exit();
}

// Manejar la carga de la imagen
$banner = $_POST['banner'];
if ($_FILES['imagen']['size'] > 0) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["imagen"]["name"]);
    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
        $banner = $target_file;
    } else {
        echo "Error al subir la imagen";
        exit();
    }
}

// Preparar y ejecutar la consulta SQL para actualizar el evento
$query = "UPDATE Evento SET nombre = ?, liberacion_creditos = ?, descripcion = ?, fecha_inicio = ?, fecha_fin = ?, fk_carrera = ?, tipo_evento = ?, banner = ? WHERE idEvento = ?";
$params = array($nombre, $creditos, $descripcion, $fecha_inicio, $fecha_fin, $carreraId, $tipo_evento, $banner, $idEvento);
$stmt = sqlsrv_query($con, $query, $params);

if ($stmt) {
    header("Location: index.php.");
} else {
    echo "Error al actualizar el evento.";
    print_r(sqlsrv_errors(), true);
}
?>
