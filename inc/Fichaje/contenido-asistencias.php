<div class="container-fluid" style="margin-top:50px">
    <div class='row' style='text-align: center;'>
        <div class='col-xs-12 col-md-1'></div>
        <div class='col-xs-12 col-md-3'>
<?php
        include_once($dirs['Fichaje'] . 'fichajes.php');
    echo "</div>";
    echo "<div class='col-xs-12 col-md-7'>";
        include_once($dirs['Fichaje'] . 'faltas_profesor.php');
?>
        <div class='col-xs-12 col-md-1'></div>
        </div>
    </div>
</div>

<div id="loading" class="col-xs-12" style="position: absolute; top: 0; left: 0; width: 100%; height: 100vh; text-align: center;">
    <div class="caja" style="margin-top: 35vh; display: inline-block; padding: 25px; background-color: white; border-radius: 10px; box-shadow: 4px 4px 16px 0 #808080bf;">
        <div>
            <img src="resources/img/loading.gif" alt="Cargando...">
            <h2 id="loading-msg"></h2>
        </div>
    </div>
</div>
<script src="js/update_marcajes.js"></script>