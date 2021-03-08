<?php

if ($_SESSION['Perfil'] !== 'Admin') {
    header("Location: index.php");
}

$act_profesores = 'active';
$opt = $_GET['OPT'] ?? '';

switch ($opt) {
    case 'import-form':
        $act_importProf = 'active';

        $scripts = '<link rel="stylesheet" href="css/import-csv.css">';
        include_once($dirs['Interfaces'] . 'header.php');
        include_once($dirs['Interfaces'] . 'top-nav.php');
        include_once($dirs['Profesores'] . 'Import/form.php');
        break;

    case 'preview':
        include_once($dirs['Profesores'] . 'Import/Ajax/preview.php');
        break;

    case 'import-csv':
        require_once($dirs['Profesores'] . 'Import/Ajax/import.php');
        break;

    case 'edit':
        $scripts = '<link rel="stylesheet" href="css/profesores-edit.css">';
        include_once($dirs['Profesores'] . 'Edit/form.php');
        break;

    case 'actualizar':
        include_once($dirs['Profesores'] . 'Edit/Ajax/validate.php');
        break;

    case 'sustituir':
        include_once($dirs['Profesores'] . 'Sustituto/form.php');
        break;

    case 'add-profesor':
        $act_addProf = 'active';
        
        $scripts = '<link rel="stylesheet" href="css/profesores-edit.css">';
        $scripts .= '<link rel="stylesheet" href="css/login-style.css">';

        include_once($dirs['Interfaces'] . 'header.php');
        include_once($dirs['Interfaces'] . 'top-nav.php');
        include_once($dirs['Profesores'] . 'Add/form.php');
        break;

    case 'register-profesor':
        echo $class->validRegisterProf();
        exit;
        break;

    case 'add-sustituto':
        include_once($dirs['Profesores'] . 'Sustituto/Ajax/add.php');
        break;

    case 'remove-sustituto':
        include_once($dirs['Profesores'] . 'Sustituto/Ajax/remove.php');
        break;

    case 'des-act':
        include_once($dirs['Profesores'] . 'Ajax/deactivate-activate.php');
        break;

    case 'reset-pass':
        include_once($dirs['Profesores'] . 'Ajax/reset_pass.php');
        break;

    case 'delete-all':
        include_once($dirs['Admon'] . 'delete_all_profesores.php');
        break;

    default:
        $act_showProf = 'active';
        $scripts = '<link rel="stylesheet" href="css/profesores.css">';
        include_once($dirs['Interfaces'] . 'header.php');
        include_once($dirs['Interfaces'] . 'top-nav.php');
        include_once($dirs['Profesores'] . 'profesores.php');
        break;
}

include_once($dirs['Interfaces'] . 'footer.php');
