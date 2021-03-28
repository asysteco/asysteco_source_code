<?php

$opt = $_GET['OPT'] ?? '';

if ($opt === 'update') {
  include_once($dirs['Marcajes'] . 'Ajax/update.php');
} elseif ($opt === 'getRow') {
  include_once($dirs['Marcajes'] . 'Ajax/getRow.php');
} else {
  header('Location: index.php');
}
