<?php

$profesor = $_GET['profesor'] ?? '';
$fechaInicio = $_GET['fechainicio'] ?? '';
$fechaFin = $_GET['fechafin'] ?? '';
$whereFilter = ' AND M.Fecha <= CURDATE()';
$errorMessage = '';
$element = $_GET['element'];
$page_size = 200;
$offset_var = $_GET['pag'];
$tituloListado = "Faltas Justificadas";

if (isset($profesor) && !empty($profesor)) {
    $whereFilter .= " AND M.ID_PROFESOR = $profesor";
}

if (isset($fechaInicio) && !empty($fechaInicio) && isset($fechaFin) && !empty($fechaFin)) {
    $fini = $class->formatEuropeanDateToSQLDate($fechaInicio);
    $ffin = $class->formatEuropeanDateToSQLDate($fechaFin);

    if($fini && $ffin) {
        $whereFilter .= " AND M.Fecha >= '$fini' AND M.Fecha <= '$ffin'";
    }
}

$query = "SELECT M.*, P.Nombre, P.Iniciales, P.TIPO, D.Diasemana, Ho.Inicio, Ho.Fin
FROM (Marcajes M INNER JOIN Profesores P ON M.ID_PROFESOR=P.ID)
    INNER JOIN Diasemana D ON M.Dia=D.ID
    INNER JOIN Horas Ho ON M.Hora=Ho.Hora
WHERE M.Asiste = 0
    AND Justificada = 1 $whereFilter
ORDER BY M.Fecha DESC, P.Nombre ASC, M.Hora ASC";

if(! $response = $class->query($query)) {
    $errorMessage = 'Ha ocurrido un error inesperado...';
}

$total_records = $response->num_rows;
$count=ceil($total_records/$page_size);

$mysql = $class->conex;
$mysql->autocommit(FALSE);

if (empty($errorMessage) && $response->num_rows > 0) {
    try {
        if(isset($offset_var)) {
            echo "<h2>$tituloListado</h2>";
            if($count > 1) {
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
            echo "</div>";
            }   
            $sql = "SELECT M.*, P.Nombre, P.Iniciales, P.TIPO, D.Diasemana, Ho.Inicio, Ho.Fin
            FROM (Marcajes M INNER JOIN Profesores P ON M.ID_PROFESOR=P.ID)
                INNER JOIN Diasemana D ON M.Dia=D.ID
                INNER JOIN Horas Ho ON M.Hora=Ho.Hora
                WHERE M.Asiste = 0
                    AND Justificada = 1 $whereFilter
            ORDER BY M.Fecha DESC, P.Nombre ASC, M.Hora ASC
            LIMIT $page_size OFFSET $offset_var";
            $result = $class->autocommitOffQuery($mysql, $sql, 'Ha ocurrido un error...');
            
            if ($result->num_rows > 0) {
                echo "<table class='table table-striped responsiveTable'>";
                    echo "<thead class='thead-dark'>";
                        echo "<tr>";
                            echo "<th>INICIALES</th>";
                            echo "<th>PROFESOR</th>";
                            echo "<th>FECHA</th>";
                            echo "<th>HORA</th>";
                            echo "<th>DIA</th>";
                            echo "<th>DIA SEMANA</th>";
                            echo "<th>ASISTENCIA</th>";
                            echo "<th>ACTIVIDAD EXTRAESCOLAR</th>";
                            echo "<th>JUSTIFICADA</th>";
                        echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
            
                while ($datos = $result->fetch_assoc())
                {
                    $horaInicio = $class->transformHoraMinutos($datos['Inicio']);
                    $horaFin = $class->transformHoraMinutos($datos['Fin']);

                    $fecha = $class->formatSQLDateToEuropeanDate($datos['Fecha']);
                    echo "<tr>";

                    $typeIcon = $datos['TIPO'] == 2? '<i class="fa fa-graduation-cap" aria-hidden="true" title="Profesorado"></i>': '<i class="fa fa-user personal-icon-azul" aria-hidden="true" title="Personal No Docente"></i>';

                    echo "<td class='text-left' data-th='INICIALES'>$typeIcon $datos[Iniciales]</td>";
                    echo "<td data-th='PROFESOR'>$datos[Nombre]</td>";
                    echo "<td data-th='FECHA'>$fecha</td>";
                    echo "<td data-th='HORA'>$horaInicio - $horaFin</td>";
                    echo "<td data-th='DIA'>$datos[Dia]</td>";
                    echo "<td data-th='DIA SEMANA'>$datos[Diasemana]</td>";
                    echo "<td data-th='ASISTENCIA'>NO</td>";
                    echo "<td data-th='ACTIVIDAD EXTRAESCOLAR'>NO</td>";
                    echo "<td data-th='JUSTIFICADA'>SI</td>";
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
