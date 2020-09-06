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
            echo "<h1>Asistencias lectivas de <b>$n</b></h1>";
            echo "<input id='busca_asiste' class='fadeIn' type='text' placeholder='Seleccionar fecha ...' autocomplete='off'>";
            echo "<div id='marcaje-response'></div>";
            echo "<div id='table-container'>";
                echo "<div id='full-table'>";
                    echo "<table class='table'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th>Fecha</th>";
                                echo "<th>Dia</th>";
                                echo "<th>Hora</th>";
                                echo "<th>Asistencia</th>";
                                echo "<th>Faltado</th>";
                                echo "<th>Asistido</th>";
                                echo "<th>Act. Extra.</th>";
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while($datos = $response->fetch_assoc())
                        {
                            $sep = preg_split('/-/', $datos['Fecha']);
                            $dia = $sep[2];
                            $m = $sep[1];
                            $Y = $sep[0];
                            echo "<tr>";
                            echo "<td>$dia/$m/$Y</td>";
                            echo "<td>$datos[Diasemana]</td>";
                            echo "<td>$datos[Hora]</td>";
                            if($datos['Asiste'] == 1)
                            {
                                echo "<td>Si</td>";
                                echo "<td></td>";
                                echo "<td><a title='Haz clic aquí si ha faltado esta hora.'  asiste='$datos[ID_PROFESOR],$datos[Fecha],$datos[Hora],0' class='actualiza' ><span class='glyphicon glyphicon-ok'></span></a></td>";
                                echo "<td><a title='Has clic aqui si tiene Actividad Extraescolar.' asiste='$datos[ID_PROFESOR],$datos[Fecha],$datos[Hora],2' class='actualiza' ><span class='glyphicon glyphicon-unchecked'></span></a></td>";
                            }
                            elseif($datos['Asiste'] == 2)
                            {
                                echo "<td>Si</td>";
                                echo "<td></td>";
                                echo "<td><a title='Haz clic aquí si ha faltado esta hora.'  asiste='$datos[ID_PROFESOR],$datos[Fecha],$datos[Hora],0' class='actualiza' ><span class='glyphicon glyphicon-ok'></span></a></td>";
                                echo "<td><a title='Has clic aqui si no tiene Actividad Extraescolar.' asiste='$datos[ID_PROFESOR],$datos[Fecha],$datos[Hora],1' class='actualiza' ><span class='glyphicon glyphicon-check'></span></a></td>";
                            }
                            else
                            {
                                echo "<td>No</td>";
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
        $ERR_MSG = $class->ERR_ASYSTECO;
    }
}
else
{
    $ERR_MSG = $class->ERR_ASYSTECO;
}

?>