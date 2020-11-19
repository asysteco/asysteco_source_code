<!DOCTYPE html>
<html lang="es">
<head>
  <title>Inicio</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
  <link rel="stylesheet" href="css/bootstrap-3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/asysteco.css">
  <link rel="stylesheet" href="js/jquery-ui/jquery-ui.min.css">
  <link href="js/toastr/toastr.min.css" rel="stylesheet"/>
  <link rel="shortcut icon" href="resources/img/asysteco.ico" type="image/x-icon">
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery-ui/jquery-ui.min.js"></script>
  <script src="js/toastr/toastr.min.js"></script>
  <script src="js/datepicker_common.js"></script>
  <script src="js/flecha.js"></script>
  
  <script>
    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": false,
      "positionClass": "toast-bottom-right",
      "preventDuplicates": true,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }
  </script>
  
  <?php if(isset($scripts)){ echo $scripts;} ?>

  <script>
    <?php if(isset($extras)){ echo $extras;} ?>
  </script>

  <style>
    <?php if(isset($style)){ echo $style;} ?>
  </style>
</head>