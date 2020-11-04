<?php

require_once($dirs['class'] . 'Cursos.php');

$classCurso = new Cursos;

$action = $_GET['action'];
$curso = $_GET['curso'];
$data = $_GET['data'];
$MSG = 'Ok-action';

if ($action === 'add') {
    if ($response = $class->query("SELECT Nombre FROM Cursos WHERE Nombre = '$curso'")) {
        if ($response->num_rows == 0) {
            if ($classCurso->cursoValido($curso)) {
                if (!$class->query("INSERT INTO Cursos (Nombre) VALUES ('$curso')")) {
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
    if ($response = $class->query("SELECT Nombre FROM Cursos WHERE Nombre = '$curso'")) {
        if ($response->num_rows == 0) {
            if ($classCurso->cursoValido($curso)) {
                if (!$class->query("UPDATE Cursos SET Nombre = '$curso' WHERE ID = '$data'")) {
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
    if (!$class->query("DELETE FROM Cursos WHERE ID = '$data'")) {
        $MSG = 'Error-delete';
    }
} else {
    $MSG = 'Error-inesperado';
}

echo $MSG;
