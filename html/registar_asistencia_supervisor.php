<?php
$error = "";
$registrarSalidaHabilitado = false;

session_start();

// Obtener el RUT y rol de la sesión
$rut = isset($_SESSION['rut']) ? $_SESSION['rut'] : '';
$rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : '';


// Verificar si se ha iniciado sesión con un rol válido
if (empty($rol) || !in_array($rol, ['Empleado Salud', 'Empleado Gestión', 'Empleado', 'Supervisor'])) {
    // Redirigir a una página de error o mostrar un mensaje de error apropiado
    echo "Error: Rol de usuario inválido.";
    exit;
}

$hora_entrada = "";
$hora_salida = "";

// Verificar si ya se registró la hora de entrada hoy
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $host = 'magallanes.inf.unap.cl';
    $port = '5432';
    $dbname = 'jgomez';
    $user = 'jgomez';
    $password = '262m79VhrgMj';

    try {
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
        $conexion = new PDO($dsn);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Obtener la fecha actual
        $fecha_actual = date('Y-m-d');

        // Verificar si ya se registró la hora de entrada hoy
        $consulta_asistencia = "SELECT * FROM asistencia WHERE rut = :rut AND fecha_asistencia = :fecha_asistencia";
        $stmt_asistencia = $conexion->prepare($consulta_asistencia);
        $stmt_asistencia->bindParam(':rut', $rut);
        $stmt_asistencia->bindParam(':fecha_asistencia', $fecha_actual);
        $stmt_asistencia->execute();
        $asistencia = $stmt_asistencia->fetch();

        if ($asistencia) {
            $hora_entrada = $asistencia['hora_entrada'];
            $hora_salida = $asistencia['hora_salida'];
            $fecha_asistencia = $asistencia['fecha_asistencia'];
        
            if (($fecha_actual== $fecha_asistencia) && (!empty($hora_salida))) {
                header("Location: " . getMenuURL($rol));
                    exit;
            }

            if (empty($hora_salida)) {
                $registrarSalidaHabilitado = true;

            }
        }
    } catch (PDOException $e) {
        // Manejar errores en caso de que ocurra una excepción
        $error = "Error al conectarse a la base de datos: " . $e->getMessage();
    }
}

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

        // Establecer la zona horaria
        date_default_timezone_set('America/Santiago');

        // Obtener la fecha actual
        $fecha_actual = date('Y-m-d');

        // Verificar si se ha registrado la hora de entrada
        if (!empty($_POST['hora_entrada']) && empty($_POST['hora_salida'])) {
            
            $hora_entrada = $_POST['hora_entrada'];

            // Obtener la hora de entrada actual
            $hora_actual = date('H:i:s');


            // Insertar el registro de asistencia con la hora de entrada y estado_asistencia = true
            $consulta_asistencia = "INSERT INTO asistencia (rut, fecha_asistencia, hora_entrada, estado_asistencia) 
            VALUES (:rut, :fecha_asistencia, :hora_entrada, true)";
            $stmt_asistencia = $conexion->prepare($consulta_asistencia);
            $stmt_asistencia->bindParam(':rut', $rut);
            $stmt_asistencia->bindParam(':fecha_asistencia', $fecha_actual);
            $stmt_asistencia->bindParam(':hora_entrada', $hora_entrada);
            $stmt_asistencia->execute();

            // Almacenar el ID de asistencia en la sesión
            $_SESSION['id_asistencia'] = $conexion->lastInsertId();

            //Redirigir al menú después de registrar la entrada
            header("Location: " . getMenuURL($rol));
            exit;
        }

        // Verificar si se ha presionado el botón de "Registrar Salida" y se ha ingresado la hora de salida
        if (isset($_POST['registrar_salida']) && !empty($_POST['hora_salida'])) {
            $hora_salida = $_POST['hora_salida'];

            // Obtener el ID de asistencia desde la sesión
            $id_asistencia = isset($_SESSION['id_asistencia']) ? $_SESSION['id_asistencia'] : null;

            if ($id_asistencia) {
                // Actualizar el registro de asistencia con la hora de salida
                echo "entro al if de hora salida";
                $consulta_actualizar_asistencia = "UPDATE asistencia SET hora_salida = :hora_salida WHERE id_asistencia = :id_asistencia";
                $stmt_actualizar_asistencia = $conexion->prepare($consulta_actualizar_asistencia);
                $stmt_actualizar_asistencia->bindParam(':hora_salida', $hora_salida);
                $stmt_actualizar_asistencia->bindParam(':id_asistencia', $id_asistencia);
                $stmt_actualizar_asistencia->execute();

                // Redirigir al menú después de registrar la salida
                header("Location: " . getMenuURL($rol));
                exit;
            }
        }
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
    <title>Registrar Asistencia</title>
    <style>
        body {
            background-image: url("3.jpg");
            background-size: cover;
        }

        h1 {
            color: #ffffff;
            text-align: center;
        }

        .menu-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 10px;
            background-color: #f2f2f2;
            border-radius: 5px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 50px;
        }

        label {
            color: #ffffff;
            margin-bottom: 10px;
        }

        input[type="text"] {
            padding: 5px;
            border-radius: 5px;
            border: none;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            background-color: #4caf50;
            color: #ffffff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-top: 20px;
        }
        
        .date {
            color: #ffffff;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <h1>Registrar Asistencia</h1>

    <a class="menu-btn" href="<?php echo getMenuURL($rol); ?>">Regresar al Menú</a>

    <form action="registrar_asistencia.php" method="POST">
        <label for="rut">RUT:</label>
        <input type="text" id="rut" name="rut" value="<?php echo $rut; ?>" readonly required>

        <label for="hora_entrada">Hora de Entrada:</label>
        <input type="text" id="hora_entrada" name="hora_entrada" value="<?php echo $hora_entrada; ?>" readonly required>

        <?php if ($registrarSalidaHabilitado) { ?>
            <label for="hora_salida">Hora de Salida:</label>
            <input type="text" id="hora_salida" name="hora_salida" required>
        <?php } ?>

        <p class="date">Fecha actual: <?php echo date('d/m/Y'); ?></p>

        <?php if (!$registrarSalidaHabilitado) { ?>
            <input type="submit" name="registrar_entrada" value="Registrar Entrada">
        <?php } else { ?>
            <input type="submit" name="registrar_salida" value="Registrar Salida">
        <?php } ?>
    </form>

    <?php
    if ($error !== "") {
        echo "<p class=\"error-message\">Error al conectar a la base de datos: $error</p>";
    }
    ?>

    <script>
        var horaEntradaInput = document.getElementById('hora_entrada');
        var horaActual = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        horaEntradaInput.value = horaActual;

        <?php if ($registrarSalidaHabilitado) { ?>
            var horaSalidaInput = document.getElementById('hora_salida');
            horaSalidaInput.value = horaActual;
        <?php } ?>
    </script>
    
    <?php
    function getMenuURL($rol) {
        switch ($rol) {
            case 'Empleado Salud':
                return 'menu_empleado_salud.php';
            case 'Empleado Gestión':
                return 'menu_empleado_gestion.php';
            case 'Empleado':
                return 'menu_supervisor.php';
            default:
                return 'menu.php';
        }
    }
    ?>
</body>

</html>
