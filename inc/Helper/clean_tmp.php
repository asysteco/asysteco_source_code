<?php

$action = $_GET['action'] ?? '';
$element = $_GET['element'] ?? '';

if (!empty($action) && $action === 'backup') {
    $backupFile = 'tmp/Copia_Seguridad_' . $options['Centro'] . '.zip';
    if(is_file($backupFile))
    {
        echo 'deleted';
        unlink($backupFile);
    }
} elseif (!empty($action) && !empty($element) && $action === 'export') {
    $exportFile = 'tmp/Listado_' . $element . '.csv';
    if(is_file($exportFile))
    {
        echo 'deleted';
        unlink($exportFile);
    }
}

$files = glob('tmp/*.*');
foreach($files as $file)
{
    if(is_file($file))
    {
        unlink($file);
    }
}