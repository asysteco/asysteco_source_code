<?php

if(! $response = $class->query("SELECT ID_PROFESOR FROM Marcajes WHERE Asiste=0"))
{
    die($class->ERR_ASYSTECO);
}

$page_size = 200;
$total_records = $response->num_rows;
$count=ceil($total_records/$page_size);

if(isset($_GET['pag']))
{
    echo "<div class='páginas' style='margin-top: 25px;'>";
        echo "<h3>Página ";
        echo "<select id='select_pag'>";
            for($j=0; $j<$count; $j++)
            {
                if($_GET['pag'] == $j*$page_size)
                {
                    $selected = 'selected';
                }
                else
                {
                    $selected = '';
                }
                echo '<option value="index.php?ACTION=admon&OPT=select&select=faltas&pag=' . $j*$page_size . '" class="btn-select" ' . $selected . '><span class="glyphicon glyphicon-eye-open"></span> ' . $pag = ($j+1) . '</option> ';
            }
        echo "</select>";
        echo "</h3>";
    echo "<div>";
    $offset_var = $_GET['pag'];
    $fi = preg_split('/\//', $_GET['fechainifaltas']);
            $dia = $fi[0];
            $m = $fi[1];
            $Y = $fi[2];
    $fini = $Y .'-'. $m .'-'. $dia;
    $ff = preg_split('/\//', $_GET['fechafinfaltas']);
            $dia = $ff[0];
            $m = $ff[1];
            $Y = $ff[2];
    $ffin = $Y .'-'. $m .'-'. $dia;
    if(isset($_GET['fechainifaltas']) && isset($_GET['fechafinfaltas']) && $_GET['fechainifaltas'] !='' && $_GET['fechafinfaltas'] !='')
    {
        $query = "SELECT Marcajes.*, Nombre, Iniciales, Diasemana.Diasemana
        FROM (Marcajes INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID)
            INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID
        WHERE Asiste=0 AND Fecha BETWEEN '$fini' AND '$ffin'
        ORDER BY Profesores.Nombre ASC
        LIMIT $page_size OFFSET $offset_var";
    }
    else
    {
        $query = "SELECT Marcajes.*, Nombre, Iniciales, Diasemana.Diasemana
        FROM (Marcajes INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID)
            INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID
        WHERE Asiste=0
        ORDER BY Profesores.Nombre ASC
        LIMIT $page_size OFFSET $offset_var";
    }
    # "select id from shipment Limit ".$page_size." OFFSET ".$offset_var;

    $result =  $class->query($query);
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
else
{
    echo "No hay páginas.<br>";
}

echo "<script>";
    echo "$(document).ready(function () {
        $('#loading').delay().fadeOut()
    });";
    echo "
    $('#select_pag').on('change', function() {
        $('#btn-response').html(''),
        $('#loading-msg').html('Cargando...'),
        $('#loading').show(),
        enlace = $(this).val(),
        $('#btn-response').load(enlace)
    });
    ";
echo "</script>";