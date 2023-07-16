<!DOCTYPE html>
<html>
<head>
  <title>Iniciar Sesión</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="indexdecoracion.css">
</head>

<body>
  <div class="container">
    <form action="conexion.php" method="POST">
      <a href="https://www.fach.mil.cl/">
        <img src="fuerza_aerea.png" alt="Fuerza Aerea" class="center">
      </a>
      <h1>Iniciar Sesión</h1>
      <div class="form-input">
        <input type="text" name="rut" id="rut" placeholder="Rut" required>
      </div>
      <div class="form-input">
        <input type="password" name="contrasena" id="contrasena" placeholder="Contraseña">
      </div>
      <input type="submit" value="Ingresar" class="btn-submit">
    </form>
  </div>
</body>
</html>
