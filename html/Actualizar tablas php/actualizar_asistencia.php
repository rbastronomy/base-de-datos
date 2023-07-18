<?php
$error = "";
$registrarSalidaHabilitado = false;

session_start();

// Obtener el RUT y rol de la sesión
$rut = isset($_SESSION['rut']) ? $_SESSION['rut'] : '';
$rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : '';

// Verificar si se ha iniciado sesión con un rol válido
if (empty($rol) || !in_array($rol, ['Empleado Salud', 'Empleado Gestión', 'Empleado', 'Supervisor'])) {
    // Redirigir a una página de error o mostrar un mensaje de error apropiado
    echo "Error: Rol de usuario inválido.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $host = 'magallanes.inf.unap.cl';
        $port = '5432';
        $dbname = 'jgomez';
        $user = 'jgomez';
        $password = '262m79VhrgMj';

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
        $conexion = new PDO($dsn);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $data = json_decode($_POST['data'], true);

        $id_asistencia = $data['id_asistencia'];
        $fecha_asistencia = $data['fecha_asistencia'];
        $hora_entrada = $data['hora_entrada'];
        $hora_salida = $data['hora_salida'];
        $estado_asistencia = $data['estado_asistencia'];
        $horas_trabajadas = $data['horas_trabajadas'];
        $razon_ausencia = $data['razon_ausencia'];

        // Validar el formato de hora (HH:MM:SS)
        if (!preg_match('/^\d{2}:\d{2}:\d{2}$/', $hora_entrada) || !preg_match('/^\d{2}:\d{2}:\d{2}$/', $hora_salida)) {
            echo "Error: El formato de hora no es válido.";
            exit;
        }

        // Validar que los campos de hora no estén vacíos
        if (empty($hora_entrada) || empty($hora_salida)) {
            echo "Error: Los campos de hora no pueden estar vacíos.";
            exit;
        }

        $sql = "UPDATE asistencia
                SET fecha_asistencia = :fecha_asistencia,
                    hora_entrada = :hora_entrada,
                    hora_salida = :hora_salida,
                    estado_asistencia = :estado_asistencia,
                    horas_trabajadas = :horas_trabajadas,
                    razon_ausencia = :razon_ausencia
                WHERE id_asistencia = :id_asistencia";

        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':fecha_asistencia', $fecha_asistencia);
        $stmt->bindParam(':hora_entrada', $hora_entrada);
        $stmt->bindParam(':hora_salida', $hora_salida);
        $stmt->bindParam(':estado_asistencia', $estado_asistencia);
        $stmt->bindParam(':horas_trabajadas', $horas_trabajadas);
        $stmt->bindParam(':razon_ausencia', $razon_ausencia);
        $stmt->bindParam(':id_asistencia', $id_asistencia);

        $stmt->execute();

        // Enviar una respuesta de éxito al cliente
        echo "Los datos se actualizaron correctamente.";
    } catch (PDOException $e) {
        // Enviar una respuesta de error al cliente
        echo "Error al actualizar los datos: " . $e->getMessage();
    }
}
?>
