<?php

if($class->validFormIni($_POST['Iniciales'])) {
    if($response = $class->query("SELECT ID FROM $class->profesores WHERE Iniciales = '$_POST[Iniciales]' AND ID = '$_POST[ID]'")) {
        if(! $response->num_rows > 0) {
            if($class->searchDuplicateField($_POST['Iniciales'], 'Iniciales', $class->profesores)) {
                if($class->validFormName($_POST['Nombre'])) {
                    include_once($dirs['Profesores'] . 'Edit/Ajax/update.php');
                } else {
                    $MSG = 'warning-nombre';
                }
            } else {
                $MSG = "warning-iniciales";
            }
        } else {
            if($class->validFormName($_POST['Nombre'])) {
                include_once($dirs['Profesores'] . 'Edit/Ajax/update.php');
            } else {
                $MSG = 'warning-nombre';
            }
        }
    } else {
        $MSG = 'error-query';
    }
} else {
    $MSG = 'warning-iniciales';
}

echo $MSG;
exit;
