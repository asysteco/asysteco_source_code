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
            </br>
            <h2>Mostrar en Pantalla</h2>
            <a enlace="index.php?ACTION=admon&OPT=select&select=marcajes&pag=0" id='filtromarcaje' class="btn btn-success btn-select"><span class="glyphicon glyphicon-eye-open"></span> Marcajes</a>
            <a enlace="index.php?ACTION=admon&OPT=select&select=asistencias&pag=0"id='filtroasistencias' class="btn btn-success btn-select"><span class="glyphicon glyphicon-eye-open"></span> Asistencias</a>
            <a enlace="index.php?ACTION=admon&OPT=select&select=faltas&pag=0" id='filtrofaltas' class="btn btn-success btn-select"><span class="glyphicon glyphicon-eye-open"></span> Faltas</a>
            <a enlace="index.php?ACTION=admon&OPT=select&select=horarios&pag=0" class="btn btn-success btn-select"><span class="glyphicon glyphicon-eye-open"></span> Horarios</a>
            <a enlace="index.php?ACTION=admon&OPT=select&select=fichadi&pag=0" class="btn btn-success btn-select"><span class="glyphicon glyphicon-calendar"></span> Fichajes de hoy</a>
            <a enlace="index.php?ACTION=admon&OPT=select&select=fichafe&pag=0" id='filtrofichajes' class="btn btn-success btn-select"><span class="glyphicon glyphicon-calendar"></span> Fichaje Por Fechas</a>
        </div>
        </br>
        <div class="col-xs-12">
            <div id="loading" style='text-align: center; position: absolute; width: 100%; height: 100%;'>
                <img style="text-align: center; background-color: transparent;" src="resources/img/loading.gif" alt="Cargando...">
                <h2 id="loading-msg"></h2>
            </div>
            <div id="btn-response"></div>
        </div>
    </div>
</div>