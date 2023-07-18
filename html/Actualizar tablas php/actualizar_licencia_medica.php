<?php
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Aquí iría tu código para la actualización de licencias médicas en la base de datos
        $host = 'magallanes.inf.unap.cl';
        $port = '5432';
        $dbname = 'jgomez';
        $user = 'jgomez';
        $password = '262m79VhrgMj';

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
        $conexion = new PDO($dsn);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $data = json_decode($_POST['data'], true);

        $id_licencia = $data['id_licencia'];
        $id_ficha = $data['id_ficha'];
        $lugar_reposo = $data['lugar_reposo'];
        $centro_medico = $data['centro_medico'];
        $f_otorgamiento = $data['f_otorgamiento'];
        $f_inicio_reposo = $data['f_inicio_reposo'];
        $f_termino_reposo = $data['f_termino_reposo'];
        $diagnostico = $data['diagnostico'];
        $medico = $data['medico'];

        // Actualizar la tabla "licencias_medicas"
        $sql_licencia = "UPDATE licencia_medica
                        SET id_ficha = :id_ficha, lugar_reposo = :lugar_reposo, centro_medico = :centro_medico, f_otorgamiento = :f_otorgamiento, f_inicio_reposo = :f_inicio_reposo, f_termino_reposo = :f_termino_reposo, diagnostico = :diagnostico, medico = :medico
                        WHERE id_licencia = :id_licencia";

        $stmt_licencia = $conexion->prepare($sql_licencia);
        $stmt_licencia->bindParam(':id_ficha', $id_ficha);
        $stmt_licencia->bindParam(':lugar_reposo', $lugar_reposo);
        $stmt_licencia->bindParam(':centro_medico', $centro_medico);
        $stmt_licencia->bindParam(':f_otorgamiento', $f_otorgamiento);
        $stmt_licencia->bindParam(':f_inicio_reposo', $f_inicio_reposo);
        $stmt_licencia->bindParam(':f_termino_reposo', $f_termino_reposo);
        $stmt_licencia->bindParam(':diagnostico', $diagnostico);
        $stmt_licencia->bindParam(':medico', $medico);
        $stmt_licencia->bindParam(':id_licencia', $id_licencia);

        $stmt_licencia->execute();

        echo "Los datos de la licencia médica se han actualizado correctamente";
    } catch (PDOException $e) {
        $error = "Error al actualizar la licencia médica: " . $e->getMessage();
        echo $error;
    }
} else {
    echo "Método no permitido";
}
?>
