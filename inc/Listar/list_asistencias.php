<?php

$profesor = $_GET['profesor'] ?? '';
$fechaInicio = $_GET['fechainicio'] ?? '';
$fechaFin = $_GET['fechafin'] ?? '';
$whereFilter = ' AND Fecha <= CURDATE()';
$errorMessage = '';
$element = $_GET['element'];
$offset_var = $_GET['pag'];
$page_size = 200;

if (isset($profesor) && !empty($profesor)) {
    $whereFilter .= " AND ID_PROFESOR = $profesor";
}

if (isset($fechaInicio) && !empty($fechaInicio) && isset($fechaFin) && !empty($fechaFin)) {
    $fini = $class->formatEuropeanDateToSQLDate($fechaInicio);
    $ffin = $class->formatEuropeanDateToSQLDate($fechaFin);

    if($fini && $ffin) {
        $whereFilter .= " AND Fecha >= '$fini' AND Fecha <= '$ffin'";
    }
}

$query = "SELECT Marcajes.*, Nombre, Iniciales, Diasemana.Diasemana
FROM (Marcajes INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID)
    INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID 
WHERE Asiste=1 OR Asiste=2 
ORDER BY Profesores.Nombre ASC";

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
            $sql = "SELECT Marcajes.*, Nombre, Iniciales, Diasemana.Diasemana
            FROM (Marcajes INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID)
                INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID
            WHERE (Asiste=1 OR Asiste=2) $whereFilter
            ORDER BY Marcajes.Fecha, Profesores.Nombre ASC
            LIMIT $page_size OFFSET $offset_var";
            if (!$result = $mysql->query($sql)) {
                throw new Exception('No existen datos para exportar...');
            }
            if($result->num_rows > 0) {
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
            
                while ($datos = $result->fetch_assoc())
                {
                    $sep = preg_split('/[ -]/', $datos['Fecha']);
                    $dia = $sep[2];
                    $m = $sep[1];
                    $Y = $sep[0];
                    echo "<tr>";
                        echo "<td>$datos[Iniciales]</td>";
                        echo "<td>$datos[Nombre]</td>";
                        echo "<td>$datos[Fecha]</td>";
                        echo "<td>$datos[Hora]</td>";
                        echo "<td>$datos[Dia]</td>";
                        echo "<td>$datos[Diasemana]</td>";
                        echo "<td>SI</td>";
                        if($datos['Asiste'] == 2)
                        {
                            echo "<td>SI</td>";
                        }
                        else
                        {
                            echo "<td>NO</td>";
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
}else {
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