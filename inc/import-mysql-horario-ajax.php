<?php

$hoy = true;

date_default_timezone_set('Europe/Madrid');
if (isset($_POST['fecha'])) {
    $fecha = explode('/', $_POST['fecha']);
    $fecha = $fecha[2] . '-' . $fecha[1] . '-' . $fecha[0];

    if ($fecha !== date('Y-m-d')) {
        $hoy = false;
    }
}

$response = $class->conex->query("SELECT DISTINCT Iniciales, ID FROM Profesores WHERE TIPO <> 1");
if ($response->num_rows > 0) {
    $inicialesBD = $response->fetch_all();
    $totalIniciales = [];
    foreach ($inicialesBD as $key => $value) {
        if (isset($value[0]) && isset($value[1])) {
            $inicial = strtoupper($value[0]);
            $id = $value[1];
            $totalIniciales[$inicial] = $id;
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

    $class->conex->autocommit(FALSE);

    try {
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

            $importHorario = new ImportHorario(utf8_encode($column[0]), utf8_encode($column[1]), utf8_encode($column[2]), $column[3], $column[4]);

            $grupo = $importHorario->grupo();
            $iniciales = $importHorario->iniciales();
            $aula = $importHorario->aula();
            $dia = $importHorario->dia();
            $hora = $importHorario->hora();
            $tipo = $_POST['Franja'];
            $franja = $_POST['Franja'];
            $edificio = str_split($aula);
            $edificio = $edificio[2];

            if ($iniciales != '' && array_key_exists($iniciales, $totalIniciales)) {
                $idProfesor = $totalIniciales[$iniciales];
                $profesorExist = true;
            } else {
                $profesorExist = false;
            }

            if ($importHorario->rowStatus() && $profesorExist) {
                if ($hoy) {
                    $sql = "INSERT INTO Horarios (ID_PROFESOR, Dia, Hora, Tipo, Edificio, Aula, Grupo)
                VALUES ('$idProfesor', '$dia', '$hora', '$tipo', '$edificio', '$aula', '$grupo')";
                } else {
                    $sql = "INSERT INTO T_horarios (ID_PROFESOR, Dia, Hora, Tipo, Edificio, Aula, Grupo, Fecha_incorpora)
                VALUES ('$idProfesor', '$dia', '$hora', '$tipo', '$edificio', '$aula', '$grupo', '$fecha')";
                }

                if (!$class->conex->query($sql)) {
                    throw new Exception('Error-importar');
                }
            } else {
                throw new Exception('No-profesor');
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
