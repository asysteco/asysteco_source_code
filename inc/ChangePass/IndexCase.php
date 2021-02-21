<?php

$act_usuario = 'active';
$act_changePass = 'active';

$scripts = '<link rel="stylesheet" href="css/change-pass-style.css">';
include_once($dirs['ChangePass'] . 'Valida/validate.php');
include_once($dirs['Interfaces'] . 'header.php');
include_once($dirs['Interfaces'] . 'top-nav.php');
include_once($dirs['ChangePass'] . 'form.php');
include_once($dirs['Helper'] . 'change_pass_modal.php');
include_once($dirs['Interfaces'] . 'footer.php');
