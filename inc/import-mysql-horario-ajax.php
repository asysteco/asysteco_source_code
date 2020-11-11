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

$response = $class->conex->query("SELECT DISTINCT  ID, Iniciales FROM Profesores WHERE TIPO <> 1");
if ($response->num_rows > 0) {
    $inicialesBD = $response->fetch_all();
    $totalIniciales = [];
    foreach ($inicialesBD as $key => $value) {
        if (isset($value[0]) && isset($value[1])) {
            $inicial = strtoupper($value[1]);
            $id = $value[0];
            $totalIniciales[$inicial] = $id;
        }
    }
} else {
    exit;
}

$totalCursos = [];
$rCurso = $class->conex->query("SELECT ID, Nombre FROM Cursos");
if ($rCurso->num_rows > 0) {
    $nombresCursos = $rCurso->fetch_all();
    foreach ($nombresCursos as $key => $value) {
        if (isset($value[0]) && isset($value[1])) {
            $nombre = strtoupper($value[1]);
            $id = $value[0];
            $totalCursos[$nombre] = $id;
        }
    }
}

$totalAulas = [];
$rAula = $class->conex->query("SELECT ID, Nombre FROM Aulas");
if ($rAula->num_rows > 0) {
    $nombresAulas = $rAula->fetch_all();
    foreach ($nombresAulas as $key => $value) {
        if (isset($value[0]) && isset($value[1])) {
            $nombre = strtoupper($value[1]);
            $id = $value[0];
            $totalAulas[$nombre] = $id;
        }
    }
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

            if ($grupo != '' && array_key_exists($grupo, $totalCursos)) {
                $idCurso = $totalCursos[$grupo];
                $grupoExist = true;
            } else {
                $sql = "INSERT INTO Cursos (Nombre) VALUES ('$grupo')";
                $idCurso = $class->conex->query($sql) ? $class->conex->insert_id: $grupoExist = false;

                $totalCursos = [];
                $rCurso = $class->conex->query("SELECT ID, Nombre FROM Cursos");
                if ($rCurso->num_rows > 0) {
                    $nombresCursos = $rCurso->fetch_all();
                    foreach ($nombresCursos as $key => $value) {
                        if (isset($value[0]) && isset($value[1])) {
                            $nombre = strtoupper($value[1]);
                            $id = $value[0];
                            $totalCursos[$nombre] = $id;
                        }
                    }
                }
            }

            if ($aula != '' && array_key_exists($aula, $totalAulas)) {
                $idAula = $totalAulas[$aula];
                $aulaExist = true;
            } else {
                $sql = "INSERT INTO Aulas (Nombre) VALUES ('$aula')";
                $idAula = $class->conex->query($sql) ? $class->conex->insert_id: $aulaExist = false;
                
                $totalAulas = [];
                $rAula = $class->conex->query("SELECT ID, Nombre FROM Aulas");
                if ($rAula->num_rows > 0) {
                    $nombresAulas = $rAula->fetch_all();
                    foreach ($nombresAulas as $key => $value) {
                        if (isset($value[0]) && isset($value[1])) {
                            $nombre = strtoupper($value[1]);
                            $id = $value[0];
                            $totalAulas[$nombre] = $id;
                        }
                    }
                }
            }

            if ($importHorario->rowStatus() && $profesorExist && $grupoExist && $aulaExist) {

                if ($hoy) {
                    $sql = "INSERT INTO Horarios (ID_PROFESOR, Dia, Hora, Tipo, Edificio, Aula, Grupo)
                VALUES ('$idProfesor', '$dia', '$hora', '$tipo', '$edificio', '$idAula', '$idCurso')";
                } else {
                    $sql = "INSERT INTO T_horarios (ID_PROFESOR, Dia, Hora, Tipo, Edificio, Aula, Grupo, Fecha_incorpora)
                VALUES ('$idProfesor', '$dia', '$hora', '$tipo', '$edificio', '$idAula', '$idCurso', '$fecha')";
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
