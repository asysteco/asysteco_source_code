<?php

$action = $_GET['act'];

if(isset($action) && $action != '')
{
    if($action == 'Asiste')
    {
        if($class->query("UPDATE Marcajes SET Asiste=$_GET[Valor] WHERE ID_PROFESOR='$_GET[Profesor]' AND Fecha='$_GET[Fecha]' AND Hora='$_GET[Hora]'"))
        {
            $response = $class->query("SELECT DISTINCT Tipo FROM Horarios WHERE ID_PROFESOR='$_GET[Profesor]'")->fetch_assoc();
            $franja = $response['Tipo'];
            if($_GET['Valor'] == 1)
            {
                $msg = "$_SESSION[Nombre] ha modificado el registro del Día: $_GET[Fecha] Horas: {$franjasHorarias[$franja][$_GET[Hora]]['Inicio']} - {$franjasHorarias[$franja][$_GET[Hora]]['Fin']} como Asistido.";
                $MSG= 'Ok-asiste';
            }
            elseif($_GET['Valor'] == 0)
            {
                $msg = "$_SESSION[Nombre] ha modificado el registro del Día: $_GET[Fecha] Horas: {$franjasHorarias[$franja][$_GET[Hora]]['Inicio']} - {$franjasHorarias[$franja][$_GET[Hora]]['Fin']} como Falta.";
                $MSG= 'Ok-falta';
            }
            elseif($_GET['Valor'] == 2)
            {
                $msg = "$_SESSION[Nombre] ha modificado el registro del Día: $_GET[Fecha] Horas: {$franjasHorarias[$franja][$_GET[Hora]]['Inicio']} - {$franjasHorarias[$franja][$_GET[Hora]]['Fin']} como Actividad Extraescolar.";
                $MSG= 'Ok-extraescolar';
            }
            else
            {
                $msg = "$_SESSION[Nombre] ha enviado un valor incorrecto.";
            }
        
            if(! $class->notificar($_GET['Profesor'], $msg))
            {
                echo $class->ERR_ASYSTECO;
            }
        }
        else
        {
            $MSG = 'Error-action';
        }
    }
    elseif($action == 'Justificada')
    {
        $response = $class->query("SELECT DISTINCT Tipo FROM Horarios WHERE ID_PROFESOR='$_GET[Profesor]'")->fetch_assoc();
        $franja = $response['Tipo'];
        if($class->query("UPDATE Marcajes SET Justificada=$_GET[Valor] WHERE ID_PROFESOR='$_GET[Profesor]' AND Fecha='$_GET[Fecha]' AND Hora='$_GET[Hora]'"))
        {
            if($_GET['Valor'] == 1)
            {
                $msg = "$_SESSION[Nombre] ha modificado el registro del Día: $_GET[Fecha] Hora: {$franjasHorarias[$franja][$_GET[Hora]]['Inicio']} - {$franjasHorarias[$franja][$_GET[Hora]]['Fin']} como Falta Justificada.";
                $MSG= 'Ok-justificada';
            }
            elseif($_GET['Valor'] == 0)
            {
                $msg = "$_SESSION[Nombre] ha modificado el registro del Día: $_GET[Fecha] Hora: {$franjasHorarias[$franja][$_GET[Hora]]['Inicio']} - {$franjasHorarias[$franja][$_GET[Hora]]['Fin']} retirando la justificación.";
                $MSG= 'Ok-injustificado';
            }
            else
            {
                $msg = "$_SESSION[Nombre] ha enviado un valor incorrecto.";
            }
        
            if(! $class->notificar($_GET['Profesor'], $msg))
            {
                echo $class->ERR_ASYSTECO;
            }
        }
        else
        {
            $MSG = 'Error-action';
        }
    } elseif($action == 'getrow'){
        $response = $class->query("SELECT Marcajes.*, Diasemana 
        FROM Marcajes INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID 
        WHERE ID_PROFESOR='$_GET[Profesor]' AND Fecha='$_GET[Fecha]' AND Hora='$_GET[Hora]'");
    }
    else
    {
        $MSG = 'Error-Invalid-action'; 
    }
}
else
{
    
    $MSG = 'Error-parameter';; 
}

echo $MSG;