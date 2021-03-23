<?php

$original = $_POST['ID_PROFESOR'] ?? '';
$clonado = $_POST['ID_CLONADO'] ?? '';
$alertMessage = 'Error inesperado, contacte con los administradores...';

$status = false;
$trigger = false;

$mysql = $class->conex;
$mysql->autocommit(FALSE);

try {
    $sql = "DELETE FROM Horarios WHERE ID_PROFESOR = '$clonado'";
    $class->autocommitOffQuery($mysql, $sql, 'Ha ocurrido un error inesperado...');
    $sql = "INSERT INTO Horarios (ID_PROFESOR, Dia, Hora, Tipo, Edificio, Aula, Grupo, Hora_entrada, Hora_salida)
    SELECT $clonado, Dia, Hora, Tipo, Edificio, Aula, Grupo, Hora_entrada, Hora_salida FROM Horarios WHERE ID_PROFESOR='$original'";
    $class->autocommitOffQuery($mysql, $sql, 'Ha ocurrido un error inesperado...');
    $class->marcajes($clonado, 'remove');
    $class->marcajes($clonado, 'add');
    $alertMessage = "Clonación realizada correctamente.";
    $status = true;
    $trigger = 'close-modal';
} catch (Exception $e) {
    $alertMessage = $e->getMessage();
    $mysql->rollback();
}

$mysql->commit();

$result = [
    'success' => $status,
    'msg' => $alertMessage,
    'trigger' => $trigger
];

echo json_encode($result);

exit;
