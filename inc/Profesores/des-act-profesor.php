<?php

$profesor = $_GET['ID'];
$action = $_POST['action'];
$fecha = $_POST['fecha'] ?? '';

if ($action === 'activar') {
    $MSG = "activado";
    $sql = "UPDATE Profesores SET Activo=1 WHERE ID='$profesor' AND Tipo != 1";
    if (!$class->query($sql)) {
        $MSG = "error-activar";
    }
    $class->marcajes($profesor, 'remove');
    $class->marcajes($profesor, 'add');
} elseif ($action === 'desactivar') {
    $mysql = $class->conex;
    $mysql->autocommit(FALSE);
    $MSG = "desact";
    if ($class->validFormDate($fecha)) {
        $fechaFormateada = $class->formatEuropeanDateToSQLDate($fecha);
        try {
            $sql2 = "UPDATE Profesores SET Activo=0 WHERE ID='$profesor' AND Tipo != 1 AND $fechaFormateada <= CURDATE()";
            $result = $class->autocommitOffQuery($mysql, $sql2, 'error-desactivar');

            $sql3 = "DELETE FROM Marcajes WHERE ID_PROFESOR='$profesor' AND Fecha > '$fechaFormateada'";
            $class->autocommitOffQuery($mysql, $sql3, 'error-desactivar');
        } catch (Exception $e) {
            $MSG = $e->getMessage();
            $mysql->rollback();
        }
        $mysql->commit();
    } else {
        $MSG = "error-fecha";
    }
} else {
    $MSG = "error-inesperado";
}

echo $MSG;