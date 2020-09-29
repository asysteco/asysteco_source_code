<?php
if(isset($_GET['criptedval']) && $_GET['criptedval'] != 'undefined')
{
  $_GET['criptedval'] = preg_replace('/\s/', '+', urldecode($_GET['criptedval']));
  include($dirs['inc'] . 'mcript.php');
  $dato_desencriptado = $desencriptar($_GET['criptedval']);
  $_GET['ID'] = $dato_desencriptado;
  if(isset($_GET['ID']) && $_GET['ID'] != 'undefined' && $_GET['ID'] != '')
  {
      if($response = $class->query("SELECT ID, Nombre FROM Profesores WHERE ID='$_GET[ID]'"))
      {
        if($response->num_rows == 1)
        {
          $nombre = $response->fetch_assoc();
          if($class->FicharWeb($options['ficharSalida']))
          {
              echo "<span id='okqr' style='color: white; font-weight: bolder; background-color: green;'><h3>Fichaje de asistencia correcto.<br> $nombre[Nombre]</h3></span>";
          }
          else
          {
              echo $class->ERR_ASYSTECO;
          }
        }
        else
        {
          echo "<span id='noqr' style='color: white; font-weight: bolder; background-color: red;'><h3>Código QR incorrecto.</h3></span>";
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