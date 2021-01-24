<?php

$profesor = $_GET['ID'];

$sql = "SELECT p.Iniciales FROM Profesores p WHERE p.ID='$profesor'";
if ($res = $class->query($sql)) {
    if ($res->num_rows > 0) {
        $datos = $res->fetch_assoc();
        $passenc = $class->encryptPassword($datos['Iniciales'] . '12345');
        $sql = "UPDATE Profesores SET Profesores.Password = '$passenc' WHERE Profesores.ID='$profesor'";
        $MSG = $class->query($sql)? 'ok-reset': 'error-reset';
    } else {
        $MSG = 'error-reset';
    }
} else {
    $MSG = 'error-inesperado';
}

echo $MSG;
exit;
