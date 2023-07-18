<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode($_POST['data'], true);
    $id_ficha = $data['id_ficha'];
    $rut = $data['rut'];
    $diagnostico = $data['diagnostico'];
    $fecha_agenda = $data['fecha_agenda'];
    $tratamiento = $data['tratamiento'];
    $receta_medica = $data['receta_medica'];

    $host = 'magallanes.inf.unap.cl';
    $port = '5432';
    $dbname = 'jgomez';
    $user = 'jgomez';
    $password = '262m79VhrgMj';

    try {
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
        $conexion = new PDO($dsn);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $consulta = "UPDATE ficha_clinica
                    SET diagnostico = :diagnostico, fecha_agenda = :fecha_agenda, tratamiento = :tratamiento, receta_medica = :receta_medica
                    WHERE id_ficha = :id_ficha AND rut = :rut";

        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':diagnostico', $diagnostico);
        $stmt->bindParam(':fecha_agenda', $fecha_agenda);
        $stmt->bindParam(':tratamiento', $tratamiento);
        $stmt->bindParam(':receta_medica', $receta_medica);
        $stmt->bindParam(':id_ficha', $id_ficha);
        $stmt->bindParam(':rut', $rut);
        $stmt->execute();

        echo "Los datos se han actualizado correctamente.";
    } catch (PDOException $e) {
        echo "Error al actualizar los datos: " . $e->getMessage();
    }
} else {
    echo "Acceso no válido.";
}
?>
