<?php

if($_POST['ID'] != '')
{
    $sql = "UPDATE $class->profesores 
    SET $class->profesores.Iniciales='$_POST[Iniciales]',
    $class->profesores.Nombre='$_POST[Nombre]', 
    $class->profesores.Tutor='" . mysqli_real_escape_string($class->conex, $_POST['Tutor']) . "' WHERE $class->profesores.ID=" . mysqli_real_escape_string($class->conex, $_POST['ID']);
    if($class->query($sql))
    {
        $MSG = "Datos actualizados correctamente.";
        header("Location:index.php?ACTION=profesores");
    }
    else
    {
        $ERR_MSG = $class->ERR_ASYSTECO;
    }
}

?>