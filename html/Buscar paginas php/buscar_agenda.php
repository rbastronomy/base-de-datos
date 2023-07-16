<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Agenda</title>
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
        
        #beneficioTable {
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
    </style>
</head>
<body>
    <h1>Buscar Agenda</h1>

    <div class="search-bar">
        <input type="text" id="searchInput" class="search-input" placeholder="Buscar agenda..." />
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

        $consulta = "SELECT * FROM agenda";
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

    <table id="agendaTable">
        <tr>
            <th>ID Agenda</th>
            <th>RUT</th>
            <th>Estado de Atención</th>
            <th>Hora de Citación</th>
            <th>Fecha de Agenda</th>
            <th>Duración de Atención</th>
            <th>Acciones</th>
        </tr>
        <?php
        foreach ($resultado as $fila) {
            echo "<tr>";
            echo "<td>" . $fila['id_agenda'] . "</td>";
            echo "<td>" . $fila['rut'] . "</td>";
            echo "<td contenteditable='false'>" . $fila['estado_atencion'] . "</td>";
            echo "<td contenteditable='false'>" . $fila['hora_citacion'] . "</td>";
            echo "<td contenteditable='false'>" . $fila['fecha_agenda'] . "</td>";
            echo "<td contenteditable='false'>" . $fila['duracion_atencion'] . "</td>";
            echo "<td class='acciones'><button onclick='enableEditing(this.parentElement.parentElement)'>Modificar</button></td>";
            echo "</tr>";
        }
        ?>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js"></script>
    <script>
        function filterAgendas() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("agendaTable");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0]; // Columna del ID de Agenda
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
            row.getElementsByClassName("acciones")[0].innerHTML = "<button onclick='updateAgenda(this.parentElement.parentElement)'>Guardar</button>";
        }

        function updateAgenda(row) {
            var cells = row.getElementsByTagName("td");
            var data = {
                id_agenda: cells[0].innerText,
                rut: cells[1].innerText,
                estado_atencion: cells[2].innerText,
                hora_citacion: cells[3].innerText,
                fecha_agenda: cells[4].innerText,
                duracion_atencion: cells[5].innerText
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
                    row.getElementsByClassName("acciones")[0].innerHTML = "<button onclick='enableEditing(this.parentElement.parentElement)'>Modificar</button>";
                }
            };
            xhttp.open("POST", "actualizar_agenda.php", true); // Archivo PHP para actualizar los datos
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("data=" + JSON.stringify(data));
        }

        document.getElementById("searchInput").addEventListener("input", filterAgendas);
    </script>
</body>
</html>
