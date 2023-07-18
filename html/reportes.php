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
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes</title>
    <link rel="stylesheet" href="buscar_decoracion.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.min.css">
    <style>
        #message-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }

        #asistenciaTable {
            position: relative;
            z-index: 1;
        }

        .menu-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 10px;
            background-color: #f2f2f2;
            border-radius: 5px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }

        .report-btn {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
            z-index: 2;
        }

        .report-btn:hover {
            background-color: #0056b3;
        }

        .search-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .search-input {
            flex: 1;
            width: 50%;
            padding: 5px;
            margin-right: 10px;
        }

        .export-employee-btn {
            margin-top: 10px;
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .export-employee-btn:hover {
            background-color: #1e7e34;
        }
    </style>
</head>

<body>
    <h1>Buscar Asistencia</h1>

    <div class="search-bar">
        <input type="text" id="searchInput" class="search-input" placeholder="Buscar por RUT..." />
        
        <a class="menu-btn" href="<?php echo getMenuURL($rol); ?>">Regresar al Menú</a>
        <button class="report-btn" onclick="exportToExcel()" type="button">Exportar a Excel</button>
    </div>

    <div id="message-container"></div>

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

        $consulta = "SELECT * FROM asistencia";
        $stmt = $conexion->prepare($consulta);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$resultado) {
            die("Error al ejecutar la consulta: " . $conexion->errorInfo()[2]);
        }
    } catch (PDOException $e) {
        echo "Error al conectarse a la base de datos: " . $e->getMessage();
    }
    ?>

    <table id="asistenciaTable">
        <tr>
            <th>ID Asistencia</th>
            <th>RUT</th>
            <th>Fecha de Asistencia</th>
            <th>Hora de Entrada</th>
            <th>Hora de Salida</th>
            <th>Estado de Asistencia</th>
            <th>Horas Trabajadas</th>
            <th>Razón de Ausencia</th>
        </tr>
        <?php
        foreach ($resultado as $fila) {
            echo "<tr>";
            echo "<td>" . $fila['id_asistencia'] . "</td>";
            echo "<td>" . $fila['rut'] . "</td>";
            echo "<td>" . $fila['fecha_asistencia'] . "</td>";
            echo "<td>" . $fila['hora_entrada'] . "</td>";
            echo "<td>" . $fila['hora_salida'] . "</td>";
            echo "<td>" . $fila['estado_asistencia'] . "</td>";
            echo "<td>" . $fila['horas_trabajadas'] . "</td>";
            echo "<td>" . $fila['razon_ausencia'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js"></script>
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
    <script src="https://unpkg.com/file-saver"></script>
    <script>
        function filterAsistencia() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("asistenciaTable");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1]; // Columna del RUT
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        function exportToExcel() {
            var table = document.getElementById("asistenciaTable");
            var wb = XLSX.utils.table_to_book(table, { sheet: "Sheet JS" });
            var wbout = XLSX.write(wb, { bookType: 'xlsx', bookSST: true, type: 'binary' });

            function s2ab(s) {
                var buf = new ArrayBuffer(s.length);
                var view = new Uint8Array(buf);
                for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                return buf;
            }

            var blob = new Blob([s2ab(wbout)], { type: "application/octet-stream" });
            var fileName = "reporte_asistencia.xlsx";

            if (window.navigator.msSaveOrOpenBlob) {
                // For IE browser
                window.navigator.msSaveOrOpenBlob(blob, fileName);
            } else {
                // For other browsers
                var link = document.createElement("a");
                link.href = URL.createObjectURL(blob);
                link.download = fileName;
                link.click();
                URL.revokeObjectURL(link.href);
            }
        }

        function exportEmployee() {
            var startDate = document.getElementById("startDate").value;
            var endDate = document.getElementById("endDate").value;
            var rut = document.getElementById("searchInput").value;

            if (rut.trim() === "" || startDate.trim() === "" || endDate.trim() === "") {
                Swal.fire({
                    title: 'Error',
                    text: 'Por favor, completa el RUT y las fechas.',
                    icon: 'error',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
                return;
            }

            // Realizar la exportación del empleado con las fechas especificadas
            // Puedes realizar una petición al servidor para obtener los datos del empleado
            // y luego exportarlos en el formato deseado (por ejemplo, a Excel)

            // Ejemplo de petición al servidor usando fetch:
            fetch('exportar_empleado.php?rut=' + rut + '&startDate=' + startDate + '&endDate=' + endDate)
                .then(function (response) {
                    if (response.ok) {
                        return response.blob();
                    } else {
                        throw new Error('Error al exportar el empleado');
                    }
                })
                .then(function (blob) {
                    // Crear un enlace para descargar el archivo
                    var link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = "reporte_empleado.xlsx";
                    link.click();
                    URL.revokeObjectURL(link.href);

                    Swal.fire({
                        title: 'Empleado Exportado',
                        text: 'Los datos del empleado han sido exportados correctamente.',
                        icon: 'success',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                })
                .catch(function (error) {
                    Swal.fire({
                        title: 'Error',
                        text: error.message,
                        icon: 'error',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                });
        }

        document.getElementById("searchInput").addEventListener("input", filterAsistencia);
    </script>
    <?php
    function getMenuURL($rol)
    {
        switch ($rol) {
            case 'Empleado Salud':
                return 'menu_empleado_salud.php';
            case 'Empleado Gestión':
                return 'menu_empleado_gestion.php';
            case 'Empleado':
                return 'menu_supervisor.php';
            case 'Supervisor':
                return 'menu_supervisor.php';
            default:
                return 'menu.php';
        }
    }
    ?>
</body>

</html>
