<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Verificar si se proporcionó el ID de la licencia médica
    if (!isset($_GET['id_licencia'])) {
        header('Location: buscar_licencia_medica.php?error=No se proporcionó el ID de la licencia médica');
        exit();
    }

    $id_licencia = $_GET['id_licencia'];

    // Realizar la eliminación de la licencia médica en la base de datos
    try {
        $host = 'magallanes.inf.unap.cl';
        $port = '5432';
        $dbname = 'jgomez';
        $user = 'jgomez';
        $password = '262m79VhrgMj';

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
        $conexion = new PDO($dsn);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "DELETE FROM licencia_medica WHERE id_licencia = :id_licencia";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id_licencia', $id_licencia);
        $stmt->execute();

        header('Location: eliminar_licencia_medica.php?success=Licencia médica eliminada correctamente');
        exit();
    } catch (PDOException $e) {
        header('Location: eliminar_licencia_medica.php?error=Error al eliminar la licencia médica: ' . $e->getMessage());
        exit();
    }
} else {
    header('Location: eliminar_licencia_medica.php');
    exit();
}
