<?php
	$servername = 'LAPTOP-S757GQ2H\DAILYTECDB';
	$conexion = array(
		"Database" => "DailyTecDB",
		"UID" => 'sa',
		"PWD" => '246802',
		"CharacterSet" => "UTF-8"
	);

	$con = sqlsrv_connect($servername, $conexion);
	if ($con) {
		echo "";
	} else {
		echo "Fallo en la conexión";
	}
?>