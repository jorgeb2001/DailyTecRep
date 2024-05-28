<?php
session_start();  // Iniciar la sesión PHP

if (!isset($_SESSION['email'])) {
    // Si no hay sesión de usuario, redirigir al login
    header("Location: Login.php");
    exit();
}

include('conexion.php');

// Consulta SQL para obtener todos los datos de los usuarios
$queryuser = "SELECT u.nombre AS nom_usuario, u.apellido_paterno, u.apellido_materno, u.no_control, u.correo, c.nombre AS nom_carrera
              FROM usuario u
              INNER JOIN alumno a ON a.fk_usuario = u.idUsuario
              INNER JOIN carrera c ON c.idCarrera = a.fk_carrera";

$stmt = sqlsrv_prepare($con, $queryuser);

if ($stmt) {
    sqlsrv_execute($stmt);
} else {
    echo "Error al preparar consulta SQL";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Perfil del Usuario</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">  

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #2c2c2c;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #3b3b3b;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            width: 90%;
            max-width: 800px;
            text-align: center;
        }

        h2 {
            color: #ffa500;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #555;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #555;
        }

        tr:nth-child(even) {
            background-color: #3c3c3c;
        }

        tr:nth-child(odd) {
            background-color: #2c2c2c;
        }

        .btn-eliminar, .btn-editar {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: opacity 0.3s;
        }

        .btn-eliminar {
            background-color: red;
            color: white;
        }

        .btn-eliminar:hover {
            opacity: 0.8;
        }

        .btn-editar {
            background-color: orange;
            color: white;
        }

        .btn-editar:hover {
            opacity: 0.8;
        }

        #div1 {
            position: absolute;
            top: 20px;
            left: 20px;
        }

        #div1 a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        #div1 a:hover {
            text-decoration: underline;
        }

        .iconoflecha {
            width: 20px;
            vertical-align: middle;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div id="div1">
        <a href="indexE.php"><img src="images/flecha.png" alt="flecha" class="iconoflecha"> Regresar</a>
    </div>
    <div class="container">
        <h2>Información de Usuarios</h2>
        <?php
        if (isset($_GET['msg'])) {
            if ($_GET['msg'] == 'success') {
                echo '<p style="color: green;">El registro ha sido eliminado correctamente.</p>';
            } elseif ($_GET['msg'] == 'error') {
                echo '<p style="color: red;">Error al intentar eliminar el registro.</p>';
            }
        }
        ?>
        <table>
            <tr>
                <th>Número de control</th>
                <th>Nombre Completo</th>
                <th>Correo</th>
                <th>Carrera</th>
                <th>Acciones</th>
            </tr>
            <?php
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['no_control']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nom_usuario']) . ' ' . htmlspecialchars($row['apellido_paterno']) . ' ' . htmlspecialchars($row['apellido_materno']) . "</td>";
                echo "<td>" . htmlspecialchars($row['correo']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nom_carrera']) . "</td>";
                echo "<td>";
                echo "<button onclick=\"eliminarRegistro('" . htmlspecialchars($row['no_control']) . "')\" class='btn-eliminar'>Eliminar</button> ";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

    <script>
        function eliminarRegistro(no_control) {
            if (confirm("¿Estás seguro de que deseas eliminar este registro?")) {
                window.location.href = "eliminar.php?no_control=" + no_control;
            }
        }
    </script>
</body>
</html>
