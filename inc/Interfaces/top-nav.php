<body>
<?php

$noleidos = "SELECT count(*) as new
FROM Mensajes
WHERE (ID_DESTINATARIO='$_SESSION[ID]' AND Borrado_Destinatario=0 AND Leido=0)
ORDER BY ID DESC";

if($response = $class->query($noleidos))
{
  $new = $response->fetch_assoc();
  if($new['new'] > 0)
  {
    $color = "style='background-color: #5cb85c;'";
    $notificacion = " <span $color class='badge'>$new[new]</span>";
  }
  else
  {
    $notificacion = '';
  }
}
else
{
  $ERR_MSG = $class->ERR_ASYSTECO;
}

$novisto = "SELECT count(*) as new_alert
FROM Notificaciones
WHERE Visto=0
ORDER BY ID DESC";

if($response = $class->query($novisto))
{
  $new = $response->fetch_assoc();
  if($new['new_alert'] > 0)
  {
    $color = "style='background-color: #f5d42f; color: black;'";
    $notificacion_alert = " <span $color class='badge'>$new[new_alert]</span>";
  }
  else
  {
    $notificacion_alert = '';
  }
}
else
{
  $ERR_MSG = $class->ERR_ASYSTECO;
}

echo '<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">';

    if($_SESSION['Perfil'] === 'Admin')
    {
      echo '<a class="navbar-brand" href="index.php">' . $Titulo . '</a>';
      echo '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#top-menu"
              aria-controls="top-menu" aria-expanded="false" aria-label="Toggle navigation">';
        echo '<span class="navbar-toggler-icon"></span>';
      echo '</button>';
    echo '</div>';
    echo '<div class="collapse navbar-collapse" id="top-menu">';
      echo '<ul class="navbar-nav mr-auto">';
      
        // Home start
        echo "<li class='nav-item $act_home'>";
          echo "<a class='nav-link' href='index.php'> Inicio</a>";
        echo "</li>";
        // Home end

        // Horarios Dropdown start
        echo "<li class='nav-item dropdown $act_horario'>";
          echo "<a class='nav-link dropdown-toggle' data-toggle='dropdown' href='#'>Horario</a>";
          echo '<ul class="dropdown-menu bg-dark">';
            echo "<a class='dropdown-item text-light $act_gestCursos' href='index.php?ACTION=horarios&OPT=cursos'>
              <i style='font-size: 20px; vertical-align: middle;' class='fa fa-pencil-square-o'></i>
              <span style='vertical-align: middle;'> Gestionar Cursos</span>
            </a>";
            echo "<a class='dropdown-item text-light $act_gestAulas' href='index.php?ACTION=horarios&OPT=aulas'>
              <i style='font-size: 20px; vertical-align: middle;' class='fa fa-pencil-square-o'></i>
              <span style='vertical-align: middle;'> Gestionar Aulas</span>
            </a>";
            echo '<div class="dropdown-divider"></div>';
            echo "<a class='dropdown-item text-light $act_importHorarios' href='index.php?ACTION=horarios&OPT=import-form'>
              <i style='font-size: 20px; vertical-align: middle;' class='fa fa-cloud-upload'></i>
              <span style='vertical-align: middle;'> Importar horarios</span>
            </a>";
          echo '</ul>';
        echo '</li>';
        // Horarios Dropdown start

        // Profesores Dropdown start
        echo "<li class='nav-item dropdown $act_profesores '>";
          echo "<a class='nav-link dropdown-toggle' data-toggle='dropdown' href='#'>Profesores</a>";
          echo "<ul class='dropdown-menu bg-dark'>";
            echo "<a class='dropdown-item text-light $act_showProf' href='$_SERVER[PHP_SELF]?ACTION=profesores'>
              <i style='font-size: 20px; vertical-align: middle;' class='fa fa-eye'></i>
              <span style='vertical-align: middle;'> Mostrar profesores</span>
            </a>";
            echo "<a class='dropdown-item text-light $act_regProf' href='$_SERVER[PHP_SELF]?ACTION=profesores&OPT=add-profesor'>
              <i style='font-size: 20px; vertical-align: middle;' class='fa fa-plus'></i>
              <span style='vertical-align: middle;'> Añadir profesores</span>
            </a>";
            echo '<div class="dropdown-divider"></div>';
            echo "<a class='dropdown-item text-light $act_importProf' href='$_SERVER[PHP_SELF]?ACTION=profesores&OPT=import-form'>
              <i style='font-size: 20px; vertical-align: middle;' class='fa fa-cloud-upload'></i>
              <span style='vertical-align: middle;'> Importar profesores</span>
            </a>";
          echo "</ul>";
        echo "</li>";
        // Profesores Dropdown end

        // Asistencias actuales start
        echo "<li class='nav-item $act_asistencia'>";
          echo "<a class='nav-link' href='$_SERVER[PHP_SELF]?ACTION=asistencias&OPT=all'>Asistencias actuales</a>";
        echo "</li>";
        // Asistencias actuales end

        // Calendario escolar start
        echo "<li class='nav-item $act_cal_escolar '>";
          echo "<a class='nav-link' href='$_SERVER[PHP_SELF]?ACTION=lectivos'>Calendario escolar</a>";
        echo "</li>";
        // Calendario escolar end

      // Fichaje Manual start
        echo "<li class='nav-item'>";
          echo "<a class='nav-link' href='$_SERVER[PHP_SELF]?ACTION=fichar-manual'>Fichaje Manual</a>";
        echo "</li>";
      echo "</ul>";
      // Fichaje Manual end

      echo '<ul class="nav navbar-nav navbar-right">';

        // User Dropdown start
        echo "<li class='nav-item dropdown $act_usuario'>";
          echo "<a class='nav-link dropdown-toggle' data-toggle='dropdown' href='#'>";
            echo "<i vertical-align: middle;' class='fa fa-user-o'></i> ";
            echo $_SESSION['Nombre'];
            echo $notificacion;
            echo $notificacion_alert;
          echo '</a>';
          echo '<ul class="dropdown-menu dropdown-menu-right bg-dark">';
              echo "<a class='dropdown-item text-light $act_qr' href='index.php?ACTION=qrcoder'>
                <i style='font-size: 20px; vertical-align: middle;' class='fa fa-qrcode'></i>
                <span style='vertical-align: middle;'> Activador de lector</span>
              </a>";
              echo "<a class='dropdown-item text-light $act_admon' id='admon' href='index.php?ACTION=admon'>
                <i id='admon-icon' style='font-size: 20px; vertical-align: middle;' class='fa fa-folder-o'></i>
                <span style='vertical-align: middle;'> Administración</span>
                </a>";
              echo "<a class='dropdown-item text-light $act_notification' id='notif' href='index.php?ACTION=notificaciones'>
                <i id='notif-icon' style='font-size: 20px; vertical-align: middle;' class='fa fa-bell-o'></i>
                <span style='vertical-align: middle;'> Notificaciones</span> $notificacion_alert
              </a>";
              echo "<a class='dropdown-item text-light $act_changePass' id='cambio-pass' href='index.php?ACTION=cambio_pass'>
              <i id='cambio-pass-icon' style='font-size: 20px; vertical-align: middle;' class='fa fa-refresh'></i>
              <span style='vertical-align: middle;'> Cambio de contraseña </span>
              </a>";
              echo '<div class="dropdown-divider"></div>';
              echo "<a class='dropdown-item text-light' id='admin-guide' href='index.php?ACTION=download_admin_guide'>
                <i id='cambio-pass-icon' style='font-size: 20px; vertical-align: middle;' class='fa fa-cloud-download'></i>
                <span style='vertical-align: middle;'> Guía aplicación Administración</span>
              </a>";
              echo "<a class='dropdown-item text-light' id='profesor-guide' href='index.php?ACTION=download_profesor_guide'>
                <i id='cambio-pass-icon' style='font-size: 20px; vertical-align: middle;' class='fa fa-cloud-download'></i>
                <span style='vertical-align: middle;'> Guía aplicación Profesorado</span>
              </a>";
          echo '</ul>';
        echo '</li>';
        // User Dropdown end

        echo "<li class='nav-item'>";
          echo "<a class='nav-link' href='$_SERVER[PHP_SELF]?ACTION=logout'><i class='fa fa-sign-out'></i> Cerrar Sesión</a>";
        echo "</li>";
      echo '</ul>';
    }
    
    if($_SESSION['Perfil'] === 'Profesor')
    {
      $d = date('d');
      $m = date('m');
      $Y = date('Y');
      echo "<a class='navbar-brand' href='$_SERVER[PHP_SELF]?ACTION=horarios'>$Titulo</a>";
      echo '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#top-menu"
              aria-controls="top-menu" aria-expanded="false" aria-label="Toggle navigation">';
        echo '<span class="navbar-toggler-icon"></span>';
      echo '</button>';
    echo '</div>';
    echo '<div class="collapse navbar-collapse" id="top-menu">';
      echo '<ul class="navbar-nav mr-auto">';

        // Horario start
        echo "<li class='nav-item $act_horario'>";
          echo "<a class='nav-link' href='$_SERVER[PHP_SELF]?ACTION=horarios'>Inicio</a>";
        echo "</li>";
        // Horario end

        // Guardias start
        echo "<li class='nav-item $act_guardias'>";
          echo "<a class='nav-link' href='index.php?ACTION=guardias'>Guardias</a>";
        echo "</li>";
        // Guardias end
        
        // Mis asistencias start
        echo "<li class='nav-item $act_asistencia'>";
          echo "<a class='nav-link' href='$_SERVER[PHP_SELF]?ACTION=asistencias&OPT=sesion&d=$d&m=$m&Y=$Y'>Mis asistencias</a>";
        echo "</li>";
        echo "<li class='nav-item $act_qr'>";
          echo '<a class="nav-link" href="index.php?ACTION=qrcoder">
            <span class="fa fa-qrcode"></span> Mi código QR
          </a>';
        echo '</li>';
      echo '</ul>';
      // Mis asistencias end

      echo '<ul class="nav navbar-nav navbar-right">';

        // User Dropdown start
        echo '<li class="nav-item dropdown">';
          echo "<a class='nav-link dropdown-toggle' data-toggle='dropdown' href='#'>
            <i vertical-align: middle;' class='fa fa-user-o'></i> ";
            echo $_SESSION['Nombre'];
          echo '</a>';
          echo '<ul class="dropdown-menu dropdown-menu-right bg-dark">';
            echo "<a id='cambio-pass' class='dropdown-item text-light $act_changePass' href='index.php?ACTION=cambio_pass'>
              <i id='cambio-pass-icon' style='font-size: 20px; vertical-align: middle;' class='fa fa-refresh'></i>
              <span style='vertical-align: middle;'> Cambio de contraseña </span>
            </a>";
            echo '<div class="dropdown-divider"></div>';
            echo '<a id="profesor-guide" class="dropdown-item text-light" href="index.php?ACTION=download_profesor_guide">
              <span id="download-guide-profesor-icon" style="font-size: 20px;" class="fa fa-cloud-download"></span> Guía de uso
            </a>';
          echo '</ul>';
        echo '</li>';
        // User Dropdown end

        echo "<li class='nav-item'>";
        echo "<a class='nav-link' href='$_SERVER[PHP_SELF]?ACTION=logout'><i class='fa fa-sign-out'></i> Cerrar Sesión</a>";
        echo "</li>";
      echo '</ul>';
    }
echo '</nav>';

include_once($dirs['public'] . 'js/animate.js');

echo "<div id='flecha_div' class='flecha_div'><a href='#'><img id='flecha' class='flecha' src='resources/img/flecha.png'/></a></div>";