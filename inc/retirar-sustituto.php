<?php
if($response = $class->query("SELECT ID, Nombre FROM $class->profesores WHERE ID='$_GET[ID]' AND Sustituido=1"))
{
    if($response->num_rows > 0)
    {
        if($class->query("UPDATE Profesores SET Sustituido=0 WHERE ID='$_GET[ID]'"))
        {
            header('Location: $_SERVER[HTTP_REFERER]');
        }
        else
        {
            $class->ERR_ASYSTECO;
            return false;
        }
    }
}
