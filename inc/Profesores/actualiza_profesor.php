<?php

$profesor = $_POST['ID'];
$nombre = $_POST['Nombre'];
$ini = $_POST['Iniciales'];
$tutor = $_POST['Tutor'];

if ($profesor != '') {
    $sql = "UPDATE Profesores 
    SET Profesores.Iniciales='$ini',
    Profesores.Nombre='$nombre', 
    Profesores.Tutor='" . mysqli_real_escape_string($class->conex, $tutor) . "' WHERE Profesores.ID=" . mysqli_real_escape_string($class->conex, $profesor);
    if ($class->query($sql)) {
        $MSG = "actualizado";
    } else {
        $MSG = 'error-actualizar';
    }
}

$sql = "SELECT * FROM Profesores WHERE ID=? AND Iniciales=?";
$id = "";
$iniciales = "";
$stmt = $class->conex->prepare($sql);
$bindParams = $stmt->bind_param('is', $id, $iniciales);
$class->query($sql, $bindParams);