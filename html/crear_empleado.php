<?php
if (isset($_POST['grado_academico']) && isset($_POST['cargo']) && isset($_POST['contrasena'])) {
    // Recuperar los datos de la URL
    $rut = $_GET['rut'];
    $celular = $_GET['celular'];
    $nombre = $_GET['nombre'];
    $correo = $_GET['correo'];
    $edad = $_GET['edad'];
    $f_nacimiento = $_GET['f_nacimiento'];
    $direccion = $_GET['direccion'];
    $genero = $_GET['genero'];

    // Recuperar los datos del formulario de empleado
    $grado_academico = $_POST['grado_academico'];
    $cargo = $_POST['cargo'];
    $contrasena = $_POST['contrasena'];

    // Realizar el proceso de inserción en la base de datos
    $host = 'magallanes.inf.unap.cl';
    $port = '5432';
    $dbname = 'jgomez';
    $user = 'jgomez';
    $password = '262m79VhrgMj';

    try {
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
        $conexion = new PDO($dsn);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insertar los datos en la tabla empleado
        $consulta_empleado = "INSERT INTO empleado (rut, celular, nombre, correo, edad, f_nacimiento, direccion, genero, grado_academico, cargo, contrasena)
        VALUES (:rut, :celular, :nombre, :correo, :edad, :f_nacimiento, :direccion, :genero, :grado_academico, :cargo, :contrasena)";
        $stmt_empleado = $conexion->prepare($consulta_empleado);
        $stmt_empleado->bindParam(':rut', $rut);
        $stmt_empleado->bindParam(':celular', $celular);
        $stmt_empleado->bindParam(':nombre', $nombre);
        $stmt_empleado->bindParam(':correo', $correo);
        $stmt_empleado->bindParam(':edad', $edad);
        $stmt_empleado->bindParam(':f_nacimiento', $f_nacimiento);
        $stmt_empleado->bindParam(':direccion', $direccion);
        $stmt_empleado->bindParam(':genero', $genero);
        $stmt_empleado->bindParam(':grado_academico', $grado_academico);
        $stmt_empleado->bindParam(':cargo', $cargo);
        $stmt_empleado->bindParam(':contrasena', $contrasena);
        $stmt_empleado->execute();

        // Redireccionar a una página de éxito
        header("Location: exito_empleado.php");
        exit();
    } catch (PDOException $e) {
        echo "Error al conectarse a la base de datos: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Empleado</title>
    <link rel="stylesheet" href="crear_empleado_decoracion.css">
</head>
<body>
    <div class="container">
        <h1>Crear Empleado</h1>

        <h2>Datos de Empleado</h2>
        <form action="crear_empleado.php?<?php echo $_SERVER['QUERY_STRING']; ?>" method="POST">
            <h3>Datos Personales:</h3>
            <p>RUT: <?php echo $_GET['rut']; ?></p>
            <p>Celular: <?php echo $_GET['celular']; ?></p>
            <p>Nombre: <?php echo $_GET['nombre']; ?></p>
            <p>Correo: <?php echo $_GET['correo']; ?></p>
            <p>Edad: <?php echo $_GET['edad']; ?></p>
            <p>Fecha de Nacimiento: <?php echo $_GET['f_nacimiento']; ?></p>
            <p>Dirección: <?php echo $_GET['direccion']; ?></p>
            <p>Género: <?php echo $_GET['genero']; ?></p>

            <label for="grado_academico">Grado Académico:</label>
            <input type="text" id="grado_academico" name="grado_academico" required>

            <label for="cargo">Cargo:</label>
            <input type="text" id="cargo" name="cargo" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>

            <input type="submit" value="Crear Empleado">
        </form>
    </div>
</body>
</html>
