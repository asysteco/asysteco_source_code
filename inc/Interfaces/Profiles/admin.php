<a class="navbar-brand" href="<?= $index ?>"><?= $Titulo ?></a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#top-menu" aria-controls="top-menu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>
</div>

<div class="collapse navbar-collapse" id="top-menu">
    <ul class="navbar-nav mr-auto">

        <!-- Home start -->
        <li class="nav-item <?= $act_home ?>">
            <a class="nav-link" href="<?= $index ?>"> Inicio</a>
        </li>
        <!-- Home end -->

        <!-- Horarios Dropdown start -->
        <li class="nav-item dropdown <?= $act_horario ?>">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#"> Horario</a>
            <ul class="dropdown-menu bg-dark">
                <a class="dropdown-item text-light <?= $act_gestCursos ?>" href="<?= $gestCursos ?>">
                    <i style="font-size: 20px; vertical-align: middle;" class="fa fa-pencil-square-o"></i>
                    <span style="vertical-align: middle;"> Gestionar Cursos</span>
                </a>
                <a class="dropdown-item text-light <?= $act_gestAulas ?>" href="<?= $gestAulas ?>">
                    <i style="font-size: 20px; vertical-align: middle;" class="fa fa-pencil-square-o"></i>
                    <span style="vertical-align: middle;"> Gestionar Aulas</span>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-light <?= $act_importHorarios ?>" href="<?= $importHorarios ?>">
                    <i style="font-size: 20px; vertical-align: middle;" class="fa fa-cloud-upload"></i>
                    <span style="vertical-align: middle;"> Importar horarios</span>
                </a>
            </ul>
        </li>
        <!-- Horarios Dropdown start -->

        <!-- Profesores Dropdown start -->
        <li class="nav-item dropdown <?= $act_profesores ?>">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#"> Personal</a>
            <ul class="dropdown-menu bg-dark">
                <a class="dropdown-item text-light <?= $act_showProf ?>" href="<?= $profesores ?>">
                    <i style="font-size: 20px; vertical-align: middle;" class="fa fa-eye"></i>
                    <span style="vertical-align: middle;"> Mostrar Personal</span>
                </a>
                <a class="dropdown-item text-light <?= $act_addProf ?>" href="<?= $addProfesores ?>">
                    <i style="font-size: 20px; vertical-align: middle;" class="fa fa-plus"></i>
                    <span style="vertical-align: middle;"> Añadir Personal</span>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-light <?= $act_importProf ?>" href="<?= $importProfesores ?>">
                    <i style="font-size: 20px; vertical-align: middle;" class="fa fa-cloud-upload"></i>
                    <span style="vertical-align: middle;"> Importar Profesores</span>
                </a>
            </ul>
        </li>
        <!-- Profesores Dropdown end -->

        <!-- Asistencias actuales start -->
        <li class="nav-item <?= $act_asistencia ?>">
            <a class="nav-link" href="<?= $asistenciasAll ?>"> Asistencias actuales</a>
        </li>
        <!-- Asistencias actuales end -->

        <!-- Calendario escolar start -->
        <li class="nav-item <?= $act_cal_escolar ?>">
            <a class="nav-link" href="<?= $lectivos ?>"> Calendario escolar</a>
        </li>
        <!-- Calendario escolar end -->

        <!-- Fichaje Manual start -->
        <li class="nav-item <?= $act_fichar_manual ?>">
            <a class="nav-link" href="<?= $ficharManual ?>"> Fichaje Manual</a>
        </li>
    </ul>
    <!-- Fichaje Manual end -->

    <ul class="nav navbar-nav navbar-right">

        <!-- User Dropdown start -->
        <li class="nav-item dropdown <?= $act_usuario ?>">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                <i style="vertical-align: middle;" class="fa fa-user-o"></i>
                <?= $sessionName . ' ' . $notificacion . ' ' . $notificacion_alert ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-right bg-dark">
                <a class="dropdown-item text-light <?= $act_qr ?>" href="<?= $qrCode ?>">
                    <i style="font-size: 20px; vertical-align: middle;" class="fa fa-qrcode"></i>
                    <span style="vertical-align: middle;"> Activador de lector</span>
                </a>
                <a class="dropdown-item text-light <?= $act_admon ?>" id="admon" href="<?= $admon ?>">
                    <i id="admon-icon" style="font-size: 20px; vertical-align: middle;" class="fa fa-folder-o"></i>
                    <span style="vertical-align: middle;"> Administración</span>
                </a>
                <a class="dropdown-item text-light <?= $act_notification ?>" id="notif" href="<?= $notificaciones ?>">
                    <i id="notif-icon" style="font-size: 20px; vertical-align: middle;" class="fa fa-bell-o"></i>
                    <span style="vertical-align: middle;"> Notificaciones</span> <?= $notificacion_alert ?>
                </a>
                <a class="dropdown-item text-light <?= $act_changePass ?>" id="cambio-pass" href="<?= $cambioPass ?>">
                    <i id="cambio-pass-icon" style="font-size: 20px; vertical-align: middle;" class="fa fa-refresh"></i>
                    <span style="vertical-align: middle;"> Cambio de contraseña </span>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-light" id="info-horario" href="#">
                    <i id="info-horario-icon" style="font-size: 20px; vertical-align: middle;" class="fa fa-calendar-o"></i>
                    <span style="vertical-align: middle;"> Horario del centro</span>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-light" id="admin-guide" href="<?= $adminGuide ?>">
                    <i id="cambio-pass-icon" style="font-size: 20px; vertical-align: middle;" class="fa fa-cloud-download"></i>
                    <span style="vertical-align: middle;"> Guía de uso Administración</span>
                </a>
                <a class="dropdown-item text-light" id="profesor-guide" href="<?= $profesorGuide ?>">
                    <i id="cambio-pass-icon" style="font-size: 20px; vertical-align: middle;" class="fa fa-cloud-download"></i>
                    <span style="vertical-align: middle;"> Guía de uso Profesorado</span>
                </a>
            </ul>
        </li>
        <!-- User Dropdown end -->

        <li class="nav-item">
            <a class="nav-link" href="<?= $logout ?>"><i class="fa fa-sign-out"></i> Cerrar Sesión</a>
        </li>
    </ul>
    <script src="js/top-nav.js"></script>