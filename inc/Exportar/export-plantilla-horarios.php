<?php

$ff = "templates/";
$fn = "Plantilla_Horarios.csv";
chdir($ff);
if(is_file($fn))
{
    unlink($fn);
}

$fp = fopen($fn, 'w');
$delimitador = ";";

if (isset($options['edificios']) && $options['edificios'] > 1) {
    $titulo = [
        'GRUPO',
        'INICIALES',
        'AULA',
        'DIA',
        'HORA',
        'EDIFICIO'
    ];

    $campos = [
        0 => [
            utf8_decode('2ESOA'),
            utf8_decode('MRG'),
            utf8_decode('AU101'),
            3,
            1,
            2,
        ],
        1 => [
            utf8_decode('3BACHB'),
            utf8_decode('AAM'),
            utf8_decode('AU214'),
            4,
            7,
            1
        ]
    ];
} else {
    $titulo = [
        'GRUPO',
        'INICIALES',
        'AULA',
        'DIA',
        'HORA'
    ];

    $campos = [
        0 => [
            utf8_decode('2ESOA'),
            utf8_decode('MRG'),
            utf8_decode('AU101'),
            3,
            1
        ],
        1 => [
            utf8_decode('3BACHB'),
            utf8_decode('AAM'),
            utf8_decode('AU214'),
            4,
            7
        ]
    ];
}

// Escribimos los títulos para los campos
fputcsv($fp, $titulo, $delimitador);

for ($i = 0; $i <= count($campos); $i++) {
    // Escibimos una línea por cada campo de $campos
    fputcsv($fp, $campos[$i], $delimitador);
}
    
//cabeceras para descarga
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $fn . '";');
            
ob_end_clean();
    
readfile($fn);