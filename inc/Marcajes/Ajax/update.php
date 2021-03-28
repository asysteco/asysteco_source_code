<?php

$profesor = $_POST['profesor'] ?? '';
$date = $_POST['date'] ?? '';
$hour = $_POST['hour'] ?? '';
$inicio = $_POST['inicio'] ?? '';
$fin = $_POST['fin'] ?? '';
$type = $_POST['type'] ?? '';
$action = $_POST['action'] ?? '';
$value = $_POST['value'] ?? '';
$changeDate = !empty($date) ? $class->formatSQLDateToEuropeanDate($date) : '';

$nombre = $_SESSION['Nombre'];

$success = false;
$alertMessage = 'Error inesperado...';

if ($action == 'Asiste') {
    $sql = "UPDATE Marcajes SET Asiste = $value WHERE ID_PROFESOR = '$profesor' AND Fecha = '$date' AND Hora = '$hour'";

    if (!$value) {
        $sql = "UPDATE Marcajes SET Asiste = $value, Justificada = 0 WHERE ID_PROFESOR = '$profesor' AND Fecha = '$date' AND Hora = '$hour'";
    }

    $class->conex->autocommit(FALSE);
    try {
        $class->autocommitOffQuery($class->conex, $sql, 'Error al actualizar horas.');

        if ($value == 1) {
            $msg = "$nombre ha modificado el registro del Día: $changeDate Horas: $inicio - $fin como Asistido.";
            $alertMessage = 'Ok-asiste';
        } elseif ($value == 0) {
            $msg = "$nombre ha modificado el registro del Día: $changeDate Horas: $inicio - $fin como Falta.";
            $alertMessage = 'Ok-falta';
        } elseif ($value == 2) {
            $msg = "$nombre ha modificado el registro del Día: $changeDate Horas: $inicio - $fin como Actividad Extraescolar.";
            $alertMessage = 'Ok-extraescolar';
        } else {
            throw new Exception("$nombre ha enviado un valor incorrecto.");
        }

        $class->notificar($profesor, $msg);

        $success = true;
        $alertMessage = 'Petición realizada correctamente.';
    } catch(Exception $e) {
        $alertMessage = $e->getMessage();
        $class->conex->rollback();
    }
    $class->conex->commit();
} elseif ($action == 'Justificada') {
    $sql = "UPDATE Marcajes SET Justificada = $value WHERE ID_PROFESOR = '$profesor' AND Fecha = '$date' AND Hora = '$hour'";
    $class->conex->autocommit(FALSE);
    try {
        $class->autocommitOffQuery($class->conex, $sql, 'Error al actualizar horas.');

        if ($value == 1) {
            $msg = "$nombre ha modificado el registro del Día: $changeDate Hora: $inicio - $fin como Falta Justificada.";
            $alertMessage = 'Marcado como falta justificada.';
        } elseif ($value == 0) {
            $msg = "$nombre ha modificado el registro del Día: $changeDate Hora: $inicio - $fin retirando la justificación.";
            $alertMessage = 'Marcado como falta injustificada.';
        } else {
            throw new Exception("$nombre ha enviado un valor incorrecto.");
        }

        $class->notificar($profesor, $msg);

        $success = true;
    } catch(Exception $e) {
        $alertMessage = $e->getMessage();
        $class->conex->rollback();
    }
    $class->conex->commit();
} else {
    $alertMessage = 'Acción no válida';
}

$result = [
    'success' => $success,
    'msg' => $alertMessage
];

echo json_encode($result);
exit;
