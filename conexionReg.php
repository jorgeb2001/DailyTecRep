<?php
include("conexion.php");

$nom = $_POST["nombre"];
$apP = $_POST["apellidoP"];
$apM = $_POST["apellidoM"];
$correo = $_POST["correo"];
$noCtl = $_POST["noCtl"];
$contra = $_POST["contra"];
$index = $_POST["carreraIndex"];

// Query para insertar el usuario
$queryUsuario = "INSERT INTO Usuario (no_control, correo, contraseña, nombre, apellido_paterno, apellido_materno, fk_acceso) 
                 VALUES (?, ?, ?, ?, ?, ?, 3)";
$paramsUsuario = array($noCtl, $correo, $contra, $nom, $apP, $apM);
$resUsuario = sqlsrv_query($con, $queryUsuario, $paramsUsuario);

// Verificar si la inserción del usuario fue exitosa
if ($resUsuario) {
    // Preparar la llamada al procedimiento almacenado InsertarAlumno
    $procedureName = "{call InsertarAlumno (?, ?)}";
    $stmt = sqlsrv_prepare($con, $procedureName, array(&$noCtl, &$index));
    // Verificar si la preparación de la llamada fue exitosa
    if ($stmt) {
        // Ejecutar la consulta preparada
        $resAlumno = sqlsrv_execute($stmt);
        
        // Verificar si la ejecución de la consulta preparada fue exitosa
        if ($resAlumno) {
            // Redirigir a index.php si todo ha ido bien
            header("Location: indexE.php");
            exit(); // Detener el script para evitar cualquier salida adicional
        }
    }
}

// Si hay un error al insertar los datos del usuario o del alumno, imprimir el mensaje de error
$errors = sqlsrv_errors();
if ($errors !== null) {
	header("Location: indexE.php");
            exit(); // Detener el script para evitar cualquier salida adicional
    die("Error al insertar los datos. Detalles del error: " . print_r($errors, true));
}
?>
