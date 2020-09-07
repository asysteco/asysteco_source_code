<div class="container" style="margin-top:50px">
    <h2>Importar Profesores desde CSV</h2>
    <div id="response"
        class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>">
        <?php if(!empty($message)) { echo $message; } ?>
    </div>
    <div class="outer-scontainer">
<?php

    if($class->query("SELECT ID FROM $class->profesores")->num_rows > 1)
    {
        echo "<h3>No se pueden importar más ficheros de profesores, puede dar de alta nuevos profesores en profesores -> Registrar profesor...</h3>";
    }
    else
    {
        echo '<form class="form-horizontal" action="index.php?ACTION=profesores&OPT=import-csv" method="post"
            name="frmCSVImport" id="frmCSVImport"
            enctype="multipart/form-data">
            <div class="input-row">
                <label id="import-manual-trigger">Subir documento CSV:</label><br />
                <input type="file" name="file" id="file" accept=".csv" class="btn btn-link">
                <button type="submit" id="submit" name="import" class="btn btn-success">Importar</button>
                <br />
            </div>
        </form>';
    }

        if($num_profesores_act = $class->query("SELECT count(DISTINCT ID) as activos FROM $class->profesores WHERE Activo=1"))
        {
            $num_act = $num_profesores_act->fetch_assoc();
        }
        else
        {
            $ERR_MSG = $class->ERR_ASYSTECO;
        }
        
        if($num_profesores_all = $class->query("SELECT count(ID) as total FROM $class->profesores"))
        {
            $num_all = $num_profesores_all->fetch_assoc();
        }
        else
        {
            $ERR_MSG = $class->ERR_ASYSTECO;
        }
        echo "<h3>Profesores totales: $num_all[total]</h3>";
        echo "<h3>Profesores activos: $num_act[activos]</h3>";
        echo "<a id='btn-todos-registros-prof' class='btn btn-info'>Ver todos los registros</a>";
?>
        <div id="todos-registros"></div>
        <div class="row">
            <div class="col-xs-12">
                <div id="loading" style='text-align: center; position: absolute; width: 100%; height: 100%;'>
                    <img style="text-align: center; background-color: transparent;" src="resources/img/loading.gif" alt="Cargando...">
                    <h2 id="loading-msg"></h2>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    include_once($dirs['public'] . 'js/import-profesorado.js');