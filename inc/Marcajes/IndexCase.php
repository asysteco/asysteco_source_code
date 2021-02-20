<?php

if ($class->isLogged($Titulo)) {
    if ($class->compruebaCambioPass()) {
      switch ($_GET['OPT']) {
        case 'update':
          include_once($dirs['Horarios'] . 'update-marcajes.php');
          break;

        default:
          header('Location: index.php');
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