<?php

require_once($dirs['CP'] . 'cp-header.php');

if (!$options['QR-reader']) {
    require_once($dirs['Qr'] . 'webcam-headers.php');
    echo '</head>';
    echo '<body>';
    echo '<div class="container-fluid" style="margin-top:50px">';
        echo "<div class='row'>";
            echo "<div id='qreader' class='col-12'>";
                include_once($dirs['Qr'] . 'Admin/webcam-form.php');
            echo "</div>";
        echo "</div>";
    echo "</div>";
    include_once($dirs['public'] . 'js/qr-webcam-admin-login.js');
} else {
    echo '<div class="container-fluid" style="margin-top:50px">';
        echo "<div class='row'>";
            echo "<div id='qreader' class='col-12' style='margin-top: 20vh;'>";
                include_once($dirs['Qr'] . 'Admin/reader-form.php');
            echo "</div>";
        echo "</div>";
    echo "</div>";
    include_once($dirs['public'] . 'js/qr-reader-admin-login.js');
}
include_once($dirs['Interfaces'] . 'footer.php');