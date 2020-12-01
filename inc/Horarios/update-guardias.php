<?php

$profesor = $_GET['profesor'];
$dia = $_GET['Dia'];
$hora = $_GET['Hora'];
$subopt = $_GET['SUBOPT'];
$edificio = $_GET['e'];

$sql = "SELECT Cursos.ID as Curso, Aulas.ID as Aula FROM Cursos, Aulas WHERE Cursos.Nombre = LOWER(guardia) AND Aulas.Nombre = LOWER(guardia)";
if ($response = $class->query($sql)) {
  if ($response->num_rows > 0) {
    $datos = $response->fetch_assoc();
    $idCurso = $datos['Curso'];
    $idAula = $datos['Aula'];
  } else {
    $sql = "INSERT INTO Cursos (Nombre) VALUES ('guardia');";
    $sql .= "INSERT INTO Aulas (Nombre) VALUES ('guardia');";
    if (!$class->conex->multi_query($sql)) {
      $MSG = "Error-add-guardia";
    }
  }
} else {
  $MSG = "Error-add-guardia";
}

if ($subopt == 'add') {
  $sql = "INSERT INTO Horarios (ID_PROFESOR, Dia, Hora, Tipo, Edificio, Aula, Grupo)
  VALUES ('$profesor', '$dia', '$hora', '$_GET[Tipo]', '$edificio', '$idCurso', '$idAula')";
  if ($class->query($sql)) {
    $class->updateHoras($profesor);
    $class->marcajes($profesor, $dia, $hora, $subopt);
    $MSG = 'Ok-add';
  } else {
    $MSG = "Error-add";
  }
} elseif ($subopt == 'remove') {
  $sql = "DELETE FROM Horarios WHERE ID_PROFESOR='$profesor' AND Dia='$dia' AND Hora='$hora'";
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
