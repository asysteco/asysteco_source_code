<a class="navbar-brand" href="<?= $qrCode ?>"><?= $Titulo ?></a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#top-menu" aria-controls="top-menu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>
</div>

<div class="collapse navbar-collapse" id="top-menu">
    <ul class="navbar-nav mr-auto">

        <!-- Horario start -->
        <li class='nav-item <?= $act_horario ?>'>
            <a class='nav-link' href='<?= $horarios ?>'> Horario</a>
        </li>
        <!-- Horario end -->

        <!-- Guardias start -->
        <li class='nav-item <?= $act_guardias ?>'>
            <a class='nav-link' href='<?= $guardias ?>'> Guardias</a>
        </li>
        <!-- Guardias end -->

        <!-- Mis asistencias start -->
        <li class='nav-item <?= $act_asistencia ?>'>
            <a class='nav-link' href='<?= $asistenciasSession ?>'>Mis asistencias</a>
        </li>
        <li class='nav-item <?= $act_qr ?>'>
            <a class="nav-link" href="<?= $qrCode ?>">
                <span class="fa fa-qrcode"></span> Mi código QR
            </a>
        </li>
    </ul>
    <!-- Mis asistencias end -->

    <ul class="nav navbar-nav navbar-right">

        <!-- User Dropdown start -->
        <li class="nav-item dropdown <?= $act_usuario ?>">
            <a class='nav-link dropdown-toggle' data-toggle='dropdown' href='#'>
                <i style="vertical-align: middle;" class='fa fa-user-o'></i>
                <?= $sessionName ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-right bg-dark">
                <a id='cambio-pass' class='dropdown-item text-light <?= $act_changePass ?>' href='<?= $cambioPass ?>'>
                    <i id='cambio-pass-icon' style='font-size: 20px; vertical-align: middle;' class='fa fa-refresh'></i>
                    <span style='vertical-align: middle;'> Cambio de contraseña </span>
                </a>
                <div class="dropdown-divider"></div>
                <a id="profesor-guide" class="dropdown-item text-light" href="<?= $profesorGuide ?>">
                    <span id="download-guide-profesor-icon" style="font-size: 20px;" class="fa fa-cloud-download"></span> Guía de uso
                </a>
            </ul>
        </li>
        <!-- User Dropdown end -->

        <li class='nav-item'>
            <a class='nav-link' href='<?= $logout ?>'><i class='fa fa-sign-out'></i> Cerrar Sesión</a>
        </li>
    </ul>