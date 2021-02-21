<?php

if ($class->isLogged($Titulo) && $_SESSION['Perfil'] === 'Admin') {
    if ($class->compruebaCambioPass()) {
        $act_profesores = 'active';
        if (!isset($_GET['OPT'])) {
            $_GET['OPT'] = '';
        }
        switch ($_GET['OPT']) {
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
                if ($_SESSION['Perfil'] == 'Admin') {
                    include_once($dirs['Admon'] . 'delete_all_profesores.php');
                } else {
                    $MSG = "Acceso denegado.";
                    header("Refresh:2; url=index.php");
                    include_once($dirs['Interfaces'] . 'msg_modal.php');
                }
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
    } else {
        header('Location: index.php?ACTION=primer_cambio');
    }
} else {
    $MSG = "Debes iniciar sesión para realizar esta acción.";
    header("Refresh:2; url=index.php");
    include_once($dirs['Interfaces'] . 'msg_modal.php');
}
