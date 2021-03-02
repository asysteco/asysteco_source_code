<?php

$act_horario = 'active';
$opt = $_GET['OPT'] ?? '';

switch ($opt) {
    case 'edit-horario':
        if ($_SESSION['Perfil'] !== 'Admin') {
            header("Location: index.php");
        }

        include_once($dirs['Horarios'] . 'Editar/Horario/Ajax/edit.php');
        break;
    case 'edit-t_horario':
        if ($_SESSION['Perfil'] !== 'Admin') {
            header("Location: index.php");
        }

        include_once($dirs['Horarios'] . 'Edit/HorarioTemp/Ajax/edit.php');
        break;

    case 'gest-horario':
        if ($_SESSION['Perfil'] !== 'Admin') {
            header("Location: index.php");
        }
        
        $scripts = '<link rel="stylesheet" href="css/gest-horario.css">';
        $extras = "
                    $(function (){
                        $('#fecha-programar-horario').datepicker({
                            minDate: 1,
                            beforeShowDay: $.datepicker.noWeekends
                        });
                    });
                ";
        include_once($dirs['Interfaces'] . 'header.php');
        include_once($dirs['Interfaces'] . 'top-nav.php');
        if (isset($_GET['programDate']) && $class->validFormDate($_GET['programDate'])) {
            include_once($dirs['Horarios'] . 'Edit/HorarioTemp/form.php');
        } else {
            include_once($dirs['Horarios'] . 'Edit/Horario/form.php');
        }
        break;

    case 'crear':
        if ($_SESSION['Perfil'] !== 'Admin') {
            header("Location: index.php");
        }

        $scripts = '<link rel="stylesheet" href="css/horarios-crear.css">';
        include_once($dirs['Interfaces'] . 'header.php');
        include_once($dirs['Interfaces'] . 'top-nav.php');
        include_once($dirs['Horarios'] . 'crear-horario.php');
        break;

    case 'cursos':
        if ($_SESSION['Perfil'] !== 'Admin') {
            header("Location: index.php");
        }

        $act_gestCursos = 'active';

        $scripts = '<link rel="stylesheet" href="css/aulas-cursos.css">';
        include_once($dirs['Interfaces'] . 'header.php');
        include_once($dirs['Interfaces'] . 'top-nav.php');
        include_once($dirs['Horarios'] . 'Cursos/form.php');
        break;

    case 'edit-cursos':
        if ($_SESSION['Perfil'] !== 'Admin') {
            header("Location: index.php");
        }

        include_once($dirs['Horarios'] . 'Cursos/Ajax/edit.php');
        break;

    case 'aulas':
        if ($_SESSION['Perfil'] !== 'Admin') {
            header("Location: index.php");
        }

        $act_gestAulas = 'active';

        $scripts = '<link rel="stylesheet" href="css/aulas-cursos.css">';
        include_once($dirs['Interfaces'] . 'header.php');
        include_once($dirs['Interfaces'] . 'top-nav.php');
        include_once($dirs['Horarios'] . 'Aulas/form.php');
        break;

    case 'edit-aulas':
        if ($_SESSION['Perfil'] !== 'Admin') {
            header("Location: index.php");
        }

        include_once($dirs['Horarios'] . 'Aulas/Ajax/edit.php');
        break;

    case 'cancel-changes':
        if ($_SESSION['Perfil'] !== 'Admin') {
            header("Location: index.php");
        }

        if ($class->delHorarioTemporal($_GET['profesor'], $_GET['fecha'])) {
            header("Location: index.php?ACTION=profesores");
        } else {
            $ERR_MSG = $class->ERR_ASYSTECO;
        }
        break;

    case 'import-form':
        if ($_SESSION['Perfil'] !== 'Admin') {
            header("Location: index.php");
        }

        $act_importHorarios = 'active';

        $scripts = '<link rel="stylesheet" href="css/import-csv.css">';
        $extras = "
                    $(function (){
                        $('#fecha_incorpora').datepicker({
                            minDate: 0,
                            beforeShowDay: $.datepicker.noWeekends
                        });
                    });
                    ";
        include_once($dirs['Interfaces'] . 'header.php');
        include_once($dirs['Interfaces'] . 'top-nav.php');
        include_once($dirs['Horarios'] . 'Import/form.php');
        break;

    case 'preview':

        require_once($dirs['Horarios'] . 'Import/Ajax/preview.php');
        break;

    case 'import-csv':
        if ($_SESSION['Perfil'] !== 'Admin') {
            header("Location: index.php");
        }

        require_once($dirs['Horarios'] . 'Import/Ajax/import.php');
        break;

    case 'profesor':
        if ($_SESSION['Perfil'] !== 'Admin') {
            header("Location: index.php");
        }

        include_once($dirs['Horarios'] . 'Profesor/horario.php');
        break;

    case 'remove':
        if ($_SESSION['Perfil'] !== 'Admin') {
            header("Location: index.php");
        }

        include_once($dirs['Horarios'] . 'Profesor/remove.php');
        break;

    case 'delete-all':
        if ($_SESSION['Perfil'] !== 'Admin') {
            header("Location: index.php");
        }

        include_once($dirs['Admon'] . 'Delete/delete_all_horarios.php');
        break;

    case 'delete-all-t':
        if ($_SESSION['Perfil'] !== 'Admin') {
            header("Location: index.php");
        }

        include_once($dirs['Admon'] . 'Delete/delete_all_t_horarios.php');
        break;

    case 'info':
        if ($_SESSION['Perfil'] !== 'Admin') {
            header("Location: index.php");
        }

        include_once($dirs['Horarios'] . 'Info/horario-centro.php');
        break;

    default:
        if ($_SESSION['Perfil'] === 'Admin') {
            header("Location: index.php");
        }

        include_once($dirs['Interfaces'] . 'header.php');
        include_once($dirs['Interfaces'] . 'top-nav.php');
        include_once($dirs['Horarios'] . 'Personal/horario.php');
        break;
}

include_once($dirs['Interfaces'] . 'footer.php');
