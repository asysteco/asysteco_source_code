<?php

if ($class->isLogged($Titulo)) {
    if ($class->compruebaCambioPass()) {
      include_once($dirs['Helper'] . 'clean_tmp.php');
    } else {
      header('Location: index.php?ACTION=primer_cambio');
    }
} else {
    $MSG = "Debes iniciar sesión para realizar esta acción.";
    header("Refresh:2; url=index.php");
    include_once($dirs['Interfaces'] . 'msg_modal.php');
}
