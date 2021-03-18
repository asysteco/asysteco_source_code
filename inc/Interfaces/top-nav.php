<?php

$index = 'index.php';
$sessionName = $_SESSION['Nombre'] ?? '';

$horarios = $index . '?ACTION=horarios';
$gestCursos = $horarios . '&OPT=cursos';
$gestAulas = $horarios . '&OPT=aulas';
$importHorarios = $horarios . '&OPT=import-form';

$profesores = $index . '?ACTION=profesores';
$addProfesores = $profesores . '&OPT=add-profesor';
$importProfesores = $profesores . '&OPT=import-form';

$asistencias = $index . '?ACTION=asistencias';
$asistenciasAll = $asistencias . '&OPT=all';
$asistenciasSession = $asistencias . '&OPT=sesion';

$guardias = $index . '?ACTION=guardias';
$lectivos = $index . '?ACTION=lectivos';
$ficharManual = $index . '?ACTION=fichar-manual';
$qrCode = $index . '?ACTION=qrcoder';
$admon = $index . '?ACTION=admon';
$notificaciones = $index . '?ACTION=notificaciones';
$cambioPass = $index . '?ACTION=cambio_pass';

$download = $index . '?ACTION=download';
$adminGuide = $download . '&OPT=admin-guide';
$profesorGuide = $download . '&OPT=profesor-guide';

$logout = $index . '?ACTION=logout';

$perfil = $_SESSION['Perfil'];
$noleidos = "SELECT count(*) as new
FROM Mensajes
WHERE (ID_DESTINATARIO='$_SESSION[ID]' AND Borrado_Destinatario=0 AND Leido=0)
ORDER BY ID DESC";

if ($response = $class->query($noleidos)) {
  $new = $response->fetch_assoc();
  if ($new['new'] > 0) {
    $color = "style='background-color: #5cb85c;'";
    $notificacion = " <span $color class='badge'>$new[new]</span>";
  } else {
    $notificacion = '';
  }
} else {
  $ERR_MSG = $class->ERR_ASYSTECO;
}

$novisto = "SELECT count(*) as new_alert
FROM Notificaciones
WHERE Visto=0
ORDER BY ID DESC";

if ($response = $class->query($novisto)) {
  $new = $response->fetch_assoc();
  if ($new['new_alert'] > 0) {
    $color = "style='background-color: #f5d42f; color: black;'";
    $notificacion_alert = " <span $color class='badge'>$new[new_alert]</span>";
  }
} else {
  $ERR_MSG = $class->ERR_ASYSTECO;
}
?>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">

    <?php

    if ($perfil === 'Admin') {
      include_once($dirs['Interfaces'] . 'Profiles/admin.php');
    } elseif ($perfil === 'Profesor') {
      include_once($dirs['Interfaces'] . 'Profiles/profesor.php');
    } elseif ($perfil === 'Personal') {
      include_once($dirs['Interfaces'] . 'Profiles/personal.php');
    }

    ?>
  </nav>
  <script src="js/animate.js"></script>

  <div id='flecha_div' class='flecha_div'><a href='#'><img id='flecha' class='flecha' src='resources/img/flecha.png' alt="Up row" /></a></div>

  <div id="overlay"></div>
  <div id="loading">
    <div id="spinnerWrapper">
      <div class="lds-dual-ring"></div>
    </div>
    <div id="loadingMessage">
      <h2 id="loading-msg"></h2>
    </div>
  </div>

<div id="info-horario-modal" aria-labelledby="info-horario" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h3 class="modal-title" id="info-horario">Información de horarios</h3>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-subtitle">
              <h6 class="modal-title" style="color: grey; text-align: center;"><i>*Estos son los datos de horas disponibles para crear y/o editar un horario.</i></h6>
              <h6 class="modal-title" style="color: grey; text-align: center;"><i>El Nº Referencia se utilizará como identificador de horas en los ficheros CSV a importar.</i></h6>
          </div>
          <div class="modal-body">
              <div class="container-fluid">
                  <div class="row">
                      <div class="col-12">
                          <div id="info-horario-body"></div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          </div>
      </div>
  </div>
</div>