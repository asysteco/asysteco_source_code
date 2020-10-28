<?php

$mysql = $class->conex;
$mysql->autocommit(FALSE);

try {
    if (!$mysql->query('DELETE FROM Horarios')) {
        throw new Exception('Error-horarios');
    }
    if (!$mysql->query('DELETE FROM T_horarios')) {
        throw new Exception('Error-temp-horarios');
    }
} catch (Exception $e) {
    echo $e;
    $class->conex->rollback();
}
$class->conex->commit();
