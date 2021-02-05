<?php

$pSustituido = $_POST['ID_PROFESOR'];
$pSustituto = $_POST['ID_SUSTITUTO'];

if ($response = $class->query("SELECT ID, Nombre FROM Profesores WHERE Profesores.ID='$pSustituido' AND Profesores.TIPO='1'")) {
    if ($response->num_rows > 0) {
        $MSG = "error-admin";
    } else {
        if (!$profesor = $class->query("SELECT ID, Nombre FROM Profesores WHERE Profesores.ID='$pSustituido'")->fetch_assoc()) {
            $MSG = "error-sustituido";
        }
        if (!$sustituto = $class->query("SELECT ID, Nombre FROM Profesores WHERE Profesores.ID='$pSustituto'")->fetch_assoc()) {
            $MSG = "error-sustituto";
        }
        if ($class->query("UPDATE Profesores SET Sustituido=1 WHERE ID='$pSustituido'")) {
            if ($class->query(
                "INSERT INTO Horarios (ID_PROFESOR, Dia, Hora, Tipo, Edificio, Aula, Grupo, Hora_entrada, Hora_salida)
                SELECT $pSustituto, Dia, Hora, Tipo, Edificio, Aula, Grupo, Hora_entrada, Hora_salida FROM Horarios WHERE ID_PROFESOR='$pSustituido'"
            )) {
                $MSG = "sustituido";
            }
            $class->marcajes($pSustituido, 'remove');
            $class->marcajes($pSustituto, 'add');
        } else {
            $MSG = "error-sustitucion";
            return false;
        }
    }
} else {
    $MSG = 'error-inesperado';
}

echo $MSG;
exit;
