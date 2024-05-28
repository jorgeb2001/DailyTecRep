<?php

include('auth.php');
    include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idActividad = intval($_POST['idActividad']);
    $idEvento = intval($_POST['idEvento']);
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $descripcion = htmlspecialchars(trim($_POST['descripcion']));
    $modalidad = htmlspecialchars(trim($_POST['modalidad']));
    $lugar = htmlspecialchars(trim($_POST['lugar']));
    $fecha_inicio = htmlspecialchars(trim($_POST['fecha_inicio']));
    $fecha_fin = htmlspecialchars(trim($_POST['fecha_fin']));
    $hora_inicio = htmlspecialchars(trim($_POST['hora_inicio']));
    $hora_fin = htmlspecialchars(trim($_POST['hora_fin']));

    $imagen_nombre = $_FILES['imagen']['name'];
    $imagen_temporal = $_FILES['imagen']['tmp_name'];
    $imagen_destino = '';

    if ($imagen_nombre) {
        $imagen_destino = 'uploads/' . $imagen_nombre;
        if (!move_uploaded_file($imagen_temporal, $imagen_destino)) {
            echo "Error al cargar la imagen.";
            exit();
        }
    } else {
        $query = "SELECT imagen FROM Actividad WHERE idActividad = ?";
        $params = array($idActividad);
        $result = sqlsrv_query($con, $query, $params);
        if ($result && $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $imagen_destino = $row['imagen'];
        }
        sqlsrv_free_stmt($result);
    }

    $query = "UPDATE Actividad 
              SET nombre = ?, descripcion = ?, imagen = ?, modalidad = ?, lugar = ?, fecha_inicio = ?, fecha_fin = ?, hora_inicio = ?, hora_fin = ? 
              WHERE idActividad = ?";
    $params = array($nombre, $descripcion, $imagen_destino, $modalidad, $lugar, $fecha_inicio, $fecha_fin, $hora_inicio, $hora_fin, $idActividad);

    $stmt = sqlsrv_prepare($con, $query, $params);

    if ($stmt) {
        if (sqlsrv_execute($stmt)) {
            header("Location: detallesEvento.php?idEvento=" . $idEvento);
            exit();
        } else {
            echo "Error al actualizar la actividad: " . print_r(sqlsrv_errors(), true);
        }
    } else {
        echo "Error al preparar la consulta: " . print_r(sqlsrv_errors(), true);
    }
    sqlsrv_close($con);
}
?>
