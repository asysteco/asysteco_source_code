<?php

$dia = explode('/', $_POST['fecha']);
$dia = $dia[2] . '-' . $dia[1] . '-' . $dia[0];
$diasemana = $class->getDate(strtotime($dia));
$diasemana = $diasemana['weekday'];
$id = $_POST['ID'];
$horaentrada = $_POST['horaEntrada'];
$horasalida = $_POST['horaSalida'];

if ($class->validFormSQLDate($dia)) {
    if ($comprobacion = $class->query("SELECT Fecha, Festivo FROM Lectivos WHERE Fecha = '$dia' AND Festivo = 'no'")) {
        if ($comprobacion->num_rows > 0) {
            if ($res = $class->query("SELECT ID_PROFESOR, Fecha FROM Fichar WHERE ID_PROFESOR='$id' AND Fecha='$dia'")) {
                if ($res->num_rows == 0) {
                    if ($response = $class->query("INSERT INTO Fichar (ID_PROFESOR, F_entrada, F_Salida, DIA_SEMANA, Fecha)
                    VALUES ('$id', '$horaentrada', '$horasalida', '$diasemana', '$dia')")) {
                        $MSG = 'Ok-action';
                    } else {
                        $MSG = 'Error-Insert';
                    }
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
