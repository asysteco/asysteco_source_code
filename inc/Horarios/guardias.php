<?php

echo "<table id='listado-guardias' class='table table-striped'>";
    echo "<thead id='fila_cabecera' class='thead-dark'>";
        echo "<tr>";
            echo "<th style='width: 5%;'>Fila</th>";
            echo "<th style='width: 15%;'>Hora</th>";
            if (isset($options['edificios']) && $options['edificios'] > 1) {
                echo "<th style='width: 45%;'>Profesor</th>";
                echo "<th style='width: 5%;'>Edificio</th>";
            } else {
                echo "<th style='width: 50%;'>Profesor</th>";
            }
            echo "<th style='width: 15%;'>Aula</th>";
            echo "<th style='width: 15%;'>Grupo</th>";
        echo "</tr>";
    echo "</thead>";
    echo "<tbody class='scroller'>";
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
            echo "<td style='width: 5%;'>" . $fila . "</td>";
            echo "<td style='width: 15%;'>" . $horaInicioSinSegundos . " - " . $horaFinSinSegundos . "</td>";
            if (isset($options['edificios']) && $options['edificios'] > 1) {
                echo "<td style='width: 45%;'>" . $datos[$i][0] . "</td>";
                echo "<td style='width: 5%;'>" . $datos[$i][3] . "</td>";
            } else {
                echo "<td style='width: 50%;'>" . $datos[$i][0] . "</td>";
            }
            echo "<td style='width: 15%;'><b>" . $datos[$i][1] . "</b></td>";
            echo "<td style='width: 15%;'><b>";
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
}
else
{
    echo "<tr>";
    echo "<td colspan='100%'><h3><b> $class->MSG </b><h3></td>";
    echo "</tr>";
}