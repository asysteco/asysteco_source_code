<?php
if($response = $class->query("SELECT ID, Nombre FROM $class->profesores WHERE ID='$_GET[ID]' AND Sustituido=1"))
{
    if($response->num_rows > 0)
    {
        if($fila = $response->fetch_assoc())
        {
            if($class->query("UPDATE Profesores SET Sustituido=0 WHERE ID='$_GET[ID]'"))
            {
                $MSG = "La sustitución de $fila[Nombre] ha terminado correctamente.";
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
