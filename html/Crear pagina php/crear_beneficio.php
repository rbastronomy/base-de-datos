<?php
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Aquí iría tu código para la creación de beneficios asociados a contratos en la base de datos
        $host = 'magallanes.inf.unap.cl';
        $port = '5432';
        $dbname = 'jgomez';
        $user = 'jgomez';
        $password = '262m79VhrgMj';

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
        $conexion = new PDO($dsn);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id_contrato = $_POST['id_contrato'];
        $almuerzo = $_POST['almuerzo'];
        $locomocion = $_POST['locomocion'];
        $ayuda_economica = $_POST['ayuda_economica'];
        $convenio_optico = $_POST['convenio_optico'];
        $traslado_aereo_fiscal = $_POST['traslado_aereo_fiscal'];
        $centro_recreacional = $_POST['centro_recreacional'];
        $convenio_tiendas_comerciales = $_POST['convenio_tiendas_comerciales'];
        $vivienda_fiscal = $_POST['vivienda_fiscal'];
        $convenio_buses = $_POST['convenio_buses'];

        $sql = "INSERT INTO beneficio (ID_beneficio, ID_contrato, Almuerzo, Locomocion, Ayuda_economica, Convenio_Optico, Traslado_aereo_fiscal, Centro_recreacional, Convenio_Tiendas_Comerciales, Vivienda_Fiscal, Convenio_Buses)
                VALUES (:ID_beneficio, :ID_contrato, :Almuerzo, :Locomocion, :Ayuda_economica, :Convenio_Optico, :Traslado_aereo_fiscal, :Centro_recreacional, :Convenio_Tiendas_Comerciales, :Vivienda_Fiscal, :Convenio_Buses)";

        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(':ID_beneficio', $_POST['ID_beneficio']);
        $stmt->bindParam(':ID_contrato', $id_contrato);
        $stmt->bindParam(':Almuerzo', $almuerzo);
        $stmt->bindParam(':Locomocion', $locomocion);
        $stmt->bindParam(':Ayuda_economica', $ayuda_economica);
        $stmt->bindParam(':Convenio_Optico', $convenio_optico);
        $stmt->bindParam(':Traslado_aereo_fiscal', $traslado_aereo_fiscal);
        $stmt->bindParam(':Centro_recreacional', $centro_recreacional);
        $stmt->bindParam(':Convenio_Tiendas_Comerciales', $convenio_tiendas_comerciales);
        $stmt->bindParam(':Vivienda_Fiscal', $vivienda_fiscal);
        $stmt->bindParam(':Convenio_Buses', $convenio_buses);

        $stmt->execute();

        // Redireccionar a una página de éxito o mostrar un mensaje de éxito aquí
        header('Location: exito_beneficio.php');
        exit();
    } catch (PDOException $e) {
        $error = "Error al crear el beneficio: " . $e->getMessage();
    }
}

// Obtener los ID de contratos disponibles y sus nombres de empleados asociados
try {
    $host = 'magallanes.inf.unap.cl';
    $port = '5432';
    $dbname = 'jgomez';
    $user = 'jgomez';
    $password = '262m79VhrgMj';

    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
    $conexion = new PDO($dsn);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $consulta_contratos = "SELECT contrato.ID_contrato, empleado.nombre FROM contrato JOIN empleado ON contrato.rut = empleado.rut";
    $stmt_contratos = $conexion->prepare($consulta_contratos);
    $stmt_contratos->execute();
    $contratos = $stmt_contratos->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error al conectarse a la base de datos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Beneficio</title>
    <link rel="stylesheet" href="crear_beneficio_decoracion.css">
</head>

<body>
    <div class="container">
        <h1>Crear Beneficio</h1>

        <h2>Datos del Beneficio</h2>
        <form action="crear_beneficio.php" method="POST" class="beneficio-form">
            <div class="form-row">
                <label for="id_contrato">ID de Contrato - Nombre de Empleado:</label>
                <select id="id_contrato" name="id_contrato" required>
                    <?php if (!empty($contratos)): ?>
                        <?php foreach ($contratos as $contrato) : ?>
                            <option value="<?php echo $contrato['id_contrato']; ?>"><?php echo $contrato['id_contrato'] . ' - ' . $contrato['nombre']; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-row">
                <label for="id_beneficio">ID del Beneficio:</label>
                <input type="text" id="id_beneficio" name="ID_beneficio">
            </div>

            <div class="form-row">
                <label for="almuerzo">Almuerzo:</label>
                <input type="checkbox" id="almuerzo" name="almuerzo" value="1">
            </div>

            <div class="form-row">
                <label for="locomocion">Locomoción:</label>
                <input type="checkbox" id="locomocion" name="locomocion" value="1">
            </div>

            <div class="form-row">
                <label for="ayuda_economica">Ayuda Económica:</label>
                <input type="checkbox" id="ayuda_economica" name="ayuda_economica" value="1">
            </div>

            <div class="form-row">
                <label for="convenio_optico">Convenio Óptico:</label>
                <input type="checkbox" id="convenio_optico" name="convenio_optico" value="1">
            </div>

            <div class="form-row">
                <label for="traslado_aereo_fiscal">Traslado Aéreo Fiscal:</label>
                <input type="checkbox" id="traslado_aereo_fiscal" name="traslado_aereo_fiscal" value="1">
            </div>

            <div class="form-row">
                <label for="centro_recreacional">Centro Recreacional:</label>
                <input type="checkbox" id="centro_recreacional" name="centro_recreacional" value="1">
            </div>

            <div class="form-row">
                <label for="convenio_tiendas_comerciales">Convenio Tiendas Comerciales:</label>
                <input type="checkbox" id="convenio_tiendas_comerciales" name="convenio_tiendas_comerciales" value="1">
            </div>

            <div class="form-row">
                <label for="vivienda_fiscal">Vivienda Fiscal:</label>
                <input type="checkbox" id="vivienda_fiscal" name="vivienda_fiscal" value="1">
            </div>

            <div class="form-row">
                <label for="convenio_buses">Convenio Buses:</label>
                <input type="checkbox" id="convenio_buses" name="convenio_buses" value="1">
            </div>

            <div class="form-row form-row-button">
                <input type="submit" value="Crear Beneficio">
            </div>
        </form>

        <button id="menu-button" onclick="window.location.href='menu_empleado_gestion.php'">Menú</button>

        <?php
        if (isset($_GET['error'])) {
            echo "<p class='error-message'>Error al crear el beneficio: " . $_GET['error'] . "</p>";
        } elseif ($error !== "") {
            echo "<p class='error-message'>$error</p>";
        }
        ?>
    </div>
</body>

</html>
