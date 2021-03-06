<?php

$pSustituido = $_POST['ID_PROFESOR'];
$pSustituto = $_POST['ID_SUSTITUTO'];
$alertMessage = 'Error inesperado, contacte con los administradores...';

$status = false;
$reload = false;

if ($response = $class->query("SELECT ID, Nombre FROM Profesores WHERE Profesores.ID='$pSustituido' AND Profesores.TIPO='1'")) {
    if ($response->num_rows > 0) {
        $alertMessage = "No se puede sustituir a un administrador.";
    } else {
        if (!$profesor = $class->query("SELECT ID, Nombre FROM Profesores WHERE Profesores.ID='$pSustituido'")->fetch_assoc()) {
            $alertMessage = "Error al seleccionar al profesor a sustituir.";
        }
        if (!$sustituto = $class->query("SELECT ID, Nombre FROM Profesores WHERE Profesores.ID='$pSustituto'")->fetch_assoc()) {
            $alertMessage = "Error al seleccionar al profesor sustituto.";
        }
        if ($class->query("UPDATE Profesores SET Sustituido=1 WHERE ID='$pSustituido'")) {
            if ($class->query(
                "INSERT INTO Horarios (ID_PROFESOR, Dia, Hora, Tipo, Edificio, Aula, Grupo, Hora_entrada, Hora_salida)
                SELECT $pSustituto, Dia, Hora, Tipo, Edificio, Aula, Grupo, Hora_entrada, Hora_salida FROM Horarios WHERE ID_PROFESOR='$pSustituido'"
            )) {
                $alertMessage = "Sustitución realizada correctamente.";
                $status = true;
                $reload = true;
            }
            $class->marcajes($pSustituido, 'remove');
            $class->marcajes($pSustituto, 'remove');
            $class->marcajes($pSustituto, 'add');
        }
    }
}

$result = [
    'success' => $status,
    'msg' => $alertMessage,
    'reload' => $reload
];

echo json_encode($result);
exit;
