<?php
// Recibir datos del formulario
$rut = $_POST['rut'];
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$genero = $_POST['genero'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$funcionario_salud = $_POST['funcionario_salud'];

// Crear array con los datos del paciente
$paciente = [$rut, $nombre, $correo, $genero, $fecha_nacimiento, $funcionario_salud];

// Abrir archivo CSV para añadir el registro
$archivo = fopen('pacientes.csv', 'a');

// Escribir los datos del paciente en el archivo CSV
fputcsv($archivo, $paciente);

// Cerrar archivo
fclose($archivo);

// Redireccionar al menú
header('Location: menu.html');
?>
