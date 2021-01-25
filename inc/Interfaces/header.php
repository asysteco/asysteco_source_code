<!DOCTYPE html>
<html lang="es">
<head>
  <title>Inicio</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
  <link rel="stylesheet" href="css/bootstrap-4.0.0/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="css/asysteco.css">
  <link rel="stylesheet" href="js/jquery-ui/jquery-ui.min.css">
  <link href="js/toastr/toastr.min.css" rel="stylesheet"/>
  <link rel="shortcut icon" href="resources/img/asysteco.ico" type="image/x-icon">
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap-4.0.0/bootstrap.min.js"></script>
  <script src="js/jquery-ui/jquery-ui.min.js"></script>
  <script src="js/toastr/toastr.min.js"></script>
  <script src="js/datepicker_common.js"></script>
  <script src="js/flecha.js"></script>
  <script src="js/toastr/toastr-settings.js"></script>
  <script src="js/asysteco.js"></script>
  <script src="js/timepicker/timepicker.js"></script>
  <link rel="stylesheet" href="css/timepicker/timepicker.css">
  
  <?php if(isset($scripts)){ echo $scripts;} ?>

  <script>
    <?php if(isset($extras)){ echo $extras;} ?>
  </script>

  <style>
    <?php if(isset($style)){ echo $style;} ?>
  </style>
</head>