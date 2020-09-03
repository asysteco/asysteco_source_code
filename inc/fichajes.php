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
        FROM ($class->fichar 
            INNER JOIN $class->horarios ON $class->fichar.ID_PROFESOR=$class->horarios.ID_PROFESOR) 
            INNER JOIN $class->profesores ON $class->profesores.ID=$class->horarios.ID_PROFESOR 
        WHERE $class->profesores.ID='$profesor' 
        ORDER BY $class->fichar.ID DESC 
        ";
echo "<h1>Fichajes diarios</h1>";
if($response = $class->query($sql))
{
    echo "</br><table class='table table-striped'>";
        echo "<thead>";
            echo "<tr>";
                echo "<th>Nº</th>";
                echo "<th>Hora Fichaje</th>";
                echo "<th>Día semana</th>";
                echo "<th>Fecha</th>";
            echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
    if ($response->num_rows > 0)
    {
        while ($fila = $response->fetch_assoc())
        {
            $sep = preg_split('/-/', $fila['Fecha']);
            $dia = $sep[2];
            $m = $sep[1];
            $Y = $sep[0];
            echo "<tr>";
                echo "<td>$fila[ID]</td>";
                echo "<td>$fila[F_entrada]</td>";
                echo "<td>$fila[DIA_SEMANA]</td>";
                echo "<td>$dia/$m/$Y</td>";
            echo "</tr>";
        }
    }
    else
    {
        echo "No existen registros de fichajes.";
    }
        echo "</tbody>";
    echo "</table>";
}
else
{
    $ERR_MSG = $class->ERR_ASYSTECO;
}