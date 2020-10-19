<div class="container" style="margin-top:50px">
    <div class="row">
        <div class="col-xs-12">
            <script>
                $('.table').hide()
            </script>
<?php

if(! $n = $class->query("SELECT Nombre, ID FROM $class->profesores WHERE ID='$_GET[profesor]'")->fetch_assoc())
{
    $ERR_MSG = $class->ERR_ASYSTECO;
}
$fecha = date('d/m/Y');
$_GET['fecha'] = date('Y-m-d');

echo "<h2 id='profesor' profesor='$n[ID]_$fecha'>Crear horario para $n[Nombre]</h2>";
echo "<h3>Tipo de horario: $_GET[Tipo]</h3>";
echo "<select id='select_tipo'>";
    foreach($franjasHorarias as $franja => $valores)
    {
        $_GET['Tipo'] == $franja ? $selected = 'selected' : $selected = '' ;
        echo "<option value='$franja' $selected>Horario de $franja</option>";
    }
echo "<select>";
echo '<br>';
echo '<br>';
echo "<a href='index.php?ACTION=horarios&OPT=remove&profesor=$n[ID]' class='btn btn-danger pull-left' onclick=\"return confirm('¿Seguro que desea cancelar este horario?')\"><span class='glyphicon glyphicon-remove'></span> Cancelar cambios</a>";
echo "<a href='index.php?ACTION=marcajes&OPT=create&ID_PROFESOR=$n[ID]' style='margin-left: 70%;' class='btn btn-success pull-right'><span class='glyphicon glyphicon-ok'></span> Aplicar cambios</a> ";

echo "<div id='response'></div>";
echo "</br>";
echo "<table class='table' hidden>";
echo "<thead>";
    echo "<tr>";
        echo "<th style='text-align: center;'>Horas</th>";
        echo "<th style='text-align: center;'>Lunes</th>";
        echo "<th style='text-align: center;'>Martes</th>";
        echo "<th style='text-align: center;'>Miercoles</th>";
        echo "<th style='text-align: center;'>Jueves</th>";
        echo "<th style='text-align: center;'>Viernes</th>";
        echo "</tr>";
echo "</thead>";
echo "<tbody>";

$franja = $_GET['Tipo'];
foreach ($franjasHorarias[$franja] as $valor => $datos)
{
    $Hora = $valor;
    echo "<tr>";
        echo "<td style='vertical-align: middle; text-align: center;'>$Hora</td>";
        
        for($dialoop = 1; $dialoop <= 5; $dialoop++)
        {
            if($Hora === 'R')
            {
                echo "<td id='$j-$hora' style='vertical-align: middle; text-align: center;' class=' $dia[color]'>";
                    echo 'RECREO';
                echo "</td>";
            }
            else
            {
                echo "<td id='$dialoop-$hora' style='vertical-align: middle; text-align: center;' class=' $dia[color]'>";
                    echo "<a class='act' enlace='index.php?ACTION=horarios&OPT=edit-t&act=add&ID=$n[ID]&Dia=$dialoop&Hora=$Hora&Tipo=$_GET[Tipo]&Fecha=$_GET[fecha]'>";
                        echo "<span class='glyphicon glyphicon-plus btn-react-add'></span>";
                    echo "</a>";
                echo "</td>";
            }
        }
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
include_once('js/crear-horario.js');
?>
        </div>
    </div>
</div>