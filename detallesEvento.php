<?php
    include('auth.php');
    include('conexion.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Evento</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        @import url("css/style.css");

        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #2c2c2c;
            color: white;
        }

        .container {
            text-align: center;
            background-color: #3b3b3b;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            width: 90%;
            max-width: 800px;
            margin-bottom: 20px;
        }

        h1, h2 {
            color: #ffa500;
            margin-top: 0;
        }

        p {
            color: #ccc;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .banner {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .btn {
            background-color: orange;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
            margin: 2px;
        }

        .btn:hover {
            background-color: darkorange;
        }

        .tabla-eventos {
            width: 100%;
            border-collapse: collapse;
            background-color: #444;
            margin-top: 20px;
        }

        .tabla-eventos th, .tabla-eventos td {
            border: 1px solid #555;
            padding: 8px;
            text-align: center;
        }

        .tabla-eventos th {
            background-color: #555;
            color: white;
        }

        .tabla-eventos tr:nth-child(even) {
            background-color: #3c3c3c;
        }

        .tabla-eventos tr:nth-child(odd) {
            background-color: #2c2c2c;
        }

        .tabla-eventos .activity-image {
            max-width: 100px;
            max-height: 100px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div id="div1">
        <a class="#div1 letras" href="index.php"><span class="iconoflecha" ><img src="images/flecha.png" alt="flecha" class="iconoflecha"></span> Regresar</a>
    </div>
    <div class="container">
        <h1>Detalles del Evento</h1>
        <div>
            <?php
            include('conexion.php');

            if ($con) {
                $evento_id = $_GET['idEvento'];

                $query = "SELECT E.nombre, E.descripcion, E.fecha_inicio, E.fecha_fin, E.liberacion_creditos, C.nombre AS carrera, E.banner 
                          FROM evento E 
                          INNER JOIN CARRERA C ON C.idCarrera = E.fk_carrera 
                          WHERE E.idEvento = ?";
                $params = array($evento_id);
                $result = sqlsrv_query($con, $query, $params);

                if ($result) {
                    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

                    if (!empty($row['banner'])) {
                        echo "<img src='{$row['banner']}' alt='Banner del Evento' class='banner' style='width: 150px; height: 150px; height: auto;'>";
                    }

                    echo "<h2>{$row['nombre']}</h2>";
                    echo "<p>{$row['descripcion']}</p>";
                    echo "<p>Fecha de Inicio: {$row['fecha_inicio']->format('M d, Y')}</p>";
                    echo "<p>Fecha de Fin: {$row['fecha_fin']->format('M d, Y')}</p>";
                    echo "<p>Liberación de Créditos: " . ($row['liberacion_creditos'] ? 'Sí' : 'No') . "</p>";
                    echo "<p>Carrera: {$row['carrera']}</p>";

                    sqlsrv_free_stmt($result);
                } else {
                    die("Error en la consulta: " . print_r(sqlsrv_errors(), true));
                }

                sqlsrv_close($con);
            } else {
                die("Error en la conexión: " . print_r(sqlsrv_errors(), true));
            }
            ?>
        </div>
        <div>
            &nbsp;
        </div>
        <a href="editarEvento.php?idEvento=<?php echo $evento_id; ?>" class="btn">Editar</a>
    </div>

    <h1 style="text-align: center">Actividades de este evento</h1>
    <div style="text-align: center">
        <a href="CrearAct.php?idEvento=<?php echo $evento_id; ?>" class="btn">Añadir Actividad</a>
    </div>

    <table class="tabla-eventos">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Imagen</th>
                <th>Modalidad</th>
                <th>Lugar</th>
                <th>Fecha de Inicio</th>
                <th>Fecha final</th>
                <th>Hora inicio</th>
                <th>Hora final</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include('conexion.php');

            if ($con) {
                $query = "SELECT idActividad, nombre, descripcion, imagen, modalidad, lugar, fecha_inicio, fecha_fin, hora_inicio, hora_fin 
                          FROM Actividad 
                          WHERE fk_evento = ?";
                $params = array($evento_id);
                $result = sqlsrv_query($con, $query, $params);

                if ($result) {
                    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>{$row['nombre']}</td>";
                        echo "<td>{$row['descripcion']}</td>";
                        echo "<td>" . (!empty($row['imagen']) ? "<img src='{$row['imagen']}' alt='Imagen de la Actividad' class='activity-image'>" : "No disponible") . "</td>";
                        echo "<td>{$row['modalidad']}</td>";
                        echo "<td>{$row['lugar']}</td>";
                        echo "<td>{$row['fecha_inicio']->format('M d, Y')}</td>";
                        echo "<td>{$row['fecha_fin']->format('M d, Y')}</td>";
                        echo "<td>{$row['hora_inicio']->format('H:i')}</td>";
                        echo "<td>" . ($row['hora_fin'] ? $row['hora_fin']->format('H:i') : 'No disponible') . "</td>";
                        echo "<td>
                                <a href='tablaAsistencia.php?idActividad={$row['idActividad']}' class='btn'>Asistencias</a>
                                <a href='updateAct.php?idActividad={$row['idActividad']}&idEvento={$evento_id}' class='btn'>Editar Actividad</a>
                                <a href='estadisticas.php?idActividad={$row['idActividad']}' class='btn'>Estadísticas</a>
                              </td>";
                        echo "</tr>";
                    }

                    sqlsrv_free_stmt($result);
                } else {
                    die("Error en la consulta: " . print_r(sqlsrv_errors(), true));
                }

                sqlsrv_close($con);
            } else {
                die("Error en la conexión: " . print_r(sqlsrv_errors(), true));
            }
            ?>
        </tbody>
    </table>

</body>
</html>
