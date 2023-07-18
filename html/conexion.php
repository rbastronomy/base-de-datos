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
    if (isset($_POST['rut']) && isset($_POST['contrasena'])) {
        $rut = $_POST['rut'];
        $contrasena = $_POST['contrasena'];

        // Consulta para verificar las credenciales en empleado_gestion
        $sql_gestion = "SELECT * FROM empleado_gestion WHERE rut = :rut AND contrasena = :contrasena";
        $stmt_gestion = $conexion->prepare($sql_gestion);
        $stmt_gestion->bindParam(':rut', $rut);
        $stmt_gestion->bindParam(':contrasena', $contrasena);
        $stmt_gestion->execute();

        // Verificar si se encontraron resultados en empleado_gestion
        if ($stmt_gestion->rowCount() > 0) {
            // Obtener los datos del empleado_gestion
            $empleado = $stmt_gestion->fetch(PDO::FETCH_ASSOC);

            // Iniciar sesión y almacenar los datos del empleado_gestion
            session_start();
            $_SESSION['rut'] = $empleado['rut'];
            $_SESSION['nombre'] = $empleado['nombre'];
            $_SESSION['rol'] = 'Empleado Gestión';
            $_SESSION['estado_asistencia'] = 0;

            header('Location: menu_empleado_gestion.php');
            exit();
        }

        // Consulta para verificar las credenciales en empleado_salud
        $sql_salud = "SELECT * FROM empleado_salud WHERE rut = :rut AND contrasena = :contrasena";
        $stmt_salud = $conexion->prepare($sql_salud);
        $stmt_salud->bindParam(':rut', $rut);
        $stmt_salud->bindParam(':contrasena', $contrasena);
        $stmt_salud->execute();

        // Verificar si se encontraron resultados en empleado_salud
        if ($stmt_salud->rowCount() > 0) {
            // Obtener los datos del empleado_salud
            $empleado = $stmt_salud->fetch(PDO::FETCH_ASSOC);

            // Iniciar sesión y almacenar los datos del empleado_salud
            session_start();
            $_SESSION['rut'] = $empleado['rut'];
            $_SESSION['nombre'] = $empleado['nombre'];
            $_SESSION['rol'] = 'Empleado Salud';

            header('Location: menu_empleado_salud.php');
            exit();
        }

        // Consulta para verificar las credenciales en empleado
        $sql_empleado = "SELECT * FROM empleado WHERE rut = :rut AND contrasena = :contrasena";
        $stmt_empleado = $conexion->prepare($sql_empleado);
        $stmt_empleado->bindParam(':rut', $rut);
        $stmt_empleado->bindParam(':contrasena', $contrasena);
        $stmt_empleado->execute();

        // Verificar si se encontraron resultados en empleado
        if ($stmt_empleado->rowCount() > 0) {
            // Obtener los datos del empleado
            $empleado = $stmt_empleado->fetch(PDO::FETCH_ASSOC);

            // Iniciar sesión y almacenar los datos del empleado
            session_start();
            $_SESSION['rut'] = $empleado['rut'];
            $_SESSION['nombre'] = $empleado['nombre'];
            $_SESSION['rol'] = 'Empleado';

            // Verificar el tipo de cargo del empleado normal
            if ($empleado['cargo'] == 'Supervisor') {
                header('Location: menu_supervisor.php');
            } else {
                header('Location: menu_empleado.php');
            }
            exit();
        }
    }

    // Las credenciales son incorrectas o no se encontró el rut en ninguna tabla
    header('Location: index.php');
    exit();
} catch (PDOException $e) {
    echo "Error al conectarse a la base de datos: " . $e->getMessage();
}
