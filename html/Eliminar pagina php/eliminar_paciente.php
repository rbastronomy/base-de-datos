<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrar Paciente</title>
    <link rel="stylesheet" href="eliminar_empleado_decoracion.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.min.css">
</head>

<body>
    <h1>Borrar Paciente</h1>

    <div class="container">
        <div class="search-bar">
            <input type="text" id="searchInput" class="search-input" placeholder="Buscar paciente..." />
            <a href="menu.php" class="back-button">Regresar al Menú</a>
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

            $consulta = "SELECT * FROM paciente";
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

        <table id="pacienteTable">
            <tr>
                <th>RUT</th>
                <th>Celular</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Edad</th>
                <th>Fecha de Nacimiento</th>
                <th>Dirección</th>
                <th>Género</th>
                <th>Previsión</th>
                <th>Acciones</th>
            </tr>
            <?php
            foreach ($resultado as $fila) {
                echo "<tr>";
                echo "<td>" . $fila['rut'] . "</td>";
                echo "<td>" . $fila['celular'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['nombre'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['correo'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['edad'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['f_nacimiento'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['direccion'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['genero'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['prevision'] . "</td>";
                echo "<td><button class='action-button' onclick='confirmDelete(this.parentElement.parentElement)'>Borrar</button></td>";
                echo "</tr>";
            }
            ?>
        </table>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js"></script>
        <script>
            function filterPacientes() {
                var input, filter, table, tr, td, i, txtValue;
                input = document.getElementById("searchInput");
                filter = input.value.toUpperCase();
                table = document.getElementById("pacienteTable");
                tr = table.getElementsByTagName("tr");

                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td")[0]; // Columna del RUT
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
                        deletePaciente(row);
                    }
                });
            }

            function deletePaciente(row) {
                var cells = row.getElementsByTagName("td");
                var rut = cells[0].innerText;

                // Realizar la solicitud AJAX para borrar el paciente de la base de datos
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        // La solicitud se completó correctamente, mostrar mensaje con SweetAlert2
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Paciente borrado',
                            text: this.responseText,
                            showConfirmButton: false,
                            timer: 2000 // Duración del mensaje en milisegundos (en este caso, 2 segundos)
                        });

                        // Eliminar la fila de la tabla
                        row.remove();
                    }
                };
                xhttp.open("GET", "borrar_paciente.php?rut=" + rut, true); // Archivo PHP para borrar el paciente
                xhttp.send();
            }

            document.getElementById("searchInput").addEventListener("input", filterPacientes);
        </script>
    </div>
</body>

</html>
