<?php

$perfil = $_SESSION['Perfil'] ?? '';

$act_usuario = $perfil === 'Admin' ? 'active' : '';
$act_qr = 'active';

include_once($dirs['Interfaces'] . 'header.php');
include_once($dirs['Interfaces'] . 'top-nav.php');
include_once($dirs['Qr'] . 'generate_code.php');
include_once($dirs['Interfaces'] . 'footer.php');
