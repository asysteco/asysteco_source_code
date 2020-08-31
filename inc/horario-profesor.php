<div class="container" style="margin-top:50px">
<?php
if($response = $class->query("SELECT $class->horarios.*, Diasemana.Diasemana 
                                    FROM ($class->horarios INNER JOIN $class->profesores ON $class->horarios.ID_PROFESOR=$class->profesores.ID) 
                                    INNER JOIN Diasemana ON Diasemana.ID=$class->horarios.Dia WHERE $class->profesores.ID='$_GET[profesor]' 
                                    ORDER BY $class->horarios.HORA_TIPO, $class->horarios.Dia"))
{
    if ($response->num_rows > 0)
    {
        if(! $n = $class->query("SELECT Nombre, ID FROM $class->profesores WHERE ID='$_GET[profesor]'")->fetch_assoc())
        {
            $ERR_MSG = $class->ERR_ASYSTECO;
        }
        echo "<h2>Horario: $n[Nombre]</h2>";
        echo "<a id='editar-horario' href='index.php?ACTION=horarios&OPT=edit-horario-profesor&profesor=$n[ID]' class='btn btn-success'>Editar horario</a>";
        echo "<input id='fecha-edit' style='width: 25%; display: inline-block; margin-left: 25px;' type='text' class='form-control' placeholder='Seleccione una fecha...'>";
        echo "<a id='eliminar-horario' style='margin-left: 50%;' href='index.php?ACTION=horarios&OPT=remove&profesor=$n[ID]' class='btn btn-danger' onclick=\"return confirm('¿Seguro que desea eliminar el horario de este profesor?')\">Limpiar horario</a>";
        echo "<div id='response'></div>";
        echo "</br><table class='table'>";
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
                if($response2 = $class->query("SELECT $class->horarios.HORA_TIPO FROM $class->horarios WHERE ID_PROFESOR='$_GET[profesor]' AND (HORA_TIPO LIKE '%M' OR HORA_TIPO LIKE '%C')"))
                {
                    if($response2->num_rows > 0)
                    {
                        $l = 6;
                    }
                    else
                    {
                        $l = 5;
                    }
                }
                else
                {
                    $ERR_MSG = $class->ERR_ASYSTECO;
                }
                    /* 
                    * Comienza bucle por filas horarias 
                    * Hasta completar las 6 de cada horario
                    */
                    
                    for ($i = 0; $i < $l; $i++)
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
                        WHERE $class->profesores.ID='$_GET[profesor]' AND ($class->horarios.HORA_TIPO=" . "'" . $hora ."M' OR $class->horarios.HORA_TIPO=" . "'" . $hora ."T' OR $class->horarios.HORA_TIPO=" . "'" . $hora ."C')
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
                                    echo "<td style='vertical-align: middle; text-align: center;' class='$dia[color]'>";
                                    echo "<b>Aula: </b>";
                                    echo "<span id='sp_" . $filahora[$k][0] . "_Aula' class='txt'>" . $filahora[$k][5] . "</span>";
                                    //echo "<input id='in_" . $filahora[$k][0] . "_Aula' class='entrada' type='text'>";
                                    if($response = $class->query("SELECT DISTINCT $class->horarios.Aula FROM $class->horarios WHERE $class->horarios.Aula <> '' ORDER BY $class->horarios.Aula"))
                                    {
                                        echo "<select id='in_" . $filahora[$k][0] . "_Aula' class='entrada' name='Aula'>";
                                            while($fila = $response->fetch_assoc())
                                            {
                                                echo "<option value='$fila[Aula]'>$fila[Aula]</option>";
                                            }
                                        echo "</select>";
                                    }
                                    else
                                    {
                                        echo "<span style='color:red;'>$class->ERR_ASYSTECO</span>";
                                    }
                                    echo "<br>";
                                    echo "<b>Grupo:</b>";
                                    echo "<span id='sp2_" . $filahora[$k][0] . "_Grupo' class='txt'>" . $filahora[$k][6] . "</span>";
                                    if($response2 = $class->query("SELECT DISTINCT $class->horarios.Grupo FROM $class->horarios WHERE $class->horarios.Grupo <> '' ORDER BY $class->horarios.Grupo"))
                                    {
                                        echo "<select id='in2_" . $filahora[$k][0] . "_Grupo' class='entrada' name='Grupo'>";
                                            while($fila = $response2->fetch_assoc())
                                            {
                                                echo "<option value='$fila[Grupo]'>$fila[Grupo]</option>";
                                            }
                                        echo "</select>";
                                    }
                                    else
                                    {
                                        echo "<span style='color:red;'>$class->ERR_ASYSTECO</span>";
                                    }
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
                                    echo "<td style='vertical-align: middle; text-align: center;'></td>";
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
        include_once('js/update_horario.js');
        include_once('js/temp_horario.js');
    }
    else
    {
        if(! $n = $class->query("SELECT ID, Nombre FROM $class->profesores WHERE ID='$_GET[profesor]'")->fetch_assoc())
        {
            $ERR_MSG = $class->ERR_ASYSTECO;
        }
        echo "<a id='crear-horario' href='index.php?ACTION=crear-horario&profesor=$n[ID]&Tipo=M' class='btn btn-success'>Crear horario para $n[Nombre]</a>";
    }
}
else
{
    $ERR_MSG = $class->ERR_ASYSTECO;
}
?>
</div>