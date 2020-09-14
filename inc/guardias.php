<?php

//--------------------------------------------------------

if($response = $class->getGuardias())
{
    $j = $response->num_rows;
    echo "</br><table class='table table-hover'>";
        echo "<thead>";
            echo "<tr>";
                echo "<th style='vertical-align: middle; text-align: center;'>Hora</th>";
                echo "<th style='vertical-align: middle; text-align: center;'>Profesor</th>";
                echo "<th style='vertical-align: middle; text-align: center;'>Edificio</th>";
                echo "<th style='vertical-align: middle; text-align: center;'>Aula</th>";
                echo "<th style='vertical-align: middle; text-align: center;'>Grupo</th>";
            echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        $datos = $response->fetch_all();
        for($i = 0; $i < $j; $i++)
        {
            echo "<tr>";
                echo "<td style='vertical-align: middle; text-align: center;'>" . $datos[$i][4] . "</td>";
                echo "<td style='vertical-align: middle; text-align: center;'>" . $datos[$i][0] . "</td>";
                echo "<td style='vertical-align: middle; text-align: center;'>" . $datos[$i][3] . "</td>";
                echo "<td style='vertical-align: middle; text-align: center;'><b>" . $datos[$i][1] . "</b></td>";
                echo "<td style='vertical-align: middle; text-align: center;'><b>";
                    echo $datos[$i][2];
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
                    while($datos[$i][1] == $aula && $datos[$i][4] == $ultimahora)
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
    echo $ERR_MSG = $class->ERR_ASYSTECO;
}