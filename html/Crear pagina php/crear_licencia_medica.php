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
        // Aquí iría tu código para la creación de licencias médicas en la base de datos
        $host = 'magallanes.inf.unap.cl';
        $port = '5432';
        $dbname = 'jgomez';
        $user = 'jgomez';
        $password = '262m79VhrgMj';

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
        $conexion = new PDO($dsn);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id_ficha = $_POST['id_ficha'];
        $lugar_reposo = $_POST['lugar_reposo'];
        $centro_medico = $_POST['centro_medico'];
        $f_otorgamiento = $_POST['f_otorgamiento'];
        $f_inicio_reposo = $_POST['f_inicio_reposo'];
        $f_termino_reposo = $_POST['f_termino_reposo'];
        $diagnostico = $_POST['diagnostico'];
        $medico = $_POST['medico'];

        // Insertar en la tabla "licencias_medicas"
        $sql_licencia = "INSERT INTO licencia_medica (id_ficha, lugar_reposo, centro_medico, f_otorgamiento, f_inicio_reposo, f_termino_reposo, diagnostico, medico)
                        VALUES (:id_ficha, :lugar_reposo, :centro_medico, :f_otorgamiento, :f_inicio_reposo, :f_termino_reposo, :diagnostico, :medico)";

        $stmt_licencia = $conexion->prepare($sql_licencia);
        $stmt_licencia->bindParam(':id_ficha', $id_ficha);
        $stmt_licencia->bindParam(':lugar_reposo', $lugar_reposo);
        $stmt_licencia->bindParam(':centro_medico', $centro_medico);
        $stmt_licencia->bindParam(':f_otorgamiento', $f_otorgamiento);
        $stmt_licencia->bindParam(':f_inicio_reposo', $f_inicio_reposo);
        $stmt_licencia->bindParam(':f_termino_reposo', $f_termino_reposo);
        $stmt_licencia->bindParam(':diagnostico', $diagnostico);
        $stmt_licencia->bindParam(':medico', $medico);

        $stmt_licencia->execute();

        // Redireccionar a una página de éxito o mostrar un mensaje de éxito aquí
        header('Location: exito_licencia_medica.php');
        exit();
    } catch (PDOException $e) {
        $error = "Error al crear la licencia médica: " . $e->getMessage();
    }
}

// Obtener las ID de ficha de la tabla "ficha_clinica" que NO sean empleados
try {
    $host = 'magallanes.inf.unap.cl';
    $port = '5432';
    $dbname = 'jgomez';
    $user = 'jgomez';
    $password = '262m79VhrgMj';

    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
    $conexion = new PDO($dsn);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt_fichas = $conexion->query("SELECT f.id_ficha
                                    FROM ficha_clinica f
                                    LEFT JOIN empleado e ON f.rut = e.rut
                                    WHERE e.rut IS NULL");
    $fichas = $stmt_fichas->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $error = "Error al obtener las ID de ficha: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Licencia Médica</title>
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
        <h1>Crear Licencia Médica</h1>

        <h2>Datos de la Licencia Médica</h2>
        <form action="crear_licencia_medica.php" method="POST" class="paciente-form">
            <div class="form-row">
                <label for="id_ficha">ID Ficha:</label>
                <select id="id_ficha" name="id_ficha" required>
                    <?php foreach ($fichas as $ficha) { ?>
                        <option value="<?php echo $ficha; ?>"><?php echo $ficha; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-row">
                <label for="lugar_reposo">Lugar de Reposo:</label>
                <input type="text" id="lugar_reposo" name="lugar_reposo" required>
            </div>

            <div class="form-row">
                <label for="centro_medico">Centro Médico:</label>
                <input type="text" id="centro_medico" name="centro_medico" required>
            </div>

            <div class="form-row">
                <label for="f_otorgamiento">Fecha de Otorgamiento:</label>
                <input type="date" id="f_otorgamiento" name="f_otorgamiento" required>
            </div>

            <div class="form-row">
                <label for="f_inicio_reposo">Fecha de Inicio de Reposo:</label>
                <input type="date" id="f_inicio_reposo" name="f_inicio_reposo" required>
            </div>

            <div class="form-row">
                <label for="f_termino_reposo">Fecha de Término de Reposo:</label>
                <input type="date" id="f_termino_reposo" name="f_termino_reposo" required>
            </div>

            <div class="form-row">
                <label for="diagnostico">Diagnóstico:</label>
                <input type="text" id="diagnostico" name="diagnostico" required>
            </div>

            <div class="form-row">
                <label for="medico">Médico:</label>
                <input type="text" id="medico" name="medico" required>
            </div>

            <div class="form-row form-row-button">
                <input type="submit" value="Crear Licencia Médica">
            </div>
        </form>

        <a class="menu-btn" href="<?php echo getMenuURL($rol); ?>">Regresar al Menú</a>

        <?php
        if (isset($_GET['error'])) {
            echo "<p class='error-message'>Error al crear la licencia médica: " . $_GET['error'] . "</p>";
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
