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

    // Función para obtener las actividades a las que el usuario ha asistido
    function obtenerActividadesAsistidas($con, $id) {
        $sql = "SELECT A.nombre, A.descripcion, A.imagen, A.fecha_inicio, A.fecha_fin, A.lugar, A.modalidad 
                FROM Actividad A
                INNER JOIN Asistencia ASI ON A.idActividad = ASI.fk_actividad
                WHERE ASI.fk_alumno = ? AND ASI.asistencia = 1";
        $params = array($id);
        $stmt = sqlsrv_query($con, $sql, $params);
        
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $actividades = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $actividades[] = $row;
        }

        sqlsrv_free_stmt($stmt);

        return $actividades;
    }

    $idCarreraUsuario = obtenerIdCarreraUsuario($con, $id);
    $actividadesAsistidas = obtenerActividadesAsistidas($con, $id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>BizNews - Free News Website Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">  

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Topbar Start -->
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-2 py-lg-0 px-lg-5">
        <a class="navbar-brand"><img src="images/escudo.png" class="size"></a>
        <a href="index.html" class="navbar-brand"><img src="images/dailytec.jfif" class="size"></a> 
        <div class="collapse navbar-collapse" id="navbarSupportedContent1">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="indexE.php">EVENTOS</a></li>
                <li class="nav-item"><a class="nav-link" href="historialE.php">HISTORIAL</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">Perfil</a>
                    <div class="dropdown-menu rounded-0 m-0">
                        <a href="perfil.php" class="dropdown-item">Mi perfil</a>
                        <a href="gestU.php" class="dropdown-item">Gestionar Usuarios</a>
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
            <?php
                if (isset($_GET['message'])) {
                    echo '<div class="alert alert-success" role="alert">' . htmlspecialchars($_GET['message']) . '</div>';
                }

                if ($con === false) {
                    die(print_r(sqlsrv_errors(), true));
                }

                echo '<div class="section-title"><h4 class="m-0 text-uppercase font-weight-bold">Historial</h4></div>';

                if (!empty($actividadesAsistidas)) {
                    echo '<div class="row">';
                    foreach ($actividadesAsistidas as $actividad) {
                        echo '
                            <div class="col-md-3 mb-4">
                                <div class="position-relative overflow-hidden" style="height: 300px;">
                                    <div class="event-container">
                                        <img class="img-fluid" src="' . htmlspecialchars($actividad["imagen"]) . '">
                                        <div class="overlay">
                                            <div class="mb-2">
                                                <a class="badge badge-primary text-uppercase font-weight-semi-bold p-2 mr-2">' . htmlspecialchars($actividad["modalidad"]) . '</a>
                                                <a class="text-white" href="#"><small>' . $actividad["fecha_inicio"]->format('M d, Y') . '</small></a>
                                            </div>
                                            <a class="event-title" href="#">' . htmlspecialchars($actividad["nombre"]) . '</a>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                    }
                    echo '</div>';
                } else {
                    echo '<p>No has asistido a ninguna actividad.</p>';
                }
            ?>
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

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    

</body>

</html>
