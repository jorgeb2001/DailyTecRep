<?php
// Verificar si se recibió el parámetro "no_control" en la URL
if (isset($_GET['no_control'])) {
    // Obtener el número de control de la URL y realizar la sanitización si es necesario
    $no_control = $_GET['no_control'];

    // Aquí va tu código para conectar a la base de datos y ejecutar la consulta para eliminar el registro
    include('conexion.php'); // Asegúrate de incluir tu archivo de conexión

    // Consulta SQL para eliminar el registro basado en el número de control
    $query = "DELETE FROM usuario WHERE no_control = ?";
    $stmt = sqlsrv_prepare($con, $query, array($no_control));

    if ($stmt && sqlsrv_execute($stmt)) {
        echo "El registro ha sido eliminado correctamente.";
    } else {
        echo "Error al intentar eliminar el registro.";
    }

    // Redirigir de vuelta a la página de gestión después de eliminar el registro
    header("Location: gestionU.php");
    exit();
} else {
    // Si no se proporcionó el parámetro "no_control", redirigir a una página de error o a la página de gestión
    header("Location: gestionU.php");
    exit();
}
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
