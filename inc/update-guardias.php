<?php

$profesor = $_GET['profesor'];
$dia = $_GET['d'];
$hora = $_GET['h'];
$edificio = $_GET['e'];

if($_GET['SUBOPT'] == 'add')
{
    $sql = "INSERT INTO $class->horarios (ID_PROFESOR, Dia, HORA_TIPO, Edificio, Aula, Grupo, Hora_entrada, Hora_salida) VALUES ('$profesor', '$dia', '$hora', '$edificio', 'GU". $edificio ."00', 'Guardia', '00:00:00', '00:00:00')";
    if($class->query($sql))
    {
        $class->updateHoras($profesor);
        $class->marcajes($profesor);
    }
    else
    {
        echo $ERR_MSG = $class->ERR_ASYSTECO;
    }
}
elseif($_GET['SUBOPT'] == 'remove')
{
    $sql = "DELETE FROM $class->horarios WHERE ID_PROFESOR='$profesor' AND Dia='$dia' AND HORA_TIPO='$hora'";
    if($class->query($sql))
    {
        $class->updateHoras($profesor);
        $class->marcajes($profesor, $dia, $hora);
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