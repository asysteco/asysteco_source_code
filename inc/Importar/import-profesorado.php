<div class="container">
    <h1>Importar Profesores desde CSV</h1>
    <a id="toggleInfo" href="#" class="btn btn-info"><i class="fa fa-info-circle" aria-hidden="true"></i> Formato CSV</a>
    <div id="response"
        class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>">
        <?php if(!empty($message)) { echo $message; } ?>
    </div>
    <div class="outer-scontainer">
    
    <div id="ayuda-formato" style="display: none; border-radius: 10px; padding: 10px; margin: 25px; box-shadow: 4px 4px 16px 0 #808080bf;">
        <h2 class="format-body">Formato Permitido</h2>
        <p class="format-body">El fichero CSV debe tener el siguiente formato para que sea aceptado correctamente:
<pre style="margin: 25px; border-radius: 10px; padding: 15px; background-color: #e8e8e8;">INICIALES;NOMBRE;TUTOR</pre>
        <div class="format-body">
            <b>INICIALES:</b> Iniciales correspondientes al nombre y apellidos del profesor<br>
            <b>NOMBRE:</b> Nombre completo del profesor<br>
            <b>TUTOR:</b> Nombre del grupo tutelado, si no existe grupo, escribir No<br>
            <h4>Ejemplo de formato correcto:</h4>
        </div>
<pre style="margin: 25px; border-radius: 10px; padding: 15px; background-color: #e8e8e8;">INICIALES;NOMBRE;TUTOR
AAM;Antonio Alarcón Muñoz;No
CRL;Carolina Rodríguez López;3ESOA</pre>
<p class="format-body">Si lo desea, haciendo click al siguiente botón puede descargar una plantilla del formato en CSV: 
    <a href="index.php?ACTION=download&OPT=plantilla-profesores"><span style="font-size: 20px;" class="fa fa-cloud-download"></span></a></p>
    </p>
    </div>
    <br>
    <form action="index.php?ACTION=profesores&OPT=" method="post"
            name="frmCSVImport" id="frmCSVImport"
            enctype="multipart/form-data">
        <div class="row">
            <div class="col-sm-5 mb-3">
                <div class="input-group">
                    <label id="fileName" class="custom-file-label" for="file">Subir CSV</label>
                    <input type="file" name="file" id="file" accept=".csv"  class="custom-file-input" required>
                </div>
            </div>
            <div class="col-sm-1 mb-3">
                <button type="submit" id="submit" name="import" class="btn btn-success" disabled>Importar</button>
            </div>
        </div>
    </form>
<?php
    

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
    </div>
</div>

<div id="file-content-modal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
      <div class="modal-buttons-footer">
        <button type="button" class="btn btn-danger float-left" data-dismiss="modal">Cancelar cambios</button>
        <button type="button" class="btn btn-success import-data float-right">Importar</button>
      </div>
    </div>
  </div>
</div>

<script src="js/import-profesorado.js"></script>
<script src="js/preview-import-profesores.js"></script>