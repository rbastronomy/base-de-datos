<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = 'magallanes.inf.unap.cl';
    $port = '5432';
    $dbname = 'jgomez';
    $user = 'jgomez';
    $password = '262m79VhrgMj';

    try {
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
        $conexion = new PDO($dsn);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Obtener los datos del formulario
        $rut = $_POST['rut'];
        $celular = $_POST['celular'];
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $edad = $_POST['edad'];
        $f_nacimiento = $_POST['f_nacimiento'];
        $direccion = $_POST['direccion'];
        $genero = $_POST['genero'];

        // Preparar la consulta SQL para insertar en la tabla persona
        $consulta = "INSERT INTO persona (rut, celular, nombre, correo, edad, f_nacimiento, direccion, genero) 
        VALUES (:rut, :celular, :nombre, :correo, :edad, :f_nacimiento, :direccion, :genero)";
        $stmt = $conexion->prepare($consulta);

        // Asignar los valores a los parámetros de la consulta
        $stmt->bindParam(':rut', $rut);
        $stmt->bindParam(':celular', $celular);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':edad', $edad);
        $stmt->bindParam(':f_nacimiento', $f_nacimiento);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':genero', $genero);

        // Ejecutar la consulta
        $stmt->execute();

        // Redireccionar a una página de éxito o mostrar un mensaje de éxito aquí
        header('Location: exito.php');
        exit();
    } catch (PDOException $e) {
        // Manejar errores en caso de que ocurra una excepción
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
    <link rel="stylesheet" href="crear_persona_decoracion.css">
</head>
<body>
    <div class="container">
        <h1>Crear Empleado</h1>

        <h2>Datos Personales</h2>
        <form action="crear_persona.php" method="POST">
            <label for="rut">RUT:</label>
            <input type="text" id="rut" name="rut" required>

            <label for="celular">Celular:</label>
            <input type="text" id="celular" name="celular" required>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required>

            <label for="edad">Edad:</label>
            <input type="number" id="edad" name="edad" required>

            <label for="f_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="f_nacimiento" name="f_nacimiento" required>

            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" required>

            <label for="genero">Género:</label>
            <select id="genero" name="genero" required>
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
            </select>

            <input type="submit" value="Crear Persona">
        </form>

        <?php
        if (isset($_GET['error'])) {
            echo "<p class='error-message'>Error al crear la persona: " . $_GET['error'] . "</p>";
        }
        ?>
    </div>
</body>
</html>
