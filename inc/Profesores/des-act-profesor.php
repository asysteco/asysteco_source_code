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
} elseif ($action === 'desactivar') {
    $mysql = $class->conex;
    $mysql->autocommit(FALSE);
    $MSG = "desact";
    if ($mysql->validFormDate($fecha)) {
        $fechaFormateada = $class->formatEuropeanDateToSQLDate($fecha);
        try {
            $sql2 = "UPDATE Profesores SET Activo=0 WHERE ID='$profesor' AND Tipo != 1 AND $fechaFormateada <= CURDATE()";
            if (!$result = $mysql->query($sql2)) {
                throw new Exception('error-desactivar');
            }
            $sql3 = "DELETE FROM Marcajes WHERE ID_PROFESOR='$profesor' AND Fecha > '$fechaFormateada'";
            if(!$mysql->query($sql3)) {
                throw new Exception('error-desactivar');
            }
        } catch (Exception $e) {
            $MSG = $e;
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