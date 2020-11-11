<?php

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

if(isset($_GET['profesor']) && $_GET['profesor'] != '')
{
    $profesor = " ID_PROFESOR = '$_GET[profesor]'";
    $sql = "SELECT ID_PROFESOR, Nombre, F_entrada, F_Salida, DIA_SEMANA, Fecha
    FROM (Fichar INNER JOIN Profesores ON Fichar.ID_PROFESOR=Profesores.ID)
    WHERE ID_PROFESOR = '$_GET[profesor]' 
    ORDER BY Profesores.Nombre ASC";
}
else
{
    $profesor = "";
    $sql = "SELECT ID_PROFESOR, Nombre, F_entrada, F_Salida, DIA_SEMANA, Fecha
    FROM (Fichar INNER JOIN Profesores ON Fichar.ID_PROFESOR=Profesores.ID) 
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
        if(! $response = $class->query("SELECT ID_PROFESOR, Nombre, F_entrada, F_Salida, DIA_SEMANA, Fecha
        FROM (Fichar INNER JOIN Profesores ON Fichar.ID_PROFESOR=Profesores.ID) WHERE Fecha BETWEEN '$fini' AND '$ffin'"))
        {
            die($class->ERR_ASYSTECO);
        }
    }
}
else
{
    if(! $response = $class->query("SELECT ID_PROFESOR, Nombre, F_entrada, F_Salida, DIA_SEMANA, Fecha
    FROM (Fichar INNER JOIN Profesores ON Fichar.ID_PROFESOR=Profesores.ID)
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

if($respuesta = $class->query("SELECT * FROM Fichar"))
{
    if($respuesta->num_rows > 0)
    {
        for($i=0; $i<=$count; $i++)
        {
            $offset_var = $i * $page_size;
            if((isset($profesor) && $profesor !='') || (isset($fechas) && $fechas !=''))
            {
                $query = "SELECT ID_PROFESOR, Nombre, F_entrada, F_Salida, DIA_SEMANA, Fecha
                FROM (Fichar INNER JOIN Profesores ON Fichar.ID_PROFESOR=Profesores.ID)
                WHERE $profesor $and $fechas 
                ORDER BY Profesores.Nombre ASC 
                LIMIT $page_size OFFSET $offset_var"; # "select id from shipment Limit ".$page_size." OFFSET ".$offset_var;
            }
            else
            {
                $query = "SELECT ID_PROFESOR, Nombre, F_entrada, F_Salida, DIA_SEMANA, Fecha
                FROM (Fichar INNER JOIN Profesores ON Fichar.ID_PROFESOR=Profesores.ID)
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
        
                $campos = [
                    utf8_decode($datos['Nombre']),
                    "$dia/$m/$Y",
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
        
        readfile($fn);
        
        if(is_file($fn))
        {
            unlink($fn);
        }
        exit;
    }
    else
    {
        echo "<div style='width: 100%; height: 100vh; text-align: center;'>";
        echo "<div style='box-shadow: 4px 4px 16px 16px grey; width: 50%; margin-left: auto; margin-right: auto; border-radius: 10px;'>";
            echo "<h1 style='color: red; margin-top: 40vh; vartical-align: middle; padding: 25px;'>No existen datos para exportar...</h1>";
        echo "</div>";
        echo "</div>";
        echo "<script>setTimeout(function(){window.close()}, 1500)</script>";
    }
}
else
{
    echo "<div style='width: 100%; height: 100vh; text-align: center;'>";
    echo "<div style='box-shadow: 4px 4px 16px 16px grey; width: 50%; margin-left: auto; margin-right: auto; border-radius: 10px;'>";
        echo "<h1 style='color: red; margin-top: 40vh; vartical-align: middle; padding: 25px;'>Ha ocurrido un error inesperado...</h1>";
    echo "</div>";
    echo "</div>";
    echo "<script>setTimeout(function(){window.close()}, 1500)</script>";
}