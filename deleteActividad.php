<?php
	include('auth.php');
    include('conexion.php');

$idActividad = intval($_GET['idActividad']);
$idEvento = intval($_GET['idEvento']);

// Eliminar asistencia de la actividad
$queryAsistencia = "DELETE FROM Asistencia WHERE fk_actividad = ?";
$paramsAsistencia = array($idActividad);
$stmtAsistencia = sqlsrv_prepare($con, $queryAsistencia, $paramsAsistencia);

if ($stmtAsistencia) {
    if (sqlsrv_execute($stmtAsistencia)) {
        // Si se elimina la asistencia correctamente, eliminar la actividad
        $queryActividad = "DELETE FROM Actividad WHERE idActividad = ?";
        $paramsActividad = array($idActividad);
        $stmtActividad = sqlsrv_prepare($con, $queryActividad, $paramsActividad);

        if ($stmtActividad) {
            if (sqlsrv_execute($stmtActividad)) {
                header("Location: detallesEvento.php?idEvento=" . $idEvento);
                exit();
            } else {
                echo "Error al eliminar la actividad: " . print_r(sqlsrv_errors(), true);
            }
        } else {
            echo "Error al preparar la consulta para eliminar la actividad: " . print_r(sqlsrv_errors(), true);
        }
    } else {
        echo "Error al eliminar la asistencia: " . print_r(sqlsrv_errors(), true);
    }
} else {
    echo "Error al preparar la consulta para eliminar la asistencia: " . print_r(sqlsrv_errors(), true);
}

sqlsrv_close($con);
?>
