<div class="container" style="margin-top:50px">
    <h2>Importar Horarios desde CSV</h2>
    <div id="response"
        class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>">
        <?php if(!empty($message)) { echo $message; } ?>
    </div>
    <div class="outer-container">
    <div id="ayuda-formato" style="border-radius: 10px; padding: 10px; margin: 25px; box-shadow: 4px 4px 16px 0 #808080bf;">
        <h2 class="format-body">Formato Permitido</h2>
        <p class="format-body">El fichero CSV debe tener el siguiente formato para que sea aceptado correctamente:</p>
<pre style="margin: 25px; box-shadow: 4px 4px 16px 0 #808080bf;">GRUPO;INICIALES;AULA;DIA;HORA</pre>
        <div class="format-body">
            <b>GRUPO:</b> Nombre de Curso/Grupo<br>
            <b>INICIALES:</b> Iniciales correspondientes al profesor<br>
            <b>AULA:</b><br>
            <ul>
                <li>Los dos primeros caracteres corresponden al tipo de <b>aula</b>. (AU: Aula; DP: Departamento; GU: Guardia)</li>
                <li>El tercer caracter correspone al <b>edificio</b>. (Si el centro cuenta con un solo edificio se pondrá 1)</li>
                <li>Los dos últimos corresponden al <b>número del aula</b>.</li>
            </ul>
            <b>DIA:</b> Correspondiente al día de la semana, escrito en números (1 = Lunes, etc.)<br>
            <b>HORA:</b> Número referente a la hora impartida (4 Está considerada "Recreo", por lo tanto, son 7 horas en total)<br>
            <h4>Ejemplo de formato correcto:</h4>
        </div>
<pre style="margin: 25px; box-shadow: 4px 4px 16px 0 #808080bf;">GRUPO;INICIALES;AULA;DIA;HORA  <span style="color:red;"><-- La cabecera es obligatoria y debe ser la primera línea</span>
2ESOA;MRG;AU101;3;1
3BACHB;AAM;AU214;4;3</pre>
    </div>
    <br>
        <form class="form-inline" action="index.php?ACTION=horarios&OPT=" method="post"
            name="frmCSVImport" id="frmCSVImport"
            enctype="multipart/form-data">
            <div class="input-row">
<?php
            if($response = $class->query("SELECT ID FROM $class->horarios"))
            {
                if($response->num_rows > 0)
                {
                    $fecha = date('Y-m-d');
                    echo '
                    <label id="import-manual-trigger" for="file">Subir documento CSV:</label><br />
                    <input type="file" name="file" id="file" accept=".csv" class="form-control" style="display: inline-block;" required>';
                    echo " <select name='Franja' class='form-control' title='Tipo de horario a importar'>";
                    foreach ($franjasHorarias as $franja => $dato) {
                        echo "<option value='$franja'>Horarios $franja</option>";
                    }
                    echo "</select>";
                    echo ' <input id="fecha_incorpora" style="display: inline-block; width: 25%;" type="text" class="form-control" name="fecha" placeholder="Fecha de incorporación de horarios" autocomplete="off" required>';
                }
                else
                {
                    echo '
                    <label id="import-manual-trigger" for="file">Subir documento CSV:</label><br />
                    <input type="file" name="file" id="file" accept=".csv" class="form-control" required>
                    ';
                    echo " <select name='Franja' class='form-control' title='Tipo de horario a importar'>";
                    foreach ($franjasHorarias as $franja => $dato) {
                        echo "<option value='$franja'>Horarios $franja</option>";
                    }
                    echo "</select> ";
                }
            }
            else
            {
                $ERR_MSG = $class->ERR_ASYSTECO;
            }
?>
                <button type="submit" id="submit" name="import" class="btn btn-success">Importar</button>
                <br />
            </div>
        </form>
    </div>
<?php
        if($num_horarios = $class->query("SELECT count(DISTINCT ID_PROFESOR) as numero, count(ID) as total FROM $class->horarios"))
        {
            $num = $num_horarios->fetch_assoc();
            echo "<h3>Horarios importados: $num[numero]</h3>";
            echo "<h3>Registros totales: $num[total]</h3>";
            echo "<a id='btn-todos-registros' class='btn btn-info'>Ver todos los registros</a>";
        }
        else
        {
            $ERR_MSG = $class->ERR_ASYSTECO;
        }
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

<div id="file-content-modal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header" style="text-align: right;">
            <h2 class="modal-title" style="text-align: center;">Previsualización de los datos a importar</h2>
            <h5 class="modal-title" style="color: grey; text-align: center;">*Si los datos mostrados no coinciden con los que se van a importar, cancele el proceso y modifique su fichero CSV</h5>
            <br>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar cambios</button>
            <button type="button" class="btn btn-success import-data">Continuar</button>
        </div>
        <div id="file-content-preview"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar cambios</button>
        <button type="button" class="btn btn-success import-data">Continuar</button>
      </div>
    </div>
  </div>
</div>

<script>
<?php
 include_once($dirs['public'] . 'js/preview-import-horario.js');
?>
</script>