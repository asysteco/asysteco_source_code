<?php

$opt = $_GET['OPT'] ?? '';

if ($class->isLogged($Titulo)) {
    if ($class->compruebaCambioPass()) {
      $act_asistencia = 'active';
      $scripts = '<link rel="stylesheet" href="css/asistencias.css">';

      switch ($opt) {
        case 'all':
          if ($_SESSION['Perfil'] === 'Admin') {
            $scripts .= '<script src="js/filtro_asistencias.js"></script>';
            $extras = "$(function (){ $('#busca_asiste').datepicker({
                beforeShowDay: $.datepicker.noWeekends
              });
            });";
            include_once($dirs['Interfaces'] . 'header.php');
            include_once($dirs['Interfaces'] . 'top-nav.php');
            include_once($dirs['Fichaje'] . 'contenido-asistencias-all.php');
          } else {
            $MSG = "Acceso denegado.";
            header("Refresh:2; url=index.php");
            include_once($dirs['Interfaces'] . 'msg_modal.php');
          }
          break;

        case 'sesion':
          $_GET['ID'] = $_SESSION['ID'];
          $scripts .= '<script src="js/filtro_asistencias.js"></script>';
          $scripts .= '<script src="js/update_marcajes.js"></script>';
          $extras = "$(function (){ $('#busca_asiste').datepicker({
              beforeShowDay: $.datepicker.noWeekends
            });
          });";
          include_once($dirs['Interfaces'] . 'header.php');
          include_once($dirs['Interfaces'] . 'top-nav.php');
          include_once($dirs['Fichaje'] . 'contenido-asistencias.php');
          break;

        default:
          include_once($dirs['Fichaje'] . 'contenido-asistencias.php');
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
