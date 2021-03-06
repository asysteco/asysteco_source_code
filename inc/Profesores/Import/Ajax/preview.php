<?php

require_once($dirs['class'] . 'ImportProfesor.php');
$fileName = $_FILES["file"]["tmp_name"];
if ($_FILES["file"]["size"] > 0) {
    $file = fopen($fileName, "r");
    $row = 0;
    while (($column = fgetcsv($file, 10000, ";")) !== FALSE) {
        if ($row === 0) {
            if (
                preg_match('/^INICIALES$/i', $column[0])
                && preg_match('/^NOMBRE$/i', $column[1])
                && preg_match('/^TUTOR$/i', $column[2])
            ) {
                $row++;
                continue;
            } else {
                echo "error-cabecera";
                exit;
            }
        }

        $importProfesor = new ImportProfesor(utf8_encode($column[0]), utf8_encode($column[1]), $column[2]);

        if ($importProfesor->rowStatus()) {
            echo '<tr>';
        } else {
            echo '<tr style="background-color: #ff9797;">';
        }
        echo "<td>" . $row . "</td>";
        echo "<td>" . $importProfesor->iniciales() . "</td>";
        echo "<td>" . $importProfesor->nombre() . "</td>";
        echo "<td>" . $importProfesor->tutor() . "</td>";
        echo '</tr>';
        $row++;
    }
} else {
    echo "error-file";
    exit;
}
