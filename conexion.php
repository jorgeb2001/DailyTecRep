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
