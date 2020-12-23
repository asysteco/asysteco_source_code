<?php

$profesor = $_GET['profesor'] ?? '';
$fechaInicio = $_GET['fechainicio'] ?? '';
$fechaFin = $_GET['fechafin'] ?? '';
$whereFilter = ' AND Fecha <= CURDATE()';
$errorMessage = '';

// Preparamos directorio tmp, borrando fichero si existe
$ff = "tmp/";
$fn = "Listado_Faltas.csv";
chdir($ff);
if(is_file($fn))
{
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
    'ACTIVIDAD EXTRAESCOLAR'
];

// Escribimos los títulos para los campos
fputcsv($fp, $titulo, $delimitador);

if(isset($profesor) && !empty($profesor)) {
    $whereFilter .= " AND ID_PROFESOR = $profesor";
}

if(isset($fechaInicio) && !empty($fechaInicio) && isset($fechaFin) && !empty($fechaFin)) {
    $fini = $class->formatEuropeanDateToSQLDate($fechaInicio);
    $ffin = $class->formatEuropeanDateToSQLDate($fechaFin);

    if ($fini && $ffin) {
        $whereFilter .= " AND Fecha >= '$fini' AND Fecha <= '$ffin'";
    }
}

if(! $response = $class->query("SELECT Marcajes.*, Nombre, Iniciales, Diasemana.Diasemana
FROM (Marcajes INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID)
    INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID
WHERE Asiste=0 $whereFilter
ORDER BY Profesores.Nombre ASC")) {
    $errorMessage = 'No existen datos para exportar...';
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
                $sql = "SELECT Marcajes.*, Nombre, Iniciales, Diasemana.Diasemana  
                FROM (Marcajes INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID) 
                INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID 
                WHERE Asiste=0 $whereFilter
                ORDER BY Marcajes.Fecha, Profesores.Nombre ASC 
                LIMIT $page_size OFFSET $offset_var";
                if (!$result = $mysql->query($sql)) {
                    throw new Exception('No existen datos para exportar...');
                }

            while ($datos = $result->fetch_assoc())
            {
                $fecha = $class->formatSQLDateToEuropeanDate($datos['Fecha']);
                $campos = [
                    utf8_decode($datos['Iniciales']),
                    utf8_decode($datos['Nombre']),
                    $fecha,
                    $datos['Hora'],
                    $datos['Dia'],
                    utf8_decode($datos['Diasemana']),
                    'NO',
                    'NO'
                ];
        
                // Escibimos una línea por cada $datos
                fputcsv($fp, $campos, $delimitador);
            }
        }
    } catch (Exception $e) {
        $errorMessage = $e;
        $class->conex->rollback();
    }
    $class->conex->commit();
    //cabeceras para descarga
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $fn . '";');
    
    ob_end_clean();
    
    echo $ff.$fn;
    exit;
}

if (!empty($errorMessage)) {
    echo "<div style='width: 100%; height: 100vh; text-align: center;'>";
    echo "<div style='box-shadow: 4px 4px 16px 16px grey; width: 50%; margin-left: auto; margin-right: auto; border-radius: 10px;'>";
        echo "<h1 style='color: red; margin-top: 40vh; vartical-align: middle; padding: 25px;'>" . $errorMessage . "</h1>";
    echo "</div>";
    echo "</div>";
    echo "<script>setTimeout(function(){window.close()}, 1500)</script>";
}