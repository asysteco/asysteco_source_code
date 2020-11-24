<?php

if(! $response = $class->query("SELECT ID_PROFESOR FROM Marcajes WHERE Asiste=0"))
{
    die($class->ERR_ASYSTECO);
}

if(isset($_GET['profesor']) && $_GET['profesor'] != '')
{
    $profesor = "WHERE ID_PROFESOR = '$_GET[profesor]'";
    $sql = "SELECT Horarios.*, Profesores.Nombre, Iniciales, Diasemana.Diasemana, Aulas.Nombre as Aula, Cursos.Nombre as Grupo
    FROM Horarios INNER JOIN Profesores ON Horarios.ID_PROFESOR=Profesores.ID
    INNER JOIN Diasemana ON Horarios.Dia=Diasemana.ID 
    INNER JOIN Aulas ON Aulas.ID=Horarios.Aula
    INNER JOIN Cursos ON Cursos.ID=Horarios.Grupo
    WHERE ID_PROFESOR = '$_GET[profesor]'";
}
else
{
    $profesor = "";
    $sql = "SELECT Horarios.*, Profesores.Nombre, Iniciales, Diasemana.Diasemana, Aulas.Nombre as Aula, Cursos.Nombre as Grupo
    FROM Horarios INNER JOIN Profesores ON Horarios.ID_PROFESOR=Profesores.ID
    INNER JOIN Diasemana ON Horarios.Dia=Diasemana.ID
    INNER JOIN ON Aulas ON Aulas.ID=Horarios.Aula
    INNER JOIN Cursos ON Cursos.ID=Horarios.Grupo";
}


$offset_var = $_GET['pag'];

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
    if(isset($profesor))
    {
        $query = "SELECT Horarios.*, Profesores.Nombre, Iniciales, Diasemana.Diasemana, Aulas.Nombre as Aula, Cursos.Nombre as Grupo
        FROM Horarios INNER JOIN Profesores ON Horarios.ID_PROFESOR=Profesores.ID
        INNER JOIN Diasemana ON Horarios.Dia=Diasemana.ID
        INNER JOIN Aulas ON Aulas.ID=Horarios.Aula
        INNER JOIN Cursos ON Cursos.ID=Horarios.Grupo
        $profesor 
        ORDER BY Profesores.Nombre ASC
        LIMIT $page_size OFFSET $offset_var";
    }
    else
    {
        $query = "SELECT Horarios.*, Profesores.Nombre, Iniciales, Diasemana.Diasemana, Aulas.Nombre as Aula, Cursos.Nombre as Grupo
        FROM Horarios INNER JOIN Profesores ON Horarios.ID_PROFESOR=Profesores.ID
        INNER JOIN Diasemana ON Horarios.Dia=Diasemana.ID
        INNER JOIN Aulas ON Aulas.ID=Horarios.Aula
        INNER JOIN Cursos ON Cursos.ID=Horarios.Grupo
        ORDER BY Profesores.Nombre ASC
        LIMIT $page_size OFFSET $offset_var";
    }
    # "select id from shipment Limit ".$page_size." OFFSET ".$offset_var;

    $result =  $class->query($query);
    if (isset($options['edificios']) && $options['edificios'] > 1) {
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
                    echo "<th>EDIFICIO</th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
    } else {
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
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
    }

    while ($datos = $result->fetch_assoc())
    {
        $sep = preg_split('/[ -]/', $datos['Fecha']);
        $dia = $sep[2];
        $m = $sep[1];
        $Y = $sep[0];
        if (isset($options['edificios']) && $options['edificios'] > 1) {
            echo "<tr>";
                echo "<td>$datos[Iniciales]</td>";
                echo "<td>$datos[Nombre]</td>";
                echo "<td>$datos[Grupo]</td>";
                echo "<td>$datos[Aula]</td>";
                echo "<td>$datos[Dia]</td>";
                echo "<td>$datos[Diasemana]</td>";
                echo "<td>$datos[Hora]</td>";
                echo "<td>$datos[Edificio]</td>";
            echo "</tr>";
        } else {
            echo "<tr>";
                echo "<td>$datos[Iniciales]</td>";
                echo "<td>$datos[Nombre]</td>";
                echo "<td>$datos[Grupo]</td>";
                echo "<td>$datos[Aula]</td>";
                echo "<td>$datos[Dia]</td>";
                echo "<td>$datos[Diasemana]</td>";
                echo "<td>$datos[Hora]</td>";
            echo "</tr>";
        }
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