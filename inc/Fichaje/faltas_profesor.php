<?php

$sql = "SELECT Nombre FROM $class->profesores WHERE ID=" . mysqli_real_escape_string($class->conex, $_GET['ID']);
if($resp = $class->query($sql))
{
    $n = $resp->fetch_assoc();
    $n = $n['Nombre'];
    $fecha = date('Y-m-d');
    // La variable Fecha la utilizará como día límite desde que existen marcajes para mostrar los registros
    // La siguiente línea la utilizaremos para realizar pruebas
    
    if($response = $class->query("SELECT Marcajes.*, Diasemana FROM Marcajes INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID WHERE ID_PROFESOR=" . mysqli_real_escape_string($class->conex, $_GET['ID']) . " AND Fecha <= '$fecha' ORDER BY Fecha DESC, Dia, Hora"))
    {
        echo "<h1>Asistencias lectivas de <b>$n</b></h1>";
        echo "<input id='busca_asiste' class='fadeIn' type='text' placeholder='Seleccionar fecha ...' autocomplete='off'>";
        echo "<div id='marcaje-response'></div>";
        echo "<div id='table-container'>";
            echo "<div id='full-table'>";
            echo "<div class='table-responsive'>";
                echo "<table class='table'>";
                    echo "<thead>";
                        echo "<tr>";
                            echo "<th  class='d-none'>Fecha</th>";
                            echo "<th style='vertical-align: middle; text-align: center;'>Dia</th>";
                            echo "<th style='vertical-align: middle; text-align: center;'>Hora</th>";
                            echo "<th style='vertical-align: middle; text-align: center;'>Asistencia</th>";
                            echo "<th style='vertical-align: middle; text-align: center;'>Act. Extra.</th>";
                            echo "<th style='vertical-align: middle; text-align: center;'>Justificada</th>";
                        echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                        $fechaanterior = '';
                        while($datos = $response->fetch_assoc())
                        {
                            $sep = preg_split('/-/', $datos['Fecha']);
                            $dia = $sep[2];
                            $m = $sep[1];
                            $Y = $sep[0];
                            $tipoKey = $datos['Tipo'];
                            $horaKey = $datos['Hora'];
                            $horaInicioHorario = $franjasHorarias[$tipoKey][$horaKey]['Inicio'];
                            $horaSplit = preg_split('/:/', $horaInicioHorario);
                            $horaSinSegundos = $horaSplit[0] . ":" . $horaSplit[1];
                            if($datos['Asiste'] == 1)
                            {
                                $asisteColor = 'style="background-color: #E2F0CB;"';
                            }
                            elseif($datos['Asiste'] == 2)
                            {
                                $asisteColor = 'style="background-color: #B5EAD7;"';
                            }
                            else
                            {
                                $asisteColor = 'style="background-color: #FF9AA2;"';
                            }

                            if($datos['Fecha'] != $fechaanterior)
                            {
                                echo "<tr style='background-color: #333;'>";
                                echo "<td colspan='100%' style='vertical-align: middle; text-align: center; font-weight: bolder; color: white;'>$dia/$m/$Y</td>";
                                echo "</tr>";
                            }

                            echo "<tr id='fila_$datos[ID_PROFESOR]_$datos[Fecha]_$datos[Hora]' $asisteColor>";
                                echo "<td class='d-none'>$dia/$m/$Y</td>";
                                echo "<td>$datos[Diasemana]</td>";
                                echo "<td>{$horaSinSegundos}</td>";

                            if($datos['Asiste'] == 1)
                            {
                                echo "<td><a data-toggle='tooltip' title='Haz clic aquí si ha faltado esta hora.'  asiste='$datos[ID_PROFESOR],$datos[Fecha],$datos[Hora],Asiste,0' class='actualiza asiste'><span style='font-size: 25px; vertical-align: middle;' class='fa fa-check add_icon'></span></a></td>";
                                echo "<td><a data-toggle='tooltip' title='Has clic aqui si tiene Actividad Extraescolar.' asiste='$datos[ID_PROFESOR],$datos[Fecha],$datos[Hora],Asiste,2' class='actualiza extra' ><span style='font-size: 25px; vertical-align: middle;'  class='fa fa-square-o'></span></a></td>";
                                echo "<td></td>";
                            }
                            elseif($datos['Asiste'] == 2)
                            {
                                echo "<td><a data-toggle='tooltip' title='Haz clic aquí si ha faltado esta hora.'  asiste='$datos[ID_PROFESOR],$datos[Fecha],$datos[Hora],Asiste,0' class='actualiza asiste'><span style='font-size: 25px; vertical-align: middle;' class='fa fa-check'></span></a></td>";
                                echo "<td><a data-toggle='tooltip' title='Has clic aqui si no tiene Actividad Extraescolar.' asiste='$datos[ID_PROFESOR],$datos[Fecha],$datos[Hora],Asiste,1' class='actualiza extra' ><span style='font-size: 25px; vertical-align: middle;' class='fa fa-check-square-o'></span></a></td>";
                                echo "<td></td>";
                            }
                            else
                            {
                                if($_SESSION['Perfil'] == 'Admin')
                                {
                                    echo "<td><a data-toggle='tooltip' title='Haz clic aquí si ha asistido esta hora.' asiste='$datos[ID_PROFESOR],$datos[Fecha],$datos[Hora],Asiste,1' class='actualiza asiste'><span style='font-size: 25px; vertical-align: middle;' class='fa fa-times'></span></a></td>";
                                    echo "<td class='extrabox'></td>";
                                    if($datos['Justificada'] == 1)
                                    {
                                        echo "<td><a data-toggle='tooltip' title='Haz clic aquí para retirar justificación.'  asiste='$datos[ID_PROFESOR],$datos[Fecha],$datos[Hora],Justificada,0' class='actualiza justifica'><span style='font-size: 25px; vertical-align: middle;' class='fa fa-check'></span></a></td>";
                                    }
                                    else
                                    {
                                        echo "<td><a data-toggle='tooltip' title='Haz clic aquí para justificar.'  asiste='$datos[ID_PROFESOR],$datos[Fecha],$datos[Hora],Justificada,1' class='actualiza justifica'><span style='font-size: 25px; vertical-align: middle;' class='fa fa-times'></span></a></td>";
                                    }
                                }
                                else
                                {
                                    echo "<td><span data-toggle='tooltip' style='font-size: 25px; vertical-align: middle;' class='fa fa-times' title='Para marcar esta hora como asistida, contacte con Jefatura.'></span></td>";
                                    echo "<td></td>";
                                    if($datos['Justificada'] == 1)
                                    {
                                        echo "<td><span style='font-size: 25px; vertical-align: middle;' class='fa fa-check'></span></td>";
                                    }
                                    else
                                    {
                                        echo "<td><span data-toggle='tooltip' style='font-size: 25px; vertical-align: middle;' class='fa fa-times' title='Contacte con jefatura para justificar.'></span></td>";
                                    }
                                }
                            }
                            echo "</tr>";
                            $fechaanterior = $datos['Fecha'];
                        }
                        echo "</tbody>";
                    echo "</table>";
                    echo "</div>";
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