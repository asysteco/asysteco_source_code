<?php

include_once($dirs['Helper'] . 'mcript.php');
if (isset($_SESSION['ID']) && !empty($_SESSION['ID'])) {
    echo '
        <div class="container">
            <div class="row" style="text-align: center;">
                <div class="col-12">';
            echo $_SESSION['Perfil'] == 'Admin' ? '<h1>Código activador</h1>' : '<h1>Código de fichaje</h1>';
            
    $dato_encriptado = $encriptar($_SESSION['ID']);
    if (isset($options['GoogleQR']) && $options['GoogleQR'] == 1) {
        echo '<img class="img-thumbnail" src="https://chart.googleapis.com/chart?chs=500x500&cht=qr&chl=' . urlencode($dato_encriptado) . '&choe=UTF-8" title="Código QR" />';
    } else {
        include_once($dirs['phpqrcode'] . 'qrlib.php');
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
    echo "<div id='clean_tmp' class='d-none'></div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    include_once($dirs['public'] . 'js/clean_tmp.js');
} else {
    echo $class->ERR_ASYSTECO;
}
