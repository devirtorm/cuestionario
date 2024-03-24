<?php

require_once("controlador/controlador.php");
require_once("modelo/modelo.php");
require_once("modelo/enlaces.php");

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Iniciar sesión</title>
  <!-- Enlace a Bootstrap CSS -->
  <link rel="stylesheet" type="text/css" href="vistas/css/login.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }
  </style>

  <link href="../css/login.css" rel="stylesheet">
</head>

<body class="" style="background-color: #eff6ff;">
  <div class="container-fluid align-items-center">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="login d-flex align-items-center py-5">
          <div class="container">
            <div class="row">
              <div class="card shadow-sm p-4" style="height: 60vh;">
                <div class="col-md-9 col-lg-8 mx-auto mt-5">
                  <h3 class="text-center mb-4">Iniciar sesión</h3>

                  <!-- Sign In Form -->
                  <form action="" method="post">
                    <div class="mb-3">
                      <label for="correo">Correo</label>
                      <input type="text" class="form-control mb-3" name="correo" placeholder="name@example.com">
                    </div>
                    <div class="mb-4">
                      <label for="clave">Contraseña</label>
                      <input type="password" class="form-control mb-3" name="contrasena" placeholder="password" required>
                    </div>
                    <div class="d-grid">
                      <button class="btn btn-success mb-5" name="botonson" type="submit">Iniciar</button>
                      <div class="text-center">
                        <span>¿Aun no tienes cuenta?</span> <a class="small" href="index.php?accion=registro">!Registrate aquí!</a>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



</body>


</html>

<?php
$login = new Controlador();
$login->sesion();
