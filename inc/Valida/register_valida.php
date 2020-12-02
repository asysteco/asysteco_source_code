<?php

//Validación del formulario de registro

if(! $class->validFormName($_POST['Nombre']))
{
    $ERR_REG_FORM .= $class->ERR_ASYSTECO;
}
if(! $class->validFormIni($_POST['Iniciales']))
{
    $ERR_REG_FORM .= $class->ERR_ASYSTECO;
}
if(! $class->encryptPassword($_POST['pass1']) == $class->encryptPassword($_POST['pass2']))
{
    $ERR_REG_FORM .= "Las contraseñas no coinciden <br>";
}
if(! isset($ERR_REG_FORM))
{
    if($class->searchDuplicateField($_POST['Iniciales'], 'Iniciales', $class->profesores))
    {
        if($class->registerNewUser($_POST['Nombre'], $_POST['Iniciales'], $class->encryptPassword($_POST['pass1'])))
        {
            $MSG = "Te has registrado correctamente.";
            header("Refresh:2; url=index.php");
            include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
        }
        else
        {
            $ERR_REG_FORM = $class->ERR_ASYSTECO;
            include_once($dirs['inc'] . 'register_form.php');
        }
    }
    else
    {
        $ERR_REG_FORM = "El usuario con Iniciales: $_POST[Iniciales] ya existe.";
        include_once($dirs['inc'] . 'register_form.php');
    }
}
else
{
    include_once($dirs['inc'] . 'register_form.php');
}