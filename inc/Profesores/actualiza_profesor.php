<?php

if ($_POST['ID'] != '') {
    $sql = "UPDATE Profesores 
    SET Profesores.Iniciales='$_POST[Iniciales]',
    Profesores.Nombre='$_POST[Nombre]', 
    Profesores.Tutor='" . mysqli_real_escape_string($class->conex, $_POST['Tutor']) . "' WHERE Profesores.ID=" . mysqli_real_escape_string($class->conex, $_POST['ID']);
    if ($class->query($sql)) {
        $MSG = "Datos actualizados correctamente.";
        header("Location:index.php?ACTION=profesores");
    } else {
        $ERR_MSG = $class->ERR_ASYSTECO;
    }
}



$sql = "SELECT * FROM Profesores WHERE ID=? AND Iniciales=?";
$id = "";
$iniciales = "";
$class->conex->prepare($sql);
$bindParams = $class->conex->bind_param('is', $id, $iniciales);
$class->query($sql, $bindParams);