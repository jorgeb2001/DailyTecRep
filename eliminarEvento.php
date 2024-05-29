<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Evento Eliminado</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="description" name="Free HTML Templates">
</head>
<body>
    <p>Redirigiendo a la página principal...</p>
</body>
</html>

<?php
include('auth.php');
include('conexion.php');

$idEvento = $_GET['id'];

// Función para manejar errores y redirigir en caso de fallo
function manejarError($stmt, $con) {
    if ($stmt === false) {
        // Obtener y mostrar errores de SQL Server
        if (($errors = sqlsrv_errors()) != null) {
            foreach ($errors as $error) {
                echo "SQLSTATE: " . $error['SQLSTATE'] . "<br>";
                echo "code: " . $error['code'] . "<br>";
                echo "message: " . $error['message'] . "<br>";
            }
        }
        sqlsrv_close($con);
        header("Location: index.php");
        exit;
    }
}

// Eliminar las asistencias relacionadas con las actividades del evento
$queryAsistencias = "DELETE FROM Asistencia WHERE fk_actividad IN (SELECT idActividad FROM Actividad WHERE fk_evento = ?)";
$paramsAsistencias = array($idEvento);
$stmtAsistencias = sqlsrv_query($con, $queryAsistencias, $paramsAsistencias);
manejarError($stmtAsistencias, $con);

// Eliminar las actividades relacionadas
$queryActividades = "DELETE FROM Actividad WHERE fk_evento = ?";
$paramsActividades = array($idEvento);
$stmtActividades = sqlsrv_query($con, $queryActividades, $paramsActividades);
manejarError($stmtActividades, $con);

// Eliminar el evento
$queryEvento = "DELETE FROM Evento WHERE idEvento = ?";
$paramsEvento = array($idEvento);
$stmtEvento = sqlsrv_query($con, $queryEvento, $paramsEvento);
manejarError($stmtEvento, $con);

sqlsrv_close($con);
header("Location: index.php");
exit;
?>

