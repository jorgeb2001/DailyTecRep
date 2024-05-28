<?php
include('authorE.php');
include('conexion.php');

// Función para obtener el ID de la carrera del usuario actualmente logueado
function obtenerIdCarreraUsuario($con, $id) {
    $sql = "SELECT fk_carrera FROM Alumno WHERE fk_usuario = ?";
    $params = array($id);
    $stmt = sqlsrv_query($con, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmt);

    return $row['fk_carrera'];
}

// Obtener el ID de la carrera del usuario actualmente logueado
$idCarreraUsuario = obtenerIdCarreraUsuario($con, $id);

// Consulta SQL para obtener actividades próximas filtradas por la carrera del usuario
$sqlActividades = "SELECT a.nombre, a.fecha_inicio, a.fecha_fin, a.hora_inicio, a.hora_fin, 
                          CASE
                            WHEN a.fecha_fin < GETDATE() THEN 'red'
                            ELSE 'green'
                          END AS color
                   FROM Actividad a
                   JOIN Asistencia asi ON a.idActividad = asi.fk_actividad
                   WHERE asi.fk_alumno = ? AND asi.entrada = 0";
$paramsActividades = array($id);
$stmtActividades = sqlsrv_query($con, $sqlActividades, $paramsActividades);

if ($stmtActividades === false) {
    die(print_r(sqlsrv_errors(), true));
}

$actividades = [];
while ($row = sqlsrv_fetch_array($stmtActividades, SQLSRV_FETCH_ASSOC)) {
    $actividades[] = $row;
}

sqlsrv_free_stmt($stmtActividades);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>DailyTec - Calendario</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Calendario de Actividades" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">  

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">

    <!-- FullCalendar CSS -->
    <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.css' rel='stylesheet' />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <!-- Custom CSS for Calendar -->
    <style>
        .fc .fc-toolbar {
            background-color: #343a40;
            color: #ffffff;
        }

        .fc .fc-toolbar-title {
            font-size: 1.5rem;
        }

        .fc .fc-button {
            background-color: #007bff;
            border: none;
            color: #ffffff;
        }

        .fc .fc-button:hover {
            background-color: #0056b3;
        }

        .fc .fc-today {
            background-color: #d4edda !important;
        }

        .fc .fc-daygrid-event {
            background-color: #007bff;
            color: #ffffff;
            border: none;
        }

        .fc .fc-event:hover {
            background-color: #0056b3;
        }

        .fc .fc-event-time, .fc .fc-event-title {
            color: #ffffff;
        }
    </style>
</head>
<body>
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-2 py-lg-0 px-lg-5">
        <a class="navbar-brand"><img src="images/escudo.png" class="size"></a>
        <a href="index.html" class="navbar-brand"><img src="images/dailytec.jfif" class="size"></a> 
        <div class="collapse navbar-collapse" id="navbarSupportedContent1">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="indexE.php">EVENTOS</a></li>
                <li class="nav-item"><a class="nav-link" href="calendarioE.php">CALENDARIO</a></li>
                <li class="nav-item"><a class="nav-link" href="historialE.php">HISTORIAL</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">Perfil</a>
                    <div class="dropdown-menu rounded-0 m-0">
                        <a href="perfilE.php" class="dropdown-item">Mi perfil</a>
                        <a href="Login.php" class="dropdown-item">Cerrar Sesión</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="perfilE.php" class="nav-link"><img src="<?php echo $fotoPerfil; ?>" class="size2"></a>
                </li>
            </ul>
        </div>
        <div class="ml-auto"><?php echo htmlspecialchars($nombreCompleto); ?></div>
        <form class="form-inline my-2 my-lg-0">
            <div class="input-group ml-auto d-none d-lg-flex" style="width: 100%; max-width: 300px;">
                <a class="nav-link dropdown-toggle fa fa-search" data-toggle="dropdown"> Buscar</a>
                <div class="dropdown-menu rounded-0 m-0">
                    <a href="#micarrera" class="dropdown-item">Mi carrera</a>
                    <a href="#creds" class="dropdown-item">Libera Créditos</a>
                    <a href="#oficial" class="dropdown-item">Eventos oficiales</a>
                    <a href="#extra" class="dropdown-item">Extraescolares</a>
                    <a href="#tutos" class="dropdown-item">Tutorías</a>
                    <a href="#jornada" class="dropdown-item">Jornada Académica</a>
                    <a href="#vinc" class="dropdown-item">Vinculación</a>
                </div>
            </div>
        </form>
    </nav>
    <!-- Navbar End -->

    <!-- Main Content Start -->
    <div class="container-fluid pt-5 mb-3">
        <div class="container">
            <div class="section-title">
                <h4 class="m-0 text-uppercase font-weight-bold">Calendario de Actividades</h4>
            </div>
            <div id='calendar'></div>
        </div>
    </div>
    <!-- Main Content End -->

    <!-- Footer Start -->
    <div class="container-fluid bg-dark pt-5 px-sm-3 px-md-5 mt-5"></div>
    <div class="container-fluid py-4 px-sm-3 px-md-5" style="background: #111111;">
        <p class="m-0 text-center">&copy; <a href="#">DailyTec</a>. Todos los derechos reservados.</p>
    </div>
    <!-- Footer End -->

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.js'></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <!-- FullCalendar Initialization -->
    <script>
        $(document).ready(function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                initialView: 'dayGridMonth',
                events: [
                    <?php foreach ($actividades as $actividad): ?>
                    {
                        title: '<?php echo htmlspecialchars($actividad['nombre']); ?>',
                        start: '<?php echo $actividad['fecha_inicio']->format('Y-m-d\TH:i:s'); ?>',
                        end: '<?php echo $actividad['fecha_fin']->format('Y-m-d\TH:i:s'); ?>',
                        color: '<?php echo htmlspecialchars($actividad['color']); ?>'
                    },
                    <?php endforeach; ?>
                ]
            });
            calendar.render();
        });
    </script>
</body>
</html>
