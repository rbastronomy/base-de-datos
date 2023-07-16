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

        $id_agenda = $_POST['id_agenda'];
        $rut = $_POST['rut'];
        $estado_atencion = $_POST['estado_atencion'];
        $hora_citacion = $_POST['hora_citacion'];
        $fecha_agenda = $_POST['fecha_agenda'];
        $duracion_atencion = $_POST['duracion_atencion'];

        $sql = "INSERT INTO agenda (id_agenda, rut, estado_atencion, hora_citacion, fecha_agenda, duracion_atencion)
                VALUES (:id_agenda, :rut, :estado_atencion, :hora_citacion, :fecha_agenda, :duracion_atencion)";

        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id_agenda', $id_agenda);
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

<body>
    <div class="container">
        <h1>Crear Agenda</h1>

        <h2>Datos de la Agenda</h2>
        <form action="crear_agenda.php" method="POST" class="agenda-form">
            <div class="form-row">
                <label for="id_agenda">ID de Agenda:</label>
                <input type="text" id="id_agenda" name="id_agenda" required>
            </div>

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

        <button id="menu-button" onclick="window.location.href='menu_empleado_gestion.php'">Menú</button>

        <?php
        if (isset($_GET['error'])) {
            echo "<p class='error-message'>Error al crear la agenda: " . $_GET['error'] . "</p>";
        } elseif ($error !== "") {
            echo "<p class='error-message'>$error</p>";
        }
        ?>
    </div>
</body>

</html>
