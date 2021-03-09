<?php

$profesor = $_GET['ID'];

$alertMessage = 'Error inesperado, contacte con los administradores...';

$status = false;

$sql = "SELECT p.Iniciales FROM Profesores p WHERE p.ID='$profesor'";
if ($res = $class->query($sql)) {
    if ($res->num_rows > 0) {
        $datos = $res->fetch_assoc();
        $passenc = $class->encryptPassword($datos['Iniciales'] . '12345');
        $sql = "UPDATE Profesores SET Profesores.Password = '$passenc' WHERE Profesores.ID='$profesor'";
        if ($class->query($sql)) {
            $alertMessage = 'Se ha restablecido la contraseña correctamente.';
            $status = true;
        }
    } else {
        $alertMessage = 'No se ha podido restablecer la contraseña.';
    }
}

$result = [
    'success' => $status,
    'msg' => $alertMessage,
    'reload' => false
];

echo json_encode($result);
exit;
