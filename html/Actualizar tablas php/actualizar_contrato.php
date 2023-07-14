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

    $idContrato = $data['id_contrato'];
    $rut = $data['rut'];
    $fechaInicio = $data['f_inicio'];
    $fechaTermino = $data['f_termino'];
    $horaEntrada = $data['hora_entrada'];
    $horasSemanales = $data['horas_semanales'];
    $sueldo = $data['sueldo'];
    $horaSalida = $data['hora_salida'];

    $consulta = "UPDATE contrato SET rut = ?, f_inicio = ?, f_termino = ?, hora_entrada = ?, horas_semanales = ?, sueldo = ?, hora_salida = ? WHERE id_contrato = ?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(1, $rut);
    $stmt->bindParam(2, $fechaInicio);
    $stmt->bindParam(3, $fechaTermino);
    $stmt->bindParam(4, $horaEntrada);
    $stmt->bindParam(5, $horasSemanales);
    $stmt->bindParam(6, $sueldo);
    $stmt->bindParam(7, $horaSalida);
    $stmt->bindParam(8, $idContrato);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "Los datos del contrato han sido actualizados correctamente.";
    } else {
        echo "No se encontró ningún contrato con el ID especificado.";
    }
} catch (PDOException $e) {
    echo "Error al conectarse a la base de datos: " . $e->getMessage();
}
?>
