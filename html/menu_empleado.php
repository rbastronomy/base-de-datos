<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['rut'])) {
    // El usuario no ha iniciado sesión, redirigir a la página de inicio de sesión
    header("Location: index.php");
    exit();
}

// Obtener el rut del empleado de la sesión
$rut = $_SESSION['rut'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Menú</title>
    <link rel="stylesheet" href="menudecoracion.css">
    <style>
        .user-info {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #f1f1f1;
            padding: 10px;
            border-radius: 5px;
        }
        .menu-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .menu {
            flex: 1;
            margin-right: 10px;
        }
        .menu-title {
            font-weight: bold;
            font-size: 24px;
            text-align: center;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .logout-button {
            position: fixed;
            bottom: 10px;
            right: 10px;
            padding: 15px 20px;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body>
    <header>
        <h1>Policlinico Fuerza Aerea</h1>
        <h2></h2>
    </header>

    <!-- Agregar el cuadro de información del usuario -->
    <div class="user-info">
        <p>Nombre de usuario: <?php echo $_SESSION['nombre']; ?></p>
        <p>Rol: <?php echo $_SESSION['rol']; ?></p>
    </div>

    <div class="menu-container">
        <div class="menu">
            <p class="menu-title">Acciones</p>
            <nav class="left-menu">
                <a href="registrar_asistencia.php?rut=<?php echo $rut; ?>">Registrar Asistencia</a>
                <a href="licencia_medica.php?rut=<?php echo $rut; ?>">Licencia Medica</a>
            </nav>
        </div>
    </div>

    <a class="logout-button" href="index.php">Salir</a>

    <footer>
        <p>Derechos reservados</p>
    </footer>
</body>
</html>
