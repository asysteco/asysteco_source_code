<?php

$franjas = [];
$horas = [];

foreach ($franjasHorarias as $franja => $datos) {
    $franjas[] = $franja;
}
$countFranjas = count($franjas);

echo "<table class='table table-striped'>";

for ($i = 0; $i < $countFranjas; $i++) {
    $franja = $franjas[$i];
    echo "<thead class='thead-dark'>";
    echo "<tr>";
    echo "<th colspan='100%' style='text-align: center; vertical-align: middle;'>$franja</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<thead class='thead-dark'>";
    echo "<tr>";
    echo "<th style='text-align: center; vertical-align: middle;'>Nº Referencia</th>";
    echo "<th style='text-align: center; vertical-align: middle;'>Hora Inicio</th>";
    echo "<th style='text-align: center; vertical-align: middle;'>Hora Fin</th>";
    echo "</tr>";
    echo "</thead>";
    echo "</tbody>";
    foreach ($franjasHorarias[$franja] as $key => $values) {
        $descripcionHora = isset($values['Hora']) && !empty($values['Hora']) ? " ($values[Hora])" : '';
        echo "<tr>";
        echo "<td style='text-align: center; vertical-align: middle;'>" . $key . $descripcionHora . "</td>";
        echo "<td style='text-align: center; vertical-align: middle;'>" . $values['Inicio'] . "</td>";
        echo "<td style='text-align: center; vertical-align: middle;'>" . $values['Fin'] . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
}

echo "</table>";
