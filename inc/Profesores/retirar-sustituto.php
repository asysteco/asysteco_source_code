<?php

$profesor = $_GET['ID'];

if($response = $class->query("SELECT ID, Nombre FROM $class->profesores WHERE ID='$profesor' AND Sustituido=1"))
{
    if($response->num_rows > 0)
    {
        if($fila = $response->fetch_assoc())
        {
            if($class->query("UPDATE Profesores SET Sustituido=0 WHERE ID='$profesor'"))
            {
                $MSG = "La sustitución de $fila[Nombre] ha terminado correctamente.";
                $class->marcajes($profesor, 'add');
                header("Location: index.php?ACTION=profesores");
            }
            else
            {
                $class->ERR_ASYSTECO;
                return false;
            }
        }
    }
}
