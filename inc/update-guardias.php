<?php

$profesor = $_GET['profesor'];
$dia = $_GET['Dia'];
$hora = $_GET['Hora'];
$subopt = $_GET['SUBOPT'];
$edificio = $_GET['e'];

$Tipo = preg_split('//', $_GET['Tipo'], -1, PREG_SPLIT_NO_EMPTY);
$Tipo = $Tipo[0];

if ($subopt == 'add') {
  $sql = "INSERT INTO $class->horarios (ID_PROFESOR, Dia, Hora, Tipo, Edificio, Aula, Grupo) VALUES ('$profesor', '$dia', '$hora', '$_GET[Tipo]', '$edificio', 'GU" . $edificio . "00', 'Guardia')";
  var_dump($sql);
  if ($class->query($sql)) {
    $class->updateHoras($profesor);
    $class->marcajes($profesor, $dia, $hora, $subopt);
    $MSG = 'Ok-add';
  } else {
    $MSG = "Error-add";
  }
} elseif ($subopt == 'remove') {
  $sql = "DELETE FROM $class->horarios WHERE ID_PROFESOR='$profesor' AND Dia='$dia' AND Hora='$hora'";
  if ($class->query($sql)) {
    $class->updateHoras($profesor);
    $class->marcajes($profesor, $dia, $hora, $subopt);
    $MSG = 'Ok-remove';
  } else {
    $MSG = "Error-remove";
  }
} elseif ($subopt == 'addt') {
  $fecha = date('Y-m-d');
  $sql = "INSERT INTO T_horarios (ID_PROFESOR, Dia, Hora, Tipo, Edificio, Aula, Grupo, Fecha_incorpora)
    VALUES ('$profesor', '$dia', '$hora', '$_GET[Tipo]', '$edificio', 'GU" . $edificio . "00', 'Guardia', '$fecha')";
  if (!$class->query($sql)) {
    $MSG = 'Ok-add';
  } else {
    $MSG = "Error-add";
  }
} elseif ($subopt == 'removet') {
  $sql = "DELETE FROM T_horarios WHERE ID_PROFESOR='$profesor' AND Dia='$dia' AND Hora='$hora'";
  if ($class->query($sql)) {
    $MSG = 'Ok-remove';
  } else {
    $MSG = "Error-remove";
  }
} else {
  echo $MSG = "Error-params";
}

echo $MSG;
