<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Login by Matt05 | Codepen</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="css/diseñoLogin.css" rel="stylesheet" type="text/css">
    <style>
        .btn-custom {
            background-color: orange;
            color: black;
            padding: 10px 68px;
            border-radius: 2px;
            cursor: pointer;
            font-size: 17px;
            font-family: "Helvetica";
        }
        a {
            color: orange;
            text-decoration: none;
        }
    </style>
</head>
<body style="background: #000000;">
    <div class="notification" id="notification"><?php if (isset($error)) echo $error; ?></div>
    <div>&nbsp;</div>
    <div>&nbsp;</div>
    
    <form action="conexionLogin.php" method="POST">
        <div style="text-align: center">
            <img src="images/WhatsApp Image 2024-02-27 at 11.04.44 PM.jpeg" width="200" height="184" alt=""/>
        </div>
        <div class="form-group offset-xl-3 col-xl-6">
            <div>&nbsp;</div>
            <input class="form-control" type="email" name="email" id="email" placeholder="@ittepic.edu.mx" required>
        </div>
        <div class="form-group offset-xl-3 col-xl-6">
            <input class="form-control" type="password" name="password" id="password" placeholder="Contraseña" required>
        </div>
        <!-- Agregar el campo fk_acceso -->
        <div class="form-group offset-xl-3 col-xl-6">
            <input class="form-control" type="hidden" name="fk_acceso" id="fk_acceso" value="3">
        </div>
        <div class="form-group offset-xl-0 col-xl-12" style="text-align: center;">
            <button class="btn-custom" type="submit" id="loginAlumno">Ingresar</button>
        </div>
        <div style="text-align: center; color: #F27A22;">
            <a href="LoginA.php" class="color">Ingresar como Administrador&nbsp; &nbsp;</a>
        </div>
        <div>&nbsp;</div>
    </form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
