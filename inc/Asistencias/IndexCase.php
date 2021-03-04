<?php

$opt = $_GET['OPT'] ?? '';

$act_asistencia = 'active';
$scripts = '<link rel="stylesheet" href="css/asistencias.css">';

switch ($opt) {
  case 'all':
    if ($_SESSION['Perfil'] !== 'Admin') {
        header("location: index.php");
    }

    $scripts .= '<script src="js/filtro_asistencias.js"></script>';
    $extras = "$(function (){ $('#busca_asiste').datepicker({
        beforeShowDay: $.datepicker.noWeekends
      });
    });";
    include_once($dirs['Interfaces'] . 'header.php');
    include_once($dirs['Interfaces'] . 'top-nav.php');
    include_once($dirs['Asistencias'] . 'contenido-asistencias-all.php');
    break;

  case 'sesion':
    $scripts .= '<script src="js/filtro_asistencias.js"></script>';
    $scripts .= '<script src="js/update_marcajes.js"></script>';
    $extras = "$(function (){ $('#busca_asiste').datepicker({
        beforeShowDay: $.datepicker.noWeekends
      });
    });";
    include_once($dirs['Interfaces'] . 'header.php');
    include_once($dirs['Interfaces'] . 'top-nav.php');
    include_once($dirs['Asistencias'] . 'contenido-asistencias.php');
    break;

  default:
    include_once($dirs['Asistencias'] . 'contenido-asistencias.php');
    break;
}
include_once($dirs['Interfaces'] . 'footer.php');
