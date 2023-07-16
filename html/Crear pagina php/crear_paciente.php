<?php
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Aquí iría tu código para la creación de pacientes en la base de datos
        $host = 'magallanes.inf.unap.cl';
        $port = '5432';
        $dbname = 'jgomez';
        $user = 'jgomez';
        $password = '262m79VhrgMj';

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
        $conexion = new PDO($dsn);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $rut = $_POST['rut'];
        $celular = $_POST['celular'];
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $edad = $_POST['edad'];
        $f_nacimiento = $_POST['f_nacimiento'];
        $direccion = $_POST['direccion'];
        $genero = $_POST['genero'];
        $prevision = $_POST['prevision'];

        // Verificar si el empleado ya existe como persona
        $stmt_persona_existente = $conexion->prepare("SELECT COUNT(*) FROM persona WHERE rut = :rut");
        $stmt_persona_existente->bindParam(':rut', $rut);
        $stmt_persona_existente->execute();
        $empleado_existe = $stmt_persona_existente->fetchColumn();

        // Insertar en la tabla "persona" solo si el empleado no existe
        if (!$empleado_existe) {
            $sql_persona = "INSERT INTO persona (rut, celular, nombre, correo, edad, f_nacimiento, direccion, genero)
                            VALUES (:rut, :celular, :nombre, :correo, :edad, :f_nacimiento, :direccion, :genero)";

            $stmt_persona = $conexion->prepare($sql_persona);
            $stmt_persona->bindParam(':rut', $rut);
            $stmt_persona->bindParam(':celular', $celular);
            $stmt_persona->bindParam(':nombre', $nombre);
            $stmt_persona->bindParam(':correo', $correo);
            $stmt_persona->bindParam(':edad', $edad);
            $stmt_persona->bindParam(':f_nacimiento', $f_nacimiento);
            $stmt_persona->bindParam(':direccion', $direccion);
            $stmt_persona->bindParam(':genero', $genero);

            $stmt_persona->execute();
        }

        // Insertar en la tabla "paciente"
        $sql_paciente = "INSERT INTO paciente (rut, celular, nombre, correo, edad, f_nacimiento, direccion, genero, prevision)
                         VALUES (:rut, :celular, :nombre, :correo, :edad, :f_nacimiento, :direccion, :genero, :prevision)";

        $stmt_paciente = $conexion->prepare($sql_paciente);
        $stmt_paciente->bindParam(':rut', $rut);
        $stmt_paciente->bindParam(':celular', $celular);
        $stmt_paciente->bindParam(':nombre', $nombre);
        $stmt_paciente->bindParam(':correo', $correo);
        $stmt_paciente->bindParam(':edad', $edad);
        $stmt_paciente->bindParam(':f_nacimiento', $f_nacimiento);
        $stmt_paciente->bindParam(':direccion', $direccion);
        $stmt_paciente->bindParam(':genero', $genero);
        $stmt_paciente->bindParam(':prevision', $prevision);

        $stmt_paciente->execute();

        // Redireccionar a una página de éxito o mostrar un mensaje de éxito aquí
        header('Location: exito_paciente.php');
        exit();
    } catch (PDOException $e) {
        $error = "Error al crear el paciente: " . $e->getMessage();
    }
}

// Obtener los RUT de los empleados
try {
    $host = 'magallanes.inf.unap.cl';
    $port = '5432';
    $dbname = 'jgomez';
    $user = 'jgomez';
    $password = '262m79VhrgMj';

    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
    $conexion = new PDO($dsn);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt_empleados = $conexion->query("SELECT rut FROM empleado");
    $ruts = $stmt_empleados->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $error = "Error al obtener los RUT de los empleados: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Paciente</title>
    <link rel="stylesheet" href="crear_paciente_decoracion.css">
</head>

<body>
    <div class="container">
        <h1>Crear Paciente</h1>

        <h2>Datos del Paciente</h2>
        <form action="crear_paciente.php" method="POST" class="paciente-form">
            <div class="form-row">
                <label for="rut">RUT:</label>
                <input type="text" id="rut" name="rut" required>
            </div>

            <div class="form-row">
                <label for="celular">Celular:</label>
                <input type="text" id="celular" name="celular" required>
            </div>

            <div class="form-row">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>

            <div class="form-row">
                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" required>
            </div>

            <div class="form-row">
                <label for="edad">Edad:</label>
                <input type="number" id="edad" name="edad" required>
            </div>

            <div class="form-row">
                <label for="f_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" id="f_nacimiento" name="f_nacimiento" required>
            </div>

            <div class="form-row">
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" required>
            </div>

            <div class="form-row">
                <label for="genero">Género:</label>
                <select id="genero" name="genero" required>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                </select>
            </div>

            <div class="form-row">
                <label for="prevision">Previsión:</label>
                <input type="text" id="prevision" name="prevision" required>
            </div>

            <div class="form-row form-row-button">
                <input type="submit" value="Crear Paciente">
            </div>
        </form>

        <button id="empleado-paciente-button" onclick="toggleEmpleadoPaciente()">¿Crear un empleado paciente?</button>

        <button id="menu-button" onclick="window.location.href='menu_empleado_gestion.php'">Menú</button>

        <?php
        if (isset($_GET['error'])) {
            echo "<p class='error-message'>Error al crear el paciente: " . $_GET['error'] . "</p>";
        } elseif ($error !== "") {
            echo "<p class='error-message'>$error</p>";
        }
        ?>
    </div>

    <script>
        function toggleEmpleadoPaciente() {
            var rutInput = document.getElementById('rut');
            var empleadoPacienteButton = document.getElementById('empleado-paciente-button');

            if (rutInput.tagName === 'SELECT') {
                rutInput.outerHTML = '<input type="text" id="rut" name="rut" required>';
                empleadoPacienteButton.textContent = "¿Crear un empleado paciente?";
            } else {
                rutInput.outerHTML = '<select id="rut" name="rut" required><?php foreach ($ruts as $rut) { ?><option value="<?php echo $rut; ?>"><?php echo $rut; ?></option><?php } ?></select>';
                empleadoPacienteButton.textContent = "No crear un empleado paciente";
            }
        }
    </script>
</body>

</html>
