<?php

$iniciales = $_POST['Iniciales'];
$id = $_POST['ID'];
$nombre = $_POST['Nombre'];
$alertMessage = 'Error inesperado, contacte con los administradores...';

$status = false;
$reload = false;

if($class->validFormIni($iniciales)) {
    if($response = $class->query("SELECT ID FROM Profesores WHERE Iniciales = '$iniciales' AND ID = '$id'")) {
        if(! $response->num_rows > 0) {
            if($class->searchDuplicateField($iniciales, 'Iniciales', 'Profesores')) {
                if($class->validFormName($nombre)) {
                    include_once($dirs['Profesores'] . 'Edit/Ajax/update.php');
                    $reload = true;
                } else {
                    $alertMessage = 'Nombre no válido.';
                }
            } else {
                $alertMessage = "Iniciales duplicadas.";
            }
        } else {
            if($class->validFormName($nombre)) {
                include_once($dirs['Profesores'] . 'Edit/Ajax/update.php');
                $reload = true;
            } else {
                $alertMessage = 'Nombre no válido.';
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