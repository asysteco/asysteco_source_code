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
            <a data-item="marcajes" action="export" class="btn btn-info btn-export act"><span style="font-size: 20px;" class="fa fa-cloud-download"></span> Marcajes</a>
            <a data-item="Asistencias" action="export" class="btn btn-info btn-export act"><span style="font-size: 20px;" class="fa fa-cloud-download"></span> Asistencias</a>
            <a data-item="Faltas" action="export" class="btn btn-info btn-export act"><span style="font-size: 20px;" class="fa fa-cloud-download"></span> Faltas</a>
            <a data-item="Horarios" action="export" class="btn btn-info btn-export act"><span style="font-size: 20px;" class="fa fa-cloud-download"></span> Horarios</a>
            <a data-item="Profesores" action="export" class="btn btn-info btn-export act"><span style="font-size: 20px;" class="fa fa-cloud-download"></span> Profesores</a>
            <a data-item="Fichajes" action="export" class="btn btn-info btn-export act"><span style="font-size: 20px;" class="fa fa-cloud-download"></span> Fichajes</a>
            <a data-item="Faltas_Injustificadas" action="export" class="btn btn-info btn-export act"><span style="font-size: 20px;" class="fa fa-cloud-download"></span> Faltas Injustificadas</a>
            <a data-item="Faltas_Justificadas" action="export" class="btn btn-info btn-export act"><span style="font-size: 20px;" class="fa fa-cloud-download"></span> Faltas Justificadas</a>
            </br>
            <h2>Mostrar en Pantalla</h2>
            <a data-item="asistencias" action="select" class="btn btn-success btn-select act"><span style="font-size: 20px;" class="fa fa-eye"></span> Asistencias</a>
            <a data-item="faltas" action="select" class="btn btn-success btn-select act"><span style="font-size: 20px;" class="fa fa-eye"></span> Faltas</a>
            <a data-item="horarios" action="select" class="btn btn-success btn-select act"><span style="font-size: 20px;" class="fa fa-eye"></span> Horarios</a>
            <a data-item="fichajeDiario" action="select" class="btn btn-success btn-select act"><span style="font-size: 20px;" class="fa fa-calendar"></span> Fichajes de hoy</a>
            <a data-item="fichajeFechaFilter" action="select" class="btn btn-success btn-select act"><span style="font-size: 20px;" class="fa fa-calendar"></span> Fichaje Por Fechas</a>
            <a data-item="faltasInjustificadas" action="select" class="btn btn-success btn-select act"><span style="font-size: 20px;" class="fa fa-calendar-minus-o"></span> Faltas Injustificadas</a>
            <a data-item="faltasJustificadas" action="select" class="btn btn-success btn-select act"><span style="font-size: 20px;" class="fa fa-calendar-check-o"></span> Faltas Justificadas</a>
            </br>
            <h2>Borrado de Datos (Atención estas acciones son <b>IRREVERSIBLES</b>)</h2>
            <a enlace="index.php?ACTION=profesores&OPT=delete-all" class="btn btn-danger eliminar" elemento="profesores"><span style="font-size: 20px;" class="fa fa-user-times"></span> Borrar Profesores</a>
            <a enlace="index.php?ACTION=horarios&OPT=delete-all" class="btn btn-danger eliminar" elemento="horarios"><span style="font-size: 20px;" class="fa fa-calendar-times-o"></span> Borrar Horarios</a>
            <a enlace="index.php?ACTION=horarios&OPT=delete-all-t" id="eliminar-t-horarios" class="btn btn-danger eliminar" elemento="t-horarios"><span style="font-size: 20px;" class="fa fa-calendar-times-o"></span> Borrar Horarios Programados</a>
            </br>
            <h2>Copia de seguridad del centro</h2>
            <a href="index.php?ACTION=admon&OPT=backup-centro" id="backup" class="btn btn-success" target="_blank" download><span style="font-size: 20px;" class="fa fa-floppy-o"></span> Copia de seguridad</a>
            </br>
        <div class="col-12" style="margin-top: 25px;">
            <div id="btn-response" class="table-responsive"></div>
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