<?php
if($class->query("UPDATE Marcajes SET Asiste=$_GET[Valor] WHERE ID_PROFESOR='$_GET[Profesor]' AND Fecha='$_GET[Fecha]' AND Hora='$_GET[Hora]'"))
{
    if($_GET['Valor'] == 1)
    {
        $msg = "$_SESSION[Nombre] ha modificado el registro del Día: $_GET[Fecha] Hora: $_GET[Hora] como Asistido.";
    }
    elseif($_GET['Valor'] == 0 && $_SESSION['Perfil'] == 'Admin')
    {
        $msg = "$_SESSION[Nombre] ha modificado el registro del Día: $_GET[Fecha] Hora: $_GET[Hora] como Ausente.";
    }
    elseif($_GET['Valor'] == 2)
    {
        $msg = "$_SESSION[Nombre] ha modificado el registro del Día: $_GET[Fecha] Hora: $_GET[Hora] como Actividad Extraescolar.";
    }
    else
    {
        $msg = "";
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