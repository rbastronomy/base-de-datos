<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Empleado</title>
    <link rel="stylesheet" href="buscardecoracion.css">
</head>
<body>
    <h1>Buscar Empleado</h1>

    <div class="search-bar">
        <input type="text" id="searchInput" class="search-input" placeholder="Buscar empleado..." />
        <a href="menu.html" class="back-button">Regresar al Menú</a>
    </div>

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

        $consulta = "SELECT * FROM empleado";
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

    <table id="employeeTable" style="display: none;">
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
        </tr>
        <?php
        foreach ($resultado as $fila) {
            echo "<tr>";
            echo "<td>" . $fila['rut'] . "</td>";
            echo "<td>" . $fila['celular'] . "</td>";
            echo "<td>" . $fila['nombre'] . "</td>";
            echo "<td>" . $fila['correo'] . "</td>";
            echo "<td>" . $fila['edad'] . "</td>";
            echo "<td>" . $fila['f_nacimiento'] . "</td>";
            echo "<td>" . $fila['direccion'] . "</td>";
            echo "<td>" . $fila['genero'] . "</td>";
            echo "<td>" . $fila['grado_academico'] . "</td>";
            echo "<td>" . $fila['cargo'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <script>
        function filterEmployees() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("employeeTable");
            tr = table.getElementsByTagName("tr");

            // Mostrar u ocultar la tabla según la búsqueda
            if (filter !== "") {
                table.style.display = "";
            } else {
                table.style.display = "none";
            }

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

        document.getElementById("searchInput").addEventListener("input", filterEmployees);
    </script>
</body>
</html>
