<div class="container">
    <div class="row">
        <div class="col-12">
<?php
$sql = "SELECT DISTINCT Tipo
FROM Horarios H
WHERE H.ID_PROFESOR='$_SESSION[ID]'";
if($response = $class->query($sql))
{
    if ($response->num_rows == 1)
    {
        $dia = $class->getDate();
        $datosprof = $response->fetch_assoc();
        $franja = $datosprof['Tipo'];
        echo "<H1>Horario</H1>";
        echo "</br>";
        echo "<div class='table-responsive'>";
            echo "<table class='table' style='margin-top: 25px;'>";
                echo "<thead class='thead-dark'>";
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
                    
                    if (! $class->query("SELECT H.*, C.Nombre as Curso, A.Nombre as Aula
                                        FROM (Horarios H
                                            INNER JOIN Cursos C ON H.grupo = C.ID)
                                            INNER JOIN Aulas A ON H.Aula = A.ID
                                        WHERE H.ID_PROFESOR='$_SESSION[ID]'
                                        AND H.Hora='$Hora'
                                        ORDER BY H.Hora ")->num_rows > 0) {
                        continue;
                    }
                    echo "<tr id='Hora_$Hora'>";
                    echo "<td style='text-align: center; vertical-align: middle;'>$horaInicioSinSegundos <br>$horaFinSinSegundos</td>";
                        for($dialoop = 1; $dialoop <= 5; $dialoop++)
                        {
                            $dia['wday'] == $dialoop ? $dia['color'] = "table-success" : $dia['color'] = '';
                            if($response = $class->query("SELECT Hora, Dia, Aulas.Nombre as Aula, Cursos.Nombre as Curso, Edificio
                            FROM (Horarios 
                                INNER JOIN Cursos ON Horarios.grupo = Cursos.ID)
                                INNER JOIN Aulas ON Horarios.Aula = Aulas.ID
                            WHERE ID_PROFESOR='$_SESSION[ID]'
                                AND Hora='$Hora'
                                AND Dia='$dialoop'
                            ORDER BY Hora "))
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
        echo "</div>";
    }
    elseif($response->num_row > 1)
    {
        echo "<h1 class='no-horario'>Formato no válido, revise su horario...</h1>";
    }
    else
    {
        echo "<h1 class='no-horario'>Todavía no dispone de horario...</h1>";
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