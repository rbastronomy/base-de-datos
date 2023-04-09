<?php
// credenciales de conexión a la base de datos
$db_host = 'localhost';
$db_user = 'usuario';
$db_password = 'contraseña';
$db_name = 'usuarios';

// conexión a la base de datos
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// verificación de las credenciales del usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM usuarios WHERE username='$username'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);

    if (password_verify($password, $user['password'])) {
      session_start();
      $_SESSION['username'] = $username;
      header('Location: menu.html');
      exit();
    } else {
      $error_msg = 'Contraseña incorrecta.';
    }
  } else {
    $error_msg = 'Usuario no encontrado.';
  }
}
?>