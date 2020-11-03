<div class="container" style="margin-top:50px">
    <h2>Importar Profesores desde CSV</h2>
    <div id="response"
        class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>">
        <?php if(!empty($message)) { echo $message; } ?>
    </div>
    <div class="outer-scontainer">
    
    <div id="ayuda-formato" style="border-radius: 10px; padding: 10px; margin: 25px; box-shadow: 4px 4px 16px 0 #808080bf;">
        <h2>Formato Permitido</h2>
        <p>El fichero CSV debe tener el siguiente formato para que sea aceptado correctamente:
<pre style="margin: 25px; box-shadow: 4px 4px 16px 0 #808080bf;">INICIALES;NOMBRE;TUTOR</pre>
            <b>INICIALES:</b> Iniciales correspondientes al nombre y apellidos del profesor<br>
            <b>NOMBRE:</b> Nombre completo del profesor<br>
            <b>TUTOR:</b> Nombre del grupo tutelado, si no existe grupo, escribir No<br>
            <h4>Ejemplo de formato correcto:</h4>
<pre style="margin: 25px; box-shadow: 4px 4px 16px 0 #808080bf;">INICIALES;NOMBRE;TUTOR  <span style="color:red;"><-- La cabecera es obligatoria y debe ser la primera línea</span>
AAM;Antonio Alarcón Muñoz;No
CRL;Carolina Rodríguez López;3ESOA</pre>
<p class="format-body">Si lo desea, haciendo click al siguiente botón puede descargar una plantilla del formato en CSV: 
    <a href="index.php?ACTION=plantilla-profesores"><span class="glyphicon glyphicon-download-alt"></span></a></p>
    </p>
    </div>
    <br>
<?php
        echo '<form class="form-horizontal" action="index.php?ACTION=profesores&OPT=" method="post"
            name="frmCSVImport" id="frmCSVImport"
            enctype="multipart/form-data">
            <div class="input-row">
                <label id="import-manual-trigger">Subir documento CSV:</label><br />
                <input type="file" name="file" id="file" accept=".csv" class="btn btn-link" required>
                <button type="submit" id="submit" name="import" class="btn btn-success">Importar</button>
                <br />
            </div>
        </form>';
    

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
        //echo "<a id='btn-todos-registros-prof' class='btn btn-info'>Ver todos los registros</a>";
?>
        <div id="todos-registros"></div>
        <div id="loading" class="col-xs-12" style="position: absolute; top: 0; left: 0; width: 100%; height: 100vh; text-align: center; z-index: -1;">
            <div class="caja" style="margin-top: 35vh; display: inline-block; padding: 25px; background-color: white; border-radius: 10px; box-shadow: 4px 4px 16px 0 #808080bf;">
                <div>
                    <img src="resources/img/loading.gif" alt="Cargando...">
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
            <button type="button" class="btn btn-success import-data">Importar</button>
        </div>
      <div class='modal-body'>
        <div class='container-fluid'>
          <div class='row'>
            <div class='col-xs-12'>
              <table class="table-striped" style="width: 100%;">
                <thead>
                  <tr>
                    <th>Línea</th>
                    <th>Iniciales</th>
                    <th>Nombre</th>
                    <th>Tutor</th>
                  </tr>
                </thead>
                <tbody id="file-content-preview"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar cambios</button>
        <button type="button" class="btn btn-success import-data">Importar</button>
      </div>
    </div>
  </div>
</div>

<script>
<?php
    include_once($dirs['public'] . 'js/preview-import-profesores.js');
?>
</script>