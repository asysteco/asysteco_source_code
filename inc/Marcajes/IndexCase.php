<?php

switch ($_GET['OPT']) {
  case 'update':
    include_once($dirs['Marcajes'] . 'Ajax/update.php');
    break;

  default:
    header('Location: index.php');
    break;
}
