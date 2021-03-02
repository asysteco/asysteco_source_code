<?php

if (!isset($_GET['OPT'])) {
    $_GET['OPT'] = '';
}

switch ($_GET['OPT']) {
    case 'plantilla-horarios':
        if ($_SESSION['Perfil'] === 'Admin') {
        require_once($dirs['Downloads'] . 'plantilla-horarios.php');
        }
    break;

    case 'plantilla-profesores':
        if ($_SESSION['Perfil'] === 'Admin') {
        require_once($dirs['Downloads'] . 'plantilla-profesores.php');
        }
    break;

    case 'admin-guide':
        if ($_SESSION['Perfil'] === 'Admin') {
        require_once($dirs['Downloads'] . 'guide-admin.php');
        }
    break;

    case 'profesor-guide':
        require_once($dirs['Downloads'] . 'guide-profesor.php');
    break;

    default:    
        $MSG = "Acción errónea.";
        header("Refresh:2; url=index.php");
        include_once($dirs['Interfaces'] . 'msg_modal.php');
    break;
}
