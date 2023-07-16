<?php
session_start();

// Realizar el cierre de sesión
session_destroy();

// Redirigir al inicio de sesión
header('Location: index.php');
exit();
?>
