<div class="container" id="botonera" style="margin-top:75px">
    <div class="row"> 
        <div class="col-xs-12">
        <input id='fechainicio' class='form-control' type='text' placeholder='Fecha Inicio'> <input id='fechafin' class='form-control' type='text' placeholder='Fecha Fin'>
        <?php
                if($response = $class->query("SELECT ID, Nombre FROM $class->profesores WHERE TIPO <>1 ORDER BY Nombre"))
                {
                    if($response->num_rows > 0)
                    {
                        echo "<select id='select_admon' name='Profesor' class='form-control'>";
                        echo "<option value=''> Selecciona un profesor... </option>";
                        while($fila = $response->fetch_assoc())
                        {
                            echo "<option value='$fila[ID]'> $fila[Nombre] </option>";
                        }
                        echo "</select>";
                    }
                }
                else
                {
                    $ERR_MSG = $class->ERR_ASYSTECO;
                }
            ?>
            <h2>Exportar a Excel</h2>
            <a enlace="index.php?ACTION=admon&OPT=select&export=marcajes" id="exportmarcajes" class="btn btn-info btn-export"><span class="glyphicon glyphicon-open"></span> Marcajes</a>
            <a enlace="index.php?ACTION=admon&OPT=select&export=asistencias" id="exportasistencias" class="btn btn-info btn-export"><span class="glyphicon glyphicon-open"></span> Asistencias</a>
            <a enlace="index.php?ACTION=admon&OPT=select&export=faltas" id="exportfaltas" class="btn btn-info btn-export"><span class="glyphicon glyphicon-open"></span> Faltas</a>
            <a enlace="index.php?ACTION=admon&OPT=select&export=horarios" id="exporthorarios" class="btn btn-info btn-export"><span class="glyphicon glyphicon-open"></span> Horarios</a>
            <a enlace="index.php?ACTION=admon&OPT=select&export=profesores" id="exportprofesores" class="btn btn-info btn-export"><span class="glyphicon glyphicon-open"></span> Profesores</a>
            </br>
            <h2>Mostrar en Pantalla</h2>
            <!--a enlace="index.php?ACTION=admon&OPT=select&select=marcajes&pag=0" id='filtromarcaje' class="btn btn-success btn-select"><span class="glyphicon glyphicon-eye-open"></span> Marcajes</a-->
            <a enlace="index.php?ACTION=admon&OPT=select&select=asistencias&pag=0"id='filtroasistencias' class="btn btn-success btn-select"><span class="glyphicon glyphicon-eye-open"></span> Asistencias</a>
            <a enlace="index.php?ACTION=admon&OPT=select&select=faltas&pag=0" id='filtrofaltas' class="btn btn-success btn-select"><span class="glyphicon glyphicon-eye-open"></span> Faltas</a>
            <a enlace="index.php?ACTION=admon&OPT=select&select=horarios&pag=0" id='filtrohorarios' class="btn btn-success btn-select"><span class="glyphicon glyphicon-eye-open"></span> Horarios</a>
            <a enlace="index.php?ACTION=admon&OPT=select&select=fichadi&pag=0" class="btn btn-success btn-select"><span class="glyphicon glyphicon-calendar"></span> Fichajes de hoy</a>
            <a enlace="index.php?ACTION=admon&OPT=select&select=fichafe&pag=0" id='filtrofichajes' class="btn btn-success btn-select"><span class="glyphicon glyphicon-calendar"></span> Fichaje Por Fechas</a>
            </br>
            <h2>Borrado de Datos (Atención estas acciones son <b>IRREVERSIBLES</b>)</h2>
            <a enlace="index.php?ACTION=profesores&OPT=delete-all" class="btn btn-danger eliminar" elemento="profesores"><span class="glyphicon glyphicon-user"></span> Borrar Profesores</a>&nbsp; &nbsp;
            <a enlace="index.php?ACTION=horarios&OPT=delete-all" class="btn btn-danger eliminar" elemento="horarios"><span class="glyphicon glyphicon-calendar"></span> Borrar Horarios</a>&nbsp; &nbsp;
            <!--a enlace="index.php?ACTION=admon&OPT=" class="btn btn-danger eliminar" onclick="deshabilitado()"><span class="glyphicon glyphicon-envelope"></span> Borrar Mensajes</a-->
            </br>
        <div class="col-xs-12">
            <div id="btn-response"></div>
        </div>
    </div>
</div>
<div id="loading" class="col-xs-12" style="position: absolute; top: 0; left: 0; width: 100%; height: 100vh; text-align: center; z-index: -1;">
    <div class="caja" style="margin-top: 35vh; display: inline-block; padding: 25px; background-color: white; border-radius: 10px; box-shadow: 4px 4px 16px 0 #808080bf;">
        <div>
            <img src="resources/img/loading.gif" alt="Cargando...">
            <h2 id="loading-msg"></h2>
        </div>
    </div>
</div>

<div id="error-modal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" style="color: red;">Error!</h4>
      </div>
      <div class="modal-body">
        <div id="error-content-modal" style="color: red;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<div id="fine-modal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Correcto!</h4>
      </div>
      <div class="modal-body">
        <div id="fine-content-modal" style="color: green;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<script language="JavaScript">
    function deshabilitado() 
    {
        alert("Boton temporalmente deshabilitado, disculpen las molestias");
    }
</script>
<script src="js/admon_delete.js"></script>