<?php

if (isset($_POST['Iniciales']) || isset($_POST['pass'])) {
    require_once($dirs['Login'] . 'valida.php');
}

$perfil = $_SESSION['Perfil'] ?? '';

if ($perfil === 'Admin') {
    if (!$class->isLogged($Titulo)) {
        include_once($dirs['Login'] . 'form.php');
      return;
    }
  
    if (!$class->compruebaCambioPass()) {
      require_once($dirs['FirstChangePass'] . 'IndexCase.php');
      return;
    }
    require_once($dirs['Profesores'] . 'IndexCase.php');
} elseif ($perfil === 'Profesor' || $perfil === 'Personal') {
    if (!$class->isLogged($Titulo)) {
        include_once($dirs['Login'] . 'form.php');
      return;
    }
  
    if (!$class->compruebaCambioPass()) {
      require_once($dirs['FirstChangePass'] . 'IndexCase.php');
      return;
    }
    require_once($dirs['Qr'] . 'IndexCase.php');
} else {
    include_once($dirs['Login'] . 'form.php');
}
