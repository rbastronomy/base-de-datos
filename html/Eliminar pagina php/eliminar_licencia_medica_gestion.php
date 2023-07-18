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
    <title>Borrar Licencia Médica</title>
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
    <h1>Borrar Licencia Médica</h1>

    <div class="container">
        <div class="search-bar">
            <input type="text" id="searchInput" class="search-input" placeholder="Buscar licencia médica..." />
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

            $consulta = "SELECT id_licencia, id_ficha, rut, emp_rut, lugar_reposo, centro_medico, f_otorgamiento, f_inicio_reposo, f_termino_reposo, diagnostico, medico FROM licencia_medica";
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

        <table id="licenciamedicaTable">
            <tr>
                <th>ID Licencia</th>
                <th>ID Ficha</th>
                <th>RUT</th>
                <th>RUT Empleado</th>
                <th>Lugar Reposo</th>
                <th>Centro Médico</th>
                <th>F. Otorgamiento</th>
                <th>F. Inicio Reposo</th>
                <th>F. Término Reposo</th>
                <th>Diagnóstico</th>
                <th>Médico</th>
                <th>Acciones</th>
            </tr>
            <?php
            foreach ($resultado as $fila) {
                echo "<tr>";
                echo "<td>" . $fila['id_licencia'] . "</td>";
                echo "<td>" . $fila['id_ficha'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['rut'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['emp_rut'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['lugar_reposo'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['centro_medico'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['f_otorgamiento'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['f_inicio_reposo'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['f_termino_reposo'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['diagnostico'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['medico'] . "</td>";
                echo "<td><button class='action-button' onclick='confirmDelete(this.parentElement.parentElement)'>Borrar</button></td>";
                echo "</tr>";
            }
            ?>
        </table>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js"></script>
        <script>
            function filterLicenciaMedica() {
                var input, filter, table, tr, td, i, txtValue;
                input = document.getElementById("searchInput");
                filter = input.value.toUpperCase();
                table = document.getElementById("licenciamedicaTable");
                tr = table.getElementsByTagName("tr");

                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td")[0]; // Columna del ID de Licencia
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
                        deleteLicenciaMedica(row);
                    }
                });
            }

            function deleteLicenciaMedica(row) {
                var cells = row.getElementsByTagName("td");
                var id_licencia = cells[0].innerText;

                // Realizar la solicitud AJAX para borrar la licencia médica de la base de datos
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        // La solicitud se completó correctamente, mostrar mensaje con SweetAlert2
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Licencia médica borrada',
                            text: this.responseText,
                            showConfirmButton: false,
                            timer: 2000 // Duración del mensaje en milisegundos (en este caso, 2 segundos)
                        });

                        // Eliminar la fila de la tabla
                        row.remove();
                    }
                };
                xhttp.open("GET", "borrar_licencia_medica.php?id_licencia=" + id_licencia, true); // Archivo PHP para borrar la licencia médica
                xhttp.send();
            }

            document.getElementById("searchInput").addEventListener("input", filterLicenciaMedica);
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
