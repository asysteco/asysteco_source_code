<?php

// Preparamos directorio tmp, borrando fichero si existe
$ff = "tmp/";
$fn = "Listado_Profesores.csv";
chdir($ff);
if(is_file($fn))
{
    unlink($fn);
}

$fp = fopen($fn, 'w');
$delimitador = ";";
$titulo = [
    'INICIALES',
    'NOMBRE',
    'TUTOR'
];

// Escribimos los títulos para los campos
fputcsv($fp, $titulo, $delimitador);

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