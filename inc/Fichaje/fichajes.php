<?php

if(isset($_GET['ID']))
{
    $profesor = $_GET['ID'];
}
else
{
    $profesor = $_SESSION['ID'];
}
$sql = "SELECT DISTINCT $class->fichar.* 
        FROM $class->fichar
            INNER JOIN $class->profesores ON $class->profesores.ID=$class->fichar.ID_PROFESOR
        WHERE $class->profesores.ID='$profesor'
        ORDER BY $class->fichar.Fecha DESC, $class->fichar.F_entrada DESC
        ";
echo "<h1>Fichajes diarios</h1>";
if($response = $class->query($sql))
{
    echo "</br><table class='table table-striped'>";
        echo "<thead>";
            echo "<tr>";
                echo "<th style='vertical-align: middle; text-align: center;'>Hora Fichaje</th>";
                echo "<th style='vertical-align: middle; text-align: center;'>Día semana</th>";
                echo "<th class='hidden'>Fecha</th>";
            echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
    if ($response->num_rows > 0)
    {
        $fechaanterior = '';
        while ($fila = $response->fetch_assoc())
        {
            $sep = preg_split('/-/', $fila['Fecha']);
            $dia = $sep[2];
            $m = $sep[1];
            $Y = $sep[0];
            if($fila['Fecha'] != $fechaanterior)
            {
                echo "<tr style='background-color: #333;'>";
                echo "<td colspan='100%' style='vertical-align: middle; text-align: center; font-weight: bolder; color: white;'>$dia/$m/$Y</td>";
                echo "</tr>";
            }
            echo "<tr>";
                echo "<td>$fila[F_entrada]</td>";
                echo "<td>$fila[DIA_SEMANA]</td>";
                echo "<td class='hidden'>$dia/$m/$Y</td>";
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
}
else
{
    $ERR_MSG = $class->ERR_ASYSTECO;
}