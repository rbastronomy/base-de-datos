<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Contrato</title>
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
        
        #contratoTable {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>
    <h1>Buscar Contrato</h1>

    <div class="search-bar">
        <input type="text" id="searchInput" class="search-input" placeholder="Buscar contrato..." />
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

        $consulta = "SELECT * FROM contrato";
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

    <table id="contratoTable">
        <tr>
            <th>ID Contrato</th>
            <th>RUT</th>
            <th>Fecha de Inicio</th>
            <th>Fecha de Término</th>
            <th>Hora de Entrada</th>
            <th>Horas Semanales</th>
            <th>Sueldo</th>
            <th>Hora de Salida</th>
            <th>Acciones</th>
        </tr>
        <?php
        foreach ($resultado as $fila) {
            echo "<tr>";
            echo "<td>" . $fila['id_contrato'] . "</td>";
            echo "<td>" . $fila['rut'] . "</td>";
            echo "<td contenteditable='false' oninput='enableSaveButton(this.parentElement)'>" . $fila['f_inicio'] . "</td>";
            echo "<td contenteditable='false' oninput='enableSaveButton(this.parentElement)'>" . $fila['f_termino'] . "</td>";
            echo "<td contenteditable='false' oninput='enableSaveButton(this.parentElement)'>" . $fila['hora_entrada'] . "</td>";
            echo "<td contenteditable='false' oninput='enableSaveButton(this.parentElement)'>" . $fila['horas_semanales'] . "</td>";
            echo "<td contenteditable='false' oninput='enableSaveButton(this.parentElement)'>" . $fila['sueldo'] . "</td>";
            echo "<td contenteditable='false' oninput='enableSaveButton(this.parentElement)'>" . $fila['hora_salida'] . "</td>";
            echo "<td><button onclick='enableEditing(this.parentElement.parentElement)'>Modificar</button></td>";
            echo "</tr>";
        }
        ?>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js"></script>
    <script>
        function filterContratos() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("contratoTable");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0]; // Columna del ID de Contrato
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
            for (var i = 2; i < cells.length - 1; i++) {
                cells[i].setAttribute("contenteditable", "true");
            }
            row.getElementsByTagName("button")[0].innerText = "Guardar";
            row.getElementsByTagName("button")[0].setAttribute("onclick", "saveContratoChanges(this.parentElement.parentElement)");
        }

        function enableSaveButton(row) {
            var saveButton = row.getElementsByTagName("button")[0];
            saveButton.disabled = false;
            saveButton.classList.add("save-button-enabled");
        }

        function saveContratoChanges(row) {
            var cells = row.getElementsByTagName("td");
            var data = {
                id_contrato: cells[0].innerText,
                rut: cells[1].innerText,
                f_inicio: cells[2].innerText,
                f_termino: cells[3].innerText,
                hora_entrada: cells[4].innerText,
                horas_semanales: cells[5].innerText,
                sueldo: cells[6].innerText,
                hora_salida: cells[7].innerText
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
                        cells[i].setAttribute("contenteditable", "false");
                    }
                    row.getElementsByTagName("button")[0].innerText = "Modificar";
                    row.getElementsByTagName("button")[0].setAttribute("onclick", "enableEditing(this.parentElement.parentElement)");
                }
            };
            xhttp.open("POST", "actualizar_contrato.php", true); // Archivo PHP para actualizar los datos
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("data=" + JSON.stringify(data));
        }

        document.getElementById("searchInput").addEventListener("input", filterContratos);
    </script>
</body>
</html>
