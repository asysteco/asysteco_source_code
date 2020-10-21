<?php

$hoy = true;

date_default_timezone_set('Europe/Madrid');
if (isset($_POST['fecha'])) {
    $fecha = explode('/', $_POST['fecha']);
    $fecha = $fecha[2] . '-' . $fecha[1] . '-' . $fecha[0];

    if ($fecha !== date('Y-m-d')) {
        $hoy = false;
    }
}

require_once($dirs['class'] . 'ImportHorario.php');
$fileName = $_FILES["file"]["tmp_name"];
if ($_FILES["file"]["size"] > 0) {
    $file = fopen($fileName, "r");
    $row = 0;
    
    $class->conex->autocommit(FALSE);

try{

    while (($column = fgetcsv($file, 10000, ";")) !== FALSE) {
        if ($row === 0) {
            if (preg_match('/^GRUPO$/i', $column[0])
            && preg_match('/^INICIALES$/i', $column[1])
            && preg_match('/^AULA$/i', $column[2])
            && preg_match('/^DIA$/i', $column[3])
            && preg_match('/^HORA$/i', $column[4])) {
                $row ++;
                continue;
            } else {
                echo "error-cabecera";
                exit;
            }
        }

        $importHorario = new ImportHorario($column[0], $column[1], $column[2], $column[3], $column[4]);

        $grupo =$importHorario->grupo();
        $iniciales = $importHorario->iniciales();
        $aula = $importHorario->aula();
        $dia = $importHorario->dia();
        $hora = $importHorario->hora();
        $tipo = $_POST['Franja'];
        $franja = $_POST['Franja'];
        $horaTipo = $franjasHorarias[$franja][$hora]['Hora'];
        $edificio = str_split($aula);
        $edificio = $edificio[2];

        $response = $class->conex->query("SELECT ID FROM Profesores WHERE Iniciales = '$iniciales'");
        if ($response->num_rows === 1) {
            $fila = $response->fetch_assoc();
            $idProfesor = $fila['ID'];
        } else {
            $msg = "El profesor con las Iniciales $Iniciales no existe, su horario no se importará.";
            $class->notificar($_SESSION['ID'], $msg);
            continue;
        }

        if ($hoy) {
            $sql = "INSERT INTO Horarios (ID_PROFESOR, Dia, HORA_TIPO, Hora, Tipo, Edificio, Aula, Grupo)
            VALUES ('$idProfesor', '$dia', '$horaTipo', '$hora', '$tipo', '$edificio', '$aula', '$grupo')";
        } else {
            $sql = "INSERT INTO T_horarios (ID_PROFESOR, Dia, HORA_TIPO, Hora, Tipo, Edificio, Aula, Grupo, Fecha_incorpora)
            VALUES ('$idProfesor', '$dia', '$horaTipo', '$hora', '$tipo', '$edificio', '$aula', '$grupo', '$fecha')";
        }
        echo $sql;
        if (!$class->conex->query($sql)) {
            throw new Exception('error!');
        }
        $row++;
    }
    if ($hoy) {
        $class->updateHoras();
        $class->marcajes();
    }

} catch ( Exception $e ){
      echo "Error-importar";
      $class->conex->rollback();
}
$class->conex->commit();
}