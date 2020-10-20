<?php

// Preparamos directorio tmp, borrando fichero si existe
$ff = "tmp/";
$fn = "Listado_Asistencias.csv";
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

if(isset($_GET['profesor']) && $_GET['profesor'] != '')
{
    $profesor = " AND ID_PROFESOR = '$_GET[profesor]'";
    $sql = "SELECT Marcajes.*, Nombre, Iniciales, Diasemana.Diasemana
    FROM (Marcajes INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID)
        INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID 
    WHERE Asiste=1 OR Asiste=2 AND ID_PROFESOR = '$_GET[profesor]'
    ORDER BY Profesores.Nombre ASC";
}
else
{
    $profesor = "";
    $sql = "SELECT Marcajes.*, Nombre, Iniciales, Diasemana.Diasemana  
    FROM (Marcajes INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID) 
    INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID 
    WHERE Asiste=1 OR Asiste=2 
    ORDER BY Profesores.Nombre ASC";
}

if(isset($_GET['fechainicio']) && isset($_GET['fechafin']))
{
    $fi = preg_split('/\//', $_GET['fechainicio']);
            $dia = $fi[0];
            $m = $fi[1];
            $Y = $fi[2];
    $fini = $Y .'-'. $m .'-'. $dia;
    $ff = preg_split('/\//', $_GET['fechafin']);
            $dia = $ff[0];
            $m = $ff[1];
            $Y = $ff[2];
    $ffin = $Y .'-'. $m .'-'. $dia;
    if($class->validFormSQLDate($fini) && $class->validFormSQLDate($ffin))
    {
        if(! $response = $class->query("SELECT ID_PROFESOR FROM Marcajes INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID WHERE (Asiste=1 OR Asiste=2) AND Fecha BETWEEN '$fini' AND '$ffin'"))
        {
            die($class->ERR_ASYSTECO);
        }
    }
}
else
{
    if(! $response = $class->query("SELECT Marcajes.*, Nombre, Iniciales, Diasemana.Diasemana
    FROM (Marcajes INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID)
        INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID 
    WHERE Asiste=1 OR Asiste=2 
    ORDER BY Profesores.Nombre ASC"))
    {
        die($class->ERR_ASYSTECO);
    }
}

if(isset($_GET['fechainicio']) && isset($_GET['fechafin']) && $_GET['fechainicio'] !='' && $_GET['fechafin'] !='')
{
    if(isset($_GET['profesor']) && $_GET['profesor'] != '')
    {
        $and= "AND";
    }
    else
    {
        $and = "";
    }
    $fechas="Fecha BETWEEN '$fini' AND '$ffin'";
}
else
{
    $fechas="";
}

$page_size = 15000;
$total_records = $response->num_rows;
$count=ceil($total_records/$page_size);

for($i=0; $i<=$count; $i++)
{
    $offset_var = $i * $page_size;
    if(isset($profesor) || isset($fechas))
    {
        $query = "SELECT Marcajes.*, Nombre, Iniciales, Diasemana.Diasemana  
        FROM (Marcajes INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID) 
        INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID 
        WHERE (Asiste=1 OR Asiste=2) $profesor $and $fechas 
        ORDER BY Profesores.Nombre ASC 
        LIMIT $page_size OFFSET $offset_var"; # "select id from shipment Limit ".$page_size." OFFSET ".$offset_var;
    }
    else
    {
        $query = "SELECT Marcajes.*, Nombre, Iniciales, Diasemana.Diasemana  
        FROM (Marcajes INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID) 
        INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID 
        WHERE Asiste=1 OR Asiste=2 
        ORDER BY Profesores.Nombre ASC 
        LIMIT $page_size OFFSET $offset_var"; # "select id from shipment Limit ".$page_size." OFFSET ".$offset_var;
    }
    $result =  $class->query($query);

    while ($datos = $result->fetch_assoc())
    {
        $sep = preg_split('/[ -]/', $datos['Fecha']);
        $dia = $sep[2];
        $m = $sep[1];
        $Y = $sep[0];

        if($datos['Asiste'] == 2)
        {
            $extra = "SI";
        }
        else
        {
            $extra = '';
        }

        $campos = [
            utf8_decode($datos['Iniciales']),
            utf8_decode($datos['Nombre']),
            "$dia/$m/$Y",
            $datos['Hora'],
            $datos['Dia'],
            utf8_decode($datos['Diasemana']),
            'SI',
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

readfile($fn);

if(is_file($fn))
{
    unlink($fn);
}
exit;