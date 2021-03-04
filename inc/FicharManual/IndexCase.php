<?php

$opt = $_GET['OPT'] ?? '';

if ($_SESSION['Perfil'] !== 'Admin') {
  header("location: index.php");
}

if ($opt === 'ajax') {
  include_once($dirs['FicharManual'] . 'Ajax/FicharManual.php');
} else {
  $extras = "
    $(function (){
      $('#add-fecha').datepicker({
        minDate: -7,
        maxDate: 0,
        beforeShowDay: $.datepicker.noWeekends
      });
    });
  ";
  $scripts = '<link rel="stylesheet" href="css/profesores-edit.css">';
  $scripts .= '<link rel="stylesheet" href="css/login-style.css">';
  include_once($dirs['Interfaces'] . 'header.php');
  include_once($dirs['Interfaces'] . 'top-nav.php');
  include_once($dirs['FicharManual'] . 'form.php');
  include_once($dirs['Interfaces'] . 'footer.php');
}
