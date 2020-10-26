<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="resources/img/asysteco.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/bootstrap-3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/login-style.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <title>Login</title>
</head>
<body>
    <div class="wrapper fadeInDown">
        <div id="formContent">
          <!-- Tabs Titles -->

          <!-- Icon -->
          <div class="fadeIn first">
            <img src="<?php
            $path = 'resources/img/';
            $name = 'logo-centro.';
            $ext = ['jpg', 'png', 'svg'];
            $logoFile = "";
            
            foreach ($ext as $value) {
              if (is_file($path . $name . $value)) {
                $logoFile = $path . $name . $value;
              }
            }

            if ($logoFile !== '') {
              echo $logoFile; 
            } else {
              echo $path . "default-logo.png";
            }

             ?>" width='125' id="icon" alt="Icono del centro" /> <h1>Iniciar Sesión</h1>
          </div>

          <!-- Login Form -->
          <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <input type="text" id="login" class="fadeIn second" name="Iniciales" value="<?php echo $_POST['Iniciales']; ?>" placeholder="Usuario (Iniciales)" required>
            <input type="password" id="password" class="fadeIn third" name="pass" placeholder="Contraseña" required>
            <input type="submit" class="fadeIn fourth" value="Iniciar">
          </form>

          <!-- Remind Passowrd -->
          <div id="formFooter">
            <!--a class="underlineHover" href="<?php echo $_SERVER['PHP_SELF'] . "?ACTION=registrarse"; ?>">Registrarme</a-->
            <p>Si has olvidado la contraseña, por favor contacta con Jefatura para que puedan reiniciarla. Gracias.</p>
          </div>
        </div>
      </div>
<?php 
if(isset($ERR_LOGIN_FORM))
{
  echo "
  <script>
  window.onload = function() {
    $('#ERR_LOGIN_MODAL').modal('show')
  };
  </script>
  ";
  echo '
  <!-- Modal -->
  <div class="modal fade" id="ERR_LOGIN_MODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p style="color: red;">
            ' . $ERR_LOGIN_FORM . '
          </p>
        </div>
      </div>
    </div>
  </div>
  ';
}
?>
</body>
</html>