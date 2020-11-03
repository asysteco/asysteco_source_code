<?php
if($response = $class->query("SELECT ID, Nombre FROM $class->profesores WHERE $class->profesores.ID='$_GET[ID_PROFESOR]' AND $class->profesores.TIPO='1'"))
{
    if($response->num_rows > 0)
    {
        $ERR_MSG = "No puedes sustituir a un administrador del sistema.";
    }
    else
    {
        if(! $profesor = $class->query("SELECT ID, Nombre FROM $class->profesores WHERE $class->profesores.ID='$_GET[ID_PROFESOR]'")->fetch_assoc())
        {
            echo $class->ERR_ASYSTECO;
        }
        if(! $sustituto = $class->query("SELECT ID, Nombre FROM $class->profesores WHERE $class->profesores.ID='$_GET[ID_SUSTITUTO]'")->fetch_assoc())
        {
            echo $class->ERR_ASYSTECO;
        }
        if($class->query("UPDATE Profesores SET Sustituido=1 WHERE ID='$_GET[ID_PROFESOR]'"))
        {
            if($class->query("INSERT INTO $class->horarios (ID_PROFESOR, Dia, Hora, Tipo, Edificio, Aula, Grupo, Hora_entrada, Hora_salida) SELECT $_GET[ID_SUSTITUTO], Dia, Hora, Tipo, Edificio, Aula, Grupo, Hora_entrada, Hora_salida FROM $class->horarios WHERE ID_PROFESOR='$_GET[ID_PROFESOR]'"))
            {
                $MSG = "$profesor[Nombre] ha sido sustituido/a correctamente por $sustituto[Nombre]";
            }
            $_GET['ID_PROFESOR'] = $_GET['ID_SUSTITUTO'];
            $class->marcajes($_GET['ID_PROFESOR']);
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