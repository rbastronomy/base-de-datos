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

    $id_agenda = $data['id_agenda'];
    $estado_atencion = $data['estado_atencion'];
    $hora_citacion = $data['hora_citacion'];
    $fecha_agenda = $data['fecha_agenda'];
    $duracion_atencion = $data['duracion_atencion'];

    $consulta = "UPDATE agenda SET estado_atencion = :estado_atencion, hora_citacion = :hora_citacion, fecha_agenda = :fecha_agenda, duracion_atencion = :duracion_atencion WHERE id_agenda = :id_agenda";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':estado_atencion', $estado_atencion);
    $stmt->bindParam(':hora_citacion', $hora_citacion);
    $stmt->bindParam(':fecha_agenda', $fecha_agenda);
    $stmt->bindParam(':duracion_atencion', $duracion_atencion);
    $stmt->bindParam(':id_agenda', $id_agenda);
    $stmt->execute();

    echo "Los datos se actualizaron correctamente en la base de datos.";
} catch (PDOException $e) {
    echo "Error al actualizar los datos en la base de datos: " . $e->getMessage();
}
?>
