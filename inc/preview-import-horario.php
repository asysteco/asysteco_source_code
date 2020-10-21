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

require_once($dirs['class'] . 'ImportHorario.php');
$fileName = $_FILES["file"]["tmp_name"];
if ($_FILES["file"]["size"] > 0) {
    $file = fopen($fileName, "r");
    $row = 0;
    echo "<div class='modal-body'>";
        echo "<div class='container-fluid'>";
            echo "<div class='row'>";
                echo "<div class='col-xs-12'>";
                    echo '<table class="table-striped" style="width: 100%;">';
                        echo '<thead>';
                            echo '<tr>';
                                echo '<th>Línea</th>';
                                echo '<th>Grupo</th>';
                                echo '<th>Iniciales</th>';
                                echo '<th>Aula</th>';
                                echo '<th>Día</th>';
                                echo '<th>Hora</th>';
                            echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                    while (($column = fgetcsv($file, 10000, ";")) !== FALSE) {
                        if ($row === 0) {
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
                        echo '</tr>';
                        $row ++;
                    }
                        echo '</tbody>';
                    echo '</table>';
                echo "</div>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
}