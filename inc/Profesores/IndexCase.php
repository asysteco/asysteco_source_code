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
            include_once($dirs['Importar'] . 'import-profesorado.php');
            break;

        case 'preview':
            include_once($dirs['Importar'] . 'preview-import-profesores.php');
            break;

        case 'import-csv':
            require_once($dirs['Importar'] . 'import-mysql-profesorado-ajax.php');
            break;

        case 'registros':
            include_once($dirs['Profesores'] . 'muestra-registros-profesores.php');
            break;

        case 'edit':
            $scripts = '<link rel="stylesheet" href="css/profesores-edit.css">';
            include_once($dirs['Valida'] . 'valida_edit_profesor.php');
            include_once($dirs['Interfaces'] . 'header.php');
            include_once($dirs['Profesores'] . 'editar_profesor.php');
            break;

        case 'sustituir':
            $scripts = '<link rel="stylesheet" href="css/profesores-sustituir.css">';
            $scripts .= '<link rel="stylesheet" href="css/login-style.css">';
            include_once($dirs['Interfaces'] . 'header.php');
            include_once($dirs['Interfaces'] . 'top-nav.php');
            include_once($dirs['Form'] . 'form_sustituto.php');
            break;

        case 'add-profesor':
            $scripts = '<link rel="stylesheet" href="css/profesores-edit.css">';
            $scripts .= '<link rel="stylesheet" href="css/login-style.css">';
            if (isset($_POST['add-profesor']) && $_POST['add-profesor'] === 'add') {
            if ($class->validRegisterProf()) {
                $act_regProf = 'active';
                $MSG = "Profesor: $_POST[Nombre] con iniciales: $_POST[Iniciales] añadido correctamente";
                header('Refresh: 2; index.php?ACTION=profesores');
                include_once($dirs['Interfaces'] . 'header.php');
                include_once($dirs['Interfaces'] . 'top-nav.php');
            } else {
                include_once($dirs['Interfaces'] . 'header.php');
                include_once($dirs['Interfaces'] . 'top-nav.php');
                include_once($dirs['Form'] . 'form-add-profesor.php');
            }
            } else {
            include_once($dirs['Interfaces'] . 'header.php');
            include_once($dirs['Interfaces'] . 'top-nav.php');
            include_once($dirs['Form'] . 'form-add-profesor.php');
            }
            break;

        case 'add-sustituto':
            include_once($dirs['Profesores'] . 'agregar-sustituto.php');
            if (isset($ERR_MSG)  && $ERR_MSG != '') {
            header("Location: index.php?ACTION=profesores&ERR_MSG=" . $ERR_MSG);
            } else {
            header("Location: index.php?ACTION=profesores&MSG=" . $MSG);
            }
            break;

        case 'remove-sustituto':
            include_once($dirs['Profesores'] . 'retirar-sustituto.php');
            if (isset($ERR_MSG)  && $ERR_MSG != '') {
            header("Location: index.php?ACTION=profesores&ERR_MSG=" . $ERR_MSG);
            } else {
            header("Location: index.php?ACTION=profesores&MSG=" . $MSG);
            }
            break;

        case 'des-act':
            include_once($dirs['Profesores'] . 'des-act-profesor.php');
            break;
            
        case 'reset-pass':
            include_once($dirs['Login'] . 'reset_pass.php');
            break;

        case 'delete-all':
            if ($_SESSION['Perfil'] == 'Admin') {
            include_once($dirs['Profesores'] . 'delete_all_profesores.php');
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