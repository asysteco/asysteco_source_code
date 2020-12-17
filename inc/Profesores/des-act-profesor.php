<?php

$profesor = $_GET['ID'];

if($resp = $class->query("SELECT ID, Nombre, TIPO FROM $class->profesores WHERE $class->profesores.ID='$profesor' AND $class->profesores.Activo=1"))
{
    if($resp->num_rows > 0)
    {
        $datos = $resp->fetch_assoc();
        if($datos['TIPO'] == 1)
        {
            $ERR_MSG = "No se puede desactivar a un administrador del sistema.";
        }
        else
        {
            if($class->query("UPDATE Profesores SET Activo=0 WHERE ID='$profesor'"))
            {
                $msg = "Usuario desactivado.";
                $class->notificar($profesor, $msg);
                $MSG = "Cambios realizados correctamente.";
                $_GET['profesor'] = $profesor;
                include_once($dirs['Horarios'] . 'remove-horario-profesor.php');
            }
            else
            {
                echo $class->ERR_ASYSTECO;
                return false;
            }
        }
    }
    else
    {
        if($class->query("UPDATE Profesores SET Activo=1 WHERE ID='$profesor'"))
        {
            $msg = "Usuario activado.";
            $class->notificar($profesor, $msg);
            $class->marcajes($profesor, 'add');
            $MSG = "Cambios realizados correctamente.";
        }
        else
        {
            echo $class->ERR_ASYSTECO;
            return false;
        }
    }
}
else
{
    $ERR_MSG = $class->ERR_ASYSTECO;
}