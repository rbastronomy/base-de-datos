<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Empleado</title>
    <link rel="stylesheet" href="eliminar_empleado_decoracion.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.min.css">

</head>

<body>
    <h1>Buscar Empleado</h1>

    <div class="container">
        <div class="search-bar">
            <input type="text" id="searchInput" class="search-input" placeholder="Buscar empleado..." />
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

            $consulta = "SELECT empleado.rut, empleado.celular, empleado.nombre, empleado.correo, empleado.edad, empleado.f_nacimiento, empleado.direccion, empleado.genero, empleado.grado_academico, empleado.cargo, 
            CASE WHEN empleado_salud.rut IS NOT NULL THEN 'Salud' 
                WHEN empleado_gestion.rut IS NOT NULL THEN 'Gestión' 
                ELSE '' 
            END AS sector
            FROM empleado
            LEFT JOIN empleado_salud ON empleado.rut = empleado_salud.rut
            LEFT JOIN empleado_gestion ON empleado.rut = empleado_gestion.rut";
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

        <table id="employeeTable">
            <tr>
                <th>RUT</th>
                <th>Celular</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Edad</th>
                <th>Fecha de Nacimiento</th>
                <th>Dirección</th>
                <th>Género</th>
                <th>Grado Académico</th>
                <th>Cargo</th>
                <th>Sector</th>
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
                echo "<td contenteditable='false'>" . $fila['grado_academico'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['cargo'] . "</td>";
                echo "<td contenteditable='false'>" . $fila['sector'] . "</td>";
                echo "<td><button class='action-button' onclick='confirmDelete(this.parentElement.parentElement)'>Borrar</button></td>";
                echo "</tr>";
            }
            ?>
        </table>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js"></script>
        <script>
            function filterEmployees() {
                var input, filter, table, tr, td, i, txtValue;
                input = document.getElementById("searchInput");
                filter = input.value.toUpperCase();
                table = document.getElementById("employeeTable");
                tr = table.getElementsByTagName("tr");

                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td")[2]; // Columna del nombre
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
                row.getElementsByTagName("button")[0].setAttribute("onclick", "updateEmployee(this.parentElement.parentElement)");
            }

            function updateEmployee(row) {
                var cells = row.getElementsByTagName("td");
                var data = {
                    rut: cells[0].innerText,
                    celular: cells[1].innerText,
                    nombre: cells[2].innerText,
                    correo: cells[3].innerText,
                    edad: cells[4].innerText,
                    f_nacimiento: cells[5].innerText,
                    direccion: cells[6].innerText,
                    genero: cells[7].innerText,
                    grado_academico: cells[8].innerText,
                    cargo: cells[9].innerText
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
                xhttp.open("POST", "borrar_empleado.php", true); // Archivo PHP para actualizar los datos
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("data=" + JSON.stringify(data));
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
                        deleteEmployee(row);
                    }
                });
            }

            function deleteEmployee(row) {
                var cells = row.getElementsByTagName("td");
                var rut = cells[0].innerText;
                var sector = cells[10].innerText;

                if (sector === 'Salud') {
                    deleteFromSalud();
                } else if (sector === 'Gestión') {
                    deleteFromGestion();
                } else {
                    deleteFromEmpleado();
                }

                function deleteFromSalud() {
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            // La solicitud se completó correctamente, mostrar mensaje con SweetAlert2
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Empleado borrado',
                                text: this.responseText,
                            showConfirmButton: false,
                            timer: 2000 // Duración del mensaje en milisegundos (en este caso, 2 segundos)
                        });

                        // Eliminar la fila de la tabla
                        row.remove();
                    }
                };
                    xhttp.open("GET", "borrar_empleado_salud.php?rut=" + rut, true);
                    xhttp.send();
                }

                function deleteFromGestion() {
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            // La solicitud se completó correctamente, mostrar mensaje con SweetAlert2
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Empleado borrado',
                                text: this.responseText,
                            showConfirmButton: false,
                            timer: 2000 // Duración del mensaje en milisegundos (en este caso, 2 segundos)
                        });

                        // Eliminar la fila de la tabla
                        row.remove();
                    }
                };
                    xhttp.open("GET", "borrar_empleado_gestion.php?rut=" + rut, true);
                    xhttp.send();
                }

                function deleteFromEmpleado() {
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            // La solicitud se completó correctamente, mostrar mensaje con SweetAlert2
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Empleado borrado',
                                text: this.responseText,
                            showConfirmButton: false,
                            timer: 2000 // Duración del mensaje en milisegundos (en este caso, 2 segundos)
                        });

                        // Eliminar la fila de la tabla
                        row.remove();
                    }
                };
                    xhttp.open("GET", "borrar_empleado.php?rut=" + rut, true);
                    xhttp.send();
                }
            }

            document.getElementById("searchInput").addEventListener("input", filterEmployees);
        </script>
    </div>
</body>

</html>
