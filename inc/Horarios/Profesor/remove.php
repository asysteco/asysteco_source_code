<?php

$profesor = $_POST['profesor'];
$alertMessage = 'Error inesperado, contacte con los administradores...';

$deleted = false;

if ($class->query("DELETE FROM Horarios WHERE ID_PROFESOR='$profesor'")) {
    $class->marcajes($profesor, 'remove');
    $alertMessage = 'Horario eliminado correctamente.';
    $deleted = true;
} else {
    $alertMessage = 'Ha ocurrido un error inesperado.';
}

$result = [
    'success' => $deleted,
    'msg' => $alertMessage,
    'reload' => false
];

echo json_encode($result);
exit;