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

        $idContrato = $_POST['id_contrato'];
        $idBeneficio = $_POST['id_beneficio'];
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
        $sql_beneficio = "INSERT INTO beneficio (id_beneficio, almuerzo, locomocion, ayuda_economica, convenio_optico, traslado_aereo_fiscal, centro_recreacional, convenio_tiendas_comerciales, vivienda_fiscal, convenio_buses)
                        VALUES (:id_beneficio, :almuerzo, :locomocion, :ayuda_economica, :convenio_optico, :traslado_aereo_fiscal, :centro_recreacional, :convenio_tiendas_comerciales, :vivienda_fiscal, :convenio_buses)";

        $stmt_beneficio = $conexion->prepare($sql_beneficio);
        $stmt_beneficio->bindParam(':id_beneficio', $idBeneficio);
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

<body>
    <div class="container">
        <h1>Crear Beneficio</h1>

        <h2>Datos del Beneficio</h2>
        <form action="crear_beneficio.php" method="POST" class="paciente-form">
            <div class="form-row">
                <label for="id_contrato">ID Contrato:</label>
                <select id="id_contrato" name="id_contrato" required>
                    <?php
                    try {
                        $host = 'magallanes.inf.unap.cl';
                        $port = '5432';
                        $dbname = 'jgomez';
                        $user = 'jgomez';
                        $password = '262m79VhrgMj';

                        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
                        $conexion = new PDO($dsn);
                        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $stmt_contratos = $conexion->query("SELECT id_contrato FROM contrato");
                        $contratos = $stmt_contratos->fetchAll(PDO::FETCH_COLUMN);

                        foreach ($contratos as $contrato) {
                            echo "<option value='$contrato'>$contrato</option>";
                        }
                    } catch (PDOException $e) {
                        $error = "Error al obtener los contratos: " . $e->getMessage();
                    }
                    ?>
                </select>
            </div>

            <div class="form-row">
                <label for="id_beneficio">ID Beneficio:</label>
                <input type="text" id="id_beneficio" name="id_beneficio" required>
            </div>

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
