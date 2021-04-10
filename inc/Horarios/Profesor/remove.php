<?php

$profesor = $_POST['profesor'];
$alertMessage = 'Error inesperado, contacte con los administradores...';
$trigger = false;

$deleted = false;

if ($class->query("DELETE FROM Horarios WHERE ID_PROFESOR='$profesor'")) {
    $class->marcajes($profesor, 'remove');
    $alertMessage = 'Horario eliminado correctamente.';
    $deleted = true;
    $trigger = 'close-modal';
} else {
    $alertMessage = 'Ha ocurrido un error inesperado.';
}

$result = [
    'success' => $deleted,
    'msg' => $alertMessage,
    'reload' => false,
    'trigger' => $trigger
];

echo json_encode($result);
exit;
