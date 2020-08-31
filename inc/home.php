<?php

$scripts = '
<script type="text/javascript" src="js/jsqrcode/grid.js"></script>
<script type="text/javascript" src="js/jsqrcode/version.js"></script>
<script type="text/javascript" src="js/jsqrcode/detector.js"></script>
<script type="text/javascript" src="js/jsqrcode/formatinf.js"></script>
<script type="text/javascript" src="js/jsqrcode/errorlevel.js"></script>
<script type="text/javascript" src="js/jsqrcode/bitmat.js"></script>
<script type="text/javascript" src="js/jsqrcode/datablock.js"></script>
<script type="text/javascript" src="js/jsqrcode/bmparser.js"></script>
<script type="text/javascript" src="js/jsqrcode/datamask.js"></script>
<script type="text/javascript" src="js/jsqrcode/rsdecoder.js"></script>
<script type="text/javascript" src="js/jsqrcode/gf256poly.js"></script>
<script type="text/javascript" src="js/jsqrcode/gf256.js"></script>
<script type="text/javascript" src="js/jsqrcode/decoder.js"></script>
<script type="text/javascript" src="js/jsqrcode/qrcode.js"></script>
<script type="text/javascript" src="js/jsqrcode/findpat.js"></script>
<script type="text/javascript" src="js/jsqrcode/alignpat.js"></script>
<script type="text/javascript" src="js/jsqrcode/databr.js"></script>
';
$style = '
    canvas {box-shadow: 4px 6px 15px grey; padding: 2px; border-radius: 10px;}
    .respuesta span {display: block; box-shadow: 4px 6px 15px grey; padding: 50px; border-radius: 10px; margin-top: 30px;}

    .filtro_edificio {
        margin-top: 50px;
    }
    #qreader {
        text-align: center;
    }
';
include($dirs['inc'] . 'top-nav.php');
include($dirs['inc'] . 'contenido-home.php');
if($_SESSION['Perfil'] === 'Admin')
{
    echo "<div class='row'>";
        echo "<div id='qreader' class='col-xs-12 col-md-4' >";
            echo "<h3>Fichaje</h3>";
            include($dirs['inc'] . 'qr-reader.php');
        echo "</div>";
        echo "<div class='col-xs-12 col-md-8' style='text-align: center;'>";
            include($dirs['inc'] . 'filtro-edif-guardias.php');
            include($dirs['inc'] . 'contenido-guardias.php');
        echo "</div>";
    echo "</div>";
    include($dirs['inc'] . 'errors.php');
    include($dirs['inc'] . 'footer.php');
}
else
{
    echo "<div class='row'>";
        echo "<div class='col-xs-12' style='text-align: center;'>";
            include($dirs['inc'] . 'filtro-edif-guardias.php');
            include($dirs['inc'] . 'contenido-guardias.php');
        echo "</div>";
    echo "</div>";
    include($dirs['inc'] . 'errors.php');
    include($dirs['inc'] . 'footer.php');
}