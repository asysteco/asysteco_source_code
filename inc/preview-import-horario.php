<?php

require_once($dirs['class'] . 'ImportHorario.php');
$fileName = $_FILES["file"]["tmp_name"];
if ($_FILES["file"]["size"] > 0) {
    $file = fopen($fileName, "r");
    $row = 1;
    echo "<div class='modal-body'>";
        echo "<div class='container-fluid'>";
            echo "<div class='row'>";
                echo "<div class='col-xs-12'>";
                    echo '<table class="table-striped" style="width: 100%;">';
                        echo '<thead>';
                            echo '<tr>';
                                echo '<th>Grupo</th>';
                                echo '<th>Iniciales</th>';
                                echo '<th>Aula</th>';
                                echo '<th>Día</th>';
                                echo '<th>Hora</th>';
                            echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                    while (($column = fgetcsv($file, 10000, ";")) !== FALSE) {
                        if ($row === 1) {
                            if (preg_match('/^GRUPO$/i', $column[0])
                            && preg_match('/^INICIALES$/i', $column[1])
                            && preg_match('/^AULA$/i', $column[2])
                            && preg_match('/^DIA$/i', $column[3])
                            && preg_match('/^HORA$/i', $column[4])) {
                                $row ++;
                                continue;
                            } else {
                                echo "error-cabecera";
                                exit;
                            }
                        }
                        $importHorario = new ImportHorario($column[0], $column[1], $column[2], $column[3], $column[4]);
                        echo '<tr>';
                            echo "<td>" . $importHorario->grupo() . "</td>";
                            echo "<td>" . $importHorario->iniciales() . "</td>";
                            echo "<td>" . $importHorario->aula() . "</td>";
                            echo "<td>" . $importHorario->dia() . "</td>";
                            echo "<td>" . $importHorario->hora() . "</td>";
                        echo '</tr>';
                    }
                        echo '</tbody>';
                    echo '</table>';
                echo "</div>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
}