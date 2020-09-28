<div class="container">
<?php


if(! $n = $class->query("SELECT Nombre, ID FROM $class->profesores WHERE ID='$_GET[profesor]'")->fetch_assoc())
{
    $ERR_MSG = $class->ERR_ASYSTECO;
}
$sql = "SELECT DISTINCT Tipo
FROM $class->horarios
WHERE $class->horarios.ID_PROFESOR='$_GET[profesor]'";
if($response = $class->query($sql))
{
    if ($response->num_rows == 1)
    {
        $dia = $class->getDate();
        $datosprof = $response->fetch_assoc();
        $franja = $datosprof['Tipo'];
        echo "<h2>Horario: $n[Nombre]</h2>";
        echo "<a id='editar-horario' href='index.php?ACTION=horarios&OPT=edit-horario-profesor&profesor=$n[ID]' class='btn btn-success'>Editar horario</a>";
        echo "<input id='fecha-edit' style='width: 25%; display: inline-block; margin-left: 25px;' type='text' class='form-control' placeholder='Seleccione una fecha...'>";
        echo "<a id='eliminar-horario' style='margin-left: 50%;' href='index.php?ACTION=horarios&OPT=remove&profesor=$n[ID]' class='btn btn-danger' onclick=\"return confirm('¿Seguro que desea eliminar el horario de este profesor?')\">Limpiar horario</a>";
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
                    if($response = $class->query("SELECT Hora, Dia, Aula, Grupo, Edificio FROM Horarios WHERE ID_PROFESOR='$_GET[profesor]' AND Hora='$Hora' AND Dia='$dialoop' ORDER BY Hora "))
                    {
                        if($response->num_rows > 0)
                        {
                            $fila = $response->fetch_all();
                            $m=2;
                            echo "<td style='text-align: center; vertical-align: middle;' class=' $dia[color]'>";
                            
                            if($fila[0][3] == 'Guardia')
                            {
                                echo "<span><b>Guardia</b></span>";
                                echo "<br>";
                                echo "<span><b>Edificio " . $fila[0][4] . "</b></span>";
                            }
                            else
                            {
                                echo "<b>Aula: </b>" . $fila[0][2];
                                echo "<br><b>Grupo: </b>";
                                for($i=0;$i<count($fila);$i++)
                                {
                                    $m % 2 == 0 ? $espacio = " " : $espacio = "<br>";
                                    echo $espacio . $fila[$i][3];
                                    $m++;
                                }
                            }
                            echo "</td>";
                        }
                        else
                        {
                            echo "<td style='text-align: center; vertical-align: middle;' class=' $dia[color]'></td>";
                        }
                    }
                }
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        include_once('js/update_horario.js');
        include_once('js/temp_horario.js');
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