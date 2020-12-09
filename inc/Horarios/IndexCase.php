<?php

if ($class->isLogged($Titulo)) {
    if ($class->compruebaCambioPass()) {
        $act_horario = 'active';
        switch ($_GET['OPT']) {
            case 'edit-horario':
                include_once($dirs['Editar'] . 'edit-horario.php');
                break;
            case 'edit-t_horario':
                include_once($dirs['Editar'] . 'edit-t_horario.php');
                break;

            case 'gest-horario':
                $extras = "
                    $(function (){
                        $('#fecha-programar-horario').datepicker({minDate: 1});
                    });
                ";
                $style = "
                #profesor, .add-fields {
                    text-align: center;
                    margin-bottom: 25px;
                }
                table {
                    text-align: center;
                    width: 80%;
                    margin: 5px;
                }
                table th {
                    text-align:center;
                }
                .remove {
                    padding: 10px;
                    transition-duration: 0.2s;
                }
                #programar-horario {
                    text-align: center;
                }
                #program-date {
                    text-align: center;
                    color: grey;
                    margin-bottom: 25px;                  
                }
                #update-btn, #apply-program {
                    position: fixed;
                    top: 75px;
                    right: 25px;
                    background-color: #5cb85ccf;
                }
                #update-btn:hover, #apply-program:hover {
                    background-color: #449d44;
                }
                #cancel-btn, #cancel-program {
                    position: fixed;
                    top: 75px;
                    left: 25px;
                    background-color: #d9534fc4;
                }
                #cancel-btn:hover, #cancel-program:hover {
                    background-color: #d9534f;
                }
                ";
                include_once($dirs['Interfaces'] . 'header.php');
                include_once($dirs['Interfaces'] . 'top-nav.php');
                if (isset($_GET['programDate']) && $class->validFormDate($_GET['programDate'])) {
                    include_once($dirs['Horarios'] . 'gest-horario-programado.php');
                } else {
                    include_once($dirs['Horarios'] . 'gest-horario.php');
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

                    $style = "
                        table {
                        text-align: center;
                        width: 80%;
                        margin: 5px;
                        }
                        table th, table td {
                        text-align:center;
                        vertical-align: middle;
                        }
                        .remove, .edit {
                        padding: 10px;
                        transition-duration: 0.2s;
                        }
                    ";
                    include_once($dirs['Interfaces'] . 'header.php');
                    include_once($dirs['Interfaces'] . 'top-nav.php');
                    include_once($dirs['Editar'] . 'cursos.php');
                } else {
                    $MSG = "Acceso denegado.";
                    header("Refresh:2; url=index.php");
                    include_once($dirs['Interfaces'] . 'msg_modal.php');
                }
                break;

            case 'edit-cursos':
                if ($_SESSION['Perfil'] == 'Admin') {
                    include_once($dirs['Editar'] . 'edit-cursos.php');
                } else {
                    $MSG = "Acceso denegado.";
                    header("Refresh:2; url=index.php");
                    include_once($dirs['Interfaces'] . 'msg_modal.php');
                }
                break;

            case 'aulas':
                if ($_SESSION['Perfil'] == 'Admin') {
                    $act_gestAulas = 'active';

                    $style = "
                        table {
                        text-align: center;
                        width: 80%;
                        margin: 5px;
                        }
                        table th, table td {
                        text-align:center;
                        vertical-align: middle;
                        }
                        .remove, .edit {
                        padding: 10px;
                        transition-duration: 0.2s;
                        }
                    ";
                    include_once($dirs['Interfaces'] . 'header.php');
                    include_once($dirs['Interfaces'] . 'top-nav.php');
                    include_once($dirs['Editar'] . 'aulas.php');
                } else {
                    $MSG = "Acceso denegado.";
                    header("Refresh:2; url=index.php");
                    include_once($dirs['Interfaces'] . 'msg_modal.php');
                }
                break;

            case 'edit-aulas':
                if ($_SESSION['Perfil'] == 'Admin') {
                    include_once($dirs['Editar'] . 'edit-aulas.php');
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

                    $extras = "
                        $(function (){
                            $('#fecha_incorpora').datepicker({minDate: 0});
                        });
                        ";
                    $style = "
                        input[type=file] {
                            display: inline-block;
                            padding: 6px 12px 6px 0;
                        }
                        .format-body {
                            margin-left: 25px;
                        }
                        ";
                    include_once($dirs['Interfaces'] . 'header.php');
                    include_once($dirs['Interfaces'] . 'top-nav.php');
                    include_once($dirs['Importar'] . 'import-horario.php');
                } else {
                    $MSG = "Acceso denegado.";
                    header("Refresh:2; url=index.php");
                    include_once($dirs['Interfaces'] . 'msg_modal.php');
                }
                break;

            case 'preview':
                if ($_SESSION['Perfil'] == 'Admin') {
                    require_once($dirs['Importar'] . 'preview-import-horario.php');
                } else {
                    $MSG = "Acceso denegado.";
                    header("Refresh:2; url=index.php");
                    include_once($dirs['Interfaces'] . 'msg_modal.php');
                }
                break;

            case 'import-csv':
                if ($_SESSION['Perfil'] == 'Admin') {
                    require_once($dirs['Importar'] . 'import-mysql-horario-ajax.php');
                } else {
                    $MSG = "Acceso denegado.";
                    header("Refresh:2; url=index.php");
                    include_once($dirs['Interfaces'] . 'msg_modal.php');
                }
                break;

            case 'edit-horario-profesor':
                if ($_SESSION['Perfil'] == 'Admin') {
                    $scripts = '<link rel="stylesheet" href="css/horarios-edit.css">';
                    include_once($dirs['Interfaces'] . 'header.php');
                    include_once($dirs['Interfaces'] . 'top-nav.php');
                    include_once($dirs['Editar'] . 'edit-horario-profesor.php');
                } else {
                    $MSG = "Acceso denegado.";
                    header("Refresh:2; url=index.php");
                    include_once($dirs['Interfaces'] . 'msg_modal.php');
                }
                break;

            case 'edit-t':
                if ($_SESSION['Perfil'] == 'Admin') {
                    include_once($dirs['Editar'] . 'edit-t-horario.php');
                } else {
                    $MSG = "Acceso denegado.";
                    header("Refresh:2; url=index.php");
                    include_once($dirs['Interfaces'] . 'msg_modal.php');
                }
                break;

            case 'update':
                if ($_SESSION['Perfil'] == 'Admin') {
                    include_once($dirs['Interfaces'] . 'actualiza.php');
                } else {
                    $MSG = "Acceso denegado.";
                    header("Refresh:2; url=index.php");
                    include_once($dirs['Interfaces'] . 'msg_modal.php');
                }
                break;

            case 'registros':
                if ($_SESSION['Perfil'] == 'Admin') {
                    include_once($dirs['Horarios'] . 'muestra-registros-horarios.php');
                } else {
                    $MSG = "Acceso denegado.";
                    header("Refresh:2; url=index.php");
                    include_once($dirs['Interfaces'] . 'msg_modal.php');
                }
                break;

            case 'guardias':
                if ($_SESSION['Perfil'] == 'Admin') {
                    include_once($dirs['Horarios'] . 'horario-guardias.php');
                } else {
                    $MSG = "Acceso denegado.";
                    header("Refresh:2; url=index.php");
                    include_once($dirs['Interfaces'] . 'msg_modal.php');
                }
                break;

            case 'edit-guardias':
                if ($_SESSION['Perfil'] == 'Admin') {
                    if (isset($_GET['SUBOPT'])) {
                        include_once($dirs['Horarios'] . 'update-guardias.php');
                    } else {
                        $scripts = '<link rel="stylesheet" href="css/horarios-edit-guardias.css">';
                        include_once($dirs['Interfaces'] . 'header.php');
                        include_once($dirs['Interfaces'] . 'top-nav.php');
                        include_once($dirs['Editar'] . 'edit-guardias.php');
                    }
                } else {
                    $MSG = "Acceso denegado.";
                    header("Refresh:2; url=index.php");
                    include_once($dirs['Interfaces'] . 'msg_modal.php');
                }
                break;

            case 'profesor':
                if ($_SESSION['Perfil'] == 'Admin') {
                    include_once($dirs['Horarios'] . 'horario-profesor.php');
                } else {
                    $MSG = "Acceso denegado.";
                    header("Refresh:2; url=index.php");
                    include_once($dirs['Interfaces'] . 'msg_modal.php');
                }
                break;

            case 'remove':
                if ($_SESSION['Perfil'] == 'Admin') {
                    include_once($dirs['Horarios'] . 'remove-horario-profesor.php');
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
                    include_once($dirs['Horarios'] . 'delete_all_horarios.php');
                } else {
                    $MSG = "Acceso denegado.";
                    header("Refresh:2; url=index.php");
                    include_once($dirs['Interfaces'] . 'msg_modal.php');
                }
                break;

            case 'delete-all-t':
                if ($_SESSION['Perfil'] == 'Admin') {
                    include_once($dirs['Horarios'] . 'delete_all_t_horarios.php');
                } else {
                    $MSG = "Acceso denegado.";
                    header("Refresh:2; url=index.php");
                    include_once($dirs['Interfaces'] . 'msg_modal.php');
                }
                break;

            default:
                include_once($dirs['Interfaces'] . 'header.php');
                include_once($dirs['Interfaces'] . 'top-nav.php');
                include_once($dirs['Horarios'] . 'horarios.php');
                break;
        }

        include_once($dirs['Interfaces'] . 'errors.php');
        include_once($dirs['Interfaces'] . 'footer.php');
    } else {
        header('Location: index.php?ACTION=primer_cambio');
    }
} else {
    $MSG = "Debes iniciar sesión para realizar esta acción.";
    header("Refresh:2; url=index.php");
    include_once($dirs['Interfaces'] . 'msg_modal.php');
}
