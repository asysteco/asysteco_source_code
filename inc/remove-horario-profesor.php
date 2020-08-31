<?php
if($class->query("DELETE FROM $class->horarios WHERE ID_PROFESOR='$_GET[profesor]'"))
{
    if($class->query("DELETE FROM $class->marcajes WHERE Fecha > DATE_ADD(CURDATE(), INTERVAL +1 DAY) AND ID_PROFESOR='$_GET[profesor]' "))
    {
        $MSG = "Horario eliminado correctamente.";
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