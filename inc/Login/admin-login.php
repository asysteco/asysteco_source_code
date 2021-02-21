<?php

$criptedVal = $_GET['criptedval'];

if (isset($criptedVal) && $criptedVal != 'undefined') {
  $criptedVal = preg_replace('/\s/', '+', urldecode($criptedVal));
  include_once($dirs['Helper'] . 'mcript.php');
  $dato_desencriptado = $desencriptar($criptedVal);
  $_GET['ID'] = $dato_desencriptado;
  if (isset($_GET['ID']) && $_GET['ID'] != 'undefined' && $_GET['ID'] != '') {
    if ($response = $class->query("SELECT ID, Nombre FROM Profesores WHERE ID='$_GET[ID]'")) {
      if ($response->num_rows == 1) {
        if ($class->LoginAdminQR($_GET['ID'], $Titulo)) {
          echo "<span id='okqr' style='color: white; font-weight: bolder; background-color: green;'><h3>Lector activado correctamente.<br> $nombre[Nombre]</h3></span>";
          echo '<script>location.href = "index.php?ACTION=guardias";</script>';
        } else {
          echo "<span id='noqr' style='color: white; font-weight: bolder; background-color: red;'><h3>$class->ERR_ASYSTECO</h3></span>";
        }
      } else {
        echo "<span id='noqr' style='color: white; font-weight: bolder; background-color: red;'><h3>Solo un Administrador puede iniciar el lector.</h3></span>";
      }
    } else {
      echo "<span id='noqr' style='color: white; font-weight: bolder; background-color: red;'><h3>$class->ERR_ASYSTECO</h3></span>";
    }
  } else {
    echo "<span id='noqr' style='color: white; font-weight: bolder; background-color: red;'><h3>Código QR no válido.</h3></span>";
  }
} else {
  echo "<span id='noqr' style='color: white; font-weight: bolder; background-color: red;'><h3>Código QR no válido.</h3></span>";
}
