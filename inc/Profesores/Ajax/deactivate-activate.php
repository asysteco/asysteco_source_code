<?php

$profesor = $_GET['ID'];
$action = $_POST['action'];
$fecha = $_POST['fecha'] ?? '';
$alertMessage = 'Error inesperado, contacte con los administradores...';

$status = false;
$reload = false;

if ($action === 'activar') {
    $sql = "UPDATE Profesores SET Activo=1 WHERE ID='$profesor' AND Tipo != 1";
    if ($class->query($sql)) {     
        $alertMessage = "Profesor activado correctamente.";
        $status = true;
        $reload = true;
    } else {
        $alertMessage = "Error al activar profesor.";
    }
    $class->marcajes($profesor, 'remove');
    $class->marcajes($profesor, 'add');
}

if ($action === 'desactivar') {
    $mysql = $class->conex;
    $mysql->autocommit(FALSE);
    $alertMessage = "Profesor desactivado correctamente.";
    if ($class->validFormDate($fecha)) {
        $fechaFormateada = $class->formatEuropeanDateToSQLDate($fecha);
        try {
            $sql2 = "UPDATE Profesores SET Activo=0 WHERE ID='$profesor' AND Tipo != 1 AND $fechaFormateada <= CURDATE()";
            $result = $class->autocommitOffQuery($mysql, $sql2, 'Error al desactivar profesor.');

            $sql3 = "DELETE FROM Marcajes WHERE ID_PROFESOR='$profesor' AND Fecha > '$fechaFormateada'";
            $class->autocommitOffQuery($mysql, $sql3, 'Error al desactivar profesor.');
            
            $status = true;
            $reload = true;
        } catch (Exception $e) {
            $alertMessage = $e->getMessage();
            $mysql->rollback();
        }
        $mysql->commit();
    } else {
        $alertMessage = "Error en el formato de fecha.";
    }
}

$result = [
    'success' => $status,
    'msg' => $alertMessage,
    'reload' => $reload
];

echo json_encode($result);
exit;
