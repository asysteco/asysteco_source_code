<?php

$fechahoy = date('Y-m-d');
$sql = "SELECT F.ID_PROFESOR, P.Nombre, F.F_entrada, F.F_Salida, F.DIA_SEMANA, F.Fecha
FROM (Fichar F INNER JOIN Profesores P ON F.ID_PROFESOR=P.ID)
WHERE F.Fecha = '$fechahoy'
ORDER BY F.F_entrada DESC, P.Nombre ASC";

if ($result =  $class->query($sql)) {
    if ($result->num_rows > 0) {
        echo "<table class='table table-striped responsiveTable'>";
            echo "<thead class='thead-dark'>";
                echo "<tr>";
                    echo "<th>NOMBRE</th>";
                    echo "<th>FICHAJE DE ENTRADA</th>";
                    echo "<th>FICHAJE DE SALIDA</th>";
                    echo "<th>DIA SEMANA</th>";
                    echo "<th>FECHA</th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while ($datos = $result->fetch_assoc())
            {
                $fecha = $class->formatSQLDateToEuropeanDate($datos['Fecha']);
                echo "<tr>";
                    echo "<td data-th='NOMBRE'>$datos[Nombre]</td>";
                    echo "<td data-th='FICHAJE DE ENTRADA'>$datos[F_entrada]</td>";
                    echo "<td data-th='FICHAJE DE SALIDA'>$datos[F_Salida]</td>";
                    echo "<td data-th='DIA SEMANA'>$datos[DIA_SEMANA]</td>";
                    echo "<td data-th='FECHA'>$fecha</td>";
                echo "</tr>";
            }
            echo "</tbody>";
        echo "</table>";
    } else {
        echo "<h2 style='color: grey;'><i>No existen datos que mostrar.</i></h2>";
    }
}
