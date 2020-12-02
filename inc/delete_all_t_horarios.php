<?php

$mysql = $class->conex;
$mysql->autocommit(FALSE);

$profesor = $_GET['profesor'] ?? '';
$fechaInicio = $_GET['fechainicio'] ?? '';
$fechaFin = $_GET['fechafin'] ?? '';
$whereFilter = '';

if (isset($profesor) && !empty($profesor)) {
    $whereFilter = empty($whereFilter) ? "WHERE ID_PROFESOR = $profesor" : " AND ID_PROFESOR = $profesor";
}
if (isset($fechaInicio) && !empty($fechaInicio) && isset($fechaFin) && !empty($fechaFin)) {
    $fini = $class->formatEuropeanDateToSQLDate($fechaInicio);
    $ffin = $class->formatEuropeanDateToSQLDate($fechaFin);

    if ($fini && $ffin) {
        $whereFilter .= empty($whereFilter) ? "WHERE Fecha_incorpora >= '$fini' AND Fecha_incorpora <= '$ffin'" : " AND Fecha_incorpora >= '$fini' AND Fecha_incorpora <= '$ffin'";
    }
}
try {
    if (!$mysql->query("DELETE FROM T_horarios $whereFilter")) {
        throw new Exception('Error-temp-horarios');
    }
} catch (Exception $e) {
    echo $e;
    $class->conex->rollback();
}
$class->conex->commit();
