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

echo '<nav class="navbar navbar-inverse navbar-fixed-top">';
  echo '<div class="container-fluid">';
    echo '<div class="navbar-header">';
      echo '<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#top-menu">';
        echo '<span class="icon-bar"></span>';
        echo '<span class="icon-bar"></span>';
        echo '<span class="icon-bar"></span>';
      echo '</button>';

      if($_SESSION['Perfil'] === 'Admin')
      {
        echo '<a class="navbar-brand" href="index.php">' . $Titulo . '</a>';
      echo '</div>';
      echo '<div class="collapse navbar-collapse" id="top-menu">';
        echo '<ul class="nav navbar-nav">';
          echo "<li class='$act_home'>";
            echo "<a href='index.php'><span class='glyphicon glyphicon-home'></span> Inicio</a>";
          echo "</li>";

          echo "<li class='dropdown $act_horario'><a class='dropdown-toggle' data-toggle='dropdown' href='#'><span class='glyphicon glyphicon-calendar'></span> Horario <span class='caret'></span></a>";
            echo '<ul class="dropdown-menu">';
              echo '<li class="' . $act_gestCursos . '"><a href="index.php?ACTION=horarios&OPT=cursos"><span class="glyphicon glyphicon-pencil"></span> Gestionar Cursos</a></li>';
              echo '<li class="' . $act_gestAulas . '"><a href="index.php?ACTION=horarios&OPT=aulas"><span class="glyphicon glyphicon-pencil"></span> Gestionar Aulas</a></li>';
              echo '<li class="' . $act_importHorarios . '"><a href="index.php?ACTION=horarios&OPT=import-form"><span class="glyphicon glyphicon-open"></span> Importar horarios</a></li>';
            echo '</ul>';
          echo '</li>';

          echo "<li class='dropdown $act_profesores '><a class='dropdown-toggle' data-toggle='dropdown' href='#'><span class='glyphicon glyphicon-education'></span> Profesores <span class='caret'></a>";
            echo "<ul class='dropdown-menu'>";
              echo "<li class='$act_showProf'><a href='$_SERVER[PHP_SELF]?ACTION=profesores'><span class='glyphicon glyphicon-education'></span> Mostrar profesores</a></li>";
              echo "<li class='$act_importProf'><a href='$_SERVER[PHP_SELF]?ACTION=profesores&OPT=import-form'><span class='glyphicon glyphicon-plus'></span> Importar profesores</a></li>";
            echo "</ul>";
          echo "</li>";

          echo "<li class='$act_asistencia'>";
            echo "<a href='$_SERVER[PHP_SELF]?ACTION=asistencias&OPT=all'><span class='glyphicon glyphicon-calendar'></span> Asistencias actuales</a>";
          echo "</li>";

          echo "<li class=' $act_cal_escolar '>";
            echo "<a href='$_SERVER[PHP_SELF]?ACTION=lectivos'><span class='glyphicon glyphicon-calendar'></span> Calendario escolar</a>";
          echo "</li>";

          echo "<li>";
            echo "<a href='$_SERVER[PHP_SELF]?ACTION=fichar-manual'><span class='glyphicon glyphicon-pencil'></span> Fichaje Manual</a>";
          echo "</li>";
        echo "</ul>";

        echo '<ul class="nav navbar-nav navbar-right">';
          echo "<li class='dropdown $act_usuario'>";
            echo "<a class='dropdown-toggle' data-toggle='dropdown' href='#'><span class='glyphicon glyphicon-user'></span> ";
              echo $_SESSION['Nombre'];
              echo $notificacion;
              echo $notificacion_alert;
              echo '<span class="caret"></span>';
            echo '</a>';
            echo '<ul class="dropdown-menu">';
              echo "<li class='$act_qr'>";
                echo '<a href="index.php?ACTION=qrcoder"><span class="glyphicon glyphicon-qrcode"></span> Activador de lector</a>';
              echo '</li>';
              echo "<li class='$act_admon'>";
                echo '<a id="admon" href="index.php?ACTION=admon"><span id="admon-icon" class="glyphicon glyphicon-folder-close"></span> Administración</a>';
              echo '</li>';
              echo "<li class='$act_notification'>";
                echo '<a id="notif" href="index.php?ACTION=notificaciones"><span id="notif-icon" class="glyphicon glyphicon-bell"></span> Notificaciones';
                  echo $notificacion_alert;
                echo '</a>';
              echo '</li>';
              echo "<li class='$act_changePass'>";
                echo '<a id="cambio-pass" href="index.php?ACTION=cambio_pass"><span id="cambio-pass-icon" class="glyphicon glyphicon-refresh"></span> Cambio de contraseña</a>';
              echo '</li>';
              echo '<li>';
                echo '<a id="admin-guide" href="index.php?ACTION=download_admin_guide"><span id="download-guide-admin-icon" class="glyphicon glyphicon-download-alt"></span> Guía aplicación Administración</a>';
              echo '</li>';
              echo '<li>';
                echo '<a id="profesor-guide" href="index.php?ACTION=download_profesor_guide"><span id="download-guide-profesor-icon" class="glyphicon glyphicon-download-alt"></span> Guía aplicación Profesorado</a>';
              echo '</li>';
            echo '</ul>';
          echo '</li>';

          echo "<li>";
            echo "<a href='$_SERVER[PHP_SELF]?ACTION=logout'><span class='glyphicon glyphicon-log-out'></span> Cerrar Sesión</a>";
          echo "</li>";
        echo '</ul>';
      }
      
      if($_SESSION['Perfil'] === 'Profesor')
      {
        $d = date('d');
        $m = date('m');
        $Y = date('Y');
        echo "<a class='navbar-brand' href='$_SERVER[PHP_SELF]?ACTION=horarios'>$Titulo</a>";
      echo '</div>';
      echo '<div class="collapse navbar-collapse" id="top-menu">';
        echo '<ul class="nav navbar-nav">';

        echo "<li class='$act_horario'>";
          echo "<a href='$_SERVER[PHP_SELF]?ACTION=horarios'><span class='glyphicon glyphicon-home'></span> Inicio</a>";
        echo "</li>";

          echo "<li class='$act_guardias'>";
            echo "<a href='index.php?ACTION=guardias'><span class='glyphicon glyphicon-eye-open'></span> Guardias</a>";
          echo "</li>";
          
          echo "<li class='$act_asistencia'>";
            echo "<a href='$_SERVER[PHP_SELF]?ACTION=asistencias&OPT=sesion&d=$d&m=$m&Y=$Y'><span class='glyphicon glyphicon-check'></span> Mis asistencias</a>";
          echo "</li>";
          echo "<li class='$act_qr'>";
            echo '<a href="index.php?ACTION=qrcoder"><span class="glyphicon glyphicon-qrcode"></span> Mi código QR</a>';
          echo '</li>';
          echo "<li class='$act_changePass'>";
            echo '<a id="cambio-pass" href="index.php?ACTION=cambio_pass"><span id="cambio-pass-icon" class="glyphicon glyphicon-refresh"></span> Cambio de contraseña</a>';
          echo '</li>'; 
          echo '<li>';
            echo '<a id="profesor-guide" href="index.php?ACTION=download_profesor_guide"><span id="download-guide-profesor-icon" class="glyphicon glyphicon-download-alt"></span> Guía aplicación Profesorado</a>';
          echo '</li>';
        echo '</ul>';

        echo '<ul class="nav navbar-nav navbar-right">';
        echo '<li>';
          echo "<a class='dropdown-toggle' data-toggle='dropdown'><span class='glyphicon glyphicon-user'></span> ";
            echo $_SESSION['Nombre'];
          echo '</a>';
        echo '</li>';

        echo "<li>";
          echo "<a href='$_SERVER[PHP_SELF]?ACTION=logout'><span class='glyphicon glyphicon-log-out'></span> Cerrar Sesión</a>";
        echo "</li>";
      echo '</ul>';
      }

    echo '</div>';
  echo '</div>';
echo '</nav>';

include_once($dirs['public'] . 'js/animate.js');

echo "<div id='flecha_div' class='flecha_div'><a href='#'><img id='flecha' class='flecha' src='resources/img/flecha.png'/></a></div>";