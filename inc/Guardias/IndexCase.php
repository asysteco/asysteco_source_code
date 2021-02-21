<?php

if ($class->isLogged($Titulo)) {
    if ($class->compruebaCambioPass()) {
      $act_guardias = 'active';
      $scripts = '<link rel="stylesheet" href="css/qr-reader.css">';

      if (!$options['QR-reader']) {
        $scripts .= '
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
      }

      include_once($dirs['Interfaces'] . 'header.php');
      include_once($dirs['Guardias'] . 'interface.php');
      include_once($dirs['Interfaces'] . 'footer.php');
    } else {
      header('Location: index.php?ACTION=primer_cambio');
    }
} else {
    $MSG = "Debes iniciar sesión para realizar esta acción.";
    header("Refresh:2; url=index.php");
    include_once($dirs['Interfaces'] . 'msg_modal.php');
}