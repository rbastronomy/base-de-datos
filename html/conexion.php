<?php

// datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = " ";
$dbname = "usuarios";

// crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// verificar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// definir la consulta SQL para crear la tabla de usuarios
$sql = "CREATE TABLE usuarios (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(30) NOT NULL,
        correo VARCHAR(50) NOT NULL,
        contraseña VARCHAR(30) NOT NULL
    )";

// ejecutar la consulta SQL
if ($conn->query($sql) === TRUE) {
    echo "Tabla creada exitosamente";
} else {
    echo "Error al crear la tabla: " . $conn->error;
}

// cerrar la conexión
$conn->close();

?>
