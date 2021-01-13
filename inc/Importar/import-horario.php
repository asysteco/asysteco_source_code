<div class="container">
    <h1>Importar Horarios desde CSV</h1>
    <a id="toggleInfo" href="#" class="btn btn-info"><i class="fa fa-info-circle" aria-hidden="true"></i> Formato CSV</a>
    <div id="response"
        class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>">
        <?php if(!empty($message)) { echo $message; } ?>
    </div>
    <div class="outer-container">
    <div id="ayuda-formato" style="display: none; border-radius: 10px; padding: 10px; margin: 25px; box-shadow: 4px 4px 16px 0 #808080bf;">
        <h2 class="format-body">Formato Permitido</h2>
        <p class="format-body">El fichero CSV debe tener el siguiente formato para que sea aceptado correctamente:</p>
<pre style="margin: 25px; padding: 15px; border-radius: 10px; background-color: #e8e8e8;">GRUPO;INICIALES;AULA;DIA;HORA</pre>
        <div class="format-body">
            <b>GRUPO:</b> Nombre de Curso/Grupo<br>
            <b>INICIALES:</b> Iniciales correspondientes al profesor<br>
            <b>AULA:</b>Nombre del Aula<br>
            <b>DIA:</b> Correspondiente al día de la semana, escrito en números (1 = Lunes, etc.)<br>
            <b>HORA:</b> Número de referencia a la hora impartida <i>(Puede encontrar el <b>Nº de Referencia</b> en el apartado <b>"Horario del centro"</b> del desplegable <b>"<?= $_SESSION['Nombre'] ?>"</b> en el menú superior.)</i><br>
            <?php if (isset($options['edificios']) && $options['edificios'] > 1){ echo '<b>EDIFICIO:</b> Número del edificio en el que se impartirá la hora.<br>';}?>
            <h4>Ejemplo de formato correcto:</h4>
        </div>
        <?php
        if (isset($options['edificios']) && $options['edificios'] > 1){
          echo '<pre style="margin: 25px; background-color: #e8e8e8;">GRUPO;INICIALES;AULA;DIA;HORA;EDIFICIO
2ESOA;MRG;AU101;3;1;2
3BACHB;AAM;AU214;4;3;1</pre>';
        } else {
          echo '<pre style="margin: 25px; padding: 15px; border-radius: 10px; background-color: #e8e8e8;">GRUPO;INICIALES;AULA;DIA;HORA
2ESOA;MRG;AU101;3;1
3BACHB;AAM;AU214;4;3</pre>';
        }
    ?>
    <p class="format-body">Si lo desea, haciendo click al siguiente botón puede descargar una plantilla del formato en CSV: 
    <a href="index.php?ACTION=download&OPT=plantilla-horarios"><span style="font-size: 20px;" class="fa fa-cloud-download"></span></a></p>
    </div>
    <br>
    <form action="index.php?ACTION=horarios&OPT=" method="post"
            name="frmCSVImport" id="frmCSVImport"
            enctype="multipart/form-data">
        <div class="row">
            <div class="col-sm-5 mb-3">
                <div class="input-group">
                    <label id="fileName" class="custom-file-label" for="file">Subir CSV</label>
                    <input type="file" name="file" id="file" accept=".csv"  class="custom-file-input" required>
                </div>
            </div>
            <div class="col-sm-3 mb-3">
                <div class="input-group">
                  <select class="custom-select" name="Franja" title="Tipo de horario a importar">
                    <?php foreach ($franjasHorarias as $franja => $dato) { echo "<option value='$franja'>Horarios $franja</option>";} ?>
                  </select>
                </div>
            </div>
<?php
            if($response = $class->query("SELECT ID FROM Horarios")) {
                if($response->num_rows > 0) {
                    $fecha = date('Y-m-d');
                    echo '<div class="col-sm-3 mb-3">';
                    echo '<div class="input-group">';
                        echo '<div class="input-group-prepend">';
                            echo '<label for="fecha_incorpora" class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></label>';
                        echo '</div>';
                          echo '<input id="fecha_incorpora" type="text" class="form-control" name="fecha" placeholder="Fecha de incorporación de horarios" aria-label="Fecha de incorporación de horarios" autocomplete="off" required>';
                    echo '</div>';
                echo '</div>';
                }
            } else {
                $ERR_MSG = $class->ERR_ASYSTECO;
            }
?>
            <div class="col-sm-1 mb-3">
                <button type="submit" id="submit" name="import" class="btn btn-success" disabled>Importar</button>
            </div>
        </div>
    </form>
<?php
        if($num_horarios = $class->query("SELECT count(DISTINCT ID_PROFESOR) as numero, count(ID) as total FROM $class->horarios"))
        {
            $num = $num_horarios->fetch_assoc();
            echo "<h3>Horarios importados: $num[numero]</h3>";
            echo "<h3>Registros totales: $num[total]</h3>";
        }
        else
        {
            $ERR_MSG = $class->ERR_ASYSTECO;
        }
?>
      <div id="todos-registros"></div>
    </div>
</div>

<div id="file-content-modal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-buttons-header">
        <button type="button" class="btn btn-danger float-left" data-dismiss="modal">Cancelar cambios</button>
        <button type="button" class="btn btn-success import-data float-right">Importar</button>
      </div>
      <div class="modal-subtitle">
        <h3 class="modal-title" style="text-align: center;">Previsualización de los datos a importar</h3>
        <h6 class="modal-title" style="color: grey; text-align: center;"><i>
          *El fichero no será importado, si se encuentra una línea de color: <div style="display: inline-block; width: 25px; height: 10px; background: #ff9797;"></div> .
          <br>
          *Si los datos mostrados no coinciden con los que se desea importar, cancele el proceso y modifique su fichero CSV.
        </i></h6>
      </div>
    <div class='modal-body'>
      <div class='container-fluid'>
        <div class='row'>
          <div class='col-12'>
            <table class="table-striped" style="width: 100%;">
              <thead>
                <tr>
                  <th>Línea</th>
                  <th>Grupo</th>
                  <th>Iniciales</th>
                  <th>Aula</th>
                  <th>Día</th>
                  <th>Hora</th>
                  <?php
                  if (isset($options['edificios']) && $options['edificios'] > 1) {
                    echo '<th>Edificio</th>';
                  }
                  ?>
                </tr>
              </thead>
                <tbody id="file-content-preview"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
      <div class="modal-buttons-footer">
        <button type="button" class="btn btn-danger float-left" data-dismiss="modal">Cancelar cambios</button>
        <button type="button" class="btn btn-success import-data float-right">Importar</button>
      </div>
    </div>
  </div>
</div>

<script src="js/import-horario.js"></script>
<script src="js/preview-import-horario.js"></script>