<?php

$profesor = $_GET['profesor'] ?? '';
$fechaInicio = $_GET['fechainicio'] ?? '';
$fechaFin = $_GET['fechafin'] ?? '';
$whereFilter = ' AND Fecha <= CURDATE()';
$errorMessage = '';

// Preparamos directorio tmp, borrando fichero si existe
$ff = "tmp/";
$fn = "Listado_Faltas_Injustificadas.csv";
chdir($ff);
if (is_file($fn)) {
    unlink($fn);
}

$fp = fopen($fn, 'w');
$delimitador = ";";
$titulo = [
    'INICIALES',
    'PROFESOR',
    'FECHA',
    'HORA',
    'DIA',
    'DIA SEMANA',
    'ASISTENCIA',
    'ACTIVIDAD EXTRAESCOLAR',
    'JUSTIFICADA'
];

// Escribimos los títulos para los campos
fputcsv($fp, $titulo, $delimitador);

if (isset($profesor) && !empty($profesor)) {
    $whereFilter .= " AND ID_PROFESOR = $profesor";
}

if (isset($fechaInicio) && !empty($fechaInicio) && isset($fechaFin) && !empty($fechaFin)) {
    $fini = $class->formatEuropeanDateToSQLDate($fechaInicio);
    $ffin = $class->formatEuropeanDateToSQLDate($fechaFin);

    if ($fini && $ffin) {
        $whereFilter .= " AND Fecha >= '$fini' AND Fecha <= '$ffin'";
    }
}

$sql = "SELECT M.*, P.Nombre, P.Iniciales, D.Diasemana
FROM (Marcajes M INNER JOIN Profesores P ON M.ID_PROFESOR=P.ID)
    INNER JOIN Diasemana D ON M.Dia=D.ID
WHERE M.Asiste = 0
    AND M.Justificada = 0 $whereFilter
ORDER BY M.Fecha DESC, P.Nombre ASC, M.Hora ASC";

if (!$response = $class->query($sql)) {
    $errorMessage = 'No existen datos para exportar...';
}

$page_size = 15000;
$total_records = $response->num_rows;
$count = ceil($total_records / $page_size);

$mysql = $class->conex;
$mysql->autocommit(FALSE);

if (empty($errorMessage) && $response->num_rows > 0) {
    try {
        for ($i = 0; $i <= $count; $i++) {
            $offset_var = $i * $page_size;
            
            $sql = "SELECT M.*, P.Nombre, P.Iniciales, D.Diasemana
                FROM (Marcajes M INNER JOIN Profesores P ON M.ID_PROFESOR=P.ID)
                    INNER JOIN Diasemana D ON M.Dia=D.ID
                WHERE M.Asiste = 0
                    AND M.Justificada = 0 $whereFilter
                ORDER BY M.Fecha DESC, P.Nombre ASC, M.Hora ASC 
                LIMIT $page_size OFFSET $offset_var";

            $result = $class->autocommitOffQuery($mysql, $sql, 'No existen datos para exportar...');

            while ($datos = $result->fetch_assoc()) {
                $fecha = $class->formatSQLDateToEuropeanDate($datos['Fecha']);
                $campos = [
                    utf8_decode($datos['Iniciales']),
                    utf8_decode($datos['Nombre']),
                    $fecha,
                    $datos['Hora'],
                    $datos['Dia'],
                    utf8_decode($datos['Diasemana']),
                    'NO',
                    'NO',
                    'NO'
                ];

                // Escibimos una línea por cada $datos
                fputcsv($fp, $campos, $delimitador);
            }
        }
        //cabeceras para descarga
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $fn . '";');

        ob_end_clean();

        echo $ff . $fn;
        exit;
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
        $class->conex->rollback();
    }
    $class->conex->commit();
}

echo 'No-data';
exit;
