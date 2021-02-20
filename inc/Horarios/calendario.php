

<div class="container">
    <div class='row'>
       <div class='col-12'>
            <h1>Calendario escolar</h1>

<?php

$date = date('Y');
if($response = $class->query("SELECT * FROM $class->lectivos ORDER BY Fecha ASC"))
{
    echo "<div id='mitablita'>";
    $contador = 0;
    $diaanterior = 99;
    while($calendario = $response->fetch_assoc())
    {
        if($calendario['Festivo'] == 'si')
        {
            $festivo = "background-color: #dff0d8; color: black;";
            $festivo = "festivo";
            $action = "festivo";
        }
        else
        {
            $festivo = "lectivo";
            $action = "lectivo";
        }
        if($calendario['Fecha'] == 'Y-m-d')
        {
            $diahoy = "background-color: #b2e5ff !important;";
        }
        else
        {
            $diahoy = "";
        }

        $date = $calendario['Fecha'];

        $sep=explode("-", $calendario['Fecha']);
        $dia = $sep[2];
        $m = $sep[1];
        $Y = $sep[0];
        $start = unixtojd(mktime(0,0,0,$m,$dia,$Y));
        $array = cal_from_jd($start,CAL_GREGORIAN);
        $nuevodia = $array['day'];
       
        if($array['monthname'] == 'January')
        {
            $array['monthname'] = 'Enero <br/>'. $Y;
        }
        if($array['monthname'] == 'February')
        {
            $array['monthname'] = 'Febrero <br/>'. $Y;
        }
        if($array['monthname'] == 'March')
        {
            $array['monthname'] = 'Marzo <br/>'. $Y;
        }
        if($array['monthname'] == 'April')
        {
            $array['monthname'] = 'Abril <br/>'. $Y;
        }
        if($array['monthname'] == 'May')
        {
            $array['monthname'] = 'Mayo <br/>'. $Y;
        }
        if($array['monthname'] == 'June')
        {
            $array['monthname'] = 'Junio <br/>'. $Y;
        }
        if($array['monthname'] == 'July')
        {
            $array['monthname'] = 'Julio';
        }
        if($array['monthname'] == 'August')
        {
            $array['monthname'] = 'Agosto';
        }
        if($array['monthname'] == 'September')
        {
            $array['monthname'] = 'Septiembre <br/>'. $Y;
        }
        if($array['monthname'] == 'October')
        {
            $array['monthname'] = 'Octubre <br/>'. $Y;
        }
        if($array['monthname'] == 'November')
        {
            $array['monthname'] = 'Noviembre <br/>'. $Y;
        }
        if($array['monthname'] == 'December')
        {
            $array['monthname'] = 'Diciembre <br/>'. $Y;
        }
        if($nuevodia < $diaanterior)
        {
            $contador = 0;
            echo "</table>";
            echo "</div>";
            echo "<div class='calendario' style='display:inline-block; margin-right: 17px;'>";
            echo "<h2>$array[monthname]</h2>";            
            echo "<table>";

        }
        if($contador == 0)
        {
            echo "<thead>";
            echo "<tr>";
            echo "<th>L</th>";
            echo "<th>M</th>";
            echo "<th>X</th>";
            echo "<th>J</th>";
            echo "<th>V</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tr>";
            if($array['dayname'] == 'Monday')
            {
                echo "<td data-date='$date' action='$action' class='act $festivo $diahoy'>$array[day]</td>";
                
            }
            if($array['dayname'] == 'Tuesday')
            {
                echo "<td class='$diahoy'></td>";
                echo "<td data-date='$date' action='$action' class='act $festivo $diahoy'>$array[day]</td>";
            }
            if($array['dayname'] == 'Wednesday')
            {
                echo "<td class='$diahoy'></td>";
                echo "<td class='$diahoy'></td>";
                echo "<td data-date='$date' action='$action' class='act $festivo $diahoy'>$array[day]</td>";
            }
            if($array['dayname'] == 'Thursday')
            {
                echo "<td class='$diahoy'></td>";
                echo "<td class='$diahoy'></td>";
                echo "<td class='$diahoy'></td>";
                echo "<td data-date='$date' action='$action' class='act $festivo $diahoy'>$array[day]</td>";
            }
            if($array['dayname'] == 'Friday')
            {
                echo "<td class='$diahoy'></td>";
                echo "<td class='$diahoy'></td>";
                echo "<td class='$diahoy'></td>";
                echo "<td class='$diahoy'></td>";
                echo "<td data-date='$date' action='$action' class='act $festivo $diahoy'>$array[day]</td>";
                echo "</tr>";
            }
            $contador++;
            $diaanterior = $array['day'];
            continue;
        }
        else
        {
            if($nuevodia < $diaanterior && $diaanterior != 0)
            {
                $contador = 0;
                echo "<tr>";
                echo "<th >$array[monthname]</th>";            
                echo "</tr>";
                echo "<tr>";
            }
            if($array['dayname'] == 'Monday')
            {
                echo "<td data-date='$date' action='$action' class='act $festivo $diahoy'>$array[day]</td>";   
            }
            if($array['dayname'] == 'Tuesday')
            {
                echo "<td data-date='$date' action='$action' class='act $festivo $diahoy'>$array[day]</td>";
            }
            if($array['dayname'] == 'Wednesday')
            {
                echo "<td data-date='$date' action='$action' class='act $festivo $diahoy'>$array[day]</td>";
            }
            if($array['dayname'] == 'Thursday')
            {
                echo "<td data-date='$date' action='$action' class='act $festivo $diahoy'>$array[day]</td>";
            }
            if($array['dayname'] == 'Friday')
            {
                echo "<td data-date='$date' action='$action' class='act $festivo $diahoy'>$array[day]</td>";
                echo "</tr>";
            }
        }
        $diaanterior = $array['day'];
    }
    echo "</table>";
}
else
{
    $ERR_MSG = $class->ERR_ASYSTECO;
}

?>

        </div>
    </div>
</div>

<script src="js/lectivos.js"></script>

<div id='modal-calendario' class='modal fade' tabindex='-1' role='dialog'>
<div id='modal-size' class='modal-dialog' role='document'>
    <div class='modal-content'> 
    <div id='modal-cabecera' class='modal-header'></div>
    <div class='modal-body'>
        <div id='modal-contenido'></div>
    </div>
    <div id='modal-pie' class='modal-footer'></div>
    </div>
</div>
</div>
