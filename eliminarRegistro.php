<?php
// Verificar si se recibió el parámetro "no_control" en la URL
if (isset($_GET['no_control'])) {
    // Obtener el número de control de la URL y realizar la sanitización si es necesario
    $no_control = htmlspecialchars($_GET['no_control'], ENT_QUOTES, 'UTF-8');

    // Aquí va tu código para conectar a la base de datos y ejecutar la consulta para eliminar el registro
    include('conexion.php'); // Asegúrate de incluir tu archivo de conexión

    // Consulta SQL para eliminar el registro basado en el número de control
    $query = "DELETE FROM usuario WHERE no_control = ?";
    $stmt = sqlsrv_prepare($con, $query, array($no_control));

    if ($stmt) {
        if (sqlsrv_execute($stmt)) {
            echo "El registro ha sido eliminado correctamente.";
        } else {
            echo "Error al intentar eliminar el registro: " . print_r(sqlsrv_errors(), true);
        }
    } else {
        echo "Error al preparar la consulta SQL: " . print_r(sqlsrv_errors(), true);
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
