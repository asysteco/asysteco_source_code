<div class="container" style="margin-top:50px">
    <div class="row">
        <div class="col-xs-12">
<?php

if(! $n = $class->query("SELECT Nombre, ID FROM $class->profesores WHERE ID='$_GET[profesor]'")->fetch_assoc())
{
    $ERR_MSG = $class->ERR_ASYSTECO;
}
if(isset($_GET['Tipo']) && $_GET['Tipo'] == 'M')
{
    $l = 6;
    $titulotipo = "Mañana";
    $manana = 'selected';
}
elseif(isset($_GET['Tipo']) && $_GET['Tipo'] == 'C')
{
    $l = 6;
    $titulotipo = "Mañana (COVID)";
    $covid = 'selected';
}
else
{
    $l = 5;
    $titulotipo = "Tarde";
    $tarde = 'selected';
}
echo "<h2>Crear horario para $n[Nombre]</h2>";
echo "<h3>Tipo de horario: $titulotipo</h3>";
echo "<select id='select_tipo'>";
    echo "<option value='M' $manana>Horario de Mañana</option>";
    echo "<option value='T' $tarde>Horario de Tarde</option>";
    echo "<option value='C' $covid>Horario excepcional (COVID)</option>";
echo "<select>";
echo '<br>';
echo '<br>';
echo "<a href='index.php?ACTION=horarios&OPT=remove&profesor=$n[ID]' class='btn btn-danger' onclick=\"return confirm('¿Seguro que desea cancelar este horario?')\"><span class='glyphicon glyphicon-remove'></span> Cancelar cambios</a>";
echo "<a href='index.php?ACTION=create_marcajes&ID_PROFESOR=$n[ID]' style='margin-left: 70%;' class='btn btn-success'><span class='glyphicon glyphicon-ok'></span> Aplicar cambios</a> ";
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

                /* 
                * Comienza bucle por filas horarias 
                * Hasta completar las 6 de cada horario
                */
                
                for ($i = 0; $i < $l; $i++)
                {
                    $dia = $class->getDate();
                    $hora = $i+1;
                    $tipo = $_GET['Tipo'];

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
                                echo "<td id='$j-$hora' style='vertical-align: middle; text-align: center;' class='$dia[color]'>";
                                isset($filahora[$k][3]) ? $horavar = $filahora[$k][3] : $horavar = $hora . $tipo;
                                echo "<a style='color: red;' class='act' enlace='index.php?ACTION=horarios&OPT=edit-crear&act=del_hora&ID_PROFESOR=" . $filahora[$k][1] . "&Dia=$j&Hora=" . $horavar . "'>";
                                    echo "<span class='glyphicon glyphicon-remove-circle btn-react-del'></span>";
                                echo "</a><br>";
                                echo "<b>Aula: </b>";
                                echo "<span id='sp_" . $filahora[$k][0] . "_Aula' class='txt'>" . $filahora[$k][5] . "</span>";
                                $mismoaula = $filahora[$k][5];
                                //echo "<input id='in_" . $filahora[$k][0] . "_Aula' class='entrada' type='text'>";
                                if($response = $class->query("SELECT DISTINCT $class->horarios.Aula FROM $class->horarios WHERE $class->horarios.Aula <> '' AND $class->horarios.Aula <> 'Selec.' ORDER BY $class->horarios.Aula"))
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
                                if($response2 = $class->query("SELECT DISTINCT $class->horarios.Grupo FROM $class->horarios WHERE $class->horarios.Grupo <> '' AND $class->horarios.Grupo <> 'Selec.' ORDER BY $class->horarios.Grupo"))
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

                                while($filahora[$k][10] == $j)
                                {
                                    echo "<br>";
                                    echo "<span id='sp2_" . $filahora[$k][0] . "_Grupo' class='txt'>" . $filahora[$k][6] . "</span>";
                                    if($response2 = $class->query("SELECT DISTINCT $class->horarios.Grupo FROM $class->horarios WHERE $class->horarios.Grupo <> '' AND $class->horarios.Grupo <> 'Selec.' ORDER BY $class->horarios.Grupo"))
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
                                    echo "<a style='color: red;' class='act' enlace='index.php?ACTION=horarios&OPT=edit-crear&act=del&ID=" . $filahora[$k][0] . "'>";
                                        echo "<span class='glyphicon glyphicon-minus btn-react-del-group'></span>";
                                    echo "</a>";
                                    $k++;
                                }
                                    isset($filahora[$k][3]) ? $horavar = $filahora[$k][3] : $horavar = $hora . $tipo;
                                    if($mismoaula != 'Selec.' && $mismoaula != '')
                                    {
                                        echo "<br>";
                                        echo "<a class='act' enlace='index.php?ACTION=horarios&OPT=edit-crear&act=add_more&Aula=" . $mismoaula . "&ID=$n[ID]&Dia=$j&Hora=" . $horavar . "'>";
                                            echo "<span class='glyphicon glyphicon-plus btn-react-add-more'></span>";
                                        echo "</a>";
                                    }
                                echo "</td>";
                            }
                            else
                            {
                                echo "<td id='$j-$hora' style='vertical-align: middle; text-align: center;'>";
                                isset($filahora[$k][3]) ? $horavar = $filahora[$k][3] : $horavar = $hora . $tipo;
                                    echo "<a class='act' enlace='index.php?ACTION=horarios&OPT=edit-crear&act=add&ID=$n[ID]&Dia=$j&Hora=" . $horavar . "'>";
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
include_once('js/crear-horario.js');