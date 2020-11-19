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
$edificio = 1;

$totalHorarios = [];
$response = $class->conex->query("SELECT DISTINCT  ID_PROFESOR, Dia, Hora, Aula, Grupo FROM Horarios");
if ($response->num_rows > 0) {
    $registroHorarios = $response->fetch_all();
    foreach ($registroHorarios as $key => $value) {
        if (isset($value[0]) && isset($value[1])) {
            $ID = $value[0];
            $diaHoraAulaGrupo = $value[1] . '_' . $value[2] . '_' . $value[3] . '_' . $value[4];
            $totalHorarios[$ID][$diaHoraAulaGrupo] = true;
        }
    }
}

$totalIniciales = [];
$response = $class->conex->query("SELECT DISTINCT  ID, Iniciales FROM Profesores WHERE TIPO <> 1");
if ($response->num_rows > 0) {
    $inicialesBD = $response->fetch_all();
    foreach ($inicialesBD as $key => $value) {
        if (isset($value[0]) && isset($value[1])) {
            $inicial = strtoupper($value[1]);
            $id = $value[0];
            $totalIniciales[$inicial] = $id;
        }
    }
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
    $sqlHoy = '';
    $sqlFecha = '';

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
            
            if (isset($column[5])) {
                if (preg_match('/^[1-9]$/', $column[5])) {
                    $edificio = $column[5];
                } else {
                    echo "error-edificio";
                    exit;
                }
            }

            if ($iniciales != '' && array_key_exists($iniciales, $totalIniciales)) {
                $idProfesor = $totalIniciales[$iniciales];
            } else {
                throw new Exception('No-profesor');
            }

            if ($grupo != '' && array_key_exists($grupo, $totalCursos)) {
                $idCurso = $totalCursos[$grupo];
            } else {
                $sqlCursos = "INSERT INTO Cursos (Nombre) VALUES ('$grupo')";
                $idCurso = $class->conex->query($sqlCursos) ? $class->conex->insert_id: false;

                $totalCursos[$grupo] = $idCurso;
            }

            if ($aula != '' && array_key_exists($aula, $totalAulas)) {
                $idAula = $totalAulas[$aula];
            } else {
                $sqlAulas = "INSERT INTO Aulas (Nombre) VALUES ('$aula')";
                $idAula = $class->conex->query($sqlAulas) ? $class->conex->insert_id: false;
                
                $totalAulas[$aula] = $idAula;
            }

            $horarioNotExist = false;
            if ($idProfesor != '' && $idCurso && $idCurso != '' && $idAula && $idAula != '' && $dia != '' && $hora != '') {
                if ($hoy) {
                    $diaHoraKey = $dia . '_' . $hora . '_' . $idAula . '_' . $idCurso;
                    if (isset($totalHorarios[$idProfesor][$diaHoraKey]) && $totalHorarios[$idProfesor][$diaHoraKey] === true) {
                        continue;
                    } else {
                        $sqlHoy .= $row === 1 ? "('$idProfesor', '$dia', '$hora', '$tipo', '$edificio', '$idAula', '$idCurso')":
                                            ",('$idProfesor', '$dia', '$hora', '$tipo', '$edificio', '$idAula', '$idCurso')";
                        $totalHorarios[$idProfesor][$diaHoraKey] = true;
                    }
                } else {
                    $sqlFecha .= $row === 1 ? "('$idProfesor', '$dia', '$hora', '$tipo', '$edificio', '$idAula', '$idCurso', '$fecha')":
                                        ",('$idProfesor', '$dia', '$hora', '$tipo', '$edificio', '$idAula', '$idCurso', '$fecha')";
                }

                $horarioNotExist = true;
            } else {
                throw new Exception('Error-unexpected');
            }
            $row++;
        }

        if (!empty($sqlHoy) || !empty($sqlFecha)) {
            if ($hoy) {
                $sqlInsert = sprintf("INSERT INTO Horarios (ID_PROFESOR, Dia, Hora, Tipo, Edificio, Aula, Grupo) VALUES %s", $sqlHoy);
            } else {
                $sqlInsert = sprintf("INSERT INTO T_horarios (ID_PROFESOR, Dia, Hora, Tipo, Edificio, Aula, Grupo, Fecha_incorpora) VALUES %s", $sqlFecha);
            }
            if (!$class->conex->query($sqlInsert)) {
                echo $class->conex->error;
                throw new Exception('Error-importar');
            }
            if ($hoy) {
                $class->updateHoras();
                $class->marcajes();
            }
        } else {
            echo "empty-import";
        }
    } catch (Exception $e) {
        echo $e;
        $class->conex->rollback();
    }
    $class->conex->commit();
}
exit;