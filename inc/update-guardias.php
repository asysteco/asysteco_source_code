<?php

$profesor = $_GET['profesor'];
$dia = $_GET['Dia'];
$hora = $_GET['Hora'];
$subopt = $_GET['SUBOPT'];
$edificio = $_GET['e'];

$Tipo = preg_split('//', $_GET['Tipo'], -1, PREG_SPLIT_NO_EMPTY);
$Tipo = $Tipo[0];
$Horatipo= $_GET['Hora'] . $Tipo;

if($subopt == 'add')
{
    $sql = "INSERT INTO $class->horarios (ID_PROFESOR, Dia, HORA_TIPO, Hora, Tipo, Edificio, Aula, Grupo, Hora_entrada, Hora_salida) VALUES ('$profesor', '$dia', '$Horatipo', '$hora', '$_GET[Tipo]', '$edificio', 'GU". $edificio ."00', 'Guardia', '00:00:00', '00:00:00')";
    if($class->query($sql))
    {
        $class->updateHoras($profesor);
        $class->marcajes($profesor, $dia, $hora, $subopt);
    }
    else
    {
        echo $ERR_MSG = $class->ERR_ASYSTECO;
    }
}
elseif($subopt == 'remove')
{
    $sql = "DELETE FROM $class->horarios WHERE ID_PROFESOR='$profesor' AND Dia='$dia' AND Hora='$hora'";
    if($class->query($sql))
    {
        $class->updateHoras($profesor);
        $class->marcajes($profesor, $dia, $hora, $subopt);
    }
    else
    {
        echo $ERR_MSG = $class->ERR_ASYSTECO;
    }
}
else
{
    echo $ERR_MSG = "No SUBOPT has given.";
}