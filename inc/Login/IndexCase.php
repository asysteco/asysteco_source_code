<?php

if (isset($_POST['Iniciales']) || isset($_POST['pass'])) {
    require_once($dirs['Login'] . 'valida.php');
}

$perfil = $_SESSION['Perfil'] ?? '';

if ($perfil === 'Admin') {
    header('Location: index.php?ACTION=profesores');
} elseif ($perfil === 'Profesor' || $perfil === 'Personal') {
    header('Location: index.php?ACTION=qrcoder');
} else {
    include_once($dirs['Login'] . 'form.php');
}
