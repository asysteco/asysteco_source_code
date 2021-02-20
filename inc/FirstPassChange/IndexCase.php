<?php

if ($class->isLogged($Titulo)) {
    $scripts = '<link rel="stylesheet" href="css/login-style.css">';
    include_once($dirs['Valida'] . 'valida_primer_cambio.php');
    include_once($dirs['Interfaces'] . 'header.php');
    include_once($dirs['Interfaces'] . 'top-nav.php');
    include_once($dirs['Login'] . 'primer_cambio.php');
    include_once($dirs['Interfaces'] . 'change_pass_modal.php');
    include_once($dirs['Interfaces'] . 'footer.php');
} else {
    $MSG = "Debes iniciar sesión para realizar esta acción.";
    header("Refresh:2; url=index.php");
    include_once($dirs['Interfaces'] . 'msg_modal.php');
}