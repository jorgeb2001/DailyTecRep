<?php
include(__DIR__ . '/conexion.php');

if ($con) {
    $actividad_id = $_GET['idActividad'];

    // Obtener asistencias
    $queryAsistencias = "SELECT COUNT(*) AS total, SUM(CASE WHEN asistencia = 1 THEN 1 ELSE 0 END) AS asistieron 
                         FROM Asistencia 
                         WHERE fk_actividad = ?";
    $params = array($actividad_id);
    $resultAsistencias = sqlsrv_query($con, $queryAsistencias, $params);
    if (!$resultAsistencias) {
        echo json_encode(array('error' => 'Error en la consulta de asistencias: ' . print_r(sqlsrv_errors(), true)));
        exit;
    }
    $rowAsistencias = sqlsrv_fetch_array($resultAsistencias, SQLSRV_FETCH_ASSOC);

    $total = $rowAsistencias['total'] ?? 0;
    $asistieron = $rowAsistencias['asistieron'] ?? 0;
    $noAsistieron = $total - $asistieron;

    // Obtener asistencias por carrera
    $queryCarreras = "SELECT C.nombre, COUNT(*) AS total 
                      FROM Asistencia A
                      JOIN Alumno AL ON A.fk_alumno = AL.fk_usuario
                      JOIN Carrera C ON AL.fk_carrera = C.idCarrera
                      WHERE A.fk_actividad = ? AND A.asistencia = 1
                      GROUP BY C.nombre";
    $paramsCarreras = array($actividad_id);
    $resultCarreras = sqlsrv_query($con, $queryCarreras, $paramsCarreras);
    if (!$resultCarreras) {
        echo json_encode(array('error' => 'Error en la consulta de carreras: ' . print_r(sqlsrv_errors(), true)));
        exit;
    }

    $carreras = array('labels' => [], 'data' => []);
    $porcentajeAsistenciaPorCarrera = [];
    while ($rowCarreras = sqlsrv_fetch_array($resultCarreras, SQLSRV_FETCH_ASSOC)) {
        $carreras['labels'][] = $rowCarreras['nombre'];
        $carreras['data'][] = $rowCarreras['total'];

        // Calcular porcentaje de asistencia por carrera
        $totalPorCarrera = $rowCarreras['total'];
        $queryTotalCarrera = "SELECT COUNT(*) AS total 
                              FROM Alumno AL
                              JOIN Carrera C ON AL.fk_carrera = C.idCarrera
                              WHERE C.nombre = ?";
        $paramsTotalCarrera = array($rowCarreras['nombre']);
        $resultTotalCarrera = sqlsrv_query($con, $queryTotalCarrera, $paramsTotalCarrera);
        if (!$resultTotalCarrera) {
            echo json_encode(array('error' => 'Error en la consulta total de carrera: ' . print_r(sqlsrv_errors(), true)));
            exit;
        }
        $rowTotalCarrera = sqlsrv_fetch_array($resultTotalCarrera, SQLSRV_FETCH_ASSOC);
        $totalCarrera = $rowTotalCarrera['total'] ?? 1; // Evitar división por cero
        $porcentajeAsistencia = ($totalPorCarrera / $totalCarrera) * 100;
        $porcentajeAsistenciaPorCarrera[] = $porcentajeAsistencia;
    }

    // Obtener datos de los alumnos que asistieron
    $queryAlumnos = "SELECT U.nombre, U.apellido_paterno, U.apellido_materno, U.correo, U.no_control 
                     FROM Asistencia A
                     JOIN Usuario U ON A.fk_alumno = U.idUsuario
                     WHERE A.fk_actividad = ? AND A.asistencia = 1";
    $paramsAlumnos = array($actividad_id);
    $resultAlumnos = sqlsrv_query($con, $queryAlumnos, $paramsAlumnos);
    if (!$resultAlumnos) {
        echo json_encode(array('error' => 'Error en la consulta de alumnos: ' . print_r(sqlsrv_errors(), true)));
        exit;
    }

    $alumnos = array();
    while ($rowAlumnos = sqlsrv_fetch_array($resultAlumnos, SQLSRV_FETCH_ASSOC)) {
        $alumnos[] = $rowAlumnos;
    }

    sqlsrv_free_stmt($resultAsistencias);
    sqlsrv_free_stmt($resultCarreras);
    sqlsrv_free_stmt($resultAlumnos);
    sqlsrv_close($con);

    header('Content-Type: application/json'); // Asegurar que el contenido sea JSON
    echo json_encode(array(
        'asistieron' => $asistieron,
        'noAsistieron' => $noAsistieron,
        'carreras' => $carreras,
        'porcentajeAsistenciaPorCarrera' => $porcentajeAsistenciaPorCarrera,
        'alumnos' => $alumnos
    ));
} else {
    echo json_encode(array('error' => 'Error en la conexión: ' . print_r(sqlsrv_errors(), true)));
}
?>
