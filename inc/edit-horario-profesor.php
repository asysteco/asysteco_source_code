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
            "INSERT INTO T_horarios(ID_PROFESOR, Dia, HORA_TIPO, Hora, Tipo, Edificio, Aula, Grupo, Hora_Entrada, Hora_Salida, Fecha_incorpora)
                    SELECT ID_PROFESOR, Dia, HORA_TIPO, Hora, Tipo, Edificio, Aula, Grupo, Hora_Entrada, Hora_Salida, '$_GET[fecha]' as Fecha_incorpora
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
        echo "<h2>Horario: $n[Nombre]</h2>";
        echo "<a href='index.php?ACTION=horarios&OPT=apply-now' class='btn btn-success pull-right'> Aplicar cambios ahora</a>";
        echo "<h4 style='color: grey;'><i>* Este horario entrará en vigor el día $fechaget</i></h4>";
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
            echo "<tr>";
                echo "<td>$Hora</td>";
                
                for($dialoop = 1; $dialoop <= 5; $dialoop++)
                {
                    $dia['wday'] == $dialoop ? $dia['color'] = "success" : $dia['color'] = '';
                    if($response = $class->query("SELECT Hora, Dia, Aula, Grupo, ID, Tipo FROM T_horarios WHERE ID_PROFESOR='$_GET[profesor]' AND Hora='$Hora' AND Dia='$dialoop' ORDER BY Hora "))
                    {
                        if($response->num_rows > 0)
                        {
                            $fila = $response->fetch_all();
                            $aula = $fila[0][2];
                            $grupo = $fila[0][3];
                            $Tipo = $fila[0][5];
                            $m=2;

                            echo "<td style='vertical-align: middle; text-align: center;' class=' $dia[color]'>";
                                echo "<a style='color: red;' class='act' enlace='index.php?ACTION=horarios&OPT=edit-t&act=del_hora&ID_PROFESOR=$_GET[profesor]&Dia=$dialoop&Hora=$Hora&Fecha=" . $_GET['fecha'] . "'>";
                                    echo "<span class='glyphicon glyphicon-remove-circle btn-react-del'></span>";
                                echo "</a><br>";
                                
                                echo "<b>Aula: </b>";
                                echo "<span id='sp_" . $fila[0][4] . "_Aula' class='txt'>" . $fila[0][2] . "</span>";
                                if($res_aula = $class->query("SELECT DISTINCT $class->horarios.Aula FROM $class->horarios WHERE $class->horarios.Aula <> '' ORDER BY $class->horarios.Aula"))
                                {
                                    echo "<select id='in_" . $fila[0][4] . "_Aula' class='entrada' name='Aula'>";
                                        while($fila_aula = $res_aula->fetch_assoc())
                                        {
                                            echo "<option value='$fila_aula[Aula]'>$fila_aula[Aula]</option>";
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
                                    if($res_grupo = $class->query("SELECT DISTINCT $class->horarios.Grupo FROM $class->horarios WHERE $class->horarios.Grupo <> '' ORDER BY $class->horarios.Grupo"))
                                    {
                                        echo "<select id='in2_" . $fila[$i][4] . "_Grupo' class='entrada' name='Grupo'>";
                                            while($fila_grupo = $res_grupo->fetch_assoc())
                                            {
                                                echo "<option value='$fila_grupo[Grupo]'>$fila_grupo[Grupo]</option>";
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
                                    echo "<a class='act' enlace='index.php?ACTION=horarios&OPT=edit-t&act=add_more&Aula=$aula&ID=$n[ID]&Dia=$dialoop&Hora=$Hora&Fecha=$_GET[fecha]'>";
                                        echo "<span class='glyphicon glyphicon-plus btn-react-add-more'></span>";
                                    echo "</a>";
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
        include_once('js/update_t_horario.js');
    }
    elseif($response->num_row > 1)
    {
        echo "<h1 style='vertical-align: middle; text-align: center;'>Formato no válido, revise su horario...</h1>";
    }
    else
    {
        echo "<a id='crear-horario' href='index.php?ACTION=horarios&OPT=crear&profesor=$n[ID]&Tipo=Mañana' class='btn btn-success'>Crear horario para $n[Nombre]</a>";
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