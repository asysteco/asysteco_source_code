<?php

$profesor = $_GET['profesor'];
$dia = $_GET['dia'];
$hora = $_GET['hora'];
$edificio = "1";

if($_GET['SUBOPT'] == 'add')
{
    $sql "INSERT INTO $class->horarios (ID_PROFESOR, Dia, HORA_TIPO, Edificio, Aula, Grupo, Hora_entrada, Hora_salida) VALUES ('$profesor', '$dia', '$hora', '$edificio', 'Guardia', 'Guardia', '00:00:00', '00:00:00')";
    if($response = $class->query($sql))
    {
        echo "Hecho insert";
    }
    else
    {
        echo $ERR_MSG = $class->ERR_ASYSTECO;
    }
}
elseif($_GET['SUBOPT'] == 'remove')
{
    $sql "DELETE FROM $class->horarios WHERE ID_PROFESOR='$profesor' AND Dia='$dia' AND HORA_TIPO='$hora'";
    if($response = $class->query($sql))
    {
        echo "Hecho delete";
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