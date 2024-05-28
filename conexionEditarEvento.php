<?php
    // Incluir el archivo de conexión a la base de datos
    include('conexion.php');

    // Verificar si se ha enviado el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recibir los datos editados del formulario
        $idEvento = $_POST['idEvento'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        // Otros campos del formulario...

        // Actualizar el registro del evento en la base de datos
        $query = "UPDATE evento SET nombre = ?, descripcion = ? WHERE idEvento = ?";
        $params = array(&$nombre, &$descripcion, &$idEvento);
        $stmt = sqlsrv_prepare($con, $query, $params);

        if ($stmt && sqlsrv_execute($stmt)) {
            // Los datos se actualizaron correctamente
            // Redireccionar a la página de detalles del evento
            header("Location: detallesEvento.php?idEvento=$idEvento");
            exit(); // Terminar el script después de la redirección
        } else {
            // Error al ejecutar la consulta
            echo "Error al guardar los cambios: " . print_r(sqlsrv_errors(), true);
        }
    }

    // Cerrar la conexión a la base de datos
    sqlsrv_close($con);
?>




