<?php

$ff = "templates/";
$fn = "Guia_Administracion.pdf";

chdir($ff);

//cabeceras para descarga
header('Content-Type: application/pdf;');
header('Content-Disposition: attachment; filename="' . $fn . '";');
            
ob_end_clean();
    
readfile($fn);