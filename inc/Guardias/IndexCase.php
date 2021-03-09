<?php

$act_guardias = 'active';
$scripts = '<link rel="stylesheet" href="css/qr-reader.css">';

if (!$options['QR-reader']) {
  include_once($dirs['Guardias'] . 'Headers/webcam.php');
}

include_once($dirs['Interfaces'] . 'header.php');
include_once($dirs['Guardias'] . 'interface.php');
include_once($dirs['Interfaces'] . 'footer.php');