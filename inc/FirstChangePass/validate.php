<?php

$alertTitle = '';
$alertMessage = '';

$cambiada = false;

$sessionId = $_SESSION['ID'];
$sessionInitials = $_SESSION['Iniciales'];
$actualPass = $class->encryptPassword($sessionInitials . '12345');
$newPass = $class->encryptPassword($_POST['new_pass']);
$newPassConfirm = $class->encryptPassword($_POST['new_pass_confirm']);

$sql = "SELECT ID FROM Profesores WHERE ID='$sessionId' AND Password = '$actualPass'";
if ($response = $class->query($sql)) {
    if ($response->num_rows == 1) {
        if ($newPass === $newPassConfirm) {
            if ($newPass !== $actualPass) {
                $sql = "UPDATE Profesores SET Profesores.Password='$newPass' WHERE Profesores.ID='$sessionId'";
                if ($class->query($sql)) {
                    $alertMessage = 'Contraseña cambiada satisfatoriamente.';
                    $cambiada = true;
                } else {
                    $alertMessage = 'Error inesperado, contacte con los administradores...';
                }
            } else {
                $alertMessage = 'La nueva contraseña debe ser distinta a la actual.';
            }
        } else {
            $alertMessage = 'Las nuevas contraseñas no coinciden.';
        }
    } else {
        $alertMessage = 'Contraseña actual incorrecta.';
    }
} else {
    $alertMessage = 'Error inesperado, contacte con los administradores...';
}

$result = [
    'success' => $cambiada,
    'msg' => $alertMessage
];

echo json_encode($result);
