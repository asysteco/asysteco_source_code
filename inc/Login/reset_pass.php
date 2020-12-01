<?php
if($res = $class->query("SELECT $class->profesores.Iniciales FROM $class->profesores WHERE $class->profesores.ID='$_GET[ID]'"))
{
    if($res->num_rows > 0)
    {
        $datos = $res->fetch_assoc();
        $passenc = $class->encryptPassword($datos['Iniciales'] . '12345');
        if($class->query("UPDATE $class->profesores SET $class->profesores.Password = '$passenc' WHERE $class->profesores.ID='$_GET[ID]'"))
        {
            $MSG = 'Contraseña restablecida satisfatoriamente.';
        }
        else
        {
            $ERR_MSG = $class->ERR_ASYSTECO;
        }
    }
    else
    {
        $ERR_MSG = $class->ERR_ASYSTECO;
    }
}
else
{
    $ERR_MSG = $class->ERR_ASYSTECO;
}