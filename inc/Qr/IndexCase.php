<?php

if ($class->isLogged($Titulo)) {
    if ($class->compruebaCambioPass()) {
      $act_qr = 'active';
      include_once($dirs['Interfaces'] . 'header.php');
      include_once($dirs['Interfaces'] . 'top-nav.php');
      include_once($dirs['Qr'] . 'generate_code.php');
      include_once($dirs['Interfaces'] . 'footer.php');
    } else {
      header('Location: index.php?ACTION=primer_cambio');
    }
} else {
    $MSG = "Debes iniciar sesión para realizar esta acción.";
    header("Refresh:2; url=index.php");
    include_once($dirs['Interfaces'] . 'msg_modal.php');
}