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

    if (isset($_POST['data'])) {
        $data = json_decode($_POST['data'], true);

        $rut = $data['rut'];
        $celular = $data['celular'];
        $nombre = $data['nombre'];
        $correo = $data['correo'];
        $edad = $data['edad'];
        $f_nacimiento = $data['f_nacimiento'];
        $direccion = $data['direccion'];
        $genero = $data['genero'];
        $prevision = $data['prevision'];

        $consulta = "UPDATE paciente SET celular = :celular, nombre = :nombre, correo = :correo, edad = :edad, f_nacimiento = :f_nacimiento, direccion = :direccion, genero = :genero, prevision = :prevision WHERE rut = :rut";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':celular', $celular);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':edad', $edad);
        $stmt->bindParam(':f_nacimiento', $f_nacimiento);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':genero', $genero);
        $stmt->bindParam(':prevision', $prevision);
        $stmt->bindParam(':rut', $rut);

        if ($stmt->execute()) {
            echo "Los datos del paciente se han actualizado correctamente.";
        } else {
            echo "Error al actualizar los datos del paciente.";
        }
    } else {
        echo "No se han recibido datos para actualizar.";
    }
} catch (PDOException $e) {
    echo "Error al conectarse a la base de datos: " . $e->getMessage();
}
