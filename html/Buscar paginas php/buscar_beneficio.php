<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Beneficio</title>
    <link rel="stylesheet" href="buscar_contrato_decoracion.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.min.css">
    <style>
        #message-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }
        
        #beneficioTable {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>
    <h1>Buscar Beneficio</h1>

    <div class="search-bar">
        <input type="text" id="searchInput" class="search-input" placeholder="Buscar beneficio..." />
        <a href="menu_empleado_gestion.php" class="back-button">Regresar al Menú</a>
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

        $consulta = "SELECT * FROM beneficio";
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

    <table id="beneficioTable">
        <tr>
            <th>ID Beneficio</th>
            <th>ID Contrato</th>
            <th>Almuerzo</th>
            <th>Locomoción</th>
            <th>Ayuda Económica</th>
            <th>Convenio Óptico</th>
            <th>Traslado Aéreo Fiscal</th>
            <th>Centro Recreacional</th>
            <th>Convenio Tiendas Comerciales</th>
            <th>Vivienda Fiscal</th>
            <th>Convenio Buses</th>
            <th>Acciones</th>
        </tr>
        <?php
        foreach ($resultado as $fila) {
            echo "<tr>";
            echo "<td>" . $fila['id_beneficio'] . "</td>";
            echo "<td>" . $fila['id_contrato'] . "</td>";
            echo "<td><input type='checkbox' " . ($fila['almuerzo'] ? 'checked' : '') . " disabled></td>";
            echo "<td><input type='checkbox' " . ($fila['locomocion'] ? 'checked' : '') . " disabled></td>";
            echo "<td><input type='checkbox' " . ($fila['ayuda_economica'] ? 'checked' : '') . " disabled></td>";
            echo "<td><input type='checkbox' " . ($fila['convenio_optico'] ? 'checked' : '') . " disabled></td>";
            echo "<td><input type='checkbox' " . ($fila['traslado_aereo_fiscal'] ? 'checked' : '') . " disabled></td>";
            echo "<td><input type='checkbox' " . ($fila['centro_recreacional'] ? 'checked' : '') . " disabled></td>";
            echo "<td><input type='checkbox' " . ($fila['convenio_tiendas_comerciales'] ? 'checked' : '') . " disabled></td>";
            echo "<td><input type='checkbox' " . ($fila['vivienda_fiscal'] ? 'checked' : '') . " disabled></td>";
            echo "<td><input type='checkbox' " . ($fila['convenio_buses'] ? 'checked' : '') . " disabled></td>";
            echo "<td><button onclick='enableEditing(this.parentElement.parentElement)'>Modificar</button></td>";
            echo "</tr>";
        }
        ?>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js"></script>
    <script>
        function filterBeneficios() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("beneficioTable");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0]; // Columna del ID de Beneficio
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
            var checkboxes = row.getElementsByTagName("input");
            for (var i = 2; i < cells.length - 1; i++) {
                checkboxes[i - 2].removeAttribute("disabled");
                checkboxes[i - 2].addEventListener("change", function() {
                    enableSaveButton(this.parentElement.parentElement);
                });
            }
            row.getElementsByTagName("button")[0].innerText = "Guardar";
            row.getElementsByTagName("button")[0].setAttribute("onclick", "saveBeneficioChanges(this.parentElement.parentElement)");
        }

        function enableSaveButton(row) {
            var saveButton = row.getElementsByTagName("button")[0];
            saveButton.disabled = false;
            saveButton.classList.add("save-button-enabled");
        }

        function saveBeneficioChanges(row) {
            var cells = row.getElementsByTagName("td");
            var checkboxes = row.getElementsByTagName("input");
            var data = {
                id_beneficio: cells[0].innerText,
                id_contrato: cells[1].innerText,
                almuerzo: checkboxes[0].checked ? 1 : 0,
                locomocion: checkboxes[1].checked ? 1 : 0,
                ayuda_economica: checkboxes[2].checked ? 1 : 0,
                convenio_optico: checkboxes[3].checked ? 1 : 0,
                traslado_aereo_fiscal: checkboxes[4].checked ? 1 : 0,
                centro_recreacional: checkboxes[5].checked ? 1 : 0,
                convenio_tiendas_comerciales: checkboxes[6].checked ? 1 : 0,
                vivienda_fiscal: checkboxes[7].checked ? 1 : 0,
                convenio_buses: checkboxes[8].checked ? 1 : 0
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
                    for (var i = 2; i < cells.length - 1; i++) {
                        checkboxes[i - 2].setAttribute("disabled", "disabled");
                        checkboxes[i - 2].removeEventListener("change", enableSaveButton);
                    }
                    row.getElementsByTagName("button")[0].innerText = "Modificar";
                    row.getElementsByTagName("button")[0].setAttribute("onclick", "enableEditing(this.parentElement.parentElement)");
                }
            };
            xhttp.open("POST", "actualizar_beneficio.php", true); // Archivo PHP para actualizar los datos
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("data=" + JSON.stringify(data));
        }

        document.getElementById("searchInput").addEventListener("input", filterBeneficios);
    </script>
</body>
</html>
