<?php

if ($_SESSION['Perfil'] !== 'Admin') {
    header("location: index.php");
}

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
        case 'backup-centro':
            include_once($dirs['Exportar'] . 'backup-centro.php');
            break;
            
        case 'select':
            $action = $_GET['action'];
            $element = $_GET['element'];

            if (isset($action) && $action === 'export') {
                if (!$class->existsFolder()) {
                    error_log('Error: While trying to create tmp dir.');
                    echo 'Error-mkdir';
                    exit;
                }

                if ($element === 'marcajes') {
                    include_once($dirs['Exportar'] . 'marcajes.php');
                } elseif ($element === 'Asistencias') {
                    include_once($dirs['Exportar'] . 'asistencias.php');
                } elseif ($element === 'Faltas') {
                    include_once($dirs['Exportar'] . 'faltas.php');
                } elseif ($element === 'Horarios') {
                    include_once($dirs['Exportar'] . 'horarios.php');
                } elseif ($element === 'Profesores') {
                    include_once($dirs['Exportar'] . 'profesores.php');
                } elseif ($element === 'Fichajes') {
                    include_once($dirs['Exportar'] . 'fichajes.php');
                } elseif ($element === 'Faltas_Injustificadas') {
                    include_once($dirs['Exportar'] . 'faltas_injustificadas.php');
                } elseif ($element === 'Faltas_Justificadas') {
                    include_once($dirs['Exportar'] . 'faltas_justificadas.php');
                } else {
                    $MSG = 'error-export';
                }
            } elseif (isset($action) && $action === 'select') {
                if ($element === 'marcajes') {
                    include_once($dirs['Listar'] . 'marcajes.php');
                } elseif ($element === 'asistencias') {
                    include_once($dirs['Listar'] . 'asistencias.php');
                } elseif ($element === 'faltas') {
                    include_once($dirs['Listar'] . 'faltas.php');
                } elseif ($element === 'horarios') {
                    include_once($dirs['Listar'] . 'horarios.php');
                } elseif ($element === 'fichajeDiario') {
                    include_once($dirs['Listar'] . 'fichaje_diario.php');
                } elseif ($element === 'fichajeFechaFilter') {
                    include_once($dirs['Listar'] . 'fichaje_fecha.php');
                } elseif ($element === 'faltasInjustificadas') {
                    include_once($dirs['Listar'] . 'faltas_injustificadas.php');
                } elseif ($element === 'faltasJustificadas') {
                    include_once($dirs['Listar'] . 'faltas_justificadas.php');
                } else {
                    $MSG = 'error-export';
                }
            }
            break;

        default:
            include_once($dirs['Interfaces'] . 'header.php');
            include_once($dirs['Interfaces'] . 'top-nav.php');
            include_once($dirs['Admon'] . 'interface.php');
            include_once($dirs['Interfaces'] . 'footer.php');
            break;
    }
