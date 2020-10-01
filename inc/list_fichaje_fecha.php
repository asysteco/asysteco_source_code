<?php

$offset_var = $_GET['pag'];
if(isset($_GET['fechainicio']) && isset($_GET['fechafin']))
{
    $fi = preg_split('/\//', $_GET['fechainicio']);
            $dia = $fi[0];
            $m = $fi[1];
            $Y = $fi[2];
    $fini = $Y .'-'. $m .'-'. $dia;
    $ff = preg_split('/\//', $_GET['fechafin']);
            $dia = $ff[0];
            $m = $ff[1];
            $Y = $ff[2];
    $ffin = $Y .'-'. $m .'-'. $dia;
    if($class->validFormSQLDate($fini) && $class->validFormSQLDate($ffin))
    {
        if(! $response = $class->query("SELECT ID_PROFESOR FROM Fichar INNER JOIN Profesores ON Fichar.ID_PROFESOR=Profesores.ID AND Fecha BETWEEN '$fini' AND '$ffin'"))
        {
            die($class->ERR_ASYSTECO);
        }
    }
}
else
{
    if(! $response = $class->query("SELECT ID_PROFESOR FROM Fichar INNER JOIN Profesores ON Fichar.ID_PROFESOR=Profesores.ID"))
    {
        die($class->ERR_ASYSTECO);
    }
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
                echo '<option value="index.php?ACTION=admon&OPT=select&select=fichafe&pag=' . $j*$page_size . '&fechainicio=' . $_GET['fechainicio'] . '&fechafin=' . $_GET['fechafin'] . '" class="btn-select" ' . $selected . '><span class="glyphicon glyphicon-eye-open"></span> ' . $pag = ($j+1) . '</option> ';
            }
        echo "</select>";
        echo "</h3>";
    echo "<div>";
    if(isset($_GET['fechainicio']) && isset($_GET['fechafin']) && $_GET['fechainicio'] !='' && $_GET['fechafin'] !='')
    {
        $query = "SELECT ID_PROFESOR, Nombre, F_entrada, F_Salida, DIA_SEMANA, Fecha
        FROM (Fichar INNER JOIN Profesores ON Fichar.ID_PROFESOR=Profesores.ID)
        WHERE Fecha BETWEEN '$fini' AND '$ffin'
        ORDER BY Profesores.Nombre ASC
        LIMIT $page_size OFFSET $offset_var";
    }
    else
    {
        $query = "SELECT ID_PROFESOR, Nombre, F_entrada, F_Salida, DIA_SEMANA, Fecha
        FROM (Fichar INNER JOIN Profesores ON Fichar.ID_PROFESOR=Profesores.ID)
        ORDER BY Profesores.Nombre ASC
        LIMIT $page_size OFFSET $offset_var";
    }
    # "select id from shipment Limit ".$page_size." OFFSET ".$offset_var;
    $result =  $class->query($query);
    echo "<table class='table table-striped'>";
        echo "<thead>";
            echo "<tr>";
                echo "<th>ID PROFESOR</th>";
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
                echo "<td>$datos[ID_PROFESOR]</td>";
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