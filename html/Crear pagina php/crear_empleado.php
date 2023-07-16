<?php
$error = "";

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

        // Obtener los datos del formulario de persona
        $rut = $_POST['rut'];
        $celular = $_POST['celular'];
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $edad = $_POST['edad'];
        $f_nacimiento = $_POST['f_nacimiento'];
        $direccion = $_POST['direccion'];
        $genero = $_POST['genero'];

        // Preparar la consulta SQL para insertar en la tabla persona
        $consulta_persona = "INSERT INTO persona (rut, celular, nombre, correo, edad, f_nacimiento, direccion, genero) 
        VALUES (:rut, :celular, :nombre, :correo, :edad, :f_nacimiento, :direccion, :genero)";
        $stmt_persona = $conexion->prepare($consulta_persona);

        // Asignar los valores a los parámetros de la consulta de persona
        $stmt_persona->bindParam(':rut', $rut);
        $stmt_persona->bindParam(':celular', $celular);
        $stmt_persona->bindParam(':nombre', $nombre);
        $stmt_persona->bindParam(':correo', $correo);
        $stmt_persona->bindParam(':edad', $edad);
        $stmt_persona->bindParam(':f_nacimiento', $f_nacimiento);
        $stmt_persona->bindParam(':direccion', $direccion);
        $stmt_persona->bindParam(':genero', $genero);

        // Ejecutar la consulta de persona
        $stmt_persona->execute();

        // Obtener los datos del formulario de empleado
        $grado_academico = $_POST['grado_academico'];
        $cargo = $_POST['cargo'];
        $contrasena = $_POST['contrasena'];

        // Preparar la consulta SQL para insertar en la tabla empleado
        $consulta_empleado = "INSERT INTO empleado (rut, celular, nombre, correo, edad, f_nacimiento, direccion, genero, grado_academico, cargo, contrasena) 
        VALUES (:rut, :celular, :nombre, :correo, :edad, :f_nacimiento, :direccion, :genero, :grado_academico, :cargo, :contrasena)";
        $stmt_empleado = $conexion->prepare($consulta_empleado);

        // Asignar los valores a los parámetros de la consulta de empleado
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

        // Ejecutar la consulta de empleado
        $stmt_empleado->execute();

        // Verificar si se seleccionó crear un empleado de salud
        if (isset($_POST['crear_salud'])) {
            $especialidad = $_POST['especialidad'];

            // Obtener el ID del empleado recién creado
            $empleado_id = $conexion->lastInsertId();

            // Preparar la consulta SQL para insertar en la tabla empleado_salud
            $consulta_empleado_salud = "INSERT INTO empleado_salud (rut, grado_academico, cargo, contrasena, celular, nombre, correo, edad, f_nacimiento, direccion, genero, especialidad) 
            VALUES (:rut, :grado_academico, :cargo, :contrasena, :celular, :nombre, :correo, :edad, :f_nacimiento, :direccion, :genero, :especialidad)";
            $stmt_empleado_salud = $conexion->prepare($consulta_empleado_salud);

            // Asignar los valores a los parámetros de la consulta de empleado_salud
            $stmt_empleado_salud->bindParam(':rut', $rut);
            $stmt_empleado_salud->bindParam(':grado_academico', $grado_academico);
            $stmt_empleado_salud->bindParam(':cargo', $cargo);
            $stmt_empleado_salud->bindParam(':contrasena', $contrasena);
            $stmt_empleado_salud->bindParam(':celular', $celular);
            $stmt_empleado_salud->bindParam(':nombre', $nombre);
            $stmt_empleado_salud->bindParam(':correo', $correo);
            $stmt_empleado_salud->bindParam(':edad', $edad);
            $stmt_empleado_salud->bindParam(':f_nacimiento', $f_nacimiento);
            $stmt_empleado_salud->bindParam(':direccion', $direccion);
            $stmt_empleado_salud->bindParam(':genero', $genero);
            $stmt_empleado_salud->bindParam(':especialidad', $especialidad);

            // Ejecutar la consulta de empleado_salud
            $stmt_empleado_salud->execute();

            // Redireccionar a una página de éxito o mostrar un mensaje de éxito aquí
            header('Location: exito_empleado.php');
            exit();
        }

        // Verificar si se seleccionó crear un empleado de gestión
        if (isset($_POST['crear_gestion'])) {
            $tipo_cursos = $_POST['tipo_cursos'];

            // Obtener el ID del empleado recién creado
            $empleado_id = $conexion->lastInsertId();

            // Preparar la consulta SQL para insertar en la tabla empleado_gestion
            $consulta_empleado_gestion = "INSERT INTO empleado_gestion (rut, grado_academico, cargo, contrasena, celular, nombre, correo, edad, f_nacimiento, direccion, genero, tipo_cursos) 
            VALUES (:rut, :grado_academico, :cargo, :contrasena, :celular, :nombre, :correo, :edad, :f_nacimiento, :direccion, :genero, :tipo_cursos)";
            $stmt_empleado_gestion = $conexion->prepare($consulta_empleado_gestion);

            // Asignar los valores a los parámetros de la consulta de empleado_gestion
            $stmt_empleado_gestion->bindParam(':rut', $rut);
            $stmt_empleado_gestion->bindParam(':grado_academico', $grado_academico);
            $stmt_empleado_gestion->bindParam(':cargo', $cargo);
            $stmt_empleado_gestion->bindParam(':contrasena', $contrasena);
            $stmt_empleado_gestion->bindParam(':celular', $celular);
            $stmt_empleado_gestion->bindParam(':nombre', $nombre);
            $stmt_empleado_gestion->bindParam(':correo', $correo);
            $stmt_empleado_gestion->bindParam(':edad', $edad);
            $stmt_empleado_gestion->bindParam(':f_nacimiento', $f_nacimiento);
            $stmt_empleado_gestion->bindParam(':direccion', $direccion);
            $stmt_empleado_gestion->bindParam(':genero', $genero);
            $stmt_empleado_gestion->bindParam(':tipo_cursos', $tipo_cursos);

            // Ejecutar la consulta de empleado_gestion
            $stmt_empleado_gestion->execute();

            // Redireccionar a una página de éxito o mostrar un mensaje de éxito aquí
            header('Location: exito_empleado.php');
            exit();
        }

        // Redireccionar a una página de éxito o mostrar un mensaje de éxito aquí
        header('Location: exito_empleado.php');
        exit();
    } catch (PDOException $e) {
        // Manejar errores en caso de que ocurra una excepción
        $error = "Error al conectarse a la base de datos: " . $e->getMessage();
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

        <h2>Datos Personales</h2>
        <form action="crear_empleado.php" method="POST">
            <label for="rut">RUT:</label>
            <input type="text" id="rut" name="rut" pattern="[0-9]{7,8}[0-9Kk]" maxlength="13" required title="El RUT debe contener solo números y el dígito verificador puede ser un número o la letra K. Longitud: 13 dígitos." oninput="formatRUT(this)">

            <label for="celular">Celular:</label>
            <input type="text" id="celular" name="celular" pattern="[0-9]+" required title="Solo se permiten números en el campo del celular.">

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" pattern="[A-Za-z]+" required title="Solo se permiten letras en el campo del nombre.">

            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required pattern=".+@.+" title="El campo del correo debe contener una dirección de correo válida con el símbolo @.">

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

            <label for="grado_academico">Grado Académico:</label>
            <input type="text" id="grado_academico" name="grado_academico" required>

            <label for="cargo">Cargo:</label>
            <input type="text" id="cargo" name="cargo" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$" required title="La contraseña debe tener al menos un símbolo, una mayúscula, una minúscula y un número.">

            <h2>Tipo de Empleado</h2>

            <div class="checkbox-container">
                <input type="checkbox" id="crear_salud" name="crear_salud">
                <label for="crear_salud">Crear Empleado de Salud</label>
            </div>

            <div id="opciones_salud" class="opciones" style="display: none;">
                <label for="especialidad">Especialidad:</label>
                <input type="text" id="especialidad" name="especialidad">
            </div>

            <div class="checkbox-container">
                <input type="checkbox" id="crear_gestion" name="crear_gestion">
                <label for="crear_gestion">Crear Empleado de Gestión</label>
            </div>

            <div id="opciones_gestion" class="opciones" style="display: none;">
                <label for="tipo_cursos">Tipo de Cursos:</label>
                <input type="text" id="tipo_cursos" name="tipo_cursos">
            </div>

            <input type="submit" value="Crear Empleado">
        </form>

        <button id="menu-button" onclick="window.location.href='menu_empleado_gestion.php'">Menú</button>

        <?php
        if (isset($_GET['error'])) {
            echo "<p class='error-message'>Error al crear la persona: " . $_GET['error'] . "</p>";
        } elseif ($error !== "") {
            echo "<p class='error-message'>$error</p>";
        }
        ?>
    </div>

    <script>
        function formatRUT(input) {
            // Remover guiones y puntos
            var rut = input.value.replace(/[-.]/g, '');
            // Volver a formatear el RUT con puntos y guión
            var formattedRUT = rut.slice(0, -1).replace(/\B(?=(\d{3})+(?!\d))/g, '.') + '-' + rut.slice(-1);
            // Actualizar el valor del campo de entrada
            input.value = formattedRUT;
        }
    </script>
</body>

</html>
