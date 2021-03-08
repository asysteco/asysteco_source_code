<?php

$mysql = $class->conex;
$mysql->autocommit(FALSE);

try {
    $class->autocommitOffQuery($mysql, 'DELETE FROM Horarios', 'Error-horarios');
} catch (Exception $e) {
    echo $e->getMessage();
    $class->conex->rollback();
}
$class->conex->commit();
