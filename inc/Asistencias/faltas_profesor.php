<?php

$sql = "SELECT Nombre FROM $class->profesores WHERE ID=" . mysqli_real_escape_string($class->conex, $_GET['ID']);
if($resp = $class->query($sql))
{
    $n = $resp->fetch_assoc();
    $n = $n['Nombre'];
    // La variable Fecha la utilizará como día límite desde que existen marcajes para mostrar los registros
    $fecha = date('Y-m-d');
    
    if($response = $class->query("SELECT Marcajes.*, Diasemana FROM Marcajes INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID WHERE ID_PROFESOR=" . mysqli_real_escape_string($class->conex, $_GET['ID']) . " AND Fecha <= '$fecha' ORDER BY Fecha DESC, Dia, Hora"))
    {
        echo "<h1>Asistencias</h1>";
        echo "<br><div id='table-container'>";
            echo "<div id='full-table'>";
            echo "<div class='table-responsive'>";
                echo "<table id='table-asistencias' class='table'>";
                    echo "<thead>";
                        echo "<tr>";
                            echo "<th class='d-none'>Fecha</th>";
                            echo "<th style='vertical-align: middle; text-align: center;'>Dia</th>";
                            echo "<th style='vertical-align: middle; text-align: center;'>Hora</th>";
                            echo "<th style='vertical-align: middle; text-align: center;'>Asistencia</th>";
                            echo "<th style='vertical-align: middle; text-align: center;'>Act. Extra.</th>";
                            echo "<th style='vertical-align: middle; text-align: center;'>Justificada</th>";
                        echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                        
                        if ($response->num_rows > 0)
                        {
                            $fechaanterior = '';
                            while($datos = $response->fetch_assoc())
                            {
                                $tipoKey = $datos['Tipo'];
                                $horaKey = $datos['Hora'];
                                $profesor = $datos['ID_PROFESOR'];
                                $fechaSQL = $datos['Fecha'];
                                $diasemana = $datos['Diasemana'];
                                $fechaFormateada = $class->formatSQLDateToEuropeanDate($fechaSQL);
                                $horaInicioHorario = $franjasHorarias[$tipoKey][$horaKey]['Inicio'];
                                $horaSinSegundos = $class->transformHoraMinutos($horaInicioHorario);

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

                                if($fechaSQL != $fechaanterior)
                                {
                                    echo "<tr style='background-color: #333;'>";
                                        echo "<td colspan='100%' style='vertical-align: middle; text-align: center; font-weight: bolder; color: white;'>$fechaFormateada</td>";
                                    echo "</tr>";
                                }

                                echo "<tr id='fila_" . "$profesor" . "_" . "$fechaSQL" . "_" . "$horaKey' $asisteColor>";
                                    echo "<td class='d-none'>$fechaFormateada</td>";
                                    echo "<td>$diasemana</td>";
                                    echo "<td>$horaSinSegundos</td>";

                                if($datos['Asiste'] == 1)
                                {
                                    echo "<td>
                                        <a title='Haz clic aquí si ha faltado esta hora.' asiste='$profesor,$fechaSQL,$horaKey,Asiste,0' class='actualiza asiste marcaje'>
                                            <span style='font-size: 25px; vertical-align: middle;' class='fa fa-check add_icon'></span>
                                        </a>
                                    </td>";
                                    echo "<td>
                                        <a title='Has clic aqui si tiene Actividad Extraescolar.' asiste='$profesor,$fechaSQL,$horaKey,Asiste,2' class='actualiza extra marcaje' >
                                            <span style='font-size: 25px; vertical-align: middle;'  class='fa fa-square-o'></span>
                                        </a>
                                    </td>";
                                    echo "<td></td>";
                                }
                                elseif($datos['Asiste'] == 2)
                                {
                                    echo "<td>
                                        <a title='Haz clic aquí si ha faltado esta hora.'  asiste='$profesor,$fechaSQL,$horaKey,Asiste,0' class='actualiza asiste marcaje'>
                                            <span style='font-size: 25px; vertical-align: middle;' class='fa fa-check'></span>
                                        </a>
                                    </td>";
                                    echo "<td>
                                        <a title='Has clic aqui si no tiene Actividad Extraescolar.' asiste='$profesor,$fechaSQL,$horaKey,Asiste,1' class='actualiza extra marcaje' >
                                            <span style='font-size: 25px; vertical-align: middle;' class='fa fa-check-square-o'></span>
                                        </a>
                                    </td>";
                                    echo "<td></td>";
                                }
                                else
                                {
                                    if($_SESSION['Perfil'] == 'Admin')
                                    {
                                        echo "<td>
                                            <a title='Haz clic aquí si ha asistido esta hora.' asiste='$profesor,$fechaSQL,$horaKey,Asiste,1' class='actualiza asiste marcaje'>
                                                <span style='font-size: 25px; vertical-align: middle;' class='fa fa-times'></span>
                                            </a>
                                        </td>";
                                        echo "<td class='extrabox'></td>";
                                        if($datos['Justificada'] == 1)
                                        {
                                            echo "<td>
                                                <a title='Haz clic aquí para retirar justificación.'  asiste='$profesor,$fechaSQL,$horaKey,Justificada,0' class='actualiza justifica marcaje'>
                                                    <span style='font-size: 25px; vertical-align: middle;' class='fa fa-check'></span>
                                                </a>
                                            </td>";
                                        }
                                        else
                                        {
                                            echo "<td>
                                                <a title='Haz clic aquí para justificar.'  asiste='$profesor,$fechaSQL,$horaKey,Justificada,1' class='actualiza justifica marcaje'>
                                                    <span style='font-size: 25px; vertical-align: middle;' class='fa fa-times'></span>
                                                </a>
                                            </td>";
                                        }
                                    }
                                    else
                                    {
                                        echo "<td>
                                            <span style='font-size: 25px; vertical-align: middle;' class='fa fa-times' title='Para marcar esta hora como asistida, contacte con Jefatura.'></span>
                                        </td>";
                                        echo "<td></td>";
                                        if($datos['Justificada'] == 1)
                                        {
                                            echo "<td><span style='font-size: 25px; vertical-align: middle;' class='fa fa-check'></span></td>";
                                        }
                                        else
                                        {
                                            echo "<td><span style='font-size: 25px; vertical-align: middle;' class='fa fa-times' title='Contacte con jefatura para justificar.'></span></td>";
                                        }
                                    }
                                }
                                echo "</tr>";
                                $fechaanterior = $fechaSQL;
                            }
                        } else {
                            echo "<td colspan='100%' style='text-align: center; background-color: rgba(0,0,0,.05);'>No existen registros de faltas.</td>";
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