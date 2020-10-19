<?php

require_once($dirs['class'] . 'ImportHorario.php');
$fileName = $_FILES["file"]["tmp_name"];
if ($_FILES["file"]["size"] > 0) {
    $file = fopen($fileName, "r");
    $inserted = true;

    while (($column = fgetcsv($file, 10000, ";")) !== FALSE) {
        if (count($column) === 5) {
            $importHorario = new ImportHorario($column[0], $column[1], $column[2], $column[3], $column[4]);

            $grupo = $importHorario->grupo();
            $iniciales = $importHorario->iniciales();
            $aula = $importHorario->aula();
            $dia = $importHorario->dia();
            $hora = $importHorario->hora();

            if ($grupo && $grupo != '' && $iniciales && $iniciales != '' && $aula && $aula != '' && $dia && $dia != '' && $hora && $hora != '') {
                $tipo = $_POST['Tipo'];
                $horaTipo = explode('', $tipo);
                $horaTipo = $hora . $horaTipo[0];
                $edificio = explode('', $aula);
                $edificio = $edificio[2];

                if ($response = $class->query("SELECT ID FROM Profesores WHERE Iniciales = '$iniciales'")) {
                    if ($response->num_rows === 1) {
                        $fila = $response->fetch_assoc();
                        $idProfesor = $fila['ID'];
                    } else {
                        continue;
                    }
                } else {
                    $msg = "El profesor con las Iniciales $Iniciales no existe, su horario no se importará.";
                    $class->notificar($_SESSION['ID'], $msg);
                    continue;
                }

                if (!$class->query("INSERT INTO Horarios (ID_PROFESOR, Dia, HORA_TIPO, Hora, Tipo, Edificio, Aula, Grupo)
                VALUES ('$idProfesor', '$dia', '$horaTipo', '$hora', '$tipo', '$edificio', '$aula', '$grupo')")) {
                    echo "Error al importar datos.";
                    $inserted = false;
                }
            } else {
                echo "Error al importar datos.<br> Revise los datos de la tabla y vuelva a intentarlo.";
                $inserted = false;
            }
        }
    }
    if ($inserted) {
        echo "Datos importados correctamente";
    }
}