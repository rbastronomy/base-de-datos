<?php
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Aquí iría tu código para la eliminación de asistencias en la base de datos
        $host = 'magallanes.inf.unap.cl';
        $port = '5432';
        $dbname = 'jgomez';
        $user = 'jgomez';
        $password = '262m79VhrgMj';

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
        $conexion = new PDO($dsn);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $idAsistencia = $_GET['id'];

        // Eliminar la asistencia de la tabla "asistencia"
        $sql = "DELETE FROM asistencia WHERE id_asistencia = :id_asistencia";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id_asistencia', $idAsistencia);
        $stmt->execute();

        echo "La asistencia se ha borrado correctamente";
    } catch (PDOException $e) {
        $error = "Error al borrar la asistencia: " . $e->getMessage();
        echo $error;
    }
} else {
    echo "Método no permitido";
}
?>
