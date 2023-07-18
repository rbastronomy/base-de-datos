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
        // Aquí iría tu código para la creación de contratos en la base de datos
        $host = 'magallanes.inf.unap.cl';
        $port = '5432';
        $dbname = 'jgomez';
        $user = 'jgomez';
        $password = '262m79VhrgMj';

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
        $conexion = new PDO($dsn);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $rut = $_POST['rut'];
        $f_inicio = $_POST['f_inicio'];
        $f_termino = $_POST['f_termino'];
        $hora_entrada = $_POST['hora_entrada'];
        $sueldo = $_POST['sueldo'];
        $hora_salida = $_POST['hora_salida'];

        $sql = "INSERT INTO contrato (rut, f_inicio, f_termino, hora_entrada, sueldo, hora_salida)
                VALUES (:rut, :f_inicio, :f_termino, :hora_entrada, :sueldo, :hora_salida)";

        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':rut', $rut);
        $stmt->bindParam(':f_inicio', $f_inicio);
        $stmt->bindParam(':f_termino', $f_termino);
        $stmt->bindParam(':hora_entrada', $hora_entrada);
        $stmt->bindParam(':sueldo', $sueldo);
        $stmt->bindParam(':hora_salida', $hora_salida);

        $stmt->execute();

        // Redireccionar a una página de éxito o mostrar un mensaje de éxito aquí
        header('Location: exito_contrato.php');
        exit();
    } catch (PDOException $e) {
        $error = "Error al crear el contrato: " . $e->getMessage();
    }
}

// Obtener los RUTs de los empleados disponibles y sus nombres
try {
    $host = 'magallanes.inf.unap.cl';
    $port = '5432';
    $dbname = 'jgomez';
    $user = 'jgomez';
    $password = '262m79VhrgMj';

    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
    $conexion = new PDO($dsn);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $consulta_ruts = "SELECT rut, nombre FROM empleado";
    $stmt_ruts = $conexion->prepare($consulta_ruts);
    $stmt_ruts->execute();
    $ruts = $stmt_ruts->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error al conectarse a la base de datos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Contrato</title>
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
        <h1>Crear Contrato</h1>

        <h2>Datos del Contrato</h2>
        <form action="crear_contrato.php" method="POST" class="contrato-form">
            <div class="form-row">
                <label for="rut">RUT - Nombre de Empleado:</label>
                <select id="rut" name="rut" required>
                    <?php if (!empty($ruts)): ?>
                        <?php foreach ($ruts as $rut) : ?>
                            <option value="<?php echo $rut['rut']; ?>"><?php echo $rut['rut'] . ' - ' . $rut['nombre']; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-row">
                <label for="f_inicio">Fecha de Inicio:</label>
                <input type="date" id="f_inicio" name="f_inicio" required>
            </div>

            <div class="form-row">
                <label for="f_termino">Fecha de Término:</label>
                <input type="date" id="f_termino" name="f_termino" required>
            </div>

            <div class="form-row">
                <label for="hora_entrada">Hora de Entrada:</label>
                <input type="time" id="hora_entrada" name="hora_entrada" required>
            </div>

            <div class="form-row">
                <label for="sueldo">Sueldo:</label>
                <input type="number" id="sueldo" name="sueldo" required>
            </div>

            <div class="form-row">
                <label for="hora_salida">Hora de Salida:</label>
                <input type="time" id="hora_salida" name="hora_salida" required>
            </div>

            <div class="form-row form-row-button">
                <input type="submit" value="Crear Contrato">
            </div>
        </form>

        <a class="menu-btn" href="<?php echo getMenuURL($rol); ?>">Regresar al Menú</a>


        <?php
        if (isset($_GET['error'])) {
            echo "<p class='error-message'>Error al crear el contrato: " . $_GET['error'] . "</p>";
        } elseif ($error !== "") {
            echo "<p class='error-message'>$error</p>";
        }
        ?>
    </div>
    <?php
    function getMenuURL($rol)
    {
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
