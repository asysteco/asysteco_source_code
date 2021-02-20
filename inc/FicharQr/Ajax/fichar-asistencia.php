<?php

$encriptedVal = $_GET['criptedval'] ?? '';

if (isset($encriptedVal) && !empty($encriptedVal) && $encriptedVal != 'undefined') {
  $encriptedVal = preg_replace('/\s/', '+', urldecode($encriptedVal));
  include($dirs['Helper'] . 'mcript.php');
  $dato_desencriptado = $desencriptar($encriptedVal);
  $profesor = $dato_desencriptado;

  if (isset($profesor) && $profesor != 'undefined' && $profesor != '') {
    if ($response = $class->query("SELECT ID, Nombre FROM Profesores WHERE ID='$profesor'")) {
      if ($response->num_rows == 1) {
        $nombre = $response->fetch_assoc();
        if ($class->FicharWeb($profesor, $options['ficharSalida'])) {
          echo "<span id='okqr' style='color: white; font-weight: bolder; background-color: green;'><h3>Fichaje de asistencia correcto.<br> $nombre[Nombre]</h3></span>";
        } else {
          echo $class->ERR_ASYSTECO;
        }
      } else {
        echo "<span id='noqr' style='color: white; font-weight: bolder; background-color: red;'><h3>Código QR incorrecto.</h3></span>";
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
