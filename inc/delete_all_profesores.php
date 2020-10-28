<?php

$mysql = $class->conex;
$mysql->autocommit(FALSE);

try {
    if (!$mysql->query('DELETE FROM Fichar')) {
        throw new Exception('Error-fichar');
    }
    if (!$mysql->query('DELETE FROM Profesores WHERE TIPO != 1')) {
        throw new Exception('Error-profesores');
    }
} catch (Exception $e) {
    echo $e;
    $class->conex->rollback();
}
$class->conex->commit();
