<?php

// PROFESORES
/*$ff = "tmp/";*/
$pf = "Profesores.csv";
/*chdir($ff);
if(is_file($pf))
{
    unlink($pf);
}*/

$fp = fopen($pf, 'w');
$delimitador = ";";
$titulo = [
    'INICIALES',
    'NOMBRE',
    'TUTOR'
];

// Escribimos los títulos para los campos
fputcsv($fp, $titulo, $delimitador);
if($respuesta = $class->query("SELECT * FROM Profesores"))
{
    if($respuesta->num_rows > 0)
    {
        $query = "SELECT Iniciales, Nombre, Tutor FROM Profesores WHERE TIPO <> 1 ORDER BY Profesores.Nombre ASC";
        $result =  $class->query($query);
        
        while ($datos = $result->fetch_assoc())
        {
            $campos = [
                utf8_decode($datos['Iniciales']),
                utf8_decode($datos['Nombre']),
                utf8_decode($datos['Tutor'])
            ];
        
            // Escibimos una línea por cada $datos
            fputcsv($fp, $campos, $delimitador);
        }
    }else{
        $ERR_MSG = $class->ERR_ASYSTECO;
    }
}

// HORARIOS
if(isset($_GET['profesor']) && $_GET['profesor'] != '')
{
    $sql = "SELECT Horarios.*, Profesores.Nombre, Profesores.Iniciales, Diasemana.Diasemana FROM
    (Horarios INNER JOIN Profesores ON Horarios.ID_PROFESOR=Profesores.ID) INNER JOIN Diasemana ON Diasemana.ID=Horarios.Dia
    WHERE ID_PROFESOR = '$_GET[profesor]'
    ORDER BY ID_PROFESOR, Dia, Hora";
}
else
{
    $sql = "SELECT Horarios.*, Profesores.Nombre, Profesores.Iniciales
    FROM Horarios INNER JOIN Profesores ON Horarios.ID_PROFESOR=Profesores.ID
    ORDER BY ID_PROFESOR, Dia, Hora";
}
if($response = $class->query($sql))
{
    if ($response->num_rows > 0) 
    {
        /*$ff = "tmp/";*/
        $ho = "Horarios.csv";
        /*chdir($ff);
        if(is_file($ho))
        {
            unlink($ho);
        }*/
        $fp = fopen($ho, 'w');
        $delimitador = ";";
        $titulo = [
            'GRUPO',
            'INICIALES',
            'AULA',
            'DIA',
            'HORA',
        ];
        // Escribimos los títulos para los campos
        fputcsv($fp, $titulo, $delimitador);
        while($datos = $response->fetch_assoc()) 
        {
            if($nombre = $class->query("SELECT Nombre, Iniciales FROM Profesores WHERE ID='$datos[ID_PROFESOR]'"))
            {
                $prof = $nombre->fetch_assoc();
                $profesor = $prof['Nombre'];
                $iniciales = $prof['Iniciales'];
            }
            else
            {
                $ERR_MSG = $class->ERR_ASYSTECO;
            }
            $campos = [
                utf8_decode($datos['Grupo']),
                utf8_decode($iniciales),
                utf8_decode($datos['Aula']),
                $datos['Dia'],
                $datos['Hora'],
            ];
            
            // Escibimos una línea por cada $datos
            fputcsv($fp, $campos, $delimitador);
        }

        

    }else{
        $ERR_MSG = $class->ERR_ASYSTECO;
    }
}else{
    $ERR_MSG = $class->ERR_ASYSTECO;
}


// MARCAJES
/*$ff = "tmp/";*/
$ma = "Marcajes.csv";
/*chdir($ff);
if(is_file($ma))
{
    unlink($ma);
}*/

$fp = fopen($ma, 'w');
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
    WHERE Profesores.Activo=1 AND ID_PROFESOR = '$_GET[profesor]' 
    ORDER BY Profesores.Nombre ASC";
}
else
{
    $profesor = "";
    $sql = "SELECT Marcajes.*, Nombre, Iniciales, Diasemana.Diasemana
    FROM (Marcajes INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID)
        INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID
    WHERE Profesores.Activo=1 AND Marcajes.Fecha<=NOW()
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
        if(! $response = $class->query("SELECT ID_PROFESOR FROM Marcajes INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID WHERE Profesores.Activo=1 AND Fecha BETWEEN '$fini' AND '$ffin'"))
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
    WHERE Profesores.Activo=1
    ORDER BY Profesores.Nombre ASC"))
    {
        die($class->ERR_ASYSTECO);
    }
}

if(isset($_GET['fechainicio']) && isset($_GET['fechafin']) && $_GET['fechainicio'] !='' && $_GET['fechafin'] !='')
{
    $fechas=" AND Fecha BETWEEN '$fini' AND '$ffin'";
}
else
{
    $fechas="";
}

$page_size = 15000;
$total_records = $response->num_rows;
$count=ceil($total_records/$page_size);

if($respuesta = $class->query("SELECT * FROM Marcajes"))
{
    if($respuesta->num_rows > 0)
    {
        for($i=0; $i<=$count; $i++) 
        {
            $offset_var = $i * $page_size;
        
            if(isset($profesor) && $profesor !='' || (isset($fechas) && $fechas !=''))
            {
                $query = "SELECT Marcajes.*, Nombre, Iniciales, Diasemana.Diasemana
                        FROM (Marcajes INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID)
                            INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID
                        WHERE Profesores.Activo=1 $profesor $fechas AND Marcajes.Fecha<=NOW()
                        ORDER BY Marcajes.Fecha, Profesores.Nombre ASC 
                        LIMIT $page_size OFFSET $offset_var"; # "select id from shipment Limit ".$page_size." OFFSET ".$offset_var;
            }
            else
            {
                $query = "SELECT Marcajes.*, Nombre, Iniciales, Diasemana.Diasemana
                        FROM (Marcajes INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID)
                            INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID
                        WHERE Profesores.Activo=1 AND Marcajes.Fecha<=NOW()
                        ORDER BY Marcajes.Fecha, Profesores.Nombre ASC 
                        LIMIT $page_size OFFSET $offset_var"; # "select id from shipment Limit ".$page_size." OFFSET ".$offset_var;
            }
            $result =  $class->query($query);
        
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
    }else{
        $ERR_MSG = $class->ERR_ASYSTECO;
    }
}else{
    $ERR_MSG = $class->ERR_ASYSTECO;
}

// FICHAJE
/*$ff = "tmp/";*/
$fi = "Fichajes.csv";
chdir($ff);
/*if(is_file($fi))
{
    unlink($fi);
}*/

$fp = fopen($fi, 'w');
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
    }else{
        $ERR_MSG = $class->ERR_ASYSTECO;
    }

}else{
    $ERR_MSG = $class->ERR_ASYSTECO;
}


$zip =  new ZipArchive();

$filename = 'Copia_Seguridad.zip';
$archivo1 = $pf;
$archivo2 = $ho;
$archivo3 = $ma;
$archivo4 = $fi;

if($zip->open($filename, ZIPARCHIVE::CREATE)===true) {

    $zip->addFile($archivo1);
    $zip->addFile($archivo2);
    $zip->addFile($archivo3);
    $zip->addFile($archivo4);
    $zip->close();
    header("location: $filename");
}else{
    echo "Error creando archivo ". $filename;
}

       