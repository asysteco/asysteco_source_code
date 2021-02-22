<?php

$newPass = $_POST['new_pass'];
$newPassConfirm = $_POST['new_pass_confirm'];

if (!empty($newPass) && !empty($newPassConfirm)) {
    include_once($dirs['FirstChangePass'] . 'Ajax/validate.php');
    exit;
}

$scripts = '<link rel="stylesheet" href="css/login-style.css">';
include_once($dirs['Interfaces'] . 'header.php');
include_once($dirs['Interfaces'] . 'top-nav.php');
include_once($dirs['FirstChangePass'] . 'form.php');
include_once($dirs['Interfaces'] . 'footer.php');
