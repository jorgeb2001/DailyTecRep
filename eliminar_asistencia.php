<?php
include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Consulta para eliminar el registro de asistencia
    $query = "DELETE FROM Asistencia WHERE fk_actividad = ? AND fk_alumno = ?";
    $params = array($id['fk_actividad'], $id['fk_alumno']);
    $result = sqlsrv_query($con, $query, $params);

    if ($result) {
        // Obtener los registros de asistencia actualizados
        $query = "SELECT A.fk_actividad, U.no_control, U.nombre, A.entrada, A.salida 
                  FROM Asistencia A 
                  INNER JOIN Usuario U ON A.fk_alumno = U.idUsuario 
                  WHERE A.fk_actividad = ?";
        $params = array($id['fk_actividad']);
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

        echo json_encode(['success' => true, 'data' => $asistencias]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el registro']);
    }

    sqlsrv_close($con);
}
?>
