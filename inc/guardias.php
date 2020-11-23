<?php

//--------------------------------------------------------

echo "<div id='scroller' class='scroller'>";
echo "</br><table id='listado-guardias' class='table table-striped scroller'>";
    echo "<thead id='fila_cabecera'>";
        echo "<tr>";
            echo "<th style='vertical-align: middle; text-align: center;'>Fila</th>";
            echo "<th style='vertical-align: middle; text-align: center;'>Hora</th>";
            echo "<th style='vertical-align: middle; text-align: center;'>Profesor</th>";
            if (isset($options['edificios']) && $options['edificios'] > 1) {
                echo "<th style='vertical-align: middle; text-align: center;'>Edificio</th>";
            }
            echo "<th style='vertical-align: middle; text-align: center;'>Aula</th>";
            echo "<th style='vertical-align: middle; text-align: center;'>Grupo</th>";
        echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
if($response = $class->getGuardias())
{
    $j = $response->num_rows;
    $datos = $response->fetch_all();
    for($i = 0; $i < $j; $i++)
    {
        $tipoKey = $datos[$i][5];
        $horaKey = $datos[$i][4];
        $horaInicioHorario = $franjasHorarias[$tipoKey][$horaKey]['Inicio'];
        $horaFinHorario = $franjasHorarias[$tipoKey][$horaKey]['Fin'];
        $horaInicioSplit = preg_split('/:/', $horaInicioHorario);
        $horaInicioSinSegundos = $horaInicioSplit[0] . ":" . $horaInicioSplit[1];
        $horaFinSplit = preg_split('/:/', $horaFinHorario);
        $horaFinSinSegundos = $horaFinSplit[0] . ":" . $horaFinSplit[1];
        $fila = $i+1;
        echo "<tr id='fila_$i'>";
            echo "<td style='vertical-align: middle; text-align: center;'>" . $fila . "</td>";
            echo "<td style='vertical-align: middle; text-align: center;'>" . $horaInicioSinSegundos . " - " . $horaFinSinSegundos . "</td>";
            echo "<td style='vertical-align: middle; text-align: center;'>" . $datos[$i][0] . "</td>";
            if (isset($options['edificios']) && $options['edificios'] > 1) {
                echo "<td style='vertical-align: middle; text-align: center;'>" . $datos[$i][3] . "</td>";
            }
            echo "<td style='vertical-align: middle; text-align: center;'><b>" . $datos[$i][1] . "</b></td>";
            echo "<td style='vertical-align: middle; text-align: center;'><b>";
                echo $datos[$i][2];
                $profesor = $datos[$i][0];
                $aula = $datos[$i][1];
                $grupo = $datos[$i][2];
                $ultimahora = $datos[$i][4];
                $i++;
                // $m -> Contador de pares para saltar línea o añadir espacio
                $m = 1;

                /*
                * Comprobamos si el siguiente objeto (Registro) coincide con el mismo Aula
                * Esta comprobación se realizará hasta que ya no coincida
                * Ya que pertenecerá al siguiente registro
                */
                while($datos[$i][1] == $aula && $datos[$i][4] == $ultimahora && $datos[$i][0] == $profesor)
                {
                    if($m % 2 == 0)
                    {
                        echo "<br>";
                    }
                    else
                    {
                        echo " ";
                    }
                    echo $datos[$i][2];
                    $ultimahora = $datos[$i][4];
                    $m++;
                    $i++;
                }
                $i--;
                echo "</b></td>";
        echo "</tr>";
    }
    echo "</tbody>";
echo "</table>";
echo '</div>';
}
else
{
    echo "<tr>";
    echo "<td colspan='100%'><h3><b> $class->MSG </b><h3></td>";
    echo "</tr>";
}