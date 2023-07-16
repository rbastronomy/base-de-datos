<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrar Contrato</title>
    <link rel="stylesheet" href="eliminar_empleado_decoracion.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.min.css">

</head>

<body>
    <h1>Borrar Contrato</h1>

    <div class="container">
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
                echo "<td contenteditable='false'>" . $fila['f_inicio'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['f_termino'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['hora_entrada'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['horas_semanales'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['sueldo'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['hora_salida'] . "</td>";
                echo "<td><button class='action-button' onclick='confirmDelete(this.parentElement.parentElement)'>Borrar</button></td>";
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
                        deleteContrato(row);
                    }
                });
            }

            function deleteContrato(row) {
                var cells = row.getElementsByTagName("td");
                var idContrato = cells[0].innerText;

                // Realizar la solicitud AJAX para borrar el contrato de la base de datos
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        // La solicitud se completó correctamente, mostrar mensaje con SweetAlert2
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Contrato borrado',
                            text: this.responseText,
                            showConfirmButton: false,
                            timer: 2000 // Duración del mensaje en milisegundos (en este caso, 2 segundos)
                        });

                        // Eliminar la fila de la tabla
                        row.remove();
                    }
                };
                xhttp.open("GET", "borrar_contrato.php?id=" + idContrato, true); // Archivo PHP para borrar el contrato
                xhttp.send();
            }

            document.getElementById("searchInput").addEventListener("input", filterContratos);
        </script>
    </div>
</body>

</html>
