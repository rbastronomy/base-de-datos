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

    // Verificar el ID del beneficio a eliminar
    if (isset($_GET['id'])) {
        $idBeneficio = $_GET['id'];

        // Consulta para eliminar el beneficio
        $sql = "DELETE FROM beneficio WHERE id_beneficio = :idBeneficio";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idBeneficio', $idBeneficio);
        $stmt->execute();

        // Verificar si se eliminó el beneficio correctamente
        if ($stmt->rowCount() > 0) {
            echo "El beneficio se ha eliminado correctamente.";
        } else {
            echo "No se pudo eliminar el beneficio.";
        }
    } else {
        echo "ID de beneficio no proporcionado.";
    }
} catch (PDOException $e) {
    echo "Error al conectarse a la base de datos: " . $e->getMessage();
}
