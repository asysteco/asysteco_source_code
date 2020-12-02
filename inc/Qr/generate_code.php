<?php

include_once($dirs['inc'] . 'Helper/mcript.php');
if (isset($_SESSION['ID']) && !empty($_SESSION['ID'])) {
    echo '
        <div class="container" style="margin-top:50px">
            <div class="row" style="text-align: center;">
                <div class="col-xs-12">
                <h3>Código de fichaje</h3>
        ';
    $dato_encriptado = $encriptar($_SESSION['ID']);
    if (isset($options['GoogleQR']) && $options['GoogleQR'] == 1) {
        echo '<img class="img-thumbnail" src="https://chart.googleapis.com/chart?chs=500x500&cht=qr&chl=' . urlencode($dato_encriptado) . '&choe=UTF-8" title="Código QR" />';
    } else {
        include_once($dirs['inc'] . 'phpqrcode/qrlib.php');
        $codesDir = "tmp/";
        $codeFile = uniqid() . '.png';
        QRcode::png($dato_encriptado, $codesDir . $codeFile, 'H', '10');
        echo '<img class="img-thumbnail" src="' . $codesDir . $codeFile . '" />';
    }

    if ($_SESSION['Perfil'] == 'Admin') {
        echo "<br><br><span>* Acerque el código al lector QR para activarlo.</span>";
    } else {
        echo "<br><br><span>* Acerque el código al lector QR para fichar.</span>";
    }
    echo "<div id='clean_tmp' class='hidden'></div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    include_once($dirs['public'] . 'js/clean_tmp.js');
} else {
    echo $class->ERR_ASYSTECO;
}
