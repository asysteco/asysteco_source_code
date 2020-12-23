<?php

require_once($dirs['class'] . 'Aulas.php');

$classAulas = new Aulas;

$action = $_POST['action'] ?? '';
$aula = $_POST['aula'] ?? '';
$data = $_POST['data'] ?? '';
$MSG = 'Ok-action';

if ($action === 'add') {
    if ($response = $class->query("SELECT Nombre FROM Aulas WHERE Nombre = '$aula'")) {
        if ($response->num_rows == 0) {
            if ($classAulas->aulaValida($aula)) {
                if (!$class->query("INSERT INTO Aulas (Nombre) VALUES ('$aula')")) {
                    $MSG = 'Error-add';
                }
            } else {
                $MSG = 'Error-valid';
            }
        } else {
            $MSG = 'Error-exist';
        }
    } else {
        $MSG = 'Error-inesperado';
    }
} elseif ($action === 'update') {
    if ($response = $class->query("SELECT Nombre FROM Aulas WHERE Nombre = '$aula'")) {
        if ($response->num_rows == 0) {
            if ($classAulas->aulaValida($aula)) {
                if (!$class->query("UPDATE Aulas SET Nombre = '$aula' WHERE ID = '$data'")) {
                    $MSG = 'Error-update';
                }
            } else {
                $MSG = 'Error-valid';
            }
        } else {
            $MSG = 'Error-exist';
        }
    } else {
        $MSG = 'Error-inesperado';
    }
} elseif ($action === 'remove') {
    if (!$class->query("DELETE FROM Aulas WHERE ID = '$data'")) {
        $MSG = 'Error-delete';
    }
} else {
    $MSG = 'Error-inesperado';
}

echo $MSG;
