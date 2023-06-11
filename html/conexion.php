<?php
$server = "magallanes.inf.unap.cl";
$username = "jgomez";
$password = "262m79VhrgMj";
$database = "jgomez";

// Establecer la conexión
$connection = mysqli_connect($server, $username, $password, $database);

// Verificar la conexión
if (!$conn) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

// Si la conexión es exitosa, puedes ejecutar consultas y realizar operaciones en la base de datos

// Ejemplo de consulta
$query = "SELECT * FROM tabla";
$result = mysqli_query($conn, $query);

// Procesar el resultado de la consulta
if (mysqli_num_rows($result) > 0) {
    // Obtener los datos de cada fila
    while ($row = mysqli_fetch_assoc($result)) {
        // Acceder a los valores de cada columna
        $column1 = $row["columna1"];
        $column2 = $row["columna2"];
        
        // Realizar las operaciones necesarias con los datos obtenidos
        // ...
    }
} else {
    echo "No se encontraron resultados";
}

// Cerrar la conexión
mysqli_close($conn);
?>
