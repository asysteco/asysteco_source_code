<?php

$profesor = $_GET['profesor'] ?? '';
$fechaInicio = $_GET['fechainicio'] ?? '';
$fechaFin = $_GET['fechafin'] ?? '';
$whereFilter = ' AND M.Fecha <= CURDATE()';
$errorMessage = '';
$element = $_GET['element'];
$page_size = 200;
$offset_var = $_GET['pag'];
$tituloListado = "Faltas Injustificadas";

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

$query = "SELECT M.*, P.Nombre, P.Iniciales, D.Diasemana, Ho.Inicio, Ho.Fin
FROM (Marcajes M INNER JOIN Profesores P ON M.ID_PROFESOR=P.ID)
    INNER JOIN Diasemana D ON M.Dia=D.ID
    INNER JOIN Horas Ho ON M.Hora=Ho.Hora
WHERE M.Asiste = 0
    AND Justificada = 0 $whereFilter
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
            $sql = "SELECT M.*, P.Nombre, P.Iniciales, D.Diasemana, Ho.Inicio, Ho.Fin
            FROM (Marcajes M INNER JOIN Profesores P ON M.ID_PROFESOR=P.ID)
                INNER JOIN Diasemana D ON M.Dia=D.ID
                INNER JOIN Horas Ho ON M.Hora=Ho.Hora
                WHERE M.Asiste = 0
                    AND Justificada = 0 $whereFilter
            ORDER BY M.Fecha DESC, P.Nombre ASC, M.Hora ASC
            LIMIT $page_size OFFSET $offset_var";
            if (!$result = $mysql->query($sql)) {
                throw new Exception('Ha ocurrido un error...');
            }
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
                        echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
            
                while ($datos = $result->fetch_assoc())
                {
                    $horaInicio = $class->transformHoraMinutos($datos['Inicio']);
                    $horaFin = $class->transformHoraMinutos($datos['Fin']);

                    $fecha = $class->formatSQLDateToEuropeanDate($datos['Fecha']);
                    echo "<tr>";
                        echo "<td data-th='INICIALES'>$datos[Iniciales]</td>";
                        echo "<td data-th='PROFESOR'>$datos[Nombre]</td>";
                        echo "<td data-th='FECHA'>$fecha</td>";
                        echo "<td data-th='HORA'>$horaInicio - $horaFin</td>";
                        echo "<td data-th='DIA'>$datos[Dia]</td>";
                        echo "<td data-th='DIA SEMANA'>$datos[Diasemana]</td>";
                        echo "<td data-th='ASISTENCIA'>NO</td>";
                        echo "<td data-th='ACTIVIDAD EXTRAESCOLAR'>NO</td>";
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

echo "<script>";
echo "$(document).ready(function () {
    $('#loading').delay().fadeOut()
});";
echo "
    $('#select_pag').on('change', function() {
        element = $(this).children().attr('element');
        action = $(this).children().attr('action');
        page = $(this).val();
        profesor = $(this).children().attr('profesor');
        start = $(this).children().attr('start');
        end = $(this).children().attr('end');
        urlPath = 'index.php?ACTION=admon&OPT=select';
        data = {
            'action': action,
            'element': element,
            'profesor': profesor,
            'fechainicio': start,
            'fechafin': end,
            'pag': page
        };
        
        $.ajax({
            url: urlPath,
            type: 'GET',
            data:  data,
            beforeSend : function() {
                $('#loading-msg').html('Cargando...');
                $('#loading').show();
            },
            success: function(data) {
                $('#btn-response').html(data);
                $('#loading').fadeOut();
            },
            error: function(e) {
                $('#error-modal').modal('show'),
                $('#error-content-modal').html(e);
            }          
        });
    });
";
echo "</script>";