<?php

$fp = $dirs['public'] . 'templates/';
$fn = "Plantilla_Horarios.csv";

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $fn . '";');
            
ob_end_clean();

readfile($fp . $fn);