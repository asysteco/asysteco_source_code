<?php

$profesor = $_POST['ID'];
$nombre = $_POST['Nombre'];
$ini = $_POST['Iniciales'];
$tutor = $_POST['Tutor'];
$alertMessage = 'Error inesperado, contacte con los administradores...';

$status = false;

if ($profesor != '') {
    $sql = "UPDATE Profesores 
    SET Profesores.Iniciales='$ini',
    Profesores.Nombre='$nombre', 
    Profesores.Tutor='" . mysqli_real_escape_string($class->conex, $tutor) . "' WHERE Profesores.ID=" . mysqli_real_escape_string($class->conex, $profesor);
    if ($class->query($sql)) {
        $alertMessage = "Datos actualizados correctamente.";
        $status = true;
    }
}

$sql = "SELECT * FROM Profesores WHERE ID=? AND Iniciales=?";
$id = "";
$iniciales = "";
$stmt = $class->conex->prepare($sql);
$bindParams = $stmt->bind_param('is', $id, $iniciales);
$class->query($sql, $bindParams);
