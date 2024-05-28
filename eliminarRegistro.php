<?php
ob_start(); // Inicia el almacenamiento en búfer de salidas

// Verificar si se recibió el parámetro "no_control" en la URL
if (isset($_GET['no_control'])) {
    // Obtener el número de control de la URL y realizar la sanitización si es necesario
    $no_control = $_GET['no_control'];

    // Aquí va tu código para conectar a la base de datos y ejecutar la consulta para eliminar el registro
    include('conexion.php'); // Asegúrate de incluir tu archivo de conexión

    // Iniciar una transacción
    sqlsrv_begin_transaction($con);

    try {
        // Obtener el idUsuario basado en no_control
        $query = "SELECT idUsuario FROM Usuario WHERE no_control = ?";
        $stmt = sqlsrv_prepare($con, $query, array($no_control));
        sqlsrv_execute($stmt);
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $idUsuario = $row['idUsuario'];

        // Eliminar registros relacionados en la tabla Asistencia
        $query = "DELETE FROM Asistencia WHERE fk_alumno = ?";
        $stmt = sqlsrv_prepare($con, $query, array($idUsuario));
        sqlsrv_execute($stmt);

        // Eliminar registros relacionados en la tabla Actividad
        $query = "DELETE FROM Actividad WHERE fk_creador = ?";
        $stmt = sqlsrv_prepare($con, $query, array($idUsuario));
        sqlsrv_execute($stmt);

        // Finalmente, eliminar el registro en la tabla Usuario
        $query = "DELETE FROM Usuario WHERE idUsuario = ?";
        $stmt = sqlsrv_prepare($con, $query, array($idUsuario));
        sqlsrv_execute($stmt);

        // Confirmar la transacción
        sqlsrv_commit($con);
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        sqlsrv_rollback($con);
        echo "Error al intentar eliminar el registro: " . $e->getMessage();
    }

    // Redirigir de vuelta a la página de gestión después de eliminar el registro
    header("Location: gestU.php");
    exit();
} else {
    // Si no se proporcionó el parámetro "no_control", redirigir a una página de error o a la página de gestión
    header("Location: gestU.php");
    exit();
}
ob_end_flush(); // Enviar el contenido del búfer de salida y desactivarlo
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
</head>
<body>
</body>
</html>
