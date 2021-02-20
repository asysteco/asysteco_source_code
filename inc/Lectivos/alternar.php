<?php
// variables a declarar
$action = $_POST['action'] ?? '';
$date = $_POST['date'] ?? '';

if (!empty($action) && !empty($date)) {
    $MSG = "cambio-realizado";
    $mysql = $class->conex;
    $mysql->autocommit(FALSE);
    try {
        $sql = "UPDATE Lectivos SET Festivo = '$action' WHERE Fecha = '$date'";
        $class->query($sql);
        $class->autocommitOffQuery($mysql, $sql, 'Ha ocurrido un error inesperado...');
        $class->marcajes();
    } catch (Exception $e) {
        $MSG = $e->getMessage();
        $mysql->rollback();
    }
    $mysql->commit();
} else {
    $MSG = 'datos-incorrectos';
}

echo $MSG;
exit;
