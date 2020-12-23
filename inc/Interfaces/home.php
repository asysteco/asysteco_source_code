<?php

if ($_SESSION['Perfil'] === 'Admin') {
    if ($options['QR-reader']) {
        echo '<div class="container-fluid">';
        echo "<div class='row'>";
        echo "<div id='qreader' class='col-12 col-md-4' style='padding-top: 35vh;'>";
        include($dirs['Qr'] . 'qr-reader.php');
        echo "</div>";
        echo "<div class='col-12 col-md-8' style='text-align: center;'>";
        include($dirs['Horarios'] . 'guardias.php');
        echo "</div>";
        echo "</div>";
        echo "</div>";
        include_once($dirs['public'] . 'js/qr-reader.js');
        if ($options['autoscroll']) {
            include_once($dirs['public'] . 'js/scroller-interaction.js');
        }
        echo '
            <script>
                $("nav").hide();
            </script>
        ';
    } else {
        echo '<div class="container-fluid">';
        echo "<div class='row'>";
        echo "<div id='qreader' class='col-12 col-md-4' style='padding-top: 20vh;'>";
        include($dirs['Qr'] . 'qr-webcam.php');
        echo "</div>";
        echo "<div class='col-12 col-md-8' style='text-align: center;'>";
        include($dirs['Horarios'] . 'guardias.php');
        echo "</div>";
        echo "</div>";
        echo "</div>";
        include_once($dirs['public'] . 'js/qr-webcam.js');
        if ($options['autoscroll']) {
            include_once($dirs['public'] . 'js/scroller-interaction.js');
        }
        echo '
            <script>
            $("nav").hide();
            </script>
        ';
    }
} else {
    include_once($dirs['Interfaces'] . 'top-nav.php');
    echo '<div class="container-fluid">';
    echo "<div class='row'>";
    echo "<div class='col-12' style='text-align: center;'>";
    include($dirs['Horarios'] . 'guardias.php');
    echo "</div>";
    echo "</div>";
    echo "</div>";
}
