<?php

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
                                echo '<th>Iniciales</th>';
                                echo '<th>Nombre</th>';
                                echo '<th>Tutor</th>';
                            echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                    while (($column = fgetcsv($file, 10000, ";")) !== FALSE) {
                        echo '<tr>';
                            echo "<td>$column[0]</td>";
                            echo "<td>$column[1]</td>";
                            echo "<td>$column[2]</td>";
                        echo '</tr>';
                    }
                        echo '</tbody>';
                    echo '</table>';
                echo "</div>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
}