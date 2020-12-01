<div class="container" style="margin-top:50px">
    <div class="row">
        <div class="col-xs-12">
<?php

$fechaget = $_GET['fecha'];
$sep = preg_split('/\//', $_GET['fecha']);
$_GET['fecha'] = $sep[2] . '-' . $sep[1] . '-' . $sep[0];

if(! $n = $class->query("SELECT Nombre, ID FROM $class->profesores WHERE ID='$_GET[profesor]'")->fetch_assoc())
{
    $ERR_MSG = $class->ERR_ASYSTECO;
}
$temp_table = "SELECT * FROM T_horarios WHERE ID_PROFESOR = '$_GET[profesor]' AND Fecha_incorpora = '$_GET[fecha]'";

if($result = $class->query($temp_table))
{
    if(! $result->num_rows > 0)
    {
        $temp_horario = 
            "INSERT INTO T_horarios(ID_PROFESOR, Dia, Hora, Tipo, Edificio, Aula, Grupo, Hora_Entrada, Hora_Salida, Fecha_incorpora)
                    SELECT ID_PROFESOR, Dia, Hora, Tipo, Edificio, Aula, Grupo, Hora_Entrada, Hora_Salida, '$_GET[fecha]' as Fecha_incorpora
                    FROM Horarios
                    WHERE ID_PROFESOR = '$_GET[profesor]'";
        if(! $res = $class->query($temp_horario))
        {
            $ERR_MSG = $class->ERR_ASYSTECO;
        }
    }
}
else
{
    $ERR_MSG = $class->ERR_ASYSTECO;
}

$sql = "SELECT DISTINCT Tipo
FROM T_horarios
WHERE T_horarios.ID_PROFESOR='$_GET[profesor]' AND T_horarios.Fecha_incorpora = '$_GET[fecha]'";
if($response = $class->query($sql))
{
    if ($response->num_rows == 1)
    {
        $dia = $class->getDate();
        $datosprof = $response->fetch_assoc();
        $franja = $datosprof['Tipo'];
        echo "<h2 id='profesor' profesor='$n[ID]'>Horario: $n[Nombre]</h2>";
        echo "<h4 style='color: grey;'><i>* Este horario entrará en vigor el día $fechaget</i></h4>";
        if($_GET['fecha'] == date('Y-m-d')){
            echo "<a href='index.php?ACTION=horarios&OPT=apply-now&fecha=$_GET[fecha]' class='btn btn-success pull-right'> Aplicar cambios ahora </a>";
            echo "<a href='index.php?ACTION=horarios&OPT=cancel-changes&profesor=$_GET[profesor]&fecha=$_GET[fecha]' class='btn btn-danger'> Cancelar cambios</a>";
        } else {
            echo "<a href='index.php?ACTION=horarios&OPT=apply-now&fecha=$_GET[fecha]' class='btn btn-success pull-right' onclick=\"return confirm('¿Desea aplicar los cambios ahora? La fecha programada es $fechaget')\"> Aplicar cambios ahora </a><br><br>";
            echo "<a href='index.php?ACTION=profesores' class='btn btn-success pull-right'> Aplicar cambios en fecha </a>";
            echo "<a href='index.php?ACTION=horarios&OPT=cancel-changes&profesor=$_GET[profesor]&fecha=$_GET[fecha]' class='btn btn-danger'> Cancelar cambios</a>";
        }
        echo "<div id='response'></div>";
        echo "</br>";
        echo "<table class='table'>";
        echo "<thead>";
            echo "<tr>";
                echo "<th style='text-align: center;'>Horas</th>";
                echo "<th style='text-align: center;'>Lunes</th>";
                echo "<th style='text-align: center;'>Martes</th>";
                echo "<th style='text-align: center;'>Miercoles</th>";
                echo "<th style='text-align: center;'>Jueves</th>";
                echo "<th style='text-align: center;'>Viernes</th>";
                echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        
        foreach ($franjasHorarias[$franja] as $valor => $datos)
        {
            $Hora = $valor;
            $horaInicioSplit = preg_split('/:/', $datos['Inicio']);
            $horaInicioSinSegundos = $horaInicioSplit[0] . ":" . $horaInicioSplit[1];
            $horaFinSplit = preg_split('/:/', $datos['Fin']);
            $horaFinSinSegundos = $horaFinSplit[0] . ":" . $horaFinSplit[1];
            echo "<tr>";
            echo "<td style='text-align: center; vertical-align: middle;'>$horaInicioSinSegundos <br>$horaFinSinSegundos</td>";
                
                for($dialoop = 1; $dialoop <= 5; $dialoop++)
                {
                    $dia['wday'] == $dialoop ? $dia['color'] = "success" : $dia['color'] = '';
                    if($response = $class->query(
                        "SELECT Hora, Dia, Aulas.Nombre as Aula, Cursos.Nombre as Curso, T_horarios.ID, Tipo, Edificio
                        FROM (T_horarios 
                            INNER JOIN Cursos ON T_horarios.grupo = Cursos.ID)
                            INNER JOIN Aulas ON T_horarios.Aula = Aulas.ID
                        WHERE ID_PROFESOR='$_GET[profesor]'
                            AND Hora='$Hora'
                            AND Dia='$dialoop'
                            AND Fecha_incorpora='$_GET[fecha]'
                        ORDER BY Hora "))
                    {
                        if($response->num_rows > 0)
                        {
                            $fila = $response->fetch_all();
                            $aula = $fila[0][2];
                            $grupo = $fila[0][3];
                            $Tipo = $fila[0][5];
                            $m=2;

                            echo "<td style='vertical-align: middle; text-align: center;' class=' $dia[color]'>";
                            
                            if($fila[0][3] == 'Guardia')
                            {
                                echo "<a class='remove-guardia' style='color: red;' enlace='index.php?ACTION=horarios&OPT=edit-guardias&SUBOPT=removet&profesor=$n[ID]&Dia=$dialoop&Hora=$Hora' title='Quitar Guardia'>";
                                    echo "<span class='glyphicon glyphicon-remove-circle btn-react-del'></span>";
                                echo "</a><br>";
                                echo "<span><b>Guardia</b></span>";
                                echo "<br>";
                                echo "<span><b>Edificio " . $fila[0][6] . "</b></span>";
                            }
                            else
                            {
                                echo "<a style='color: red;' class='act' enlace='index.php?ACTION=horarios&OPT=edit-t&act=del_hora&ID_PROFESOR=$_GET[profesor]&Dia=$dialoop&Hora=$Hora&Fecha=" . $_GET['fecha'] . "'>";
                                    echo "<span class='glyphicon glyphicon-remove-circle btn-react-del'></span>";
                                echo "</a><br>";
                                
                                echo "<b>Aula: </b>";
                                echo "<span id='sp_" . $fila[0][4] . "_Aula' class='txt'>" . $fila[0][2] . "</span>";
                                if($res_aula = $class->query("SELECT DISTINCT ID, Nombre FROM Aulas WHERE Nombre <> '' ORDER BY Nombre"))
                                {
                                    echo "<select id='in_" . $fila[0][4] . "_Aula' class='entrada' name='Aula'>";
                                        while($fila_aula = $res_aula->fetch_assoc())
                                        {
                                            echo "<option value='$fila_aula[ID]'>$fila_aula[Nombre]</option>";
                                        }
                                    echo "</select>";
                                }
                                else
                                {
                                    echo "<span style='color:red;'>$class->ERR_ASYSTECO</span>";
                                }
                                echo "<br><b>Grupo: </b>";
                                for($i=0;$i<count($fila);$i++)
                                {
                                    $m % 2 == 0 ? $espacio = " " : $espacio = "<br>";
                                    //echo $espacio . $fila[$i][3];

                                    echo  $espacio . "<span id='sp2_" . $fila[$i][4] . "_Grupo' class='txt'>" . $fila[$i][3] . "</span> ";
                                    if($res_grupo = $class->query("SELECT DISTINCT ID, Nombre FROM Cursos WHERE Nombre <> '' ORDER BY Nombre"))
                                    {
                                        echo "<select id='in2_" . $fila[$i][4] . "_Grupo' class='entrada' name='Grupo'>";
                                            while($fila_grupo = $res_grupo->fetch_assoc())
                                            {
                                                echo "<option value='$fila_grupo[ID]'>$fila_grupo[Nombre]</option>";
                                            }
                                        echo "</select>";
                                    }
                                    else
                                    {
                                        echo "<span style='color:red;'>$class->ERR_ASYSTECO</span>";
                                    }
                                    
                                    echo "<a style='color: red;' class='act' enlace='index.php?ACTION=horarios&OPT=edit-t&act=del&ID=" . $fila[$i][4] . "'>";
                                        echo "<span class='glyphicon glyphicon-minus btn-react-del-group'></span>";
                                    echo "</a>";

                                    $grupo = $fila[$i][3];
                                    $m++;
                                }

                                // Añade + grupo Si Aula y grupo son distintos de Selec.
                                if($aula != 'Selec.' && $aula != '' && $grupo != 'Selec.' && $grupo != '')
                                {
                                    echo "<br>";
                                    echo "<a class='act' enlace='index.php?ACTION=horarios&OPT=edit-t&act=add_more&ID=$n[ID]&Dia=$dialoop&Hora=$Hora&Fecha=$_GET[fecha]'>";
                                        echo "<span class='glyphicon glyphicon-plus btn-react-add-more'></span>";
                                    echo "</a>";
                                }
                            }

                            echo "</td>";
                        }
                        else
                        {
                            echo "<td id='$j-$hora' style='vertical-align: middle; text-align: center;' class=' $dia[color]'>";
                                echo "<a class='act' enlace='index.php?ACTION=horarios&OPT=edit-t&act=add&ID=$n[ID]&Dia=$dialoop&Hora=$Hora&Tipo=$franja&Fecha=$_GET[fecha]'>";
                                    echo "<span class='glyphicon glyphicon-plus btn-react-add'></span>";
                                echo "</a>";
                            echo "</td>";
                        }
                    }
                }
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    }
    elseif($response->num_row > 1)
    {
        echo "<h1 style='vertical-align: middle; text-align: center;'>Formato no válido, revise su horario...</h1>";
    }
    else
    {
        echo "<h1 style='display: block; text-align: center;'>";
        echo "$n[Nombre] no tiene horario";
        echo "</h1>";
        echo "<div style='text-align: center;'>";
            echo "<a id='crear-horario' href='index.php?ACTION=horarios&OPT=crear&profesor=$n[ID]&Tipo=Mañana' class='btn btn-success'>Crear horario para $n[Nombre]</a>";
        echo "</div>";
    }
}
else
{
    $ERR_MSG = $class->ERR_ASYSTECO;
}
?>
        </div>
    </div>
</div>

<div id="loading" class="col-xs-12" style="position: absolute; top: 0; left: 0; width: 100%; height: 100vh; text-align: center;">
    <div class="caja" style="margin-top: 35vh; display: inline-block; padding: 25px; background-color: white; border-radius: 10px; box-shadow: 4px 4px 16px 0 #808080bf;">
        <div>
            <img src="resources/img/loading.gif" alt="Cargando...">
            <h2 id="loading-msg"></h2>
        </div>
    </div>
</div>

<script src="js/update-horario.js"></script>