<!DOCTYPE html>
<?php
    include('auth.php');
    include('conexion.php');
?>



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
    <link href="css/ROMAN.css" rel="stylesheet" type="text/css">
</head>

<body>
    <!-- Topbar Start -->
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <div class="container-fluid p-0">
        <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-2 py-lg-0 px-lg-5">
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"><img src="images/dailytec.jfif"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between px-0 px-lg-3" id="navbarCollapse">
                <a class="nav-item nav-link active"><img src="images/escudo.png" class="size"></a>
                <a href="index.html" class="nav-item nav-link active"><img src="images/dailytec.jfif" class="size"></a>
                <div class="navbar-nav mr-auto py-0">
                    <a href="index.php" class="nav-item nav-link active">Eventos</a>
                    <a href="calendario.php" class="nav-item nav-link">Calendario</a>
                    <a href="single.html" class="nav-item nav-link">Historial</a>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown">Perfil</a>
                        <div class="dropdown-menu rounded-0 m-0">
                            <a href="perfil.php" class="dropdown-item">Mi perfil</a>
                            <a href="gestU.php" class="dropdown-item">Gestionar Usuarios</a>
                            <a href="Login.php" class="dropdown-item">Cerrar Sesión</a>
                        </div>
                    </div>
                    <a href="perfil.php" class="nav-item nav-link"><img src="<?php echo htmlspecialchars($fotoPerfil); ?>" class="size2"></a>
                    <a href="perfil.php" class="nav-item nav-link">
                        <div id="username" name="username"><?php echo htmlspecialchars($nombreCompleto); ?></div>
                    </a>
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <div id="div1">
        <a class="#div1 letras" href="index.php"><span class="iconoflecha"><img src="images/flecha.png" alt="flecha" class="iconoflecha"></span> Regresar</a>
    </div>

    <div class="container principal">
        <form action="conexionCrearEvento.php" method="POST" enctype="multipart/form-data">
            <h2 class="datoseventotitulo">Datos del evento</h2>

            <div class="form-group row">
                <label for="nombre" class="col-sm-2 col-form-label">Nombre:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
            </div>

            <div class="form-group row">
                <label for="check-creditos" class="col-sm-2 col-form-label">Libera créditos</label>
                <div class="col-sm-10">
                    <input type="checkbox" class="form-check-input" id="check-creditos" name="creditos" onchange="updateHiddenInput(this)">
                    <input type="hidden" id="hidden-creditos" name="hidden-creditos" value="0">
                </div>
            </div>

            <div class="form-group row">
                <label for="imagen" class="col-sm-2 col-form-label">Selecciona una imagen:</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control-file" id="imagen" name="imagen" accept="image/*" onchange="mostrarImagen(this)">
                    <img id="vista-previa-imagen" src="#" alt="Vista previa de imagen" class="img-fluid mt-2">
                </div>
            </div>

            <div class="form-group row">
                <label for="descripcion" class="col-sm-2 col-form-label">Descripción:</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required></textarea>
                </div>
            </div>

            <div class="form-group row">
                <label for="fecha_inicio" class="col-sm-2 col-form-label">Fecha Inicio:</label>
                <div class="col-sm-4">
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                </div>
                <label for="fecha_fin" class="col-sm-2 col-form-label">Fecha Fin:</label>
                <div class="col-sm-4">
                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                </div>
            </div>

            <div class="form-group row">
                <label for="carrera" class="col-sm-2 col-form-label">Carrera:</label>
                <div class="col-sm-10">
                    <input type="hidden" name="carreraIndex" id="carreraIndex">
                    <select class="form-control" id="carrera" name="carrera" required>
                        <option value="Arquitectura">Arquitectura</option>
                        <option value="Ingeniería Bioquímica">Ingeniería Bioquímica</option>
                        <option value="Ingeniería Civil">Ingeniería Civil</option>
                        <option value="Ingeniería Eléctrica">Ingeniería Eléctrica</option>
                        <option value="Ingeniería en Gestión Empresarial">Ingeniería en Gestión Empresarial</option>
                        <option value="Ingeniería en Sistemas Computacionales">Ingeniería en Sistemas Computacionales</option>
                        <option value="Ingeniería Industrial">Ingeniería Industrial</option>
                        <option value="Ingeniería Mecatrónica">Ingeniería Mecatrónica</option>
                        <option value="Ingeniería Química">Ingeniería Química</option>
                        <option value="Licenciatura en Administración">Licenciatura en Administración</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="tipo_evento" class="col-sm-2 col-form-label">Tipo de Evento:</label>
                <div class="col-sm-10">
                    <select class="form-control" id="tipo_evento" name="tipo_evento" required onchange="cambiarColor()">
                        <option value="Eventos oficiales">Eventos oficiales</option>
                        <option value="Extraescolares">Extraescolares</option>
                        <option value="Tutorías">Tutorías</option>
                        <option value="Jornada Académica">Jornada Académica</option>
                        <option value="Vinculación">Vinculación</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-12 text-center">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function cambiarColor() {
            var tipoEventoSelect = document.getElementById("tipo_evento");
            var cuadrito = document.getElementById("cuadrito");

            var valorSeleccionado = tipoEventoSelect.value;

            var color;
            switch (valorSeleccionado) {
                case "Libera Créditos":
                    color = "green";
                    break;
                case "Eventos oficiales":
                    color = "blue";
                    break;
                case "Extraescolares":
                    color = "purple";
                    break;
                case "Tutorías":
                    color = "red";
                    break;
                case "Jornada Académica":
                    color = "yellow";
                    break;
                case "Vinculación":
                    color = "pink";
                    break;
                default:
                    color = "transparent";
                    break;
            }

            cuadrito.style.backgroundColor = color;
        }

        function mostrarImagen(input) {
            const vistaPrevia = document.getElementById('vista-previa-imagen');
            if (input.files && input.files[0]) {
                const lector = new FileReader();
                lector.onload = function(e) {
                    vistaPrevia.src = e.target.result;
                }
                lector.readAsDataURL(input.files[0]);
            }
        }

        function updateHiddenInput(checkbox) {
            var hiddenInput = document.getElementById('hidden-creditos');
            if (checkbox.checked) {
                hiddenInput.value = '1';
            } else {
                hiddenInput.value = '0';
            }
        }
    </script>
</body>

</html>
