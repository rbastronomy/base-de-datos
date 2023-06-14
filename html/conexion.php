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

    // Verificar las credenciales para el login
    if (isset($_POST['rut']) && isset($_POST['correo'])) {
        $rut = $_POST['rut'];
        $correo = $_POST['correo'];

        // Consulta para verificar las credenciales
        $sql = "SELECT * FROM empleado WHERE rut = :rut AND correo = :correo";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':rut', $rut);
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();

        // Verificar si se encontraron resultados
        if ($stmt->rowCount() > 0) {
            // Las credenciales son correctas
            header('Location: menu.html');
            exit();
        } else {
            // Las credenciales son incorrectas
            header('Location: index.html');
            exit();
        }
    }
} catch (PDOException $e) {
    echo "Error al conectarse a la base de datos: " . $e->getMessage();
}
?>