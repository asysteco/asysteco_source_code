<?php

if($_SESSION['Perfil'] === 'Admin')
{
    if ($options['QR-reader']) {
        echo '<div class="container-fluid" style="margin-top:50px">';
            echo "<div class='row'>";
            echo "<div id='qreader' class='col-xs-12 col-md-4' >";
                include($dirs['inc'] . 'Qr/qr-reader.php');
            echo "</div>";
            echo "<div class='col-xs-12 col-md-8' style='text-align: center;'>";
                include($dirs['inc'] . 'Horarios/guardias.php');
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
        echo '<div class="container-fluid" style="margin-top:50px">';
            echo "<div class='row'>";
            echo "<div id='qreader' class='col-xs-12 col-md-4' >";
                include($dirs['inc'] . 'Qr/qr-webcam.php');
            echo "</div>";
            echo "<div class='col-xs-12 col-md-8' style='text-align: center;'>";
                include($dirs['inc'] . 'Horarios/guardias.php');
            echo "</div>";
            echo "</div>";
        echo "</div>"; 
        include_once($dirs['public'] . 'js/qr-webcam.js');
        echo '
            <script>
            $("nav").hide();
            </script>
        ';
    }
}
else
{
echo '<div class="container-fluid" style="margin-top:50px">';
    echo "<div class='row'>";
        echo "<div class='col-xs-12' style='text-align: center;'>";
            include($dirs['inc'] . 'Horarios/guardias.php');
        echo "</div>";
    echo "</div>";
echo "</div>";
}