<?php

if ($class->isLogged($Titulo) && $_SESSION['Perfil'] == 'Admin') {
    if ($class->compruebaCambioPass()) {
        $act_usuario = 'active';
        $act_admon  = 'active';

        $scripts = "<link rel='stylesheet' href='css/admon.css'>";
        $extras = "        
            $(function (){
                $('#fechainicio').datepicker();
            });
        ";

        if (!isset($_GET['OPT'])) {
        $_GET['OPT'] = '';
        }
        switch ($_GET['OPT']) {
        case 'select':
            $action = $_GET['action'];
            $element = $_GET['element'];

            if (isset($action) && $action === 'export') {
            if ($element === 'marcajes') {
                include_once($dirs['Exportar'] . 'export_marcajes.php');
            } elseif ($element === 'asistencias') {
                include_once($dirs['Exportar'] . 'export_asistencias.php');
            } elseif ($element === 'faltas') {
                include_once($dirs['Exportar'] . 'export_faltas.php');
            } elseif ($element === 'horarios') {
                include_once($dirs['Exportar'] . 'export_horarios.php');
            } elseif ($element === 'profesores') {
                include_once($dirs['Exportar'] . 'export_profesores.php');
            } elseif ($element === 'fichajes') {
                include_once($dirs['Exportar'] . 'export_fichajes.php');
            } elseif ($element === 'Faltas_Injustificadas') {
                include_once($dirs['Exportar'] . 'export_faltas_injustificadas.php');
            } elseif ($element === 'Faltas_Justificadas') {
                include_once($dirs['Exportar'] . 'export_faltas_justificadas.php');
            } else {
                $MSG = 'error-export';
            }
            } elseif (isset($action) && $action === 'select') {
            if ($element === 'marcajes') {
                include_once($dirs['Listar'] . 'list_marcajes.php');
            } elseif ($element === 'asistencias') {
                include_once($dirs['Listar'] . 'list_asistencias.php');
            } elseif ($element === 'faltas') {
                include_once($dirs['Listar'] . 'list_faltas.php');
            } elseif ($element === 'horarios') {
                include_once($dirs['Listar'] . 'list_horarios.php');
            } elseif ($element === 'fichajeDiario') {
                include_once($dirs['Listar'] . 'list_fichaje_diario.php');
            } elseif ($element === 'fichajeFechaFilter') {
                include_once($dirs['Listar'] . 'list_fichaje_fecha.php');
            } elseif ($element === 'faltasInjustificadas') {
                include_once($dirs['Listar'] . 'list_faltas_injustificadas.php');
            } elseif ($element === 'faltasJustificadas') {
                include_once($dirs['Listar'] . 'list_faltas_justificadas.php');
            } else {
                $MSG = 'error-export';
            }
            }
            break;

        default:
            include_once($dirs['Interfaces'] . 'header.php');
            include_once($dirs['Interfaces'] . 'top-nav.php');
            include_once($dirs['Interfaces'] . 'menu_admon.php');
            include_once($dirs['Interfaces'] . 'errors.php');
            include_once($dirs['Interfaces'] . 'footer.php');
            break;
        }
    } else {
        header('Location: index.php?ACTION=primer_cambio');
    }
} else {
    $MSG = "Debes iniciar sesión para realizar esta acción.";
    header("Refresh:2; url=index.php");
    include_once($dirs['Interfaces'] . 'msg_modal.php');
}