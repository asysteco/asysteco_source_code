<?php

$changePass = $_POST['new_password'];
$alertTitle = '';
$alertMessage = '';

$cambiada = false;

$sessionId = $_SESSION['ID'];
$actualPass = $class->encryptPassword($_POST['act_pass']);
$newPass = $class->encryptPassword($_POST['new_pass']);
$newPassConfirm = $class->encryptPassword($_POST['new_pass_confirm']);

$sql = "SELECT ID FROM Profesores WHERE ID='$sessionId' AND Password = '$actualPass'";
if ($response = $class->query($sql)) {
    if ($response->num_rows == 1) {
        if ($newPass !== $actualPass) {
            if ($newPass === $newPassConfirm) {
                $sql = "UPDATE Profesores SET Profesores.Password='$newPass' WHERE Profesores.ID='$sessionId'";
                if ($class->query($sql)) {
                    $alertMessage = 'Contraseña cambiada satisfatoriamente.';
                    $cambiada = true;
                    unset($_SESSION['changedPass']);
                } else {
                    $alertMessage = 'Error inesperado, contacte con los administradores...';
                }
            } else {
                $alertMessage = 'Las nuevas contraseñas no coinciden.';
            }
        } else {
            $alertMessage = 'La nueva contraseña debe ser distinta a la actual.';
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
