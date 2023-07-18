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
        // Aquí iría tu código para la creación de beneficios en la base de datos
        $host = 'magallanes.inf.unap.cl';
        $port = '5432';
        $dbname = 'jgomez';
        $user = 'jgomez';
        $password = '262m79VhrgMj';

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
        $conexion = new PDO($dsn);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $almuerzo = isset($_POST['almuerzo']) ? 1 : 0;
        $locomocion = isset($_POST['locomocion']) ? 1 : 0;
        $ayudaEconomica = isset($_POST['ayuda_economica']) ? 1 : 0;
        $convenioOptico = isset($_POST['convenio_optico']) ? 1 : 0;
        $trasladoAereoFiscal = isset($_POST['traslado_aereo_fiscal']) ? 1 : 0;
        $centroRecreacional = isset($_POST['centro_recreacional']) ? 1 : 0;
        $convenioTiendasComerciales = isset($_POST['convenio_tiendas_comerciales']) ? 1 : 0;
        $viviendaFiscal = isset($_POST['vivienda_fiscal']) ? 1 : 0;
        $convenioBuses = isset($_POST['convenio_buses']) ? 1 : 0;

        // Insertar en la tabla "beneficio"
        $sql_beneficio = "INSERT INTO beneficio (almuerzo, locomocion, ayuda_economica, convenio_optico, traslado_aereo_fiscal, centro_recreacional, convenio_tiendas_comerciales, vivienda_fiscal, convenio_buses)
                        VALUES (:almuerzo, :locomocion, :ayuda_economica, :convenio_optico, :traslado_aereo_fiscal, :centro_recreacional, :convenio_tiendas_comerciales, :vivienda_fiscal, :convenio_buses)";

        $stmt_beneficio = $conexion->prepare($sql_beneficio);
        $stmt_beneficio->bindParam(':almuerzo', $almuerzo);
        $stmt_beneficio->bindParam(':locomocion', $locomocion);
        $stmt_beneficio->bindParam(':ayuda_economica', $ayudaEconomica);
        $stmt_beneficio->bindParam(':convenio_optico', $convenioOptico);
        $stmt_beneficio->bindParam(':traslado_aereo_fiscal', $trasladoAereoFiscal);
        $stmt_beneficio->bindParam(':centro_recreacional', $centroRecreacional);
        $stmt_beneficio->bindParam(':convenio_tiendas_comerciales', $convenioTiendasComerciales);
        $stmt_beneficio->bindParam(':vivienda_fiscal', $viviendaFiscal);
        $stmt_beneficio->bindParam(':convenio_buses', $convenioBuses);

        $stmt_beneficio->execute();

        // Redireccionar a una página de éxito o mostrar un mensaje de éxito aquí
        header('Location: exito_beneficio.php');
        exit();
    } catch (PDOException $e) {
        $error = "Error al crear el beneficio: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Beneficio</title>
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
        <h1>Crear Beneficio</h1>

        <h2>Datos del Beneficio</h2>
        <form action="crear_beneficio.php" method="POST" class="paciente-form">
            <div class="form-row">
                <label for="almuerzo">Almuerzo:</label>
                <input type="checkbox" id="almuerzo" name="almuerzo">
            </div>

            <div class="form-row">
                <label for="locomocion">Locomoción:</label>
                <input type="checkbox" id="locomocion" name="locomocion">
            </div>

            <div class="form-row">
                <label for="ayuda_economica">Ayuda Económica:</label>
                <input type="checkbox" id="ayuda_economica" name="ayuda_economica">
            </div>

            <div class="form-row">
                <label for="convenio_optico">Convenio Óptico:</label>
                <input type="checkbox" id="convenio_optico" name="convenio_optico">
            </div>

            <div class="form-row">
                <label for="traslado_aereo_fiscal">Traslado Aéreo Fiscal:</label>
                <input type="checkbox" id="traslado_aereo_fiscal" name="traslado_aereo_fiscal">
            </div>

            <div class="form-row">
                <label for="centro_recreacional">Centro Recreacional:</label>
                <input type="checkbox" id="centro_recreacional" name="centro_recreacional">
            </div>

            <div class="form-row">
                <label for="convenio_tiendas_comerciales">Convenio Tiendas Comerciales:</label>
                <input type="checkbox" id="convenio_tiendas_comerciales" name="convenio_tiendas_comerciales">
            </div>

            <div class="form-row">
                <label for="vivienda_fiscal">Vivienda Fiscal:</label>
                <input type="checkbox" id="vivienda_fiscal" name="vivienda_fiscal">
            </div>

            <div class="form-row">
                <label for="convenio_buses">Convenio Buses:</label>
                <input type="checkbox" id="convenio_buses" name="convenio_buses">
            </div>

            <div class="form-row form-row-button">
                <input type="submit" value="Crear Beneficio">
            </div>
        </form>

        <a class="menu-btn" href="<?php echo getMenuURL($rol); ?>">Regresar al Menú</a>


        <?php
        if (isset($_GET['error'])) {
            echo "<p class='error-message'>Error al crear el beneficio: " . $_GET['error'] . "</p>";
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
