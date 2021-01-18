<?php

$profesor = $_GET['profesor'] ?? '';
$fechaInicio = $_GET['fechainicio'] ?? '';
$fechaFin = $_GET['fechafin'] ?? '';
$whereFilter = '';
$errorMessage = '';
$element = $_GET['element'] ?? '';
$edificios = $options['edificios'];
$offset_var = $_GET['pag'];
$page_size = 200;

if (isset($profesor) && !empty($profesor)) {
    $whereFilter = " WHERE P.ID = $profesor";
}

$query = "SELECT P.Iniciales, P.Nombre, D.Diasemana, A.Nombre as Aula, C.Nombre as Grupo, H.Dia, H.Hora
FROM Horarios H INNER JOIN Profesores P ON H.ID_PROFESOR=P.ID
INNER JOIN Diasemana D ON H.Dia=D.ID
INNER JOIN Aulas A ON A.ID=H.Aula
INNER JOIN Cursos C ON C.ID=H.Grupo
$whereFilter 
ORDER BY P.Nombre ASC, H.Hora";

if(! $response = $class->query($query))
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
                        echo "<option value='$currentPage' action='select' element='$element' profesor='$profesor' $selected>";
                            echo $pag = ($j+1);
                        echo '</option> ';
                    }
                    echo "</select>";
                    echo "</h3>";
                echo "<div>";
            }
            $sql = "SELECT P.Iniciales, P.Nombre, D.Diasemana, A.Nombre as Aula, C.Nombre as Grupo, H.Dia, H.Hora
            FROM Horarios H INNER JOIN Profesores P ON H.ID_PROFESOR=P.ID
            INNER JOIN Diasemana D ON H.Dia=D.ID
            INNER JOIN Aulas A ON A.ID=H.Aula
            INNER JOIN Cursos C ON C.ID=H.Grupo
            $whereFilter 
            ORDER BY P.Nombre ASC, H.Hora
            LIMIT $page_size OFFSET $offset_var";
            if (!$result = $mysql->query($sql)) {
                throw new Exception('No existen datos para exportar...');
            }
            if($result->num_rows > 0) {
                echo "<table class='table table-striped responsiveTable'>";
                    echo "<thead class='thead-dark'>";
                        echo "<tr>";
                            echo "<th>INICIALES</th>";
                            echo "<th>PROFESOR</th>";
                            echo "<th>CURSO</th>";
                            echo "<th>AULA</th>";
                            echo "<th>DIA</th>";
                            echo "<th>DIA SEMANA</th>";
                            echo "<th>HORA</th>";
                if (isset($edificios) && $edificios > 1) {
                    echo "<th>EDIFICIO</th>";
                } 
                        echo "</tr>";
                    echo "</thead>";
                echo "<tbody>";
                while ($datos = $result->fetch_assoc())
                {
                    echo "<tr>";
                        echo "<td data-th='INICIALES'>$datos[Iniciales]</td>";
                        echo "<td data-th='PROFESOR'>$datos[Nombre]</td>";
                        echo "<td data-th='CURSO'>$datos[Grupo]</td>";
                        echo "<td data-th='AULA'>$datos[Aula]</td>";
                        echo "<td data-th='DIA'>$datos[Dia]</td>";
                        echo "<td data-th='DIA SEMANA'>$datos[Diasemana]</td>";
                        echo "<td data-th='HORA'>$datos[Hora]</td>";
                    if (isset($edificios) && $edificios > 1) {
                            echo "<td data-th='EDIFICIO'>$datos[Edificio]</td>";
                    }
                    echo "</tr>";
                }
                    echo "</tbody>";
                echo "</table>";
            }
        }
    } catch (Exception $e) {
        $errorMessage = $e;
        $class->conex->rollback();
    }
    $class->conex->commit();
} else {
    echo "<h2 style='color: grey;'><i>No existen datos que mostrar.</i></h2>";
}
if (!empty($errorMessage)) {
    echo $errorMessage;
}
