<?php
$host = 'magallanes.inf.unap.cl';
$port = '5432';
$dbname = 'jgomez';
$user = 'jgomez';
$password = '262m79VhrgMj';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
    $conexion = new PDO($dsn);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode($_POST['data'], true);

    $consulta = "UPDATE empleado SET nombre = :nombre, correo = :correo, edad = :edad, f_nacimiento = :f_nacimiento, direccion = :direccion, genero = :genero, grado_academico = :grado_academico, cargo = :cargo WHERE rut = :rut";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindValue(':rut', $data['rut']);
    $stmt->bindValue(':nombre', $data['nombre']);
    $stmt->bindValue(':correo', $data['correo']);
    $stmt->bindValue(':edad', $data['edad']);
    $stmt->bindValue(':f_nacimiento', $data['f_nacimiento']);
    $stmt->bindValue(':direccion', $data['direccion']);
    $stmt->bindValue(':genero', $data['genero']);
    $stmt->bindValue(':grado_academico', $data['grado_academico']);
    $stmt->bindValue(':cargo', $data['cargo']);
    $stmt->execute();

    // Verificar si se actualizó correctamente
    if ($stmt->rowCount() > 0) {
        echo "Los datos se han actualizado correctamente en la base de datos.";
    } else {
        echo "No se realizaron cambios en la base de datos.";
    }
} catch (PDOException $e) {
    echo "Error al actualizar los datos: " . $e->getMessage();
}
?>
