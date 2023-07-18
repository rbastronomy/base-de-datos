<?php
// Recibir los datos enviados por la solicitud AJAX
$data = $_POST['data'];
$data = json_decode($data, true);

// Realizar las operaciones de actualización en la base de datos
// Aquí debes escribir el código específico para tu base de datos y tabla

// Ejemplo: Actualizar la tabla 'beneficio' con los nuevos valores
$host = 'magallanes.inf.unap.cl';
$port = '5432';
$dbname = 'jgomez';
$user = 'jgomez';
$password = '262m79VhrgMj';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
    $conexion = new PDO($dsn);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $idBeneficio = $data['id_beneficio'];
    $almuerzo = $data['almuerzo'];
    $locomocion = $data['locomocion'];
    $ayudaEconomica = $data['ayuda_economica'];
    $convenioOptico = $data['convenio_optico'];
    $trasladoAereoFiscal = $data['traslado_aereo_fiscal'];
    $centroRecreacional = $data['centro_recreacional'];
    $convenioTiendasComerciales = $data['convenio_tiendas_comerciales'];
    $viviendaFiscal = $data['vivienda_fiscal'];
    $convenioBuses = $data['convenio_buses'];

    // Construir la consulta SQL para actualizar los valores en la base de datos
    $consulta = "UPDATE beneficio SET almuerzo = :almuerzo, locomocion = :locomocion, ayuda_economica = :ayuda_economica, convenio_optico = :convenio_optico, traslado_aereo_fiscal = :traslado_aereo_fiscal, centro_recreacional = :centro_recreacional, convenio_tiendas_comerciales = :convenio_tiendas_comerciales, vivienda_fiscal = :vivienda_fiscal, convenio_buses = :convenio_buses WHERE id_beneficio = :id_beneficio";

    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':almuerzo', $almuerzo, PDO::PARAM_INT);
    $stmt->bindParam(':locomocion', $locomocion, PDO::PARAM_INT);
    $stmt->bindParam(':ayuda_economica', $ayudaEconomica, PDO::PARAM_INT);
    $stmt->bindParam(':convenio_optico', $convenioOptico, PDO::PARAM_INT);
    $stmt->bindParam(':traslado_aereo_fiscal', $trasladoAereoFiscal, PDO::PARAM_INT);
    $stmt->bindParam(':centro_recreacional', $centroRecreacional, PDO::PARAM_INT);
    $stmt->bindParam(':convenio_tiendas_comerciales', $convenioTiendasComerciales, PDO::PARAM_INT);
    $stmt->bindParam(':vivienda_fiscal', $viviendaFiscal, PDO::PARAM_INT);
    $stmt->bindParam(':convenio_buses', $convenioBuses, PDO::PARAM_INT);
    $stmt->bindParam(':id_beneficio', $idBeneficio, PDO::PARAM_INT);

    $stmt->execute();

    // Si todo se ha realizado correctamente, envía una respuesta exitosa
    echo "Los datos se han actualizado correctamente";
} catch (PDOException $e) {
    // En caso de error, envía una respuesta con el mensaje de error
    echo "Error al actualizar los datos: " . $e->getMessage();
}
?>
