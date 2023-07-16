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

        $id_contrato = $_POST['id_contrato'];
        $rut = $_POST['rut'];
        $f_inicio = $_POST['f_inicio'];
        $f_termino = $_POST['f_termino'];
        $hora_entrada = $_POST['hora_entrada'];
        $horas_semanales = $_POST['horas_semanales'];
        $sueldo = $_POST['sueldo'];
        $hora_salida = $_POST['hora_salida'];

        $sql = "INSERT INTO contrato (id_contrato, rut, f_inicio, f_termino, hora_entrada, horas_semanales, sueldo, hora_salida)
                VALUES (:id_contrato, :rut, :f_inicio, :f_termino, :hora_entrada, :horas_semanales, :sueldo, :hora_salida)";

        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id_contrato', $id_contrato);
        $stmt->bindParam(':rut', $rut);
        $stmt->bindParam(':f_inicio', $f_inicio);
        $stmt->bindParam(':f_termino', $f_termino);
        $stmt->bindParam(':hora_entrada', $hora_entrada);
        $stmt->bindParam(':horas_semanales', $horas_semanales);
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
    <link rel="stylesheet" href="crear_contrato_decoracion.css">
</head>

<body>
    <div class="container">
        <h1>Crear Contrato</h1>

        <h2>Datos del Contrato</h2>
        <form action="crear_contrato.php" method="POST" class="contrato-form">
            <div class="form-row">
                <label for="id_contrato">ID de Contrato:</label>
                <input type="text" id="id_contrato" name="id_contrato" required>
            </div>

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
                <label for="horas_semanales">Horas Semanales:</label>
                <input type="text" id="horas_semanales" name="horas_semanales" required>
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

        <button id="menu-button" onclick="window.location.href='menu.php'">Menú</button>

        <?php
        if (isset($_GET['error'])) {
            echo "<p class='error-message'>Error al crear el contrato: " . $_GET['error'] . "</p>";
        } elseif ($error !== "") {
            echo "<p class='error-message'>$error</p>";
        }
        ?>
    </div>
</body>

</html>