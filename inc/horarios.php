<div class="container">
    <div class="row">
        <div class="col-xs-12">
<?php
if($response = $class->query("SELECT $class->horarios.*, Diasemana.Diasemana as Dia 
                                    FROM ($class->horarios INNER JOIN $class->profesores ON $class->horarios.ID_PROFESOR=$class->profesores.ID)
                                    INNER JOIN Diasemana ON Diasemana.ID=$class->horarios.Dia
                                    WHERE $class->profesores.ID='$_SESSION[ID]'
                                    ORDER BY $class->horarios.HORA_TIPO"))
{
    if ($response->num_rows > 0)
    {
        echo "<h2>Horario</h2>";
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

        /* 
        * Comienza bucle por filas horarias 
        * Hasta completar las 6 de cada horario
        */
        
        for ($i = 0; $i < 6; $i++)
        {
            $dia = $class->getDate();
            $hora = $i+1;

            /*
            * Recogemos valores de cada HORA_TIPO del Profesor en $response
            * Valores ordenados por HORA_TIPO y Día
            */

            if($response = $class->query("SELECT $class->horarios.*, Diasemana.Diasemana, Diasemana.ID, $class->horas.Inicio, $class->horas.Fin 
            FROM (($class->horarios INNER JOIN $class->profesores ON $class->horarios.ID_PROFESOR=$class->profesores.ID) 
            INNER JOIN Diasemana ON Diasemana.ID=$class->horarios.Dia)
            INNER JOIN $class->horas ON $class->horas.Hora=$class->horarios.HORA_TIPO
            WHERE $class->profesores.ID='$_SESSION[ID]' AND $class->horarios.HORA_TIPO=" . "'" . $hora ."M'
            ORDER BY $class->horarios.HORA_TIPO, $class->horarios.Dia"))
            {
                // $k -> Contador de índice del array
                $k = 0;
                $filahora = $response->fetch_all();
                echo "<tr>";
                echo "<td style='vertical-align: middle; text-align: center;'><b>$hora</b></td>";

                /*
                * Bucle que recorre el campo Dia
                * Este campo determinará su posición en la tabla (Horizontalmente)
                */
                
                for($j = 1; $j <= 5; $j++)
                {

                    /*
                    * Comprobamos si $filahora[$k][10] coincide con el Dia de la Semana exacto
                    */

                    if($filahora[$k][10] == $j)
                    {
                        $dia['weekday'] === $filahora[$k][9] ? $dia['color'] = "success" : $dia['color'] = '';
                        echo "<td style='vertical-align: middle; text-align: center;' class='$dia[color]'><b>Aula:</b> " . $filahora[$k][5] . "<br><b>Grupo:</b> " . $filahora[$k][6];
                        $k++;
                        // $m -> Contador de pares para saltar línea o añadir espacio
                        $m = 2;

                        /*
                        * Comprobamos si el siguiente objeto coincide con el mismo Dia de la Semana
                        * Esta comprobación se realizará hasta que ya no coincida
                        * Ya que pertenecerá al siguiente Dia
                        */

                        while($filahora[$k][10] == $j)
                        {
                            if($m % 2 == 0)
                            {
                                echo "<br>";
                            }
                            else
                            {
                                echo " ";
                            }
                            echo $filahora[$k][6];
                            $m++;
                            $k++;
                        }
                        echo "</td>";
                    }
                    else
                    {
                        echo "<td></td>";
                    }
                }
                echo "</tr>";
            }
            else
            {
                $ERR_MSG = $class->ERR_ASYSTECO;
            }
        }
        echo "</tbody>";
        echo "</table>";
    }
    else
    {
        $ERR_MSG = $class->ERR_ASYSTECO;
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