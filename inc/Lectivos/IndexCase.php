<?php

if ($_SESSION['Perfil'] !== 'Admin') {
  header("location: index.php");
}

$opt = $_GET['OPT'] ?? '';

switch ($opt) {
  case 'alternar':
    include_once($dirs['Lectivos'] . 'alternar.php');
  break;

  default:
    if ($response = $class->query("SELECT COUNT(*) as num FROM $class->marcajes")) {
      $act_cal_escolar = 'active';
      $marcajes = $response->fetch_assoc();
      if ($marcajes['num'] > 0) {
        $scripts = '<link rel="stylesheet" href="css/form.css">';
        include_once($dirs['Interfaces'] . 'header.php');
        include_once($dirs['Interfaces'] . 'top-nav.php');
        include_once($dirs['Lectivos'] . 'calendario.php');
        include_once($dirs['Interfaces'] . 'footer.php');
      } else {
        $scripts = '<link rel="stylesheet" href="css/form.css">';
        $extras = "
            $(function (){
                $('#datepicker_ini').datepicker({
                  beforeShowDay: $.datepicker.noWeekends
              });
            });
            $(function (){
                $('#datepicker_fin').datepicker({
                  beforeShowDay: $.datepicker.noWeekends
              });
            });
            $(function (){
                $('#datepicker_ini_fest').datepicker({
                  beforeShowDay: $.datepicker.noWeekends
              });
            });
            $(function (){
                $('#datepicker_fin_fest').datepicker({
                  beforeShowDay: $.datepicker.noWeekends
              });
            });
          ";
        include_once($dirs['Lectivos'] . 'valida-lectivos.php');
        include_once($dirs['Interfaces'] . 'header.php');
        include_once($dirs['Interfaces'] . 'top-nav.php');
        include_once($dirs['Lectivos'] . 'lectivos.php');
        include_once($dirs['public'] . 'js/lectivos.js');
        include_once($dirs['Interfaces'] . 'footer.php');
      }
    } else {
      $ERR_MSG = $class->ERR_ASYSTECO;
    }
  break;
}