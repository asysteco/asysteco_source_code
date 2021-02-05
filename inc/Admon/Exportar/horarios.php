<?php

$profesor = $_GET['profesor'] ?? '';
$whereFilter = '';
$errorMessage = '';
$edificios = $options['edificios'];

if (isset($profesor) && !empty($profesor)) {
    $whereFilter = "WHERE ID_PROFESOR = $profesor";
}

$sql = "SELECT Horarios.*, Profesores.Nombre, Profesores.Iniciales, Diasemana.Diasemana, Aulas.Nombre as Aula, Cursos.Nombre as Grupo FROM
Horarios INNER JOIN Profesores ON Horarios.ID_PROFESOR=Profesores.ID
INNER JOIN Diasemana ON Diasemana.ID=Horarios.Dia 
INNER JOIN Aulas ON Aulas.ID=Horarios.Aula 
INNER JOIN Cursos ON Cursos.ID=Horarios.Grupo
$whereFilter
ORDER BY ID_PROFESOR, Dia, Hora";

if($response = $class->query($sql))
{
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
        if (isset($edificios) && $edificios > 1) {
            $titulo = [
                'GRUPO',
                'INICIALES',
                'AULA',
                'DIA',
                'HORA',
                'EDIFICIO'
            ];
        } else {
            $titulo = [
                'GRUPO',
                'INICIALES',
                'AULA',
                'DIA',
                'HORA'
            ];
        }
        // Escribimos los títulos para los campos
        fputcsv($fp, $titulo, $delimitador);
        while($datos = $response->fetch_assoc()) 
        {
            if (isset($edificios) && $edificios > 1) {
                $campos = [
                    utf8_decode($datos['Grupo']),
                    utf8_decode($datos['Iniciales']),
                    utf8_decode($datos['Aula']),
                    $datos['Dia'],
                    $datos['Hora'],
                    $datos['Edificio'],
                ];
            } else {
                
            $campos = [
                utf8_decode($datos['Grupo']),
                utf8_decode($datos['Iniciales']),
                utf8_decode($datos['Aula']),
                $datos['Dia'],
                $datos['Hora'],
            ];
            }
            
            // Escibimos una línea por cada $datos
            fputcsv($fp, $campos, $delimitador);
        }
            
        //cabeceras para descarga
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $fn . '";');
                    
        ob_end_clean();
            
        echo $ff.$fn;
    }

    echo 'No-data';
    exit;
}
