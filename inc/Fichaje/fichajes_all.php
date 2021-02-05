<?php

$sql = "SELECT DISTINCT f.*, p.Nombre
        FROM Fichar f
            INNER JOIN Profesores p ON p.ID=f.ID_PROFESOR
        ORDER BY f.Fecha DESC, f.F_entrada DESC 
        ";
echo "<h1>Fichajes</h1>";
if($response = $class->query($sql))
{
    echo "<div class='table-responsive'>";
        echo "</br><table id='table-fichajes' class='table table-striped'>";
            echo "<thead>";
                echo "<tr>";
                    echo "<th style='vertical-align: middle; text-align: center;'>Profesor/Personal</th>";
                    echo "<th style='vertical-align: middle; text-align: center;'>Hora Fichaje</th>";
                    echo "<th style='vertical-align: middle; text-align: center;'>Hora Salida</th>";
                    echo "<th style='vertical-align: middle; text-align: center;'>Día semana</th>";
                    echo "<th class='d-none'>Fecha</th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
        if ($response->num_rows > 0)
        {
            $fechaanterior = '';
            while ($fila = $response->fetch_assoc())
            {
                $fecha = $class->formatSQLDateToEuropeanDate($fila['Fecha']);
                if($fila['Fecha'] != $fechaanterior)
                {
                    echo "<tr style='background-color: #333;'>";
                    echo "<td colspan='100%' style='vertical-align: middle; text-align: center; font-weight: bolder; color: white;'>$fecha</td>";
                    echo "</tr>";
                }
                echo "<tr>";
                    echo "<td>$fila[Nombre]</td>";
                    echo "<td>$fila[F_entrada]</td>";
                    echo "<td>$fila[F_Salida]</td>";
                    echo "<td>$fila[DIA_SEMANA]</td>";
                    echo "<td class='d-none'>$fecha</td>";
                echo "</tr>";
                $fechaanterior = $fila['Fecha'];
            }
        }
        else
        {
            echo "<td colspan='100%'>No existen registros de fichajes.</td>";
        }
            echo "</tbody>";
        echo "</table>";
    echo "</div>";
}
else
{
    $ERR_MSG = $class->ERR_ASYSTECO;
}