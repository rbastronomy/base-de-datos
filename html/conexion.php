<?php
$host = 'magallanes.inf.unap.cl'; // Normalmente es localhost
$port = '5432'; // Por defecto es 5432
$dbname = 'jgomez';
$user = 'jgomez';
$password = '262m79VhrgMj';

// Establecer conexión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rut = $_POST['rut'];
    $contrasena = $_POST['password'];

    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
    try {
        $conexion = new PDO($dsn);
        $sql = "SELECT * FROM empleado WHERE rut = :rut AND contrasena_usuario = :contrasena";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':rut', $rut);
        $stmt->bindParam(':contrasena', $contrasena);
        $stmt->execute();

        // Verificar si se encontró un registro
        if ($stmt->rowCount() == 1) {
            // Usuario y contraseña válidos, iniciar sesión o redireccionar a la página deseada
            // ...
            echo "Autenticación exitosa";
        } else {
            // Usuario o contraseña incorrectos, mostrar mensaje de error o redireccionar a la página de inicio de sesión
            // ...
            echo "Autenticación fallida";
        }
    } catch (PDOException $e) {
        echo "Error al conectarse a la base de datos: " . $e->getMessage();
    }
}
?>
