<?php

if (isset($_GET['ID']) && $_SESSION['Perfil'] === 'Admin') {
    $profesor = $_GET['ID'];
} else {
    $profesor = $_SESSION['ID'];
}

$sql = "SELECT Nombre
FROM $class->profesores
WHERE ID=" . mysqli_real_escape_string($class->conex, $profesor);
if($resp = $class->query($sql))
{
    $n = $resp->fetch_assoc();
    $n = $n['Nombre'];

    $sql = "SELECT m.*, d.Diasemana, DATE_FORMAT(h.Inicio, '%H:%i') Inicio, DATE_FORMAT(h.Fin, '%H:%i') Fin
            FROM Marcajes m
                INNER JOIN Diasemana d ON m.Dia = d.ID
                INNER JOIN Horas h ON m.Hora = h.Hora AND m.Tipo = h.Tipo
            WHERE ID_PROFESOR = $profesor
                AND m.Fecha <= CURDATE()
            ORDER BY m.Fecha DESC, m.Dia, m.Hora";
    if($response = $class->query($sql)) {
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
                        
                        if ($response->num_rows > 0) {
                            $fechaanterior = '';
                            while($datos = $response->fetch_assoc()) {
                                $tipo = $datos['Tipo'];
                                $hora = $datos['Hora'];
                                $inicio = $datos['Inicio'];
                                $fin = $datos['Fin'];
                                $profesor = $datos['ID_PROFESOR'];
                                $fechaSQL = $datos['Fecha'];
                                $diasemana = $datos['Diasemana'];
                                $fechaFormateada = $class->formatSQLDateToEuropeanDate($fechaSQL);

                                if($datos['Asiste'] == 1) {
                                    $colorMarcaje = 'asistido';
                                } elseif($datos['Asiste'] == 2) {
                                    $colorMarcaje = 'extraescolar';
                                } elseif($datos['Asiste'] == 0 && $datos['Justificada'] == 1) {
                                    $colorMarcaje = 'faltado-justificado';
                                } else {
                                    $colorMarcaje = 'faltado';
                                }

                                if($fechaSQL != $fechaanterior) {
                                    echo "<tr style='background-color: #333;'>";
                                        echo "<td colspan='100%' style='vertical-align: middle; text-align: center; font-weight: bolder; color: white;'>$fechaFormateada</td>";
                                    echo "</tr>";
                                }

                                echo "<tr id='fila_" . "$profesor" . "_" . "$fechaSQL" . "_" . "$hora' class='$colorMarcaje'>";
                                    echo "<td class='d-none'>$fechaFormateada</td>";
                                    echo "<td>$diasemana</td>";
                                    echo "<td>$inicio</td>";

                                if($datos['Asiste'] == 1) {
                                    echo "<td>
                                        <a title='Haz clic aquí si ha faltado esta hora.'
                                        class='actualiza asiste marcaje'
                                        data-id='$profesor'
                                        data-date='$fechaSQL'
                                        data-hour='$hora'
                                        data-startHour='$inicio'
                                        data-endHour='$fin'
                                        data-type='$tipo'
                                        data-action='Asiste'
                                        data-value='0'>
                                            <span style='font-size: 25px; vertical-align: middle;' class='fa fa-check add_icon'></span>
                                        </a>
                                    </td>";
                                    echo "<td>
                                        <a title='Has clic aqui si tiene Actividad Extraescolar.'
                                        class='actualiza extra marcaje'
                                        data-id='$profesor'
                                        data-date='$fechaSQL'
                                        data-hour='$hora'
                                        data-startHour='$inicio'
                                        data-endHour='$fin'
                                        data-type='$tipo'
                                        data-action='Asiste'
                                        data-value='2'>
                                            <span style='font-size: 25px; vertical-align: middle;'  class='fa fa-square-o'></span>
                                        </a>
                                    </td>";
                                    echo "<td></td>";
                                } elseif($datos['Asiste'] == 2) {
                                    echo "<td>
                                        <a title='Haz clic aquí si ha faltado esta hora.'
                                        class='actualiza asiste marcaje'
                                        data-id='$profesor'
                                        data-date='$fechaSQL'
                                        data-hour='$hora'
                                        data-startHour='$inicio'
                                        data-endHour='$fin'
                                        data-type='$tipo'
                                        data-action='Asiste'
                                        data-value='0'>
                                            <span style='font-size: 25px; vertical-align: middle;' class='fa fa-check'></span>
                                        </a>
                                    </td>";
                                    echo "<td>
                                        <a title='Has clic aqui si no tiene Actividad Extraescolar.'
                                        class='actualiza extra marcaje'
                                        data-id='$profesor'
                                        data-date='$fechaSQL'
                                        data-hour='$hora'
                                        data-startHour='$inicio'
                                        data-endHour='$fin'
                                        data-type='$tipo'
                                        data-action='Asiste'
                                        data-value='1'>
                                            <span style='font-size: 25px; vertical-align: middle;' class='fa fa-check-square-o'></span>
                                        </a>
                                    </td>";
                                    echo "<td></td>";
                                } else {
                                    if($_SESSION['Perfil'] == 'Admin') {
                                        echo "<td>
                                            <a title='Haz clic aquí si ha asistido esta hora.'
                                            class='actualiza asiste marcaje'
                                            data-id='$profesor'
                                            data-date='$fechaSQL'
                                            data-hour='$hora'
                                            data-startHour='$inicio'
                                            data-endHour='$fin'
                                            data-type='$tipo'
                                            data-action='Asiste'
                                            data-value='1'>
                                                <span style='font-size: 25px; vertical-align: middle;' class='fa fa-times'></span>
                                            </a>
                                        </td>";
                                        echo "<td class='extrabox'></td>";
                                        if($datos['Justificada'] == 1) {
                                            echo "<td>
                                                <a title='Haz clic aquí para retirar justificación.'
                                                class='actualiza justifica marcaje'
                                                data-id='$profesor'
                                                data-date='$fechaSQL'
                                                data-hour='$hora'
                                                data-startHour='$inicio'
                                                data-endHour='$fin'
                                                data-type='$tipo'
                                                data-action='Justificada'
                                                data-value='0'>
                                                    <span style='font-size: 25px; vertical-align: middle;' class='fa fa-check'></span>
                                                </a>
                                            </td>";
                                        } else {
                                            echo "<td>
                                                <a title='Haz clic aquí para justificar.'
                                                class='actualiza justifica marcaje'
                                                data-id='$profesor'
                                                data-date='$fechaSQL'
                                                data-hour='$hora'
                                                data-startHour='$inicio'
                                                data-endHour='$fin'
                                                data-type='$tipo'
                                                data-action='Justificada'
                                                data-value='1'>
                                                    <span style='font-size: 25px; vertical-align: middle;' class='fa fa-times'></span>
                                                </a>
                                            </td>";
                                        }
                                    } else {
                                        echo "<td>
                                            <span style='font-size: 25px; vertical-align: middle;' class='fa fa-times' title='Para marcar esta hora como asistida, contacte con Jefatura.'></span>
                                        </td>";
                                        echo "<td></td>";
                                        if($datos['Justificada'] == 1) {
                                            echo "<td><span style='font-size: 25px; vertical-align: middle;' class='fa fa-check'></span></td>";
                                        } else {
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
    } else {
        $ERR_MSG = $class->ERR_ASYSTECO;
    }
} else {
    $ERR_MSG = $class->ERR_ASYSTECO;
}
