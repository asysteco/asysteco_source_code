<?php

$profesor = $_GET['profesor'] ?? '';
$fechaInicio = $_GET['fechainicio'] ?? '';
$fechaFin = $_GET['fechafin'] ?? '';
$whereFilter = ' WHERE F.Fecha <= CURDATE()';
$errorMessage = '';
$element = $_GET['element'];
$page_size = 200;
$offset_var = $_GET['pag'];

if (isset($profesor) && !empty($profesor)) {
    $whereFilter .= " AND F.ID_PROFESOR = $profesor";
}

if (isset($fechaInicio) && !empty($fechaInicio) && isset($fechaFin) && !empty($fechaFin)) {
    $fini = $class->formatEuropeanDateToSQLDate($fechaInicio);
    $ffin = $class->formatEuropeanDateToSQLDate($fechaFin);

    if($fini && $ffin) {
        $whereFilter .= " AND F.Fecha >= '$fini' AND F.Fecha <= '$ffin'";
    }
}
$query = "SELECT F.ID_PROFESOR FROM Fichar F INNER JOIN Profesores P ON F.ID_PROFESOR=P.ID $whereFilter";

if(!$response = $class->query($query))
{
    $errorMessage = 'Ha ocurrido un error inesperado...';
}

$total_records = $response->num_rows;
$count=ceil($total_records/$page_size);

$mysql = $class->conex;
$mysql->autocommit(FALSE);

if (empty($errorMessage) && $response->num_rows > 0) {
    try {
        if(isset($offset_var)) {
            if ($count > 1) {
                echo "<div class='páginas' style='margin-top: 25px;'>";
                    echo "<h3>Página ";
                    echo "<select id='select_pag'>";
                    for($j=0; $j<$count; $j++) {
                        $currentPage = $j*$page_size;
                        $selected = $offset_var == $j*$page_size ? 'selected' : '';
                        echo "<option value='$currentPage' action='select' element='$element' profesor='$profesor' start='$fechaInicio' end='$fechaFin' $selected>";
                            echo $pag = ($j+1);
                        echo '</option> ';
                    }
                    echo "</select>";
                    echo "</h3>";
                echo "<div>";
            }
            $sql = "SELECT F.ID_PROFESOR, P.Nombre, F.F_entrada, F.F_Salida, F.DIA_SEMANA, F.Fecha
            FROM (Fichar F INNER JOIN Profesores P ON F.ID_PROFESOR=P.ID)
            $whereFilter
            ORDER BY F.Fecha DESC, F.F_entrada ASC, P.Nombre ASC
            LIMIT $page_size OFFSET $offset_var";
            $result = $class->autocommitOffQuery($mysql, $sql, 'Ha ocurrido un error...');
            
            if($result->num_rows > 0) {
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
            }
        }
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
        $class->conex->rollback();
    }
    $class->conex->commit();
} else {
    echo "<h2 style='color: grey;'><i>No existen datos que mostrar.</i></h2>";
}
if (!empty($errorMessage)) {
    echo $errorMessage;
}
