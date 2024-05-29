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
	$servername = 'dailytecserver.database.windows.net';
	$conexion = array(
		"Database" => "DailyTecDB",
		"UID" => 'DailyTec',
		"PWD" => 'Lagoblue_1',
		"CharacterSet" => "UTF-8"
	);

	$con = sqlsrv_connect($servername, $conexion);
	if ($con) {
		echo "";
	} else {
		echo "Fallo en la conexión";
	}
?>
