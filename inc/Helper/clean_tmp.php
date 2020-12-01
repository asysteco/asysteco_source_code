<?php

$action = $_GET['action'] ?? '';

if (!empty($action) && $action === 'backup') {
    $backupFile = 'tmp/Copia_Seguridad_' . $options['Centro'] . '.zip';
    if(is_file($backupFile))
    {
        echo 'deleted';
        unlink($backupFile);
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