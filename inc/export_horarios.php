<?php

if(isset($_GET['profesor']) && $_GET['profesor'] != '')
{
    $sql = "SELECT Horarios.*, Profesores.Nombre, Profesores.Iniciales, Diasemana.Diasemana FROM
    (Horarios INNER JOIN Profesores ON Horarios.ID_PROFESOR=Profesores.ID) INNER JOIN Diasemana ON Diasemana.ID=Horarios.Dia
    WHERE ID_PROFESOR = '$_GET[profesor]'
    ORDER BY ID_PROFESOR, Dia, HORA_TIPO";
}
else
{
    $sql = "SELECT Horarios.*, Profesores.Nombre, Profesores.Iniciales
    FROM Horarios INNER JOIN Profesores ON Horarios.ID_PROFESOR=Profesores.ID
    ORDER BY ID_PROFESOR, Dia, HORA_TIPO";
}
if($response = $class->query($sql))
{
    //echo "<h2>Registros de Horarios</h2>"; 
    if ($response->num_rows > 0) 
    {
        $ff = "tmp/";
        $fn = "Listado_Horarios.csv";
        chdir($ff);
        if(is_file($fn))
        {
            unlink($fn);
        }
        $fp = fopen($fn, 'w');
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
            
        //cabeceras para descarga
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $fn . '";');
                    
        ob_end_clean();
            
        readfile($fn);
            
        if(is_file($fn))
        {
            unlink($fn);
        }
    }
    exit;
}