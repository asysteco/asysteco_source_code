<?php

$profesor = $_GET['profesor'] ?? '';
$fechaInicio = $_GET['fechainicio'] ?? '';
$fechaFin = $_GET['fechafin'] ?? '';
$whereFilter = ' AND Fecha <= CURDATE()';
$errorMessage = '';

// Preparamos directorio tmp, borrando fichero si existe
$ff = "tmp/";
$fn = "Listado_marcajes.csv";
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

if(! $response = $class->query("SELECT Marcajes.*, Nombre, Iniciales, Diasemana.Diasemana
FROM (Marcajes INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID)
    INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID
WHERE Profesores.Activo=1
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

            $sql = "SELECT Marcajes.*, Nombre, Iniciales, Diasemana.Diasemana
            FROM (Marcajes INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID)
                INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID
            WHERE Profesores.Activo=1 $whereFilter
            ORDER BY Marcajes.Fecha, Profesores.Nombre ASC 
            LIMIT $page_size OFFSET $offset_var";
            $result = $class->autocommitOffQuery($mysql, $sql, 'Ha ocurrido un error inesperado...');

            while ($datos = $result->fetch_assoc())
            {
                $sep = preg_split('/[ -]/', $datos['Fecha']);
                $dia = $sep[2];
                $m = $sep[1];
                $Y = $sep[0];
        
                if($datos['Asiste'] == 0)
                {
                    $asist = "NO";
                    $extra = "NO";
                }
                elseif($datos['Asiste'] == 1)
                {
                    $asist = "SI";
                    $extra = "NO";
                }
                elseif($datos['Asiste'] == 2)
                {
                    $asist = "SI";
                    $extra = "SI";
                }
        
                $campos = [
                    utf8_decode($datos['Iniciales']),
                    utf8_decode($datos['Nombre']),
                    "$dia/$m/$Y",
                    $datos['Hora'],
                    $datos['Dia'],
                    utf8_decode($datos['Diasemana']),
                    $asist,
                    $extra
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
        $mysql->rollback();
    }
    $mysql->commit();
}
