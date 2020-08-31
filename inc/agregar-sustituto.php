<?php
if($response = $class->query("SELECT ID, Nombre FROM $class->profesores WHERE $class->profesores.ID='$_GET[ID_PROFESOR]' AND $class->profesores.TIPO='1'"))
{
    if($response->num_rows > 0)
    {
        $ERR_MSG = "No puedes sustituir a un administrador del sistema.";
    }
    else
    {
        if($class->query("UPDATE Profesores SET Sustituido=1 WHERE ID='$_GET[ID_PROFESOR]'"))
        {
            if($class->query("INSERT INTO $class->horarios (ID_PROFESOR, Dia, HORA_TIPO, Edificio, Aula, Grupo, Hora_entrada, Hora_salida) SELECT $_GET[ID_SUSTITUTO], Dia, HORA_TIPO, Edificio, Aula, Grupo, Hora_entrada, Hora_salida FROM $class->horarios WHERE ID_PROFESOR='$_GET[ID_PROFESOR]'"))
            {
                $MSG = "Cambios realizados correctamente.";
            }
            $_GET['ID_PROFESOR'] = $_GET['ID_SUSTITUTO'];
            include_once($dirs['inc'] . 'marcajes.php');
        }
        else
        {
            echo $class->ERR_ASYSTECO;
            return false;
        }
    }
}