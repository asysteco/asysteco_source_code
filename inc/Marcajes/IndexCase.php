<?php

$opt = $_GET['OPT'] ?? '';

if ($opt === 'update') {
  include_once($dirs['Marcajes'] . 'Ajax/update.php');
} else {
  header('Location: index.php');
}
