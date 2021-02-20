<div class="container-fluid">
    <div class='row' style='text-align: center;'>
        <div class="col-12">
            <h3>Filtrar por fecha</h3>
            <input id='busca_asiste' class='fadeIn w-25' type='text' placeholder='Seleccionar fecha para filtrar...' autocomplete='off' style="min-width: 250px;">
        </div>
        <div class='col-12 col-md-6'>
<?php
        include_once($dirs['Asistencias'] . 'fichajes_all.php');
    echo "</div>";
    echo "<div class='col-12 col-md-6'>";
        include_once($dirs['Asistencias'] . 'faltas_all.php');
?>
        </div>
    </div>
</div>
<script src="js/filtro_asistencias.js"></script>
