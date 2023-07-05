<?php

$host = 'magallanes.inf.unap.cl'; // Normalmente es localhost
$port = '5432'; // Por defecto es 5432
$dbname = 'jgomez';
$user = 'jgomez';
$password = '262m79VhrgMj';

try {
    // Establecer conexión
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
    $conexion = new PDO($dsn);

    // Consulta para obtener los datos de la tabla "empleado"
    $sql = "SELECT * FROM empleado";
    $resultado = $conexion->query($sql);

    // Verificar si se encontraron resultados
    if (!$resultado) {
        die("Error al ejecutar la consulta: " . $conexion->errorInfo()[2]);
    }
} catch (PDOException $e) {
    echo "Error al conectarse a la base de datos: " . $e->getMessage();
}
?>
