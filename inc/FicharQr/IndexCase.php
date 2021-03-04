<?php

if ($_SESSION['Perfil'] !== 'Admin') {
  header("location: index.php");
}

include_once($dirs['FicharQr'] . 'Ajax/fichar-asistencia.php');
