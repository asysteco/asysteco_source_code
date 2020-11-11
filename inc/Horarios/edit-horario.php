<?php

$action = $_POST['action'];
$rowId = $_POST['rowId'];
$profesor = $_POST['profesor'];
$dia = $_POST['dia'];
$hora = $_POST['hora'];
$aula = $_POST['aula'];
$curso = $_POST['curso'];
$datos = $_POST['datos'];
$MSG = 'Error-inesperado';

if (!empty($action)) {
    if ($action === 'add') {
        if (!empty($profesor) && !empty($dia) && !empty($hora) && !empty($aula) && !empty($curso)) {
            $sql = "INSERT INTO Horarios (ID_PROFESOR, Dia, Hora, Tipo, Edificio, Aula, Grupo)
                    VALUES ('$profesor', '$dia', '$hora', 'Mañana', '1', '$aula', '$curso')";
            $query = $class->conex->query($sql);
            $MSG = $query ? 'Ok-add': 'Error-add';
        } else {
            $MSG = 'Error-empty';
        }
    } elseif ($action === 'update') {
        if (!empty($datos)) {
            $aulasCase = '';
            $aulasId = [];
            $cursosCase = '';
            $cursosId = [];

            $class->conex->autocommit(FALSE);
            try {
                foreach ($datos as $key => $value) {
                    $id = $value[0];
                    $field = $value[1];
                    $val = $value[2];
                    if ($field === 'Aula') {
                        $aulasCase .= " WHEN ID = $id THEN $val";
                        $aulasId[] = $id;
                    } else if ($field === 'Grupo') {
                        $cursosCase .= " WHEN ID = $id THEN $val";
                        $cursosId[] = $id;
                    } else {
                        throw new Exception('Error-values');
                    }
                }
                $countAulas = count($aulasId);
                $countCursos = count($cursosId);
                $inlineAulasId = implode(',', $aulasId);
                $inlineCursosId = implode(',', $cursosId);

                if ($countAulas) {
                    $aulasSql = sprintf("UPDATE Horarios
                    SET Aula = CASE %s
                    END
                    WHERE ID_PROFESOR = '$profesor'
                    AND ID IN (%s)", $aulasCase, $inlineAulasId);

                    if (!$class->conex->query($aulasSql)) {
                        throw new Exception('Error-update');
                    }
                }
                var_dump($aulasSql, $datos);

                if ($countCursos) {
                    $cursosSql = sprintf("UPDATE Horarios
                    SET Grupo = CASE %s
                    END
                    WHERE ID_PROFESOR = '$profesor'
                    AND ID IN (%s)", $cursosCase, $inlineCursosId);
                    
                    if (!$class->conex->query($cursosSql)) {
                        throw new Exception('Error-update');
                    }
                }

                $MSG = 'Ok-update';
            } catch (Exception $e) {
                $MSG = $e;
                $class->conex->rollback();
            }
            $class->conex->commit();

        }
    } elseif ($action === 'remove') {
        $sql = "DELETE FROM Horarios WHERE ID = '$rowId' AND ID_PROFESOR = '$profesor'";
        $MSG = $class->conex->query($sql) ? 'Ok-remove': 'Error-remove';
    } elseif ($action === 'get') {
        $sql = "";
        $MSG = $class->conex->query($sql) ? 'Ok-get': 'Error-get';
    } else {
        $MSG = "Error-action";
    }
} else {
    $MSG = "Error-params";
}

echo $MSG;
exit;