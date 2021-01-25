<?php

$profesor = $_GET['profesor'] ?? '';
$fechaInicio = $_GET['fechainicio'] ?? '';
$fechaFin = $_GET['fechafin'] ?? '';
$whereFilter = ' WHERE Fecha <= CURDATE()';
$errorMessage = '';

// Preparamos directorio tmp, borrando fichero si existe
$ff = "tmp/";
$fn = "Listado_Fichajes.csv";
chdir($ff);
if(is_file($fn))
{
    unlink($fn);
}

$fp = fopen($fn, 'w');
$delimitador = ";";
$titulo = [
    'PROFESOR',
    'FECHA',
    'HORA FICHAJE',
    'HORA SALIDA',
    'DIA SEMANA'
];

// Escribimos los títulos para los campos
fputcsv($fp, $titulo, $delimitador);

if (isset($profesor) && !empty($profesor)) {
    $whereFilter .= " AND ID_PROFESOR = $profesor";
}

if(isset($fechaInicio) && !empty($fechaInicio) && isset($fechaFin) && !empty($fechaFin)) {
    $fini = $class->formatEuropeanDateToSQLDate($fechaInicio);
    $ffin = $class->formatEuropeanDateToSQLDate($fechaFin);

    if($fini && $ffin) {
        $whereFilter .= " AND Fecha >= '$fini' AND Fecha <= '$ffin'";
    }
}

if (! $response = $class->query("SELECT ID_PROFESOR, Nombre, F_entrada, F_Salida, DIA_SEMANA, Fecha
FROM (Fichar INNER JOIN Profesores ON Fichar.ID_PROFESOR=Profesores.ID) $whereFilter
ORDER BY Profesores.Nombre ASC")) {
    $errorMessage = 'Ha ocurrido un error inesperado...';
}

$page_size = 15000;
$total_records = $response->num_rows;
$count=ceil($total_records/$page_size);

$mysql = $class->conex;
$mysql->autocommit(FALSE);

if(empty($errorMessage) && $response->num_rows > 0) {
    try {
        for($i=0; $i<=$count; $i++)
        {
            $offset_var = $i * $page_size;

            $sql = "SELECT ID_PROFESOR, Nombre, F_entrada, F_Salida, DIA_SEMANA, Fecha
            FROM (Fichar INNER JOIN Profesores ON Fichar.ID_PROFESOR=Profesores.ID) $whereFilter 
            ORDER BY Profesores.Nombre ASC 
            LIMIT $page_size OFFSET $offset_var";
            $result = $class->autocommitOffQuery($mysql, $sql, 'No existen datos para exportar...');

            while ($datos = $result->fetch_assoc())
            {
                $fecha = $class->formatSQLDateToEuropeanDate($datos['Fecha']);
                $campos = [
                    utf8_decode($datos['Nombre']),
                    $fecha,
                    $datos['F_entrada'],
                    $datos['F_Salida'],
                    utf8_decode($datos['DIA_SEMANA'])
                ];

                // Escibimos una línea por cada $datos
                fputcsv($fp, $campos, $delimitador);
            }
        }

        //cabeceras para descarga
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $fn . '";');
        
        ob_end_clean();
        
        echo $ff.$fn;
        exit;
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
        $class->conex->rollback();
    }
    $class->conex->commit();
}