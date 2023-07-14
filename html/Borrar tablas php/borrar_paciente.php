<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["rut"])) {
        $rut = $_GET["rut"];

        // Aquí puedes agregar tu lógica para eliminar el paciente de la base de datos
        $host = 'magallanes.inf.unap.cl';
        $port = '5432';
        $dbname = 'jgomez';
        $user = 'jgomez';
        $password = '262m79VhrgMj';

        try {
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
            $conexion = new PDO($dsn);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Preparar la consulta para eliminar el paciente con el RUT específico
            $consulta = "DELETE FROM paciente WHERE rut = :rut";
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(':rut', $rut);
            $stmt->execute();

            // Se envía una respuesta de éxito (puedes personalizar el mensaje según tus necesidades)
            echo "El paciente con RUT $rut ha sido eliminado correctamente.";
        } catch (PDOException $e) {
            echo "Error al conectarse a la base de datos: " . $e->getMessage();
        }
    }
}
?>
