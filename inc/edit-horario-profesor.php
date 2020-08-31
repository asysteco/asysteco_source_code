<div class="container" style="margin-top:50px">
    <div class="row">
        <div class="col-xs-12">
<?php

$fechaget = $_GET['fecha'];
$sep = preg_split('/\//', $_GET['fecha']);
$_GET['fecha'] = $sep[2] . '-' . $sep[1] . '-' . $sep[0];
$temp_table = 
    "SELECT * 
    FROM T_horarios
    WHERE ID_PROFESOR = '$_GET[profesor]'
        AND Fecha_incorpora = '$_GET[fecha]'
    ";
if($result = $class->query($temp_table))
{
    if(! $result->num_rows > 0)
    {
        $temp_horario = 
            "INSERT INTO T_horarios
                (ID_PROFESOR,
                Dia,
                HORA_TIPO,
                Edificio,
                Aula,
                Grupo,
                Hora_Entrada,
                Hora_Salida,
                Fecha_incorpora)
                    SELECT ID_PROFESOR,
                            Dia,
                            HORA_TIPO,
                            Edificio,
                            Aula,
                            Grupo,
                            Hora_Entrada,
                            Hora_Salida,
                            '$_GET[fecha]' as Fecha_incorpora
                    FROM Horarios
                    WHERE ID_PROFESOR = '$_GET[profesor]'
                        ";
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
$consulta = 
"SELECT T_horarios.*,
Diasemana.Diasemana 
FROM (T_horarios INNER JOIN $class->profesores ON T_horarios.ID_PROFESOR=$class->profesores.ID) 
    INNER JOIN Diasemana ON Diasemana.ID=T_horarios.Dia
WHERE $class->profesores.ID = '$_GET[profesor]'
    AND T_horarios.Fecha_incorpora = '$_GET[fecha]'
ORDER BY T_horarios.HORA_TIPO, T_horarios.Dia";
if($response = $class->query($consulta))
{
    if ($response->num_rows > 0)
    {
        if(! $nombre = $class->query("SELECT Nombre, ID FROM $class->profesores WHERE ID='$_GET[profesor]'"))
        {
            $ERR_MSG = $class->ERR_ASYSTECO;
        }
        else
        {
            $n = $nombre->fetch_assoc();
        }
        echo "<h2>Horario: $n[Nombre]</h2>";
        echo "<h4 style='color: grey;'><i>* Este horario entrará en vigor el día $fechaget</i></h4>";
        echo "<div id='response'></div>";
        echo "<div id='tabla_t_horario'>";
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
                            $l == 6 ? $tipo = 'M' : $tipo = 'T';

                            /*
                            * Recogemos valores de cada HORA_TIPO del Profesor en $response
                            * Valores ordenados por HORA_TIPO y Día
                            */

                            if($response = $class->query("SELECT T_horarios.*, Diasemana.Diasemana, Diasemana.ID, $class->horas.Inicio, $class->horas.Fin 
                            FROM ((T_horarios INNER JOIN $class->profesores ON T_horarios.ID_PROFESOR=$class->profesores.ID) 
                            INNER JOIN Diasemana ON Diasemana.ID=T_horarios.Dia)
                            INNER JOIN $class->horas ON $class->horas.Hora=T_horarios.HORA_TIPO
                            WHERE $class->profesores.ID='$_GET[profesor]' AND Fecha_incorpora='$_GET[fecha]' AND (T_horarios.HORA_TIPO=" . "'" . $hora ."M' OR T_horarios.HORA_TIPO=" . "'" . $hora ."T' OR T_horarios.HORA_TIPO=" . "'" . $hora ."C')
                            ORDER BY T_horarios.HORA_TIPO, T_horarios.Dia"))
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
                                    * Comprobamos si $filahora[$k][11] coincide con el Dia de la Semana exacto
                                    */
                                    if($filahora[$k][11] == $j)
                                    {
                                        $dia['weekday'] === $filahora[$k][9] ? $dia['color'] = "success" : $dia['color'] = '';
                                        echo "<td id='$j-$hora' style='vertical-align: middle; text-align: center;' class='$dia[color]'>";
                                        isset($filahora[$k][3]) ? $horavar = $filahora[$k][3] : $horavar = $hora . $tipo;
                                        echo "<a style='color: red;' class='act' enlace='index.php?ACTION=horarios&OPT=edit-t-horario&act=del_hora&ID_PROFESOR=" . $filahora[$k][1] . "&Dia=$j&Hora=" . $horavar . "&Fecha=" . $_GET['fecha'] . "'>";
                                            echo "<span class='glyphicon glyphicon-remove-circle btn-react-del'></span>";
                                        echo "</a><br>";
                                        echo "<b>Aula: </b>";
                                        echo "<span id='sp_" . $filahora[$k][0] . "_Aula' class='txt'>" . $filahora[$k][5] . "</span>";
                                        $mismoaula = $filahora[$k][5];
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
                                        echo "<span id='sp2_" . $filahora[$k][0] . "_Grupo' class='txt'>" . $filahora[$k][6] . "</span> ";
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

                                        /*
                                        * Comprobamos si el siguiente objeto coincide con el mismo Dia de la Semana
                                        * Esta comprobación se realizará hasta que ya no coincida
                                        * Ya que pertenecerá al siguiente Dia
                                        */

                                        while($filahora[$k][11] == $j)
                                        {
                                            echo "<br>";
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
                                            echo "<a style='color: red;' class='act' enlace='index.php?ACTION=horarios&OPT=edit-t-horario&act=del&ID=" . $filahora[$k][0] . "'>";
                                                echo "<span class='glyphicon glyphicon-minus btn-react-del-group'></span>";
                                            echo "</a>";
                                            $k++;
                                        }
                                            isset($filahora[$k][3]) ? $horavar = $filahora[$k][3] : $horavar = $hora . $tipo;
                                            if($mismoaula != 'Selec.' && $mismoaula != '')
                                            {
                                                echo "<br>";
                                                echo "<a class='act' enlace='index.php?ACTION=horarios&OPT=edit-t-horario&act=add_more&Aula=" . $mismoaula . "&ID=$n[ID]&Dia=$j&Hora=" . $horavar . "&Fecha=$_GET[fecha]'>";
                                                    echo "<span class='glyphicon glyphicon-plus btn-react-add-more'></span>";
                                                echo "</a>";
                                            }
                                        echo "</td>";
                                    }
                                    else
                                    {
                                        echo "<td id='$j-$hora' style='vertical-align: middle; text-align: center;'>";
                                        isset($filahora[$k][3]) ? $horavar = $filahora[$k][3] : $horavar = $hora . $tipo;
                                            echo "<a class='act' enlace='index.php?ACTION=horarios&OPT=edit-t-horario&act=add&ID=$n[ID]&Dia=$j&Hora=" . $horavar . "&Fecha=$_GET[fecha]'>";
                                                echo "<span class='glyphicon glyphicon-plus btn-react-add'></span>";
                                            echo "</a>";
                                        echo "</td>";
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
        echo "</div>";
        include_once('js/update_t_horario.js');
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