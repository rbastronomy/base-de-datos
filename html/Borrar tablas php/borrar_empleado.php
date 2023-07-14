<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["rut"])) {
        $rut = $_GET["rut"];

        // Aquí puedes agregar tu lógica para eliminar el empleado de la base de datos
        $host = 'magallanes.inf.unap.cl';
        $port = '5432';
        $dbname = 'jgomez';
        $user = 'jgomez';
        $password = '262m79VhrgMj';

        try {
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
            $conexion = new PDO($dsn);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Preparar la consulta para eliminar el empleado con el RUT específico de la tabla "empleado"
            $consultaEmpleado = "DELETE FROM empleado WHERE rut = :rut";
            $stmtEmpleado = $conexion->prepare($consultaEmpleado);
            $stmtEmpleado->bindParam(':rut', $rut);
            $stmtEmpleado->execute();

            // Preparar la consulta para eliminar el empleado con el RUT específico de la tabla "persona"
            $consultaPersona = "DELETE FROM persona WHERE rut = :rut";
            $stmtPersona = $conexion->prepare($consultaPersona);
            $stmtPersona->bindParam(':rut', $rut);
            $stmtPersona->execute();

            // Se envía una respuesta de éxito (puedes personalizar el mensaje según tus necesidades)
            echo "El empleado con RUT $rut ha sido eliminado correctamente de las tablas 'empleado' y 'persona'.";
        } catch (PDOException $e) {
            echo "Error al conectarse a la base de datos: " . $e->getMessage();
        }
    }
}
?>
