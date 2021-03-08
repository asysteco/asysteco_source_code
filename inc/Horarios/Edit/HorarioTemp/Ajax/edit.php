<?php

$action = $_POST['action'] ?? '';
$rowId = $_POST['rowId'] ?? '';
$profesor = $_POST['profesor'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$dia = $_POST['dia'] ?? '';
$edificio = $_POST['edificio'] ?? '';
$hora = $_POST['hora'] ?? '';
$aula = $_POST['aula'] ?? '';
$curso = $_POST['curso'] ?? '';
$datos = $_POST['datos'] ?? '';
$date = $_POST['date'] ?? '';

$success = false;
$alertMessage = 'Error inesperado...';
$reload = false;
$trigger = false;


function isAvailable($conex, $profesor, $dia, $hora, $aula, $curso, $date)
{
    $sql = "SELECT *
            FROM T_horarios
            WHERE ID_PROFESOR = '$profesor'
                AND Dia = '$dia'
                AND Hora = '$hora'
                AND Aula = '$aula'
                AND Grupo = '$curso'
                AND Fecha_incorpora = '$date'";
    $query = $conex->query($sql);
    if ($query->num_rows > 0) {
        return false;
    }

    return true;
}

if ($action === 'add') {
    if (!empty($profesor) && !empty($dia) && !empty($hora) && !empty($aula) && !empty($curso)) {
        if (isAvailable($class->conex, $profesor, $dia, $hora, $aula, $curso, $date)) {
            $sql = "INSERT INTO T_horarios (ID_PROFESOR, Dia, Hora, Tipo, Edificio, Aula, Grupo, Fecha_incorpora)
                    VALUES ('$profesor', '$dia', '$hora', '$tipo', '$edificio', '$aula', '$curso', '$date')";
            $query = $class->conex->query($sql);

            if ($query) {
                $success = true;
                $alertMessage = 'Hora añadida correctamente.';
                $reload = true;
            } else {
                $alertMessage = 'Error al añadir hora.';
            }
        } else {
            $alertMessage = 'Error al añadir hora, no puedes duplicar horas con el mismo Aula y Curso.';
        }
    } else {
        $alertMessage = 'Debe seleccionar todos los campos.';
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
                    throw new Exception('Parámetros no válidos.');
                }
            }
            $countAulas = count($aulasId);
            $countCursos = count($cursosId);
            $inlineAulasId = implode(',', $aulasId);
            $inlineCursosId = implode(',', $cursosId);

            if ($countAulas) {
                $aulasSql = sprintf("UPDATE T_horarios
                SET Aula = CASE %s
                END
                WHERE ID_PROFESOR = '$profesor'
                AND ID IN (%s)", $aulasCase, $inlineAulasId);

                $class->autocommitOffQuery($class->conex, $aulasSql, 'Error al actualizar horas.');
            }

            if ($countCursos) {
                $cursosSql = sprintf("UPDATE T_horarios
                SET Grupo = CASE %s
                END
                WHERE ID_PROFESOR = '$profesor'
                AND ID IN (%s)", $cursosCase, $inlineCursosId);

                $class->autocommitOffQuery($class->conex, $cursosSql, 'Error al actualizar horas.');
            }

            $success = true;
            $alertMessage = 'Horas actualizadas correctamente.';
            $trigger = 'updated';
        } catch (Exception $e) {
            $alertMessage = $e->getMessage();
            $class->conex->rollback();
        }
        $class->conex->commit();
    }
} elseif ($action === 'remove') {
    $sql = "DELETE FROM T_horarios WHERE ID = '$rowId' AND ID_PROFESOR = '$profesor'";
    if ($class->conex->query($sql)) {
        $success = true;
        $alertMessage = 'Hora eliminada correctamente.';
        $trigger = 'remove-hora';
    } else {
        $alertMessage = 'Error al eliminar hora.';
    }
} elseif ($action === 'cancel-program') {
    $sql = "DELETE FROM T_horarios WHERE ID_PROFESOR = '$profesor' AND Fecha_incorpora = '$date'";
    if ($class->conex->query($sql)) {
        $success = true;
        $alertMessage = 'Programación cancelada.';
        $trigger = 'go-to';
    } else {
        $alertMessage = 'Error al cancelar la programación.';
        $reload = true;
    }
} else {
    $alertMessage = 'Acción no válida.';
}

$result = [
    'success' => $success,
    'msg' => $alertMessage,
    'reload' => $reload,
    'trigger' => $trigger
];

echo json_encode($result);
exit;
