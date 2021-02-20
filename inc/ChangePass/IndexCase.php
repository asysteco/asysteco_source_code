<?php

if ($class->isLogged($Titulo)) {
    if ($class->compruebaCambioPass()) {
        $act_usuario = 'active';
        $act_changePass = 'active';

        $scripts = '<link rel="stylesheet" href="css/change-pass-style.css">';
        include_once($dirs['Valida'] . 'valida_new_pass.php');
        include_once($dirs['Interfaces'] . 'header.php');
        include_once($dirs['Interfaces'] . 'top-nav.php');
        include_once($dirs['Login'] . 'new_pass.php');
        include_once($dirs['Interfaces'] . 'change_pass_modal.php');
        include_once($dirs['Interfaces'] . 'footer.php');
    } else {
        header('Location: index.php?ACTION=primer_cambio');
    }
} else {
    $MSG = "Debes iniciar sesión para realizar esta acción.";
    header("Refresh:2; url=index.php");
    include_once($dirs['Interfaces'] . 'msg_modal.php');
}