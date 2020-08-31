<?php
if($resp = $class->query("SELECT ID, Nombre, TIPO FROM $class->profesores WHERE $class->profesores.ID='$_GET[ID]' AND $class->profesores.Activo=1"))
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
            if($class->query("UPDATE Profesores SET Activo=0 WHERE ID='$_GET[ID]'"))
            {
                $msg = "Usuario desactivado.";
                $class->notificar($_GET['ID'], $msg);
                $MSG = "Cambios realizados correctamente.";
                $_GET['profesor'] = $_GET['ID'];
                include_once($dirs['inc'] . 'delete-horario-profesor.php');
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
        if($class->query("UPDATE Profesores SET Activo=1 WHERE ID='$_GET[ID]'"))
        {
            $msg = "Usuario activado.";
            $class->notificar($_GET['ID'], $msg);
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