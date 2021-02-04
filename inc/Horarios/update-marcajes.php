<?php

$action = $_GET['act'];
$valor = $_GET['Valor'];
$profesor = $_GET['Profesor'];
$fecha = $_GET['Fecha'];
$changeDate = $class->formatSQLDateToEuropeanDate($fecha);
$hora = $_GET['Hora'];

$nombre = $_SESSION['Nombre'];

if (isset($action) && $action != '') {
    if ($action == 'Asiste') {
        if ($class->query("UPDATE Marcajes SET Asiste=$valor WHERE ID_PROFESOR='$profesor' AND Fecha='$fecha' AND Hora='$hora'")) {
            if (count($franjasHorarias) === 2) {
                $response = $class->query("SELECT DISTINCT Tipo FROM Horarios WHERE ID_PROFESOR='$profesor'")->fetch_assoc();
                $franja = $response['Tipo'];
                $horaInicio = $class->transformHoraMinutos($franjasHorarias[$franja][$hora]['Inicio']);
                $horaFin = $class->transformHoraMinutos($franjasHorarias[$franja][$hora]['Fin']);
            } else {
                $response = $class->query("SELECT DISTINCT Inicio, Fin FROM Horas WHERE Hora = '$hora'")->fetch_assoc();
                $horaInicio = $class->transformHoraMinutos($response['Inicio']);
                $horaFin = $class->transformHoraMinutos($response['Fin']);
            }

            if ($valor == 1) {
                $msg = "$nombre ha modificado el registro del Día: $changeDate Horas: $horaInicio - $horaFin como Asistido.";
                $MSG = 'Ok-asiste';
            } elseif ($valor == 0) {
                $msg = "$nombre ha modificado el registro del Día: $changeDate Horas: $horaInicio - $horaFin como Falta.";
                $MSG = 'Ok-falta';
            } elseif ($valor == 2) {
                $msg = "$nombre ha modificado el registro del Día: $changeDate Horas: $horaInicio - $horaFin como Actividad Extraescolar.";
                $MSG = 'Ok-extraescolar';
            } else {
                $msg = "$nombre ha enviado un valor incorrecto.";
            }

            if (!$class->notificar($_GET['Profesor'], $msg)) {
                echo $class->ERR_ASYSTECO;
            }
        } else {
            $MSG = 'Error-action';
        }
    } elseif ($action == 'Justificada') {
        if (count($franjasHorarias) === 2) {
            $response = $class->query("SELECT DISTINCT Tipo FROM Horarios WHERE ID_PROFESOR='$profesor'")->fetch_assoc();
            $franja = $response['Tipo'];
            $horaInicio = $class->transformHoraMinutos($franjasHorarias[$franja][$hora]['Inicio']);
            $horaFin = $class->transformHoraMinutos($franjasHorarias[$franja][$hora]['Fin']);
        } else {
            $response = $class->query("SELECT DISTINCT Inicio, Fin FROM Horas WHERE Hora = '$hora'")->fetch_assoc();
            $horaInicio = $class->transformHoraMinutos($response['Inicio']);
            $horaFin = $class->transformHoraMinutos($response['Fin']);
        }

        if ($class->query("UPDATE Marcajes SET Justificada=$valor WHERE ID_PROFESOR='$profesor' AND Fecha='$fecha' AND Hora='$hora'")) {
            if ($valor == 1) {
                $msg = "$nombre ha modificado el registro del Día: $changeDate Hora: $horaInicio - $horaFin como Falta Justificada.";
                $MSG = 'Ok-justificada';
            } elseif ($valor == 0) {
                $msg = "$nombre ha modificado el registro del Día: $changeDate Hora: $horaInicio - $horaFin retirando la justificación.";
                $MSG = 'Ok-injustificada';
            } else {
                $msg = "$nombre ha enviado un valor incorrecto.";
            }

            if (!$class->notificar($profesor, $msg)) {
                echo $class->ERR_ASYSTECO;
            }
        } else {
            $MSG = 'Error-action';
        }
    } elseif ($action == 'getrow') {
        $response = $class->query("SELECT Marcajes.*, Diasemana 
        FROM Marcajes INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID 
        WHERE ID_PROFESOR='$profesor' AND Fecha='$fecha' AND Hora='$hora'");
    } else {
        $MSG = 'Error-Invalid-action';
    }
} else {

    $MSG = 'Error-parameter';;
}

echo $MSG;
