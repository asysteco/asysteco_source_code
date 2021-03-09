<?php

if ($_SESSION['Perfil'] !== 'Admin') {
  header("Location: index.php");
}

$act_usuario = 'active';
$act_notification = 'active';

include_once($dirs['Interfaces'] . 'header.php');
include_once($dirs['Interfaces'] . 'top-nav.php');
include_once($dirs['Notificaciones'] . 'interface.php');
include_once($dirs['Interfaces'] . 'footer.php');