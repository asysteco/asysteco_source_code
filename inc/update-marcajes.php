<?php
if(isset($_GET['act']) && $_GET['act'] != '')
{
    if($_GET['act'] == 'Asiste')
    {
        if($class->query("UPDATE Marcajes SET Asiste=$_GET[Valor] WHERE ID_PROFESOR='$_GET[Profesor]' AND Fecha='$_GET[Fecha]' AND Hora='$_GET[Hora]'"))
        {
            if($_GET['Valor'] == 1)
            {
                $msg = "$_SESSION[Nombre] ha modificado el registro del Día: $_GET[Fecha] Hora: $_GET[Hora] como Asistido.";
            }
            elseif($_GET['Valor'] == 0)
            {
                $msg = "$_SESSION[Nombre] ha modificado el registro del Día: $_GET[Fecha] Hora: $_GET[Hora] como Falta.";
            }
            elseif($_GET['Valor'] == 2)
            {
                $msg = "$_SESSION[Nombre] ha modificado el registro del Día: $_GET[Fecha] Hora: $_GET[Hora] como Actividad Extraescolar.";
            }
            else
            {
                $msg = "$_SESSION[Nombre] ha enviado un valor incorrecto.";
            }
        
            if(! $class->notificar($_GET['Profesor'], $msg))
            {
                echo $class->ERR_ASYSTECO;
                return false;
            }
            return true;
        }
        else
        {
            echo $class->ERR_ASYSTECO;
            return false;
        }
    }
    elseif($_GET['act'] == 'Justificada')
    {
        if($class->query("UPDATE Marcajes SET Justificada=$_GET[Valor] WHERE ID_PROFESOR='$_GET[Profesor]' AND Fecha='$_GET[Fecha]' AND Hora='$_GET[Hora]'"))
        {
            if($_GET['Valor'] == 1)
            {
                $msg = "$_SESSION[Nombre] ha modificado el registro del Día: $_GET[Fecha] Hora: $_GET[Hora] como Falta Justificada.";
            }
            elseif($_GET['Valor'] == 0)
            {
                $msg = "$_SESSION[Nombre] ha modificado el registro del Día: $_GET[Fecha] Hora: $_GET[Hora] retirando la justificación.";
            }
            else
            {
                $msg = "$_SESSION[Nombre] ha enviado un valor incorrecto.";
            }
        
            if(! $class->notificar($_GET['Profesor'], $msg))
            {
                echo $class->ERR_ASYSTECO;
                return false;
            }
            return true;
        }
        else
        {
            echo $class->ERR_ASYSTECO;
            return false;
        }
    }
    else
    {
        echo $ERR_MSG = "Acción no válida."; 
    }
}
else
{
    echo $ERR_MSG = "No se ha establecido acción."; 
}