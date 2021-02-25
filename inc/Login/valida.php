<?php

$iniciales = $_POST['Iniciales'];
$pass = $_POST['pass'];

if (!empty($iniciales) != '' && $pass != '') {
    if ($class->validFormIni($iniciales)) {
        if ($class->Login($iniciales, $pass, $Titulo)) {
            header("Location: index.php");
        } else {
            $ERR_LOGIN_FORM = $class->ERR_ASYSTECO;
        }
    } else {
        $ERR_LOGIN_FORM = $class->ERR_ASYSTECO;
    }
} else {
    $ERR_LOGIN_FORM = "· Rellene los campos vacíos </br>";
}
