<?php
ob_start(); // Inicia el almacenamiento en búfer de salidas

// Verificar si se recibió el parámetro "no_control" en la URL
if (isset($_GET['no_control'])) {
    // Obtener el número de control de la URL y realizar la sanitización
    $no_control = filter_var($_GET['no_control'], FILTER_SANITIZE_STRING);

    // Incluir el archivo de conexión a la base de datos
    include('conexion.php');

    // Iniciar una transacción
    sqlsrv_begin_transaction($con);

    try {
        // Obtener el idUsuario basado en no_control
        $query = "SELECT idUsuario FROM Usuario WHERE no_control = ?";
        $stmt = sqlsrv_prepare($con, $query, array($no_control));
        if (!$stmt) {
            throw new Exception('Error preparando la consulta: ' . print_r(sqlsrv_errors(), true));
        }
        sqlsrv_execute($stmt);
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if (!$row) {
            throw new Exception('Usuario no encontrado.');
        }
        $idUsuario = $row['idUsuario'];

        // Eliminar registros relacionados en la tabla Asistencia
        $query = "DELETE FROM Asistencia WHERE fk_alumno = ?";
        $stmt = sqlsrv_prepare($con, $query, array($idUsuario));
        if (!$stmt) {
            throw new Exception('Error preparando la consulta de eliminación de Asistencia: ' . print_r(sqlsrv_errors(), true));
        }
        sqlsrv_execute($stmt);

        // Eliminar registros relacionados en la tabla Actividad
        $query = "DELETE FROM Actividad WHERE fk_creador = ?";
        $stmt = sqlsrv_prepare($con, $query, array($idUsuario));
        if (!$stmt) {
            throw new Exception('Error preparando la consulta de eliminación de Actividad: ' . print_r(sqlsrv_errors(), true));
        }
        sqlsrv_execute($stmt);

        // Eliminar registros relacionados en la tabla Alumno
        $query = "DELETE FROM Alumno WHERE fk_usuario = ?";
        $stmt = sqlsrv_prepare($con, $query, array($idUsuario));
        if (!$stmt) {
            throw new Exception('Error preparando la consulta de eliminación de Alumno: ' . print_r(sqlsrv_errors(), true));
        }
        sqlsrv_execute($stmt);

        // Finalmente, eliminar el registro en la tabla Usuario
        $query = "DELETE FROM Usuario WHERE idUsuario = ?";
        $stmt = sqlsrv_prepare($con, $query, array($idUsuario));
        if (!$stmt) {
            throw new Exception('Error preparando la consulta de eliminación de Usuario: ' . print_r(sqlsrv_errors(), true));
        }
        sqlsrv_execute($stmt);

        // Confirmar la transacción
        sqlsrv_commit($con);

        // Redirigir de vuelta a la página de gestión después de eliminar el registro con mensaje de éxito
        header("Location: gestU.php?msg=success");
        exit();
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        sqlsrv_rollback($con);
        header("Location: gestU.php?msg=error&error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Si no se proporcionó el parámetro "no_control", redirigir a una página de error o a la página de gestión
    header("Location: gestU.php?msg=error&error=missing_param");
    exit();
}
ob_end_flush(); // Enviar el contenido del búfer de salida y desactivarlo
?>
