<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["id"])) {
        $idAgenda = $_GET["id"];

        // Aquí puedes agregar tu lógica para eliminar la agenda de la base de datos
        $host = 'magallanes.inf.unap.cl';
        $port = '5432';
        $dbname = 'jgomez';
        $user = 'jgomez';
        $password = '262m79VhrgMj';

        try {
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
            $conexion = new PDO($dsn);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Preparar la consulta para eliminar la agenda con el ID específico
            $consulta = "DELETE FROM agenda WHERE id_agenda = :idAgenda";
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(':idAgenda', $idAgenda);
            $stmt->execute();

            // Se envía una respuesta de éxito (puedes personalizar el mensaje según tus necesidades)
            echo "La agenda con ID $idAgenda ha sido eliminada correctamente.";
        } catch (PDOException $e) {
            echo "Error al conectarse a la base de datos: " . $e->getMessage();
        }
    }
}
?>
