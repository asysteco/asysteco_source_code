<?php

$dia = explode('/', $_POST['fecha']);
$dia = $dia[2] . '-' . $dia[1] . '-' . $dia[0];
$diaSemana = $class->getDate(strtotime($dia));
$diaSemana = $diaSemana['weekday'] ?? '';
$id = $_POST['ID'] ?? '';
$horaEntrada = $_POST['horaEntrada'] ?? '';
$horaSalida = $_POST['horaSalida'] ?? '';

if ($class->validFormSQLDate($dia)) {
    if ($comprobacion = $class->query("SELECT Fecha, Festivo FROM Lectivos WHERE Fecha = '$dia' AND Festivo = 'no'")) {
        if ($comprobacion->num_rows > 0) {
            if ($res = $class->query("SELECT ID_PROFESOR, Fecha FROM Fichar WHERE ID_PROFESOR='$id' AND Fecha='$dia'")) {
                if ($res->num_rows == 0) {
                    $class->conex->autocommit(FALSE);
                    try {
                        if (!$options['ficharSalida']) {
                            if (!$res = $class->conex->query("SELECT DISTINCT Hora_salida FROM Horarios WHERE ID_PROFESOR = '$id' AND Dia = WEEKDAY('$dia')+1")->fetch_object()) {           
                                throw new Exception('Error-Insert');
                            }
                            $horaSalida = $res->Hora_salida;
                        }

                        if (!$class->conex->query("INSERT INTO Fichar (ID_PROFESOR, F_entrada, F_Salida, DIA_SEMANA, Fecha)
                        VALUES ('$id', '$horaEntrada', '$horaSalida', '$diaSemana', '$dia')")) {           
                            throw new Exception('Error-Insert');
                        }

                        if (!$class->conex->query("UPDATE Marcajes SET Asiste = 1 WHERE ID_PROFESOR = '$id' AND Dia = WEEKDAY('$dia')+1 AND Hora IN
                            (SELECT DISTINCT Hora FROM Horas WHERE Fin >= '$horaEntrada' AND Inicio <= '$horaSalida')")) {
                            throw new Exception('Error-Insert');
                        }
                        $MSG = 'Ok-action';
                    } catch (Exception $e) {
                        $MSG = $e;
                        $class->conex->rollback();
                    }
                    $class->conex->commit();
                } else {
                    $MSG = 'Error-Ya-Fichado';
                }
            } else {
                $MSG = 'Error-inesperado';
            }
        } else {
            $MSG = 'Error-Festivo';
        }
    } else {
        $MSG = 'Error-inesperado';
    }
} else {
    $MSG = 'Error-Formato-Fecha';
}
echo $MSG;
