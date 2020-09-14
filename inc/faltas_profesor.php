<?php

$sql = "SELECT Nombre FROM $class->profesores WHERE ID=" . mysqli_real_escape_string($class->conex, $_GET['ID']);
if($resp = $class->query($sql))
{
    $n = $resp->fetch_assoc();
    $n = $n['Nombre'];
    $f = $class->getDate();
    $fecha = $f['year'] . "-" . $f['mon'] . "-" . $f['mday'];
    // La variable Fecha la utilizará como día límite desde que existen marcajes para mostrar los registros
    // La siguiente línea la utilizaremos para realizar pruebas
    
    //$fecha = '2020-10-22';
    if($response = $class->query("SELECT Marcajes.*, Diasemana FROM Marcajes INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID WHERE ID_PROFESOR=" . mysqli_real_escape_string($class->conex, $_GET['ID']) . " AND Fecha <= '$fecha' ORDER BY Fecha DESC, Dia, Hora"))
    {
        if($_SESSION['Perfil'] == 'Admin')
        {
            echo "<h1>Asistencias lectivas de <b>$n</b></h1>";
            echo "<input id='busca_asiste' class='fadeIn' type='text' placeholder='Seleccionar fecha ...' autocomplete='off'>";
            echo "<div id='marcaje-response'></div>";
            echo "<div id='table-container'>";
                echo "<div id='full-table'>";
                    echo "<table class='table'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th style='vertical-align: middle; text-align: center;'>Fecha</th>";
                                echo "<th style='vertical-align: middle; text-align: center;'>Dia</th>";
                                echo "<th style='vertical-align: middle; text-align: center;'>Hora</th>";
                                echo "<th style='vertical-align: middle; text-align: center;'>Faltado</th>";
                                echo "<th style='vertical-align: middle; text-align: center;'>Asistido</th>";
                                echo "<th style='vertical-align: middle; text-align: center;'>Act. Extra.</th>";
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while($datos = $response->fetch_assoc())
                        {
                            if($datos['Asiste'] == 1)
                            {
                                $asisteColor = 'style="background-color: #84c584;"';
                            }
                            elseif($datos['Asiste'] == 2)
                            {
                                $asisteColor = 'style="background-color: #b5d9f5;"';
                            }
                            else
                            {
                                $asisteColor = 'style="background-color: #ff8c8c;"';
                            }
                            $sep = preg_split('/-/', $datos['Fecha']);
                            $dia = $sep[2];
                            $m = $sep[1];
                            $Y = $sep[0];
                            echo "<tr $asisteColor>";
                            echo "<td>$dia/$m/$Y</td>";
                            echo "<td>$datos[Diasemana]</td>";
                            echo "<td>$datos[Hora]</td>";
                            if($datos['Asiste'] == 1)
                            {
                                echo "<td></td>";
                                echo "<td><a title='Haz clic aquí si ha faltado esta hora.'  asiste='$datos[ID_PROFESOR],$datos[Fecha],$datos[Hora],0' class='actualiza' ><span class='glyphicon glyphicon-ok'></span></a></td>";
                                echo "<td><a title='Has clic aqui si tiene Actividad Extraescolar.' asiste='$datos[ID_PROFESOR],$datos[Fecha],$datos[Hora],2' class='actualiza' ><span class='glyphicon glyphicon-unchecked'></span></a></td>";
                            }
                            elseif($datos['Asiste'] == 2)
                            {
                                echo "<td></td>";
                                echo "<td><a title='Haz clic aquí si ha faltado esta hora.'  asiste='$datos[ID_PROFESOR],$datos[Fecha],$datos[Hora],0' class='actualiza' ><span class='glyphicon glyphicon-ok'></span></a></td>";
                                echo "<td><a title='Has clic aqui si no tiene Actividad Extraescolar.' asiste='$datos[ID_PROFESOR],$datos[Fecha],$datos[Hora],1' class='actualiza' ><span class='glyphicon glyphicon-check'></span></a></td>";
                            }
                            else
                            {
                                echo "<td><a title='Haz clic aquí si ha asistido esta hora.' asiste='$datos[ID_PROFESOR],$datos[Fecha],$datos[Hora],1' class='actualiza'><span class='glyphicon glyphicon-remove'></span></a></td>";
                                echo "<td></td>";
                                echo "<td></td>";
                            }
                            echo "</tr>";
                        }
                        echo "</tbody>";
                    echo "</table>";
                echo "</div>";
            echo "</div>";
        }
        else
        {
            echo "<h1>Asistencias lectivas de <b>$n</b></h1>";
            echo "<input id='busca_asiste' class='fadeIn' type='text' placeholder='Seleccionar fecha ...' autocomplete='off'>";
            echo "<div id='marcaje-response'></div>";
            echo "<div id='table-container'>";
                echo "<div id='full-table'>";
                    echo "<table class='table'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th style='vertical-align: middle; text-align: center;'>Fecha</th>";
                                echo "<th style='vertical-align: middle; text-align: center;'>Dia</th>";
                                echo "<th style='vertical-align: middle; text-align: center;'>Hora</th>";
                                echo "<th style='vertical-align: middle; text-align: center;'>Faltado</th>";
                                echo "<th style='vertical-align: middle; text-align: center;'>Asistido</th>";
                                echo "<th style='vertical-align: middle; text-align: center;'>Act. Extra.</th>";
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while($datos = $response->fetch_assoc())
                        {
                            if($datos['Asiste'] == 1)
                            {
                                $asisteColor = 'style="background-color: #84c584;"';
                            }
                            elseif($datos['Asiste'] == 2)
                            {
                                $asisteColor = 'style="background-color: #b5d9f5;"';
                            }
                            else
                            {
                                $asisteColor = 'style="background-color: #ff8c8c;"';
                            }
                            $sep = preg_split('/-/', $datos['Fecha']);
                            $dia = $sep[2];
                            $m = $sep[1];
                            $Y = $sep[0];
                            echo "<tr $asisteColor>";
                            echo "<td>$dia/$m/$Y</td>";
                            echo "<td>$datos[Diasemana]</td>";
                            echo "<td>$datos[Hora]</td>";
                            if($datos['Asiste'] == 1)
                            {
                                echo "<td></td>";
                                echo "<td><a title='Haz clic aquí para marcar esta hora como faltada.'  asiste='$datos[ID_PROFESOR],$datos[Fecha],$datos[Hora],0' class='actualiza' ><span class='glyphicon glyphicon-ok'></span></a></td>";
                                echo "<td><a title='Has clic aqui si tiene Actividad Extraescolar.' asiste='$datos[ID_PROFESOR],$datos[Fecha],$datos[Hora],2' class='actualiza' ><span class='glyphicon glyphicon-unchecked'></span></a></td>";
                            }
                            elseif($datos['Asiste'] == 2)
                            {
                                echo "<td></td>";
                                echo "<td><a title='Haz clic aquí para marcar esta hora como faltada.'  asiste='$datos[ID_PROFESOR],$datos[Fecha],$datos[Hora],0' class='actualiza' ><span class='glyphicon glyphicon-ok'></span></a></td>";
                                echo "<td><a title='Has clic aqui si no tiene Actividad Extraescolar.' asiste='$datos[ID_PROFESOR],$datos[Fecha],$datos[Hora],1' class='actualiza' ><span class='glyphicon glyphicon-check'></span></a></td>";
                            }
                            else
                            {
                                echo "<td><a title='Para marcar esta hora como asistida, contacte con Jefatura.'><span class='glyphicon glyphicon-remove'></span></a></td>";
                                echo "<td></td>";
                                echo "<td></td>";
                            }
                            echo "</tr>";
                        }
                        echo "</tbody>";
                    echo "</table>";
                echo "</div>";
            echo "</div>";
        }
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