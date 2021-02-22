<?php

$act_horario = 'active';
$opt = $_GET['OPT'] ?? '';

if (!empty($opt)) {
    switch ($opt) {
        case 'edit-horario':
            include_once($dirs['Horarios'] . 'Editar/Horario/Ajax/edit.php');
            break;
        case 'edit-t_horario':
            include_once($dirs['Horarios'] . 'Edit/HorarioTemp/Ajax/edit.php');
            break;

        case 'gest-horario':
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
            if ($_SESSION['Perfil'] == 'Admin') {
                $scripts = '<link rel="stylesheet" href="css/horarios-crear.css">';
                include_once($dirs['Interfaces'] . 'header.php');
                include_once($dirs['Interfaces'] . 'top-nav.php');
                include_once($dirs['Horarios'] . 'crear-horario.php');
            } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['Interfaces'] . 'msg_modal.php');
            }
            break;

        case 'apply-now':
            if ($class->horarioTemporalAHorarioReal($_GET['fecha'])) {
                header("Location: index.php?ACTION=profesores");
            } else {
                $ERR_MSG = $class->ERR_ASYSTECO;
            }
            break;

        case 'cursos':
            if ($_SESSION['Perfil'] == 'Admin') {
                $act_gestCursos = 'active';

                $scripts = '<link rel="stylesheet" href="css/aulas-cursos.css">';
                include_once($dirs['Interfaces'] . 'header.php');
                include_once($dirs['Interfaces'] . 'top-nav.php');
                include_once($dirs['Horarios'] . 'Cursos/form.php');
            } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['Interfaces'] . 'msg_modal.php');
            }
            break;

        case 'edit-cursos':
            if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['Horarios'] . 'Cursos/Ajax/edit.php');
            } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['Interfaces'] . 'msg_modal.php');
            }
            break;

        case 'aulas':
            if ($_SESSION['Perfil'] == 'Admin') {
                $act_gestAulas = 'active';

                $scripts = '<link rel="stylesheet" href="css/aulas-cursos.css">';
                include_once($dirs['Interfaces'] . 'header.php');
                include_once($dirs['Interfaces'] . 'top-nav.php');
                include_once($dirs['Horarios'] . 'Aulas/form.php');
            } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['Interfaces'] . 'msg_modal.php');
            }
            break;

        case 'edit-aulas':
            if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['Horarios'] . 'Aulas/Ajax/edit.php');
            } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['Interfaces'] . 'msg_modal.php');
            }
            break;

        case 'cancel-changes':
            if ($_SESSION['Perfil'] == 'Admin') {
                if ($class->delHorarioTemporal($_GET['profesor'], $_GET['fecha'])) {
                    header("Location: index.php?ACTION=profesores");
                } else {
                    $ERR_MSG = $class->ERR_ASYSTECO;
                }
            } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['Interfaces'] . 'msg_modal.php');
            }
            break;

        case 'import-form':
            if ($_SESSION['Perfil'] == 'Admin') {
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
            } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['Interfaces'] . 'msg_modal.php');
            }
            break;

        case 'preview':
            if ($_SESSION['Perfil'] == 'Admin') {
                require_once($dirs['Horarios'] . 'Import/Ajax/preview.php');
            } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['Interfaces'] . 'msg_modal.php');
            }
            break;

        case 'import-csv':
            if ($_SESSION['Perfil'] == 'Admin') {
                require_once($dirs['Horarios'] . 'Import/Ajax/import.php');
            } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['Interfaces'] . 'msg_modal.php');
            }
            break;

        case 'profesor':
            if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['Horarios'] . 'Profesor/horario.php');
            } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['Interfaces'] . 'msg_modal.php');
            }
            break;

        case 'remove':
            if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['Horarios'] . 'Profesor/remove.php');
                if (isset($ERR_MSG) && $ERR_MSG != '') {
                    header("Location: index.php?ACTION=profesores&ERR_MSG=" . $ERR_MSG);
                } else {
                    header("Location: index.php?ACTION=profesores&MSG=" . $MSG);
                }
            } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['Interfaces'] . 'msg_modal.php');
            }
            break;

        case 'delete-all':
            if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['Admon'] . 'Delete/delete_all_horarios.php');
            } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['Interfaces'] . 'msg_modal.php');
            }
            break;

        case 'delete-all-t':
            if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['Admon'] . 'Delete/delete_all_t_horarios.php');
            } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['Interfaces'] . 'msg_modal.php');
            }
            break;

        case 'info':
            if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['Horarios'] . 'Info/horario-centro.php');
            } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['Interfaces'] . 'msg_modal.php');
            }
            break;

        default:
            include_once($dirs['Interfaces'] . 'header.php');
            include_once($dirs['Interfaces'] . 'top-nav.php');
            include_once($dirs['Horarios'] . 'Personal/horario.php');
            break;
    }

    include_once($dirs['Interfaces'] . 'footer.php');
}
