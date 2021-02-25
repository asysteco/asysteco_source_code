<?php

$actPass = $_POST['act_pass'];
$newPass = $_POST['new_pass'];
$newPassConfirm = $_POST['new_pass_confirm'];

if (!empty($actPass) && !empty($newPass) && !empty($newPassConfirm)) {
    include_once($dirs['ChangePass'] . 'Ajax/validate.php');
    exit;
}

$act_usuario = 'active';
$act_changePass = 'active';

$scripts = '<link rel="stylesheet" href="css/change-pass-style.css">';
include_once($dirs['Interfaces'] . 'header.php');
include_once($dirs['Interfaces'] . 'top-nav.php');
include_once($dirs['ChangePass'] . 'form.php');
include_once($dirs['Interfaces'] . 'footer.php');
