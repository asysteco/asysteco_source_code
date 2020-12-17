<div class="container" id="botonera">
    <div class="row">
        <div class="col-12">
        <h1>Panel de administración</h1>
        <input id='fechainicio' class='form-control' type='text' placeholder='Fecha Inicio'> <input id='fechafin' class='form-control' type='text' placeholder='Fecha Fin'>
        <?php
                if($response = $class->query("SELECT ID, Nombre FROM $class->profesores WHERE TIPO <>1 ORDER BY Nombre"))
                {
                    if($response->num_rows > 0)
                    {
                        echo "<select id='select_profesor' name='Profesor' class='form-control'>";
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
            <a data-item="marcajes" action="export" id="exportmarcajes" class="btn btn-info btn-export act"><span style="font-size: 20px;" class="fa fa-cloud-download"></span> Marcajes</a>
            <a data-item="asistencias" action="export" id="exportasistencias" class="btn btn-info btn-export act"><span style="font-size: 20px;" class="fa fa-cloud-download"></span> Asistencias</a>
            <a data-item="faltas" action="export" id="exportfaltas" class="btn btn-info btn-export act"><span style="font-size: 20px;" class="fa fa-cloud-download"></span> Faltas</a>
            <a data-item="horarios" action="export" id="exporthorarios" class="btn btn-info btn-export act"><span style="font-size: 20px;" class="fa fa-cloud-download"></span> Horarios</a>
            <a data-item="profesores" action="export" id="exportprofesores" class="btn btn-info btn-export act"><span style="font-size: 20px;" class="fa fa-cloud-download"></span> Profesores</a>
            <a data-item="fichajes" action="export" id="exportfichajes" class="btn btn-info btn-export act"><span style="font-size: 20px;" class="fa fa-cloud-download"></span> Fichajes</a>
            </br>
            <h2>Mostrar en Pantalla</h2>
            <a data-item="asistencias" action="select" id='filtroasistencias' class="btn btn-success btn-select act"><span style="font-size: 20px;" class="fa fa-eye"></span> Asistencias</a>
            <a data-item="faltas" action="select" id='filtrofaltas' class="btn btn-success btn-select act"><span style="font-size: 20px;" class="fa fa-eye"></span> Faltas</a>
            <a data-item="horarios" action="select" id='filtrohorarios' class="btn btn-success btn-select act"><span style="font-size: 20px;" class="fa fa-eye"></span> Horarios</a>
            <a data-item="fichajeDiario" action="select" class="btn btn-success btn-select act"><span style="font-size: 20px;" class="fa fa-calendar"></span> Fichajes de hoy</a>
            <a data-item="fichajeFechaFilter" action="select" id='filtrofichajes' class="btn btn-success btn-select act"><span style="font-size: 20px;" class="fa fa-calendar"></span> Fichaje Por Fechas</a>
            </br>
            <h2>Borrado de Datos (Atención estas acciones son <b>IRREVERSIBLES</b>)</h2>
            <a enlace="index.php?ACTION=profesores&OPT=delete-all" class="btn btn-danger eliminar" elemento="profesores"><span style="font-size: 20px;" class="fa fa-user-o"></span> Borrar Profesores</a>
            <a enlace="index.php?ACTION=horarios&OPT=delete-all" class="btn btn-danger eliminar" elemento="horarios"><span style="font-size: 20px;" class="fa fa-calendar-times-o"></span> Borrar Horarios</a>
            <a enlace="index.php?ACTION=horarios&OPT=delete-all-t" id="eliminar-t-horarios" class="btn btn-danger eliminar" elemento="t-horarios"><span style="font-size: 20px;" class="fa fa-calendar-times-o"></span> Borrar Horarios Programados</a>
            </br>
            <h2>Copia de seguridad del centro</h2>
            <a href="index.php?ACTION=backup-centro" id="backup" class="btn btn-success" target="_blank" download><span style="font-size: 20px;" class="fa fa-floppy-o"></span> Copia de seguridad</a>
            </br>
        <div class="col-12">
            <div id="btn-response"></div>
        </div>
    </div>
</div>
<div id="loading" class="col-12" style="position: absolute; top: 0; left: 0; width: 100%; height: 100vh; text-align: center; z-index: 99">
    <div class="caja" style="margin-top: 35vh; display: inline-block; padding: 25px; background-color: white; border-radius: 10px; box-shadow: 4px 4px 16px 0 #808080bf;">
        <div>
            <img src="resources/img/loading.gif" alt="Cargando...">
            <h2 id="loading-msg"></h2>
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
<script src="js/admon_filtrado_fecha.js"></script>
<script src="js/admon.js"></script>