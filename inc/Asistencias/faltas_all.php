<?php

$fecha = date('Y-m-d');
// La variable Fecha la utilizará como día límite desde que existen marcajes para mostrar los registros
// La siguiente línea la utilizaremos para realizar pruebas

if($response = $class->query("SELECT DISTINCT Nombre, Diasemana, Fecha FROM (Marcajes INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID) INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID WHERE NOT EXISTS (SELECT * FROM Fichar WHERE Fichar.ID_PROFESOR=Profesores.ID AND Marcajes.Fecha=Fichar.Fecha) AND Fecha <= '$fecha' ORDER BY Fecha DESC, Nombre ASC"))
{
        echo "<h1>Faltas</h1>";
        echo "<br><div id='table-container'>";
            echo "<div id='full-table' class='table-responsive'>";
                echo "<table id='table-asistencias' class='table'>";
                    echo "<thead>";
                        echo "<tr>";
                            echo "<th style='vertical-align: middle; text-align: center;'>Profesor/Personal</th>";
                            echo "<th style='vertical-align: middle; text-align: center;'>Día</th>";
                            echo "<th class='d-none'>Fecha</th>";
                        echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    if($response->num_rows > 0)
                    {
                        $fechaanterior = '';
                        while($datos = $response->fetch_assoc())
                        {
                            $sep = preg_split('/-/', $datos['Fecha']);
                            $dia = $sep[2];
                            $m = $sep[1];
                            $Y = $sep[0];
                            if($datos['Fecha'] != $fechaanterior)
                            {
                                echo "<tr style='background-color: #333;'>";
                                echo "<td colspan='100%' style='vertical-align: middle; text-align: center; font-weight: bolder; color: white;'>$dia/$m/$Y</td>";
                                echo "</tr>";
                            }
                            echo "<tr>";
                            echo "<td>$datos[Nombre]</td>";
                            echo "<td>$datos[Diasemana]</td>";
                            echo "<td class='d-none'>$dia/$m/$Y</td>";
                            echo "</tr>";
                            $fechaanterior = $datos['Fecha'];
                        }
                    }
                    else
                    {
                        echo "<td colspan='100%' style='text-align: center;'>No existen registros de faltas.</td>";
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