<?php

$opt = $_GET['OPT'] ?? '';

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
        header("location: index.php");
        break;
}
