<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Evento Eliminado</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="description" name="Free HTML Templates">
</head>
<body>
    <p>Redirigiendo a la página principal...</p>
</body>
</html>

<?php
session_start();  // Iniciar la sesión PHP

if (!isset($_SESSION['email'])) {
    // Si no hay sesión de usuario, redirigir al login
    header("Location: Login.php");
    exit();
}

// Si hay sesión, recuperar ID del usuario
$id = $_SESSION['id'];

include('conexion.php');

// Consulta SQL para obtener el nombre, apellido paterno, nivel de acceso y foto de perfil del usuario basado en el ID
$queryuser = "SELECT nombre, apellido_paterno, fk_acceso, foto_perfil FROM usuario WHERE idUsuario = ?";
$stmt = sqlsrv_prepare($con, $queryuser, array($id));

if ($stmt && sqlsrv_execute($stmt)) {
    if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $nombreUsuario = $row['nombre'];
        $apellidoPaterno = $row['apellido_paterno'];
        $nombreCompleto = $nombreUsuario . ' ' . $apellidoPaterno;
        $nvAcceso = $row['fk_acceso'];
        $fotoPerfil = $row['foto_perfil'] ? $row['foto_perfil'] : 'images/default-profile.png';  // Usar imagen por defecto si no hay foto de perfil

        // Verificar si el nivel de acceso es 1
        if ($nvAcceso != 3) {
            // Si el nivel de acceso no es 1, redirigir al login
            header("Location: Login.php");
            exit();
        }
    } else {
        // No se encontró ningún usuario para el ID dado
        $nombreCompleto = "Usuario Desconocido";
        header("Location: Login.php");
        exit();
    }
} else {
    // Error al ejecutar la consulta
    $nombreCompleto = "Error al recuperar el nombre de usuario";
    header("Location: Login.php");
    exit();
}
?>
