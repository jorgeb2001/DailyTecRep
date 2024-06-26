<?php
include('auth.php');
include('conexion.php');

$idEvento = $_GET['id'];

// Eliminar las asistencias relacionadas con las actividades del evento
$queryAsistencias = "DELETE FROM Asistencia WHERE fk_actividad IN (SELECT idActividad FROM Actividad WHERE fk_evento = ?)";
$paramsAsistencias = array($idEvento);
$stmtAsistencias = sqlsrv_query($con, $queryAsistencias, $paramsAsistencias);

if ($stmtAsistencias === false) {
    header("Location: index.php);
    exit();
}

// Eliminar las actividades relacionadas
$queryActividades = "DELETE FROM Actividad WHERE fk_evento = ?";
$paramsActividades = array($idEvento);
$stmtActividades = sqlsrv_query($con, $queryActividades, $paramsActividades);

if ($stmtActividades === false) {
    header("Location: index.php);
    exit();
}

// Eliminar el evento
$queryEvento = "DELETE FROM Evento WHERE idEvento = ?";
$paramsEvento = array($idEvento);
$stmtEvento = sqlsrv_query($con, $queryEvento, $paramsEvento);

if ($stmtEvento === false) {
   header("Location: index.php);
    exit();
}

?>
