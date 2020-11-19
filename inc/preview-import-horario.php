<?php

$response = $class->conex->query("SELECT DISTINCT Iniciales FROM Profesores WHERE TIPO <> 1");
if ($response->num_rows > 0) {
    $inicialesBD = $response->fetch_all();
    $totalIniciales = [];
    foreach ($inicialesBD as $key => $value) {
        if (isset($value[0])) {
            $totalIniciales[] = $value[0];
        }
    }
} else {
    exit;
}
$edificio = 1;

require_once($dirs['class'] . 'ImportHorario.php');
$fileName = $_FILES["file"]["tmp_name"];

if ($_FILES["file"]["size"] > 0) {
    $file = fopen($fileName, "r");
    $row = 0;

    while (($column = fgetcsv($file, 10000, ";")) !== FALSE) {
        if ($row === 0) {
            if (
                preg_match('/^GRUPO$/i', $column[0])
                && preg_match('/^INICIALES$/i', $column[1])
                && preg_match('/^AULA$/i', $column[2])
                && preg_match('/^DIA$/i', $column[3])
                && preg_match('/^HORA$/i', $column[4])
            ) {
                $row++;
                continue;
            } else {
                echo "error-cabecera";
                exit;
            }
        }
        if (isset($column[5])) {
            if (preg_match('/^[1-9]$/', $column[5])) {
                $edificio = $column[5];
            } else {
                echo "error-edificio";
                exit;
            }
        }

        $importHorario = new ImportHorario(utf8_encode($column[0]), utf8_encode($column[1]), utf8_encode($column[2]), $column[3], $column[4]);

        if (isset($column[1]) && in_array($column[1], $totalIniciales)) {
            $profesorExist = true;
        } else {
            $profesorExist = false;
        }

        if ($importHorario->rowStatus() && $profesorExist) {
            echo '<tr>';
        } else {
            echo '<tr style="background-color: #ff9797;">';
        }
        echo "<td>" . $row . "</td>";
        echo "<td>" . $importHorario->grupo() . "</td>";
        echo "<td>" . $importHorario->iniciales() . "</td>";
        echo "<td>" . $importHorario->aula() . "</td>";
        echo "<td>" . $importHorario->dia() . "</td>";
        echo "<td>" . $importHorario->hora() . "</td>";
        echo "<td>" . $edificio . "</td>";
        echo '</tr>';
        $row++;
    }
} else {
    echo "error-file";
    exit;
}
