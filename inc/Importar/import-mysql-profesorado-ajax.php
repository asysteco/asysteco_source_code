<?php


$response = $class->conex->query("SELECT DISTINCT Iniciales FROM Profesores WHERE TIPO <> 1");
if ($response->num_rows >= 0) {
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

require_once($dirs['class'] . 'ImportProfesor.php');
$fileName = $_FILES["file"]["tmp_name"];
if ($_FILES["file"]["size"] > 0) {
    $file = fopen($fileName, "r");
    $row = 0;

    $class->conex->autocommit(FALSE);

    try {
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

            $iniciales = $importProfesor->iniciales();
            $nombre = $importProfesor->nombre();
            $tutor = $importProfesor->tutor();

            if (isset($column[0]) && in_array($column[0], $totalIniciales)) {
                $profesorExist = true;
            } else {
                $profesorExist = false;
            }

            if ($importProfesor->rowStatus()) {
                if (!$profesorExist) {
                    $pass = $class->encryptPassword($iniciales . '12345');
                    $sql = "INSERT INTO Profesores (Iniciales, Nombre, Password, TIPO, Tutor, Activo, Sustituido)
                VALUES ('$iniciales', '$nombre', '$pass', 2, '$tutor', 1, 0)";
                } else {
                    continue;
                }
            } else {
                throw new Exception('Error-csv');
            }

            echo $sql;
            if (!$class->conex->query($sql)) {
                throw new Exception('Error-importar');
            }
            $row++;
        }
        if ($hoy) {
            $class->updateHoras();
            $class->marcajes();
        }
    } catch (Exception $e) {
        echo $e;
        $class->conex->rollback();
    }
    $class->conex->commit();
}
