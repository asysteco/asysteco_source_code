<?php

$profesor = $_GET['profesor'] ?? '';
$fechaInicio = $_GET['fechainicio'] ?? '';
$fechaFin = $_GET['fechafin'] ?? '';
$whereFilter = ' AND Fecha <= CURDATE()';
$errorMessage = '';
$selectprofesor = '';
$selectfecha = '';
$selected = '';
$element = $_GET['element'];
$page_size = 200;
$offset_var = $_GET['pag'];

if (isset($profesor) && !empty($profesor)) {
    $whereFilter .= " AND ID_PROFESOR = $profesor";
    $selectprofesor .= "&profesor=$_GET[profesor]";
}

if (isset($fechaInicio) && !empty($fechaInicio) && isset($fechaFin) && !empty($fechaFin)) {
    $fini = $class->formatEuropeanDateToSQLDate($fechaInicio);
    $ffin = $class->formatEuropeanDateToSQLDate($fechaFin);

    if($fini && $ffin) {
        $whereFilter .= " AND Fecha >= '$fini' AND Fecha <= '$ffin'";
        $selectfecha .= "&fechainicio=$_GET[fechainicio]&fechafin=$_GET[fechafin]";
    }
}

$sql = "SELECT Marcajes.*, Nombre, Iniciales, Diasemana.Diasemana  
FROM (Marcajes INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID) 
INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID 
WHERE Asiste=0 $whereFilter
ORDER BY Marcajes.Fecha DESC, Profesores.Nombre ASC, Marcajes.Hora ASC 
LIMIT $page_size OFFSET $offset_var";

if(! $response = $class->query($sql)) {
    $errorMessage = 'Ha ocurrido un error inesperado...';
}

$total_records = $response->num_rows;
$count=ceil($total_records/$page_size);

if (empty($errorMessage) && $response->num_rows > 0) {
    if(isset($offset_var)) {
        if ($count > 1) {
            echo "<div class='páginas' style='margin-top: 25px;'>";
                echo "<h3>Página ";
                echo "<select id='select_pag'>";
                for($j=0; $j<$count; $j++) {
                    $currentPage = $j*$page_size;
                    $selected = $offset_var == $j*$page_size ? 'selected' : '';
                    echo "<option value='$currentPage' action='select' element='$element' profesor='$selectprofesor' start='$fechaInicio' end='$fechaFin' $selected>";
                        echo $pag = ($j+1);
                    echo '</option> ';
                }
                echo "</select>";
                echo "</h3>";
            echo "</div>";
        }
        echo "<table class='table table-striped'>";
            echo "<thead>";
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
    
        while ($datos = $response->fetch_assoc())
        {
            $fecha = $class->formatSQLDateToEuropeanDate($datos['Fecha']);
            echo "<tr>";
                echo "<td>$datos[Iniciales]</td>";
                echo "<td>$datos[Nombre]</td>";
                echo "<td>$fecha</td>";
                echo "<td>$datos[Hora]</td>";
                echo "<td>$datos[Dia]</td>";
                echo "<td>$datos[Diasemana]</td>";
                echo "<td>NO</td>";
                echo "<td>NO</td>";
            echo "</tr>";
        }
    
            echo "</tbody>";
        echo "</table>";
    }
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