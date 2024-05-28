<?php
include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $control = $_POST['control'];
    $tipo = $_POST['tipo'];
    $fk_actividad = $_POST['fk_actividad'];

    // Validar que el usuario exista
    $query = "SELECT idUsuario, nombre FROM Usuario WHERE no_control = ?";
    $params = array($control);
    $result = sqlsrv_query($con, $query, $params);

    if ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $fk_usuario = $row['idUsuario'];
        $nombre_usuario = $row['nombre'];

        // Verificar si ya existe un registro de asistencia para este usuario y actividad
        $query = "SELECT * FROM Asistencia WHERE fk_alumno = ? AND fk_actividad = ?";
        $params = array($fk_usuario, $fk_actividad);
        $result = sqlsrv_query($con, $query, $params);

        $mensaje = '';

        if ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            // Actualizar la asistencia existente
            if ($tipo === 'entrada') {
                $query = "UPDATE Asistencia SET entrada = 1 WHERE fk_alumno = ? AND fk_actividad = ?";
                $mensaje = 'Entrada registrada correctamente';
            } else {
                $query = "UPDATE Asistencia SET salida = 1 WHERE fk_alumno = ? AND fk_actividad = ?";
                $mensaje = 'Salida registrada correctamente';
            }
            $params = array($fk_usuario, $fk_actividad);
        } else {
            // Insertar nueva asistencia
            if ($tipo === 'entrada') {
                $query = "INSERT INTO Asistencia (fk_alumno, fk_actividad, entrada) VALUES (?, ?, 1)";
                $mensaje = 'Entrada registrada correctamente';
            } else {
                $query = "INSERT INTO Asistencia (fk_alumno, fk_actividad, salida) VALUES (?, ?, 1)";
                $mensaje = 'Salida registrada correctamente';
            }
            $params = array($fk_usuario, $fk_actividad);
        }

        $result = sqlsrv_query($con, $query, $params);

        // Actualizar el campo 'asistencia' si entrada y salida son 1
        $query = "UPDATE Asistencia SET asistencia = 1 WHERE fk_alumno = ? AND fk_actividad = ? AND entrada = 1 AND salida = 1";
        $params = array($fk_usuario, $fk_actividad);
        sqlsrv_query($con, $query, $params);

        // Obtener los registros de asistencia actualizados
        $query = "SELECT A.fk_actividad, U.no_control, U.nombre, A.entrada, A.salida 
                  FROM Asistencia A 
                  INNER JOIN Usuario U ON A.fk_alumno = U.idUsuario 
                  WHERE A.fk_actividad = ?";
        $params = array($fk_actividad);
        $result = sqlsrv_query($con, $query, $params);

        $asistencias = [];
        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $asistencias[] = [
                'id' => $row['fk_actividad'],
                'control' => $row['no_control'],
                'nombre' => $row['nombre'],
                'entrada' => $row['entrada'] ? 'Sí' : 'No',
                'salida' => $row['salida'] ? 'Sí' : 'No'
            ];
        }

        echo json_encode(['success' => true, 'message' => $mensaje, 'data' => $asistencias]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Número de control no encontrado']);
    }

    sqlsrv_close($con);
}
?>
