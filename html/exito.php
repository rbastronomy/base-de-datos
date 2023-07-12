<?php
if (isset($_POST['rut']) && isset($_POST['celular']) && isset($_POST['nombre']) && isset($_POST['correo']) && isset($_POST['edad']) && isset($_POST['f_nacimiento']) && isset($_POST['direccion']) && isset($_POST['genero'])) {
    // Recuperar los datos del formulario anterior
    $rut = $_POST['rut'];
    $celular = $_POST['celular'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $edad = $_POST['edad'];
    $f_nacimiento = $_POST['f_nacimiento'];
    $direccion = $_POST['direccion'];
    $genero = $_POST['genero'];

    // Codificar los datos en la URL
    $datos_codificados = http_build_query([
        'rut' => $rut,
        'celular' => $celular,
        'nombre' => $nombre,
        'correo' => $correo,
        'edad' => $edad,
        'f_nacimiento' => $f_nacimiento,
        'direccion' => $direccion,
        'genero' => $genero
    ]);

    // Redireccionar a crear_empleado.php con los datos codificados en la URL
    header("Location: crear_empleado.php?$datos_codificados");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éxito</title>
    <link rel="stylesheet" href="exito.css">
</head>
<body>
    <div class="container">
        <h1>Éxito</h1>
        <p>¿Desea convertir a esta persona en empleado?</p>
        <div class="button-container">
            <a class="success-button" href="crear_empleado.php?confirm=yes">Sí</a>
            <a class="cancel-button" href="crear_empleado.php?confirm=no">No</a>
        </div>
    </div>
</body>
</html>
