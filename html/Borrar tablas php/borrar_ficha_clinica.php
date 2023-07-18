<?php
$host = 'magallanes.inf.unap.cl';
$port = '5432';
$dbname = 'jgomez';
$user = 'jgomez';
$password = '262m79VhrgMj';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
    $conexion = new PDO($dsn);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['id_ficha']) && isset($_GET['rut'])) {
        $id_ficha = $_GET['id_ficha'];
        $rut = $_GET['rut'];

        $consulta = "DELETE FROM ficha_clinica WHERE id_ficha = :id_ficha AND rut = :rut";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':id_ficha', $id_ficha, PDO::PARAM_INT);
        $stmt->bindParam(':rut', $rut, PDO::PARAM_STR);
        $stmt->execute();

        echo "La ficha clínica con ID $id_ficha y RUT $rut ha sido borrada correctamente.";
    } else {
        echo "Error: No se proporcionaron los parámetros necesarios.";
    }
} catch (PDOException $e) {
    echo "Error al conectarse a la base de datos: " . $e->getMessage();
}
?>
