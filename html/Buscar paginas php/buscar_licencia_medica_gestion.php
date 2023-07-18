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
    <title>Buscar Licencia Médica</title>
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

        #licenciamedicaTable {
            position: relative;
            z-index: 1;
        }

        .acciones {
            text-align: center;
        }

        .acciones button {
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .acciones button:hover {
            background-color: #0056b3;
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
    </style>
</head>

<body>
    <h1>Buscar Licencia Médica</h1>

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
            <th>Lugar de Reposo</th>
            <th>Centro Médico</th>
            <th>Fecha de Otorgamiento</th>
            <th>Fecha de Inicio de Reposo</th>
            <th>Fecha de Término de Reposo</th>
            <th>Diagnóstico</th>
            <th>Médico</th>
            <th>Acciones</th>
        </tr>
        <?php
        foreach ($resultado as $fila) {
            echo "<tr>";
            echo "<td>" . $fila['id_licencia'] . "</td>";
            echo "<td>" . $fila['id_ficha'] . "</td>";
            echo "<td>" . $fila['rut'] . "</td>";
            echo "<td>" . $fila['emp_rut'] . "</td>";
            echo "<td contenteditable='false'>" . $fila['lugar_reposo'] . "</td>";
            echo "<td contenteditable='false'>" . $fila['centro_medico'] . "</td>";
            echo "<td contenteditable='false'>" . $fila['f_otorgamiento'] . "</td>";
            echo "<td contenteditable='false'>" . $fila['f_inicio_reposo'] . "</td>";
            echo "<td contenteditable='false'>" . $fila['f_termino_reposo'] . "</td>";
            echo "<td contenteditable='false'>" . $fila['diagnostico'] . "</td>";
            echo "<td contenteditable='false'>" . $fila['medico'] . "</td>";
            echo "<td class='acciones'><button onclick='enableEditing(this.parentElement.parentElement)'>Modificar</button></td>";
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

        function enableEditing(row) {
            var cells = row.getElementsByTagName("td");
            for (var i = 4; i < cells.length - 1; i++) {
                cells[i].setAttribute("contenteditable", "true");
            }
            row.getElementsByClassName("acciones")[0].innerHTML = "<button onclick='updateLicenciaMedica(this.parentElement.parentElement)'>Guardar</button>";
        }

        function updateLicenciaMedica(row) {
            var cells = row.getElementsByTagName("td");
            var data = {
                id_licencia: cells[0].innerText,
                id_ficha: cells[1].innerText,
                rut: cells[2].innerText,
                emp_rut: cells[3].innerText,
                lugar_reposo: cells[4].innerText,
                centro_medico: cells[5].innerText,
                f_otorgamiento: cells[6].innerText,
                f_inicio_reposo: cells[7].innerText,
                f_termino_reposo: cells[8].innerText,
                diagnostico: cells[9].innerText,
                medico: cells[10].innerText
            };

            // Realizar la solicitud AJAX para actualizar los datos en la base de datos
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    // La solicitud se completó correctamente, mostrar mensaje con SweetAlert2
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Datos modificados',
                        text: this.responseText,
                        showConfirmButton: false,
                        timer: 2000 // Duración del mensaje en milisegundos (en este caso, 2 segundos)
                    });

                    // Restaurar la tabla a modo visualización
                    for (var i = 4; i < cells.length - 1; i++) {
                        cells[i].setAttribute("contenteditable", "false");
                    }
                    row.getElementsByClassName("acciones")[0].innerHTML = "<button onclick='enableEditing(this.parentElement.parentElement)'>Modificar</button>";
                }
            };
            xhttp.open("POST", "actualizar_licencia_medica.php", true); // Archivo PHP para actualizar los datos
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("data=" + JSON.stringify(data));
        }

        document.getElementById("searchInput").addEventListener("input", filterLicenciaMedica);
    </script>
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
