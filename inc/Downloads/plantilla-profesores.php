<?php

// Preparamos directorio tmp, borrando fichero si existe
$ff = "templates/";
$fn = "Plantilla_Profesores.csv";
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

    $campos = [
        0 => [
            utf8_decode('BBM'),
            utf8_decode('Blanca Briales Merino'),
            utf8_decode('No')
        ],
        1 => [
            utf8_decode('BMM'),
            utf8_decode('Begoña Martínez Martínez'),
            utf8_decode('2ESOB')
        ],
        2 => [
            utf8_decode('CRG'),
            utf8_decode('Concepción Rubio Gómez'),
            utf8_decode('No')
        ],
        3 => [
            utf8_decode('IEC'),
            utf8_decode('Inmaculada Enriquez Castaño'),
            utf8_decode('No')
        ]
    ];

$countCampos = count($campos);
    
for ($i = 0; $i < $countCampos; $i++) {
    // Escibimos una línea por cada campo de $campos
    fputcsv($fp, $campos[$i], $delimitador);
}

//cabeceras para descarga
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $fn . '";');

ob_end_clean();

readfile($fn);
