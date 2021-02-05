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
        
        //cabeceras para descarga
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $fn . '";');
        
        ob_end_clean();
        
        echo $ff.$fn;
        exit;
    }

    echo 'No-data';
    exit;
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