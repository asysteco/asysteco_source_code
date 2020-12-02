<?php

$pSustituido = $_GET['ID_PROFESOR'];
$pSustituto = $_GET['ID_SUSTITUTO'];

if($response = $class->query("SELECT ID, Nombre FROM Profesores WHERE Profesores.ID='$pSustituido' AND Profesores.TIPO='1'"))
{
    if($response->num_rows > 0)
    {
        $ERR_MSG = "No puedes sustituir a un administrador del sistema.";
    }
    else
    {
        if(! $profesor = $class->query("SELECT ID, Nombre FROM Profesores WHERE Profesores.ID='$pSustituido'")->fetch_assoc())
        {
            echo $class->ERR_ASYSTECO;
        }
        if(! $sustituto = $class->query("SELECT ID, Nombre FROM Profesores WHERE Profesores.ID='$pSustituto'")->fetch_assoc())
        {
            echo $class->ERR_ASYSTECO;
        }
        if($class->query("UPDATE Profesores SET Sustituido=1 WHERE ID='$pSustituido'"))
        {
            if($class->query(
                "INSERT INTO Horarios (ID_PROFESOR, Dia, Hora, Tipo, Edificio, Aula, Grupo, Hora_entrada, Hora_salida)
                SELECT $pSustituto, Dia, Hora, Tipo, Edificio, Aula, Grupo, Hora_entrada, Hora_salida FROM Horarios WHERE ID_PROFESOR='$pSustituido'"))
            {
                $MSG = "$profesor[Nombre] ha sido sustituido/a correctamente por $sustituto[Nombre]";
            }
            $class->marcajes($pSustituido, 'remove');
            $class->marcajes($pSustituto, 'add');
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
    echo $class->ERR_ASYSTECO;
    return false;
}