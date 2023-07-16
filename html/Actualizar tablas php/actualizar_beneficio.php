<?php
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Aquí iría tu código para la actualización de beneficios en la base de datos
        $host = 'magallanes.inf.unap.cl';
        $port = '5432';
        $dbname = 'jgomez';
        $user = 'jgomez';
        $password = '262m79VhrgMj';

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
        $conexion = new PDO($dsn);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $data = json_decode($_POST['data'], true);

        $id_beneficio = $data['id_beneficio'];
        $id_contrato = $data['id_contrato'];
        $almuerzo = $data['almuerzo'];
        $locomocion = $data['locomocion'];
        $ayuda_economica = $data['ayuda_economica'];
        $convenio_optico = $data['convenio_optico'];
        $traslado_aereo_fiscal = $data['traslado_aereo_fiscal'];
        $centro_recreacional = $data['centro_recreacional'];
        $convenio_tiendas_comerciales = $data['convenio_tiendas_comerciales'];
        $vivienda_fiscal = $data['vivienda_fiscal'];
        $convenio_buses = $data['convenio_buses'];

        $sql = "UPDATE beneficio SET ID_contrato = :ID_contrato, Almuerzo = :Almuerzo, Locomocion = :Locomocion, Ayuda_economica = :Ayuda_economica, Convenio_Optico = :Convenio_Optico, Traslado_aereo_fiscal = :Traslado_aereo_fiscal, Centro_recreacional = :Centro_recreacional, Convenio_Tiendas_Comerciales = :Convenio_Tiendas_Comerciales, Vivienda_Fiscal = :Vivienda_Fiscal, Convenio_Buses = :Convenio_Buses WHERE ID_beneficio = :ID_beneficio";

        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':ID_beneficio', $id_beneficio);
        $stmt->bindParam(':ID_contrato', $id_contrato);
        $stmt->bindParam(':Almuerzo', $almuerzo);
        $stmt->bindParam(':Locomocion', $locomocion);
        $stmt->bindParam(':Ayuda_economica', $ayuda_economica);
        $stmt->bindParam(':Convenio_Optico', $convenio_optico);
        $stmt->bindParam(':Traslado_aereo_fiscal', $traslado_aereo_fiscal);
        $stmt->bindParam(':Centro_recreacional', $centro_recreacional);
        $stmt->bindParam(':Convenio_Tiendas_Comerciales', $convenio_tiendas_comerciales);
        $stmt->bindParam(':Vivienda_Fiscal', $vivienda_fiscal);
        $stmt->bindParam(':Convenio_Buses', $convenio_buses);

        $stmt->execute();

        // Redireccionar a una página de éxito o mostrar un mensaje de éxito aquí
        echo "Los datos del beneficio han sido actualizados con éxito";
    } catch (PDOException $e) {
        $error = "Error al actualizar el beneficio: " . $e->getMessage();
    }
}

if ($error !== "") {
    echo $error;
}
?>
