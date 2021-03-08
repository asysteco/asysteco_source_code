<?php

if ($_SESSION['Perfil'] === 'Admin') {
    if ($options['QR-reader']) {

?>
        <div class="container-fluid">
            <div class='row'>
                <div id='qreader' class='col-12 col-md-4' style='padding-top: 35vh;'>
                    <?php include_once($dirs['Qr'] . 'Guardias/reader.php'); ?>
                </div>
                <div class='col-12 col-md-8' style='text-align: center;'>
                    <?php include_once($dirs['Guardias'] . 'guardias.php'); ?>
                </div>
            </div>
        </div>

        <?php if ($options['autoscroll']) { ?>
            <script src="js/scroller-interaction.js"></script>
        <?php }
    } else {
        ?>
        <div class="container-fluid">
            <div class='row'>
                <div id='qreader' class='col-12 col-md-4' style='padding-top: 20vh;'>
                    <?php include_once($dirs['Qr'] . 'Guardias/webcam.php'); ?>
                </div>
                <div class='col-12 col-md-8' style='text-align: center;'>
                    <?php include_once($dirs['Guardias'] . 'guardias.php'); ?>
                </div>
            </div>
        </div>

        <?php if ($options['autoscroll']) { ?>
            <script src="js/scroller-interaction.js"></script>
        <?php } 
    }
} else {
    include_once($dirs['Interfaces'] . 'top-nav.php');
    ?>
    <div class="container-fluid">
        <div class='row'>
            <div class='col-12' style='text-align: center;'>
                <h1>Guardias disponibles</h1>
                <?php include_once($dirs['Guardias'] . 'guardias.php'); ?>
            </div>
        </div>
    </div>
<?php } ?>