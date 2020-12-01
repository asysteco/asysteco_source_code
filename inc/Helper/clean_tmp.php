<?php
$files = glob('tmp/*.*');
foreach($files as $file)
{
    if(is_file($file))
    {
        unlink($file);
    }
}