<?php

if(isset($_GET['profesor']) && $_GET['profesor'] != '')
{
    $profesor = " AND ID_PROFESOR = '$_GET[profesor]'";
    $selectprofesor = "&profesor=$_GET[profesor]";
    $sql = "SELECT Marcajes.*, Nombre, Iniciales, Diasemana.Diasemana
    FROM (Marcajes INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID)
        INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID
    WHERE Asiste=0 AND ID_PROFESOR = '$_GET[profesor]' 
    ORDER BY Profesores.Nombre ASC";
}
else
{
    $profesor = "";
    $selectprofesor = "";
    $sql = "SELECT Marcajes.*, Nombre, Iniciales, Diasemana.Diasemana
    FROM (Marcajes INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID)
        INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID
    WHERE Asiste=0 
    ORDER BY Profesores.Nombre ASC";
}

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
        $selectfecha = "&fechainicio=$_GET[fechainicio]&fechafin=$_GET[fechafin]";
        if(! $response = $class->query("SELECT ID_PROFESOR FROM Marcajes WHERE Asiste=0 AND Fecha BETWEEN '$fini' AND '$ffin'"))
        {
            die($class->ERR_ASYSTECO);
        }
    }
    else
    {
        $selectfecha = '';
    }
}
else
{
    if(! $response = $class->query("SELECT Marcajes.*, Nombre, Iniciales, Diasemana.Diasemana
    FROM (Marcajes INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID)
        INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID
    WHERE Asiste=0 
    ORDER BY Profesores.Nombre ASC"))
    {
        die($class->ERR_ASYSTECO);
    }
    $selectfecha = '';
}

if(isset($_GET['fechainicio']) && isset($_GET['fechafin']) && $_GET['fechainicio'] !='' && $_GET['fechafin'] !='')
{
    if(isset($_GET['profesor']) && $_GET['profesor'] != '')
    {
        $and= "AND";
    }
    else
    {
        $and = "";
    }
    $fechas="Fecha BETWEEN '$fini' AND '$ffin'";
}
else
{
    $fechas="";
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
                echo '<option value="index.php?ACTION=admon&OPT=select&select=faltas&pag=' . $j*$page_size . $selectprofesor . $selectfecha . '" class="btn-select" ' . $selected . '><span class="glyphicon glyphicon-eye-open"></span> ' . $pag = ($j+1) . '</option> ';
            }
        echo "</select>";
        echo "</h3>";
    echo "</div>";
    if(isset($profesor) || isset($fechas))
    {
        $query = "SELECT Marcajes.*, Nombre, Iniciales, Diasemana.Diasemana
        FROM (Marcajes INNER JOIN Profesores ON Marcajes.ID_PROFESOR=Profesores.ID)
            INNER JOIN Diasemana ON Marcajes.Dia=Diasemana.ID
        WHERE Asiste=0 $profesor $and $fechas
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
            echo "<td>NO</td>";
            echo "<td>NO</td>";
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