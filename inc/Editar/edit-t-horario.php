<?php

$MSG = '';
if (isset($_GET['act'])) {
  if ($_GET['act'] == 'add' && isset($_GET['ID']) && isset($_GET['Dia']) && isset($_GET['Hora']) && isset($_GET['Tipo']) && isset($_GET['Fecha'])) {
    $Edificio = 0;
    $Tipo = $_GET['Tipo'];

    if (!$class->query("INSERT
          INTO T_horarios (ID_PROFESOR, Dia, Hora, Tipo, Edificio, Aula, Grupo, Hora_entrada, Hora_salida, Fecha_incorpora) 
          VALUES ('$_GET[ID]', '$_GET[Dia]', '$_GET[Hora]', '$Tipo', '$Edificio', 'Selec.', 'Selec.', '00:00:00', '00:00:00', '$_GET[Fecha]')")) {
      $MSG = 'Error-add';
    }
  } elseif ($_GET['act'] == 'del' && isset($_GET['ID'])) {
    if (!$res = $class->query("DELETE FROM T_horarios WHERE ID='$_GET[ID]'")) {
      $MSG = 'Error-remove';
    }
  } elseif ($_GET['act'] == 'del_hora' && isset($_GET['ID_PROFESOR']) && isset($_GET['Dia']) && isset($_GET['Hora']) && isset($_GET['Fecha'])) {
    if (!$res = $class->query("DELETE FROM T_horarios WHERE ID_PROFESOR='$_GET[ID_PROFESOR]' AND Fecha_incorpora='$_GET[Fecha]' AND Hora='$_GET[Hora]' AND Dia='$_GET[Dia]'")) {
      $MSG = 'Error-remove';
    }
  } elseif ($_GET['act'] == 'add_more' && isset($_GET['ID']) && isset($_GET['Dia']) && isset($_GET['Hora']) && isset($_GET['Fecha'])) {
    $Edificio = 0;
    if (isset($_GET['Aula'])) {
      $sed = explode('', $_GET['Aula']);
      $Edificio = mysqli_real_escape_string($class->conex, utf8_encode($sed[2]));
      preg_match('/^[0-9]$/', $Edificio) ? $Edificio : $Edificio = 0;
    }
    if (!$class->query("INSERT INTO T_horarios (ID_PROFESOR, Dia, Hora, Tipo, Edificio, Aula, Grupo, Hora_entrada, Hora_salida, Fecha_incorpora) 
          SELECT ID_PROFESOR, Dia, Hora, Tipo, Edificio, Aula, Grupo, Hora_entrada, Hora_salida, Fecha_incorpora
          FROM T_horarios
          WHERE ID_PROFESOR='$_GET[ID]' AND Fecha_incorpora='$_GET[Fecha]' AND Dia='$_GET[Dia]' AND Hora='$_GET[Hora]' LIMIT 1")) {
      $MSG = 'Error-remove';
    }
  }
} else {
  $MSG = 'Error-param';
}
echo $MSG;