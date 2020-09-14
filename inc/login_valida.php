<?php

// Validación del formulario

if($_POST['Iniciales'] != '' && $_POST['pass'] != '')
{
    if($class->validFormIni($_POST['Iniciales']))
    {
        if($class->Login($_POST['Iniciales'], $_POST['pass']))
        {
                header("Location: index.php");
        }
        else
        {
            $ERR_LOGIN_FORM = $class->ERR_ASYSTECO;
        }
    }
    else
    {
        $ERR_LOGIN_FORM = $class->ERR_ASYSTECO;
    }
}
else
{
    $ERR_LOGIN_FORM = "· Rellene los campos vacíos </br>";
}