<?php

$mysql = $class->conex;
$mysql->autocommit(FALSE);

try {
    $class->autocommitOffQuery($mysql, 'DELETE FROM Fichar', 'Error-fichar');
    $class->autocommitOffQuery($mysql, 'DELETE FROM Profesores WHERE TIPO != 1', 'Error-profesores');
} catch (Exception $e) {
    echo $e->getMessage();
    $class->conex->rollback();
}
$class->conex->commit();
