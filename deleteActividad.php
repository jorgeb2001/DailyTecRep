<?php
include('auth.php');
include('conexion.php');

$idActividad = intval($_GET['idActividad']);
$idEvento = intval($_GET['idEvento']);

try {
    // Eliminar asistencia de la actividad
    $queryAsistencia = "DELETE FROM Asistencia WHERE fk_actividad = ?";
    $paramsAsistencia = array($idActividad);
    $stmtAsistencia = sqlsrv_prepare($con, $queryAsistencia, $paramsAsistencia);

    if (!$stmtAsistencia) {
        throw new Exception("Error al preparar la consulta para eliminar la asistencia: " . print_r(sqlsrv_errors(), true));
    }

    if (!sqlsrv_execute($stmtAsistencia)) {
        throw new Exception("Error al eliminar la asistencia: " . print_r(sqlsrv_errors(), true));
    }

    // Si se elimina la asistencia correctamente, eliminar la actividad
    $queryActividad = "DELETE FROM Actividad WHERE idActividad = ?";
    $paramsActividad = array($idActividad);
    $stmtActividad = sqlsrv_prepare($con, $queryActividad, $paramsActividad);

    if (!$stmtActividad) {
        throw new Exception("Error al preparar la consulta para eliminar la actividad: " . print_r(sqlsrv_errors(), true));
    }

    if (!sqlsrv_execute($stmtActividad)) {
        throw new Exception("Error al eliminar la actividad: " . print_r(sqlsrv_errors(), true));
    }

    // Redirigir a detallesEvento.php si todo se elimina correctamente
    header("Location: detallesEvento.php?idEvento=" . $idEvento);
    exit();
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    sqlsrv_close($con);
}
?>
