<?php

if (isset($_POST['Iniciales']) || isset($_POST['pass'])) {
    require_once($dirs['Login'] . 'login_valida.php');
}

if ($class->isLogged($Titulo)) {
    if ($class->compruebaCambioPass()) {
        $perfil = $_SESSION['Perfil'];
        if ($perfil === 'Admin') {
            $act_home = 'active';
            $scripts = '<link rel="stylesheet" href="css/profesores.css">';
            include_once($dirs['Interfaces'] . 'header.php');
            include_once($dirs['Interfaces'] . 'top-nav.php');
            include_once($dirs['Profesores'] . 'profesores.php');
            include_once($dirs['Interfaces'] . 'footer.php');
        } elseif ($perfil === 'Profesor' || $perfil === 'Personal') {
            $act_qr = 'active';
            include_once($dirs['Interfaces'] . 'header.php');
            include_once($dirs['Interfaces'] . 'top-nav.php');
            include_once($dirs['Qr'] . 'generate_code.php');
            include_once($dirs['Interfaces'] . 'footer.php');
        } else {
            die('<h1 style="color:red;">Error de proceso...</h1>');
        }
    } else {
        header('Location: index.php?ACTION=primer_cambio');
    }
} else {
    include_once($dirs['Login'] . 'login_form.php');
}
