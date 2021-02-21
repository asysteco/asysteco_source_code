<?php

if ($class->isLogged($Titulo) && $_SESSION['Perfil'] == 'Admin') {
    if ($class->compruebaCambioPass()) {
      $act_usuario = 'active';
      $act_notification = 'active';

      include_once($dirs['Interfaces'] . 'header.php');
      include_once($dirs['Interfaces'] . 'top-nav.php');
      include_once($dirs['Notificaciones'] . 'interface.php');
      include_once($dirs['Interfaces'] . 'footer.php');
    } else {
      header('Location: index.php');
    }
} else {
    $MSG = "Debes iniciar sesión para realizar esta acción.";
    header("Refresh:2; url=index.php");
    include_once($dirs['Interfaces'] . 'msg_modal.php');
}