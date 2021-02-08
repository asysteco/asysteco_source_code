<!DOCTYPE html>
<html lang="es">
<head>
    <title>Activar Lector</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <link rel="stylesheet" href="css/bootstrap-4.0.0/bootstrap.min.css">
    <link rel="stylesheet" href="css/asysteco.css">
    <link rel="stylesheet" href="js/jquery-ui/jquery-ui.min.css">
    <link rel="shortcut icon" href="resources/img/asysteco.ico" type="image/x-icon">
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/bootstrap-4.0.0/bootstrap.min.js"></script>
    <script src="js/jquery-ui/jquery-ui.min.js"></script>
    <script src="js/datepicker_common.js"></script>
    <script src="js/flecha.js"></script>
    <link rel="stylesheet" href="css/qr-reader.css">

    <script>
    var userAgent = navigator.userAgent.toLowerCase();
    var isSupportedBrowser = (/armv.* raspbian chromium/i).test(userAgent);
    if(! isSupportedBrowser)
    {
        location.href = "index.php";
    }
    </script>

<?php if (!$options['QR-reader']) { ?>

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
</head>
<?php } ?>