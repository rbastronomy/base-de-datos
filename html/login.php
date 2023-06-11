<?php
// Paso 1: Establecer la conexión con la base de datos
$servername = "magallanes.inf.unap.cl"; // Reemplaza con el nombre del servidor de la base de datos
$username = "jgomez"; // Reemplaza con el nombre de usuario de la base de datos
$password = "262m79VhrgMj"; // Reemplaza con la contraseña de la base de datos
$database = "jgomez"; // Reemplaza con el nombre de la base de datos

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
  die("Error al conectar con la base de datos: " . $conn->connect_error);
}

// Paso 2: Obtener los valores del formulario de inicio de sesión
$rut = $_POST['rut'];
$password = $_POST['password'];

// Paso 3: Verificar las credenciales en la base de datos
$query = "SELECT * FROM usuarios WHERE rut = '$rut' AND password = '$password'";
$result = $conn->query($query);

if ($result->num_rows == 1) {
  // Las credenciales son válidas, redireccionar al menú principal
  header("Location: menu.html");
  exit();
} else {
  // Las credenciales son inválidas, redireccionar a la página de inicio de sesión con un mensaje de error
  header("Location: index.html?error=1");
  exit();
}

// Paso 4: Cerrar la conexión con la base de datos
$conn->close();
?>
