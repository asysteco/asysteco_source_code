<?php

$franjas = [];

foreach ($horarioCentro as $franja => $datos) {
    $franjas[] = $franja;
}
$countFranjas = count($franjas);


for ($i = 0; $i < $countFranjas; $i++) {
    $franja = $franjas[$i];
?>
    <table class='table table-striped'>
        <thead class='thead-dark'>
            <tr>
                <th colspan='100%' style='text-align: center; vertical-align: middle;'><?= $franja ?></th>
            </tr>
        </thead>
        <thead class='thead-dark'>
            <tr>
                <th style='text-align: center; vertical-align: middle;'>Nº Referencia</th>
                <th style='text-align: center; vertical-align: middle;'>Hora Inicio</th>
                <th style='text-align: center; vertical-align: middle;'>Hora Fin</th>
            </tr>
        </thead>
        </tbody>

        <?php
        foreach ($horarioCentro[$franja] as $key => $values) {
            $descripcionHora = isset($values['Info']) && !empty($values['Info']) ? " ($values[Info])" : '';
        ?>

            <tr>
                <td style='text-align: center; vertical-align: middle;'><?= $key . $descripcionHora ?></td>
                <td style='text-align: center; vertical-align: middle;'><?= $values['Inicio'] ?></td>
                <td style='text-align: center; vertical-align: middle;'><?= $values['Fin'] ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } ?>
