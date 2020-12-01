<?php

$action = $_GET['act'];
$valor = $_GET['Valor'];
$profesor = $_GET['Profesor'];
$fecha = $_GET['Fecha'];
$hora = $_GET['Hora'];

$nombre = $_SESSION['Nombre'];

if(isset($action) && $action != '')
{
    if($action == 'Asiste')
    {
        if($class->query("UPDATE Marcajes SET Asiste=$_GET[Valor] WHERE ID_PROFESOR='$profesor' AND Fecha='$fecha' AND Hora='$hora'"))
        {
            $response = $class->query("SELECT DISTINCT Tipo FROM Horarios WHERE ID_PROFESOR='$profesor'")->fetch_assoc();
            $franja = $response['Tipo'] ?? 'Mañana';

            if($valor == 1)
            {
                $msg = "$nombre ha modificado el registro del Día: $fecha Horas: {$franjasHorarias[$franja][$hora]['Inicio']} - {$franjasHorarias[$franja][$hora]['Fin']} como Asistido.";
                $MSG= 'Ok-asiste';
            }
            elseif($valor == 0)
            {
                $msg = "$nombre ha modificado el registro del Día: $fecha Horas: {$franjasHorarias[$franja][$hora]['Inicio']} - {$franjasHorarias[$franja][$hora]['Fin']} como Falta.";
                $MSG= 'Ok-falta';
            }
            elseif($valor == 2)
            {
                $msg = "$nombre ha modificado el registro del Día: $fecha Horas: {$franjasHorarias[$franja][$hora]['Inicio']} - {$franjasHorarias[$franja][$hora]['Fin']} como Actividad Extraescolar.";
                $MSG= 'Ok-extraescolar';
            }
            else
            {
                $msg = "$nombre ha enviado un valor incorrecto.";
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
        $response = $class->query("SELECT DISTINCT Tipo FROM Horarios WHERE ID_PROFESOR='$profesor'")->fetch_assoc();
        $franja = $response['Tipo'];
        if($class->query("UPDATE Marcajes SET Justificada=$_GET[Valor] WHERE ID_PROFESOR='$profesor' AND Fecha='$fecha' AND Hora='$hora'"))
        {
            if($valor == 1)
            {
                $msg = "$nombre ha modificado el registro del Día: $fecha Hora: {$franjasHorarias[$franja][$hora]['Inicio']} - {$franjasHorarias[$franja][$hora]['Fin']} como Falta Justificada.";
                $MSG= 'Ok-justificada';
            }
            elseif($valor == 0)
            {
                $msg = "$nombre ha modificado el registro del Día: $fecha Hora: {$franjasHorarias[$franja][$hora]['Inicio']} - {$franjasHorarias[$franja][$hora]['Fin']} retirando la justificación.";
                $MSG= 'Ok-injustificado';
            }
            else
            {
                $msg = "$nombre ha enviado un valor incorrecto.";
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
        WHERE ID_PROFESOR='$profesor' AND Fecha='$fecha' AND Hora='$hora'");
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