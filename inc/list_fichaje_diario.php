<?php

if(! $response = $class->query("SELECT ID_PROFESOR FROM Fichar INNER JOIN Profesores ON Fichar.ID_PROFESOR=Profesores.ID"))
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
                echo '<option value="index.php?ACTION=admon&OPT=select&select=fichadi&pag=' . $j*$page_size . '" class="btn-select" ' . $selected . '><span class="glyphicon glyphicon-calendar"></span> ' . $pag = ($j+1) . '</option> ';
            }
        echo "</select>";
        echo "</h3>";
    echo "<div>";
    $offset_var = $_GET['pag'];
    $fechahoy = date('Y-m-d');
    $query = "SELECT ID_PROFESOR, Nombre, F_entrada, F_Salida, DIA_SEMANA, Fecha
    FROM (Fichar INNER JOIN Profesores ON Fichar.ID_PROFESOR=Profesores.ID)
    WHERE Fecha = '$fechahoy'
    ORDER BY Profesores.Nombre ASC
    LIMIT $page_size OFFSET $offset_var";
    # "select id from shipment Limit ".$page_size." OFFSET ".$offset_var;

    $result =  $class->query($query);
    echo "<table class='table table-striped'>";
        echo "<thead>";
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
            $sep = preg_split('/[ -]/', $datos['Fecha']);
            $dia = $sep[2];
            $m = $sep[1];
            $Y = $sep[0];
            echo "<tr>";
                echo "<td>$datos[Nombre]</td>";
                echo "<td>$datos[F_entrada]</td>";
                echo "<td>$datos[F_Salida]</td>";
                echo "<td>$datos[DIA_SEMANA]</td>";
                echo "<td>$dia/$m/$Y</td>";
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