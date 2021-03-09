<?php

$profesor = $_GET['ID'];
$alertMessage = 'Error inesperado, contacte con los administradores...';

$status = false;
$reload = false;

if ($response = $class->query("SELECT ID, Nombre FROM $class->profesores WHERE ID='$profesor' AND Sustituido=1")) {
    if ($response->num_rows > 0) {
        if ($fila = $response->fetch_assoc()) {
            if ($class->query("UPDATE Profesores SET Sustituido=0 WHERE ID='$profesor'")) {
                $alertMessage = "Datos actualizados correctamente.";
                $class->marcajes($profesor, 'add');
                $status = true;
                $reload = true;
            } else {
                $alertMessage = "Ha ocurrido un problema. Los cambios no se han realizado.";
            }
        }
    }
}

$result = [
    'success' => $status,
    'msg' => $alertMessage,
    'reload' => $reload
];

echo json_encode($result);
exit;
