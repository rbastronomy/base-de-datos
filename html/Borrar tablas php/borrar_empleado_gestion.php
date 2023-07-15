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

    $rut = $_GET['rut'];

    // Borrar empleado de la tabla empleado_gestion
    $consulta = "DELETE FROM empleado_gestion WHERE rut = :rut";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':rut', $rut);
    $stmt->execute();

    // Borrar empleado de la tabla empleado
    $consulta = "DELETE FROM empleado WHERE rut = :rut";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':rut', $rut);
    $stmt->execute();

    // Borrar empleado de la tabla persona
    $consulta = "DELETE FROM persona WHERE rut = :rut";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':rut', $rut);
    $stmt->execute();

    echo "Empleado borrado correctamente desde empleado_gestion";
} catch (PDOException $e) {
    echo "Error al conectarse a la base de datos: " . $e->getMessage();
}
?>
