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

$sql = "SELECT P.Iniciales, P.Nombre, D.Diasemana, A.Nombre as Aula, C.Nombre as Grupo, H.Dia, H.Hora
FROM Horarios H INNER JOIN Profesores P ON H.ID_PROFESOR=P.ID
INNER JOIN Diasemana D ON H.Dia=D.ID
INNER JOIN Aulas A ON A.ID=H.Aula
INNER JOIN Cursos C ON C.ID=H.Grupo
$whereFilter 
ORDER BY P.Nombre ASC, H.Hora
LIMIT $page_size OFFSET $offset_var";
if(! $response = $class->query($sql))
{
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
                    echo "<option value='$currentPage' action='select' element='$element' profesor='$profesor' $selected>";
                        echo $pag = ($j+1);
                    echo '</option> ';
                }
                echo "</select>";
                echo "</h3>";
            echo "<div>";
        }
        echo "<table class='table table-striped'>";
            echo "<thead>";
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
        while ($datos = $response->fetch_assoc())
        {
            echo "<tr>";
                echo "<td>$datos[Iniciales]</td>";
                echo "<td>$datos[Nombre]</td>";
                echo "<td>$datos[Grupo]</td>";
                echo "<td>$datos[Aula]</td>";
                echo "<td>$datos[Dia]</td>";
                echo "<td>$datos[Diasemana]</td>";
                echo "<td>$datos[Hora]</td>";
            if (isset($edificios) && $edificios > 1) {
                    echo "<td>$datos[Edificio]</td>";
            }
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
        urlPath = 'index.php?ACTION=admon&OPT=select';
        data = {
            'action': action,
            'element': element,
            'profesor': profesor,
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