<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap-3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap-4.0.0/bootstrap.min.css">
    <link rel="shortcut icon" href="resources/img/asysteco.ico" type="image/x-icon">
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/bootstrap-4.0.0/bootstrap.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <title>No session</title>
</head>
<body>

  <script>
  window.onload = function() {
    $('#MSG').modal('show')
  };
  </script>

  <!-- Modal -->
  <div class="modal fade" id="MSG" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="fa fa-times" aria-hidden="true"></i>
          </button>
        </div>
        <div class="modal-body">
          <p><?= $MSG ?></p>
        </div>
      </div>
    </div>
  </div>
