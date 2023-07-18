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
    <title>Borrar Asistencia</title>
    <link rel="stylesheet" href="eliminar_decoracion.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.min.css">

</head>
<style>
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
</style>
<body>
    <h1>Borrar Asistencia</h1>

    <div class="container">
        <div class="search-bar">
            <input type="text" id="searchInput" class="search-input" placeholder="Buscar asistencia..." />
            <a class="menu-btn" href="<?php echo getMenuURL($rol); ?>">Regresar al Menú</a>

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
                <th>Acciones</th>
            </tr>
            <?php
            foreach ($resultado as $fila) {
                echo "<tr>";
                echo "<td>" . $fila['id_asistencia'] . "</td>";
                echo "<td>" . $fila['rut'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['fecha_asistencia'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['hora_entrada'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['hora_salida'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['estado_asistencia'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['horas_trabajadas'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['razon_ausencia'] . "</td>";
                echo "<td><button class='action-button' onclick='confirmDelete(this.parentElement.parentElement)'>Borrar</button></td>";
                echo "</tr>";
            }
            ?>
        </table>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js"></script>
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

            function confirmDelete(row) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Borrar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteAsistencia(row);
                    }
                });
            }

            function deleteAsistencia(row) {
                var cells = row.getElementsByTagName("td");
                var idAsistencia = cells[0].innerText;

                // Realizar la solicitud AJAX para borrar la asistencia de la base de datos
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        // La solicitud se completó correctamente, mostrar mensaje con SweetAlert2
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Asistencia borrada',
                            text: this.responseText,
                            showConfirmButton: false,
                            timer: 2000 // Duración del mensaje en milisegundos (en este caso, 2 segundos)
                        });

                        // Eliminar la fila de la tabla
                        row.remove();
                    }
                };
                xhttp.open("GET", "borrar_asistencia.php?id=" + idAsistencia, true); // Archivo PHP para borrar la asistencia
                xhttp.send();
            }

            document.getElementById("searchInput").addEventListener("input", filterAsistencia);
        </script>
    </div>
    <?php
    function getMenuURL($rol) {
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
