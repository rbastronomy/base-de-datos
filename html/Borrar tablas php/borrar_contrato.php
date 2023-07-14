<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["id"])) {
        $idContrato = $_GET["id"];

        // Aquí puedes agregar tu lógica para eliminar el contrato de la base de datos
        $host = 'magallanes.inf.unap.cl';
        $port = '5432';
        $dbname = 'jgomez';
        $user = 'jgomez';
        $password = '262m79VhrgMj';

        try {
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
            $conexion = new PDO($dsn);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Preparar la consulta para eliminar el contrato con el ID específico
            $consulta = "DELETE FROM contrato WHERE id_contrato = :idContrato";
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(':idContrato', $idContrato);
            $stmt->execute();

            // Se envía una respuesta de éxito (puedes personalizar el mensaje según tus necesidades)
            echo "El contrato con ID $idContrato ha sido eliminado correctamente.";
        } catch (PDOException $e) {
            echo "Error al conectarse a la base de datos: " . $e->getMessage();
        }
    }
}
?>
