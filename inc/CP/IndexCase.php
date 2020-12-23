<?php

require_once($dirs['CP'] . 'cp-header.php');

if (!$options['QR-reader']) {
    require_once($dirs['Qr'] . 'qr-reader-headers.php');
    echo '</head>';
    echo '<body>';
    echo '<div class="container-fluid" style="margin-top:50px">';
        echo "<div class='row'>";
            echo "<div id='qreader' class='col-xs-12'>";
                include($dirs['Qr'] . 'qr-webcam-admin-login.php');
            echo "</div>";
        echo "</div>";
    echo "</div>";
    include_once($dirs['public'] . 'js/qr-webcam-admin-login.js');
} else {
echo '<div class="container-fluid" style="margin-top:50px">';
    echo "<div class='row'>";
        echo "<div id='qreader' class='col-xs-12' style='margin-top: 20vh;'>";
            include($dirs['Qr'] . 'qr-reader-admin-login.php');
        echo "</div>";
    echo "</div>";
echo "</div>";
include_once($dirs['public'] . 'js/qr-reader-admin-login.js');
}
include($dirs['Interfaces'] . 'errors.php');
include($dirs['Interfaces'] . 'footer.php');