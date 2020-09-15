<?php
if(isset($_GET['criptedval']) && $_GET['criptedval'] != 'undefined')
{
  $_GET['criptedval'] = preg_replace('/\s/', '+', urldecode($_GET['criptedval']));
  include($dirs['inc'] . 'mcript.php');
  $dato_desencriptado = $desencriptar($_GET['criptedval']);
  $datos = preg_split('/;/', $dato_desencriptado);
  $_GET['abrev'] = $datos[0];
  $_GET['enp'] = $datos[1];
  if(isset($_GET['abrev']) && isset($_GET['enp']) && $_GET['abrev'] != 'undefined' && $_GET['enp'] != 'undefined' && $_GET['abrev'] != '' && $_GET['enp'] != '')
  {
      if($response = $class->query("SELECT ID FROM $class->profesores WHERE Iniciales='$_GET[abrev]' AND Password='$_GET[enp]' AND TIPO=1"))
      {
        if($response->num_rows == 1)
        {
            if($class->LoginAdminQR($_GET['abrev'], $_GET['enp']))
            {
                echo "<span id='okqr' style='color: white; font-weight: bolder; background-color: green;'><h3>Fichaje de asistencia correcto.<br> $nombre[Nombre]</h3></span>";
                echo '
                <script>
                    location.href = "index.php?ACTION=guardias";
                </script>
                ';
            }
            else
            {
                echo "<span id='noqr' style='color: white; font-weight: bolder; background-color: red;'><h3>$class->ERR_ASYSTECO</h3></span>";
            }            
        }
        else
        {
            echo "<span id='noqr' style='color: white; font-weight: bolder; background-color: red;'><h3>Solo un Administrador puede iniciar el lector.</h3></span>";
        }
      }
      else
      {
        echo "<span id='noqr' style='color: white; font-weight: bolder; background-color: red;'><h3>$class->ERR_ASYSTECO</h3></span>";
      }
  }
  else
  {
    echo "<span id='noqr' style='color: white; font-weight: bolder; background-color: red;'><h3>Código QR no válido.</h3></span>";
  }
}
else
{
  echo "<span id='noqr' style='color: white; font-weight: bolder; background-color: red;'><h3>Código QR no válido.</h3></span>";
}