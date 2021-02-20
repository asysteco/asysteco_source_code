<?php

$opt = $_GET['OPT'] ?? '';

if ($class->isLogged($Titulo) && $_SESSION['Perfil'] === 'Admin') {
    if ($class->compruebaCambioPass()) {
            switch($opt) {
                case 'ajax':
                    include_once($dirs['FicharManual'] . 'Ajax/FicharManual.php');
                    break;
                    
                default:
                $extras = "
                  $(function (){
                    $('#add-fecha').datepicker({
                      minDate: -7,
                      maxDate: 0,
                      beforeShowDay: $.datepicker.noWeekends
                    });
                  });
                ";
                $scripts = '<link rel="stylesheet" href="css/profesores-edit.css">';
                $scripts .= '<link rel="stylesheet" href="css/login-style.css">';
                //$scripts = '<link rel="stylesheet" href="css/profesores-sustituir.css">';
                include_once($dirs['Interfaces'] . 'header.php');
                include_once($dirs['Interfaces'] . 'top-nav.php');
                include_once($dirs['Fichaje'] . 'fichar-manual.php');
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
