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
?>
<?php
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Aquí iría tu código para la creación de agendas en la base de datos
        $host = 'magallanes.inf.unap.cl';
        $port = '5432';
        $dbname = 'jgomez';
        $user = 'jgomez';
        $password = '262m79VhrgMj';

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
        $conexion = new PDO($dsn);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $rut = $_POST['rut'];
        $estado_atencion = $_POST['estado_atencion'];
        $hora_citacion = $_POST['hora_citacion'];
        $fecha_agenda = $_POST['fecha_agenda'];
        $duracion_atencion = $_POST['duracion_atencion'];

        $sql = "INSERT INTO agenda (rut, estado_atencion, hora_citacion, fecha_agenda, duracion_atencion)
                VALUES (:rut, :estado_atencion, :hora_citacion, :fecha_agenda, :duracion_atencion)";

        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':rut', $rut);
        $stmt->bindParam(':estado_atencion', $estado_atencion);
        $stmt->bindParam(':hora_citacion', $hora_citacion);
        $stmt->bindParam(':fecha_agenda', $fecha_agenda);
        $stmt->bindParam(':duracion_atencion', $duracion_atencion);

        $stmt->execute();

        // Redireccionar a una página de éxito o mostrar un mensaje de éxito aquí
        header('Location: exito_agenda.php');
        exit();
    } catch (PDOException $e) {
        $error = "Error al crear la agenda: " . $e->getMessage();
    }
}

// Obtener los RUTs de los empleados de gestión disponibles
try {
    $host = 'magallanes.inf.unap.cl';
    $port = '5432';
    $dbname = 'jgomez';
    $user = 'jgomez';
    $password = '262m79VhrgMj';

    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
    $conexion = new PDO($dsn);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $consulta_ruts = "SELECT rut FROM empleado_gestion";
    $stmt_ruts = $conexion->prepare($consulta_ruts);
    $stmt_ruts->execute();
    $ruts = $stmt_ruts->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $error = "Error al conectarse a la base de datos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Agenda</title>
    <link rel="stylesheet" href="crear_decoracion.css">
</head>
<style>
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
</style>
<body>
    <div class="container">
        <h1>Crear Agenda</h1>

        <h2>Datos de la Agenda</h2>
        <form action="crear_agenda.php" method="POST" class="agenda-form">
            <div class="form-row">
                <label for="rut">RUT:</label>
                <select id="rut" name="rut" required>
                    <?php foreach ($ruts as $rut) : ?>
                        <option value="<?php echo $rut; ?>"><?php echo $rut; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <label for="estado_atencion">Estado de Atención:</label>
                <select id="estado_atencion" name="estado_atencion" required>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Atendido">Atendido</option>
                    <option value="Cancelado">Cancelado</option>
                    <option value="Ausente">Ausente</option>
                </select>
            </div>

            <div class="form-row">
                <label for="hora_citacion">Hora de Citación:</label>
                <input type="time" id="hora_citacion" name="hora_citacion" required>
            </div>

            <div class="form-row">
                <label for="fecha_agenda">Fecha de Agenda:</label>
                <input type="date" id="fecha_agenda" name="fecha_agenda" required>
            </div>

            <div class="form-row">
                <label for="duracion_atencion">Duración de Atención:</label>
                <input type="time" id="duracion_atencion" name="duracion_atencion" required>
            </div>

            <div class="form-row form-row-button">
                <input type="submit" value="Crear Agenda">
            </div>
        </form>

        <a class="menu-btn" href="<?php echo getMenuURL($rol); ?>">Regresar al Menú</a>
        

        <?php
        if (isset($_GET['error'])) {
            echo "<p class='error-message'>Error al crear la agenda: " . $_GET['error'] . "</p>";
        } elseif ($error !== "") {
            echo "<p class='error-message'>$error</p>";
        }
        ?>
    </div>
    <?php
    function getMenuURL($rol) {
        switch ($rol) {
            case 'Empleado Salud':
                return 'menu_empleado_salud.php';
            case 'Empleado Gestión':
                return 'menu_empleado_gestion.php';
            case 'Empleado':
                return 'menu_supervisor.php';
            case 'Supervisor':
                return 'menu_supervisor.php';
            default:
                return 'menu.php';
        }
    }
    ?>
</body>
</html>
